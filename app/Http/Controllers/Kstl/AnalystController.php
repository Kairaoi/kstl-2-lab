<?php

namespace App\Http\Controllers\Kstl;

use App\Http\Controllers\Controller;
use App\Services\NotificationService;
use App\Repositories\Kstl\SampleTestRepository;
use App\Repositories\Kstl\SampleRepository;
use App\Repositories\Kstl\SubmissionRepository;
use App\Models\Kstl\SampleTest;
use App\Models\Kstl\SampleTestAttachment;
use App\Models\Kstl\Sample;
use App\Models\Kstl\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
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
    public function dashboard(Request $request)
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

        // Historical — submissions that have reached testing or beyond.
        // When a reference number is searched (audit lookup), match across the
        // ENTIRE history rather than only the most recent records.
        $search = trim((string) $request->query('ref', ''));

        $historyQuery = \App\Models\Kstl\Submission::with(['client', 'samples.sampleTests'])
            ->whereIn('status', [
                \App\Models\Kstl\Submission::STATUS_TESTING,
                \App\Models\Kstl\Submission::STATUS_AWAITING_AUTHORISATION,
                \App\Models\Kstl\Submission::STATUS_AUTHORISED,
                \App\Models\Kstl\Submission::STATUS_COMPLETED,
            ])
            ->orderByDesc('updated_at');

        if ($search !== '') {
            $historyQuery->where('reference_number', 'like', '%' . $search . '%');
        } else {
            $historyQuery->limit(20);
        }

        $history = $historyQuery->get();

        // All flagged tests across the lab — fetched independently so the
        // director-query alert shows even when the tests aren't assigned to
        // the currently logged-in analyst.
        $flaggedTests = SampleTest::where('status', SampleTest::STATUS_FLAGGED)
            ->with(['sample.submission.client', 'assignedTo'])
            ->orderByDesc('updated_at')
            ->get();

        return view('kstl.analyst.dashboard',
            compact('queue', 'counts', 'activeSubmissions', 'history', 'search', 'flaggedTests'));
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

        // A test is locked (view-only) when finalised, or its submission has
        // been authorised/completed. Locked tests are never auto-started.
        $locked = $this->testIsLocked($test);

        // Auto-start if queued (only when not locked)
        if (! $locked && $test->status === SampleTest::STATUS_QUEUED) {
            $test = $this->testRepo->startTest($id);
        }

        return view('kstl.analyst.tests.show',
            compact('test', 'sample', 'submission', 'locked'));
    }

    // ── Save result ────────────────────────────────────────────────
    public function saveResult(Request $request, string $id)
    {
        // Gracefully handle a locked test before validating/saving, so the
        // analyst sees a friendly message instead of a bare 403 page.
        // (The repository still hard-blocks as a last-resort backstop.)
        $existing = $this->testRepo->getById($id);

        if ($this->testIsLocked($existing)) {
            $submissionStatus = $existing->sample?->submission?->status;
            $message = in_array($submissionStatus, [Submission::STATUS_AUTHORISED, Submission::STATUS_COMPLETED], true)
                ? 'This submission has been authorised, so its results are locked and can only be viewed.'
                : 'This test has been finalised and can no longer be edited. It remains available to view.';

            return redirect()->route('analyst.tests.show', $id)->with('info', $message);
        }

        $validated = $request->validate([
            'result_value'     => ['nullable', 'string', 'max:100'],
            'result_unit'      => ['nullable', 'string', 'max:30'],
            'result_qualifier' => ['required', 'in:detected,not_detected,pass,fail,less_than,greater_than,equal_to'],
            'result_notes'     => ['nullable', 'string'],
            'flag'             => ['nullable', 'boolean'],
        ]);

        // When the analyst responds to a Director query the form shows only their
        // own notes (Director query blocks are stripped in the blade). Re-append
        // the Director query blocks so they are preserved in the database.
        if ($existing->status === SampleTest::STATUS_FLAGGED) {
            $stored = $existing->result_notes ?? '';
            preg_match_all('/\[Director query\].*/s', $stored, $dqBlocks);
            if (!empty($dqBlocks[0])) {
                $directorSuffix = "\n\n" . implode('', $dqBlocks[0]);
                $validated['result_notes'] = rtrim($validated['result_notes'] ?? '') . $directorSuffix;
            }
        }

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
                $fromStatus = $submission->status;

                $this->submissionRepo->updateStatus(
                    $submission->id,
                    Submission::STATUS_AWAITING_AUTHORISATION
                );

                // Audit log — use actual previous status (may be testing or authorised
                // when responding to a director query on an already-authorised submission)
                $this->auditService->logStatusChange(
                    $submission->fresh(),
                    $fromStatus,
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

    // ── Attach a supporting file / document to a test ──────────────
    public function uploadAttachment(Request $request, string $id)
    {
        $test = $this->testRepo->getById($id);

        // Locked tests are view-only — no files may be added once finalised
        // or once the submission is authorised.
        if ($this->testIsLocked($test)) {
            return redirect()->route('analyst.tests.show', $id)
                ->with('info', 'This result is locked, so supporting files can no longer be added.');
        }

        $validated = $request->validate([
            'attachment'  => ['required', 'file', 'mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx,csv,txt', 'max:20480'],
            'description' => ['nullable', 'string', 'max:255'],
        ]);

        $file     = $request->file('attachment');
        $original = $file->getClientOriginalName();
        $stored   = 'test_' . $id . '_' . time() . '_' . Str::random(6)
                  . '.' . $file->getClientOriginalExtension();

        // Lab data lives on the private disk — not publicly reachable by URL.
        $path = $file->storeAs('test_attachments/' . $id, $stored, 'private');

        $attachment = SampleTestAttachment::create([
            'sample_test_id'    => $test->id,
            'uploaded_by'       => Auth::id(),
            'original_filename' => $original,
            'file_path'         => $path,
            'mime_type'         => $file->getClientMimeType(),
            'file_size'         => $file->getSize(),
            'description'       => $validated['description'] ?? null,
        ]);

        Log::info('Analyst attached supporting file to test', [
            'test_id'       => $id,
            'attachment_id' => $attachment->id,
            'filename'      => $original,
            'uploaded_by'   => Auth::id(),
        ]);

        return redirect()->route('analyst.tests.show', $id)
            ->with('success', "Attached \"{$original}\" to this test.");
    }

    // ── Download an attachment (streamed; staff-only via route group) ─
    public function downloadAttachment(string $attachment)
    {
        $att = SampleTestAttachment::findOrFail($attachment);

        abort_unless(
            Storage::disk('private')->exists($att->file_path),
            404,
            'File not found.'
        );

        Log::info('Staff downloaded test attachment', [
            'attachment_id' => $att->id,
            'test_id'       => $att->sample_test_id,
            'user_id'       => Auth::id(),
        ]);

        return Storage::disk('private')->download($att->file_path, $att->original_filename);
    }

    // ── Remove an attachment ───────────────────────────────────────
    public function deleteAttachment(string $attachment)
    {
        $att    = SampleTestAttachment::findOrFail($attachment);
        $testId = $att->sample_test_id;

        // Locked tests are view-only — files cannot be removed once finalised
        // or once the submission is authorised.
        $test = $this->testRepo->getById($testId);
        if ($this->testIsLocked($test)) {
            return redirect()->route('analyst.tests.show', $testId)
                ->with('info', 'This result is locked, so supporting files can no longer be removed.');
        }

        if (Storage::disk('private')->exists($att->file_path)) {
            Storage::disk('private')->delete($att->file_path);
        }

        Log::info('Staff removed test attachment', [
            'attachment_id' => $att->id,
            'test_id'       => $testId,
            'filename'      => $att->original_filename,
            'user_id'       => Auth::id(),
        ]);

        $att->delete();

        return redirect()->route('analyst.tests.show', $testId)
            ->with('success', 'Attachment removed.');
    }

    // ── Authorised results (read-only) ─────────────────────────────
    public function resultsIndex()
    {
        // Analysts may view all authorised/completed reports (results.viewAny).
        $submissions = Submission::whereIn('status', [
                Submission::STATUS_AUTHORISED,
                Submission::STATUS_COMPLETED,
            ])
            ->with(['client', 'result.authorisedBy'])
            ->orderByDesc('updated_at')
            ->get();

        Log::info('Analyst viewed results list', ['user_id' => Auth::id()]);

        return view('kstl.analyst.results.index', compact('submissions'));
    }

    public function resultsShow(string $submissionId)
    {
        $submission = Submission::where('id', $submissionId)
            ->whereIn('status', [
                Submission::STATUS_AUTHORISED,
                Submission::STATUS_COMPLETED,
            ])
            ->with(['client', 'result.authorisedBy', 'samples'])
            ->firstOrFail();

        $result  = $submission->result;
        $samples = $submission->samples;

        $testsBySample = [];
        foreach ($samples as $sample) {
            $testsBySample[$sample->id] = SampleTest::where('sample_id', $sample->id)
                ->orderBy('test_category')
                ->orderBy('test_key')
                ->get();
        }

        Log::info('Analyst viewed result', [
            'user_id'       => Auth::id(),
            'submission_id' => $submissionId,
        ]);

        return view('kstl.analyst.results.show',
            compact('submission', 'result', 'samples', 'testsBySample'));
    }

    // ── Helper: is a test locked (view-only) for audit? ────────────
    // True when the test is finalised, or its submission has been
    // authorised/completed. Used to lock result editing and attachments.
    private function testIsLocked(SampleTest $test): bool
    {
        // A flagged test has been explicitly returned by the Director for
        // analyst action — it must always be editable, regardless of the
        // submission's current status.
        if ($test->status === SampleTest::STATUS_FLAGGED) {
            return false;
        }

        if ($test->status === SampleTest::STATUS_COMPLETED) {
            return true;
        }

        $submissionStatus = $test->sample?->submission?->status;

        return in_array($submissionStatus, [
            Submission::STATUS_AUTHORISED,
            Submission::STATUS_COMPLETED,
        ], true);
    }

    // ── Notifications ─────────────────────────────────────────────

    public function notificationsIndex(Request $request)
    {
        $user = Auth::user();

        \DB::table('notifications')
            ->where('notifiable_type', 'App\\Models\\User')
            ->where('notifiable_id', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        $notifications = \DB::table('notifications')
            ->where('notifiable_type', 'App\\Models\\User')
            ->where('notifiable_id', $user->id)
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('kstl.analyst.notifications.index', compact('notifications'));
    }

    public function notificationMarkRead(Request $request, string $id)
    {
        $user = Auth::user();

        \DB::table('notifications')
            ->where('id', $id)
            ->where('notifiable_type', 'App\\Models\\User')
            ->where('notifiable_id', $user->id)
            ->update(['read_at' => now()]);

        return back()->with('success', 'Notification marked as read.');
    }

    public function notificationMarkAllRead(Request $request)
    {
        $user = Auth::user();

        \DB::table('notifications')
            ->where('notifiable_type', 'App\\Models\\User')
            ->where('notifiable_id', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return back()->with('success', 'All notifications marked as read.');
    }
}