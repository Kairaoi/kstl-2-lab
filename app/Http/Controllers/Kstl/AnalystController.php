<?php

namespace App\Http\Controllers\Kstl;

use App\Http\Controllers\Controller;
use App\Services\NotificationService;
use App\Repositories\Kstl\SampleTestRepository;
use App\Repositories\Kstl\SampleRepository;
use App\Repositories\Kstl\SubmissionRepository;
use App\Models\Kstl\SampleTest;
use App\Models\Kstl\Sample;
use App\Models\Kstl\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Services\AuditService;

class AnalystController extends Controller
{
    public function __construct(
        protected SampleTestRepository $testRepo,
        protected SampleRepository     $sampleRepo,
        protected SubmissionRepository $submissionRepo,
        protected NotificationService  $notifyService,
        protected AuditService        $auditService,
    ) {}

    // ── Dashboard ──────────────────────────────────────────────────
    public function dashboard()
    {
        $queue  = $this->testRepo->getQueue(Auth::id());
        $counts = $this->testRepo->countByStatus();

        // Group active tests by submission for cleaner display
        $activeSubmissions = $queue
            ->groupBy(fn($test) => $test->sample->submission->id)
            ->map(fn($tests) => [
                'submission' => $tests->first()->sample->submission,
                'tests'      => $tests,
                'total'      => $tests->count(),
                'done'       => $tests->where('status', 'completed')->count(),
                'flagged'    => $tests->where('status', 'flagged')->count(),
            ]);

        // Historical — all submissions that have reached testing or beyond
        $history = \App\Models\Kstl\Submission::with(['client', 'samples.sampleTests'])
            ->whereIn('status', [
                \App\Models\Kstl\Submission::STATUS_TESTING,
                \App\Models\Kstl\Submission::STATUS_AWAITING_AUTHORISATION,
                \App\Models\Kstl\Submission::STATUS_AUTHORISED,
                \App\Models\Kstl\Submission::STATUS_COMPLETED,
            ])
            ->orderByDesc('updated_at')
            ->limit(20)
            ->get();

        return view('kstl.analyst.dashboard',
            compact('queue', 'counts', 'activeSubmissions', 'history'));
    }

    // ── Test queue (all tests) ─────────────────────────────────────
    public function index(Request $request)
    {
        $queue = $this->testRepo->getQueue(
            $request->boolean('mine') ? Auth::id() : null
        );

        return view('kstl.analyst.tests.index', compact('queue'));
    }

    // ── Show individual test — enter result ────────────────────────
    public function show(string $id)
    {
        $test       = $this->testRepo->getById($id);
        $sample     = $test->sample;
        $submission = $sample->submission;

        // Auto-start if queued
        if ($test->status === SampleTest::STATUS_QUEUED) {
            $test = $this->testRepo->startTest($id);
        }

        return view('kstl.analyst.tests.show',
            compact('test', 'sample', 'submission'));
    }

    // ── Save result ────────────────────────────────────────────────
    public function saveResult(Request $request, string $id)
    {
        $validated = $request->validate([
            'result_value'     => ['nullable', 'string', 'max:100'],
            'result_unit'      => ['nullable', 'string', 'max:30'],
            'result_qualifier' => ['required', 'in:detected,not_detected,pass,fail,less_than,greater_than,equal_to'],
            'result_notes'     => ['nullable', 'string'],
            'flag'             => ['nullable', 'boolean'],
        ]);

        Log::info('Saving test result', [
            'test_id'          => $id,
            'result_qualifier' => $validated['result_qualifier'],
            'analyst_id'       => Auth::id(),
        ]);

        $test   = $this->testRepo->saveResult($id, $validated);
        $sample = $test->sample;

        // If all tests for this sample are done, mark sample completed
        if ($this->testRepo->allCompletedForSample($sample->id)) {
            $this->sampleRepo->updateStatus($sample->id, Sample::STATUS_COMPLETED);

            // Check if all samples in submission are completed
            $submission      = $sample->submission;
            $incompleteSamples = $submission->samples()
                ->whereNotIn('status', [Sample::STATUS_COMPLETED, Sample::STATUS_REJECTED])
                ->count();

            if ($incompleteSamples === 0) {
                $this->submissionRepo->updateStatus(
                    $submission->id,
                    Submission::STATUS_AWAITING_AUTHORISATION
                );

                // Audit log
                $this->auditService->logStatusChange(
                    $submission->fresh(),
                    Submission::STATUS_TESTING,
                    Submission::STATUS_AWAITING_AUTHORISATION
                );

                // Notify Director
                $this->notifyService->notifyDirectorAwaitingAuthorisation($submission);

                Log::info('All tests complete — submission awaiting authorisation', [
                    'submission_id' => $submission->id,
                    'reference'     => $submission->reference_number,
                ]);
            }
        }

        return redirect()->route('analyst.tests.index')
            ->with('success', "Result saved for {$test->getDisplayLabel()}.");
    }
}