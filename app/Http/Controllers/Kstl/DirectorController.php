<?php

namespace App\Http\Controllers\Kstl;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Services\AuditService;
use App\Services\NotificationService;
use App\Repositories\Kstl\ClientRepository;
use App\Repositories\Kstl\SubmissionRepository;
use App\Repositories\Kstl\SampleRepository;
use App\Repositories\Kstl\SampleTestRepository;
use App\Repositories\Kstl\ResultRepository;
use App\Models\Kstl\Submission;
use App\Models\Kstl\SampleTest;
use App\Models\Kstl\SampleTestAttachment;   // ← Added
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;    // ← Added

class DirectorController extends Controller
{
    public function __construct(
        protected SubmissionRepository $submissionRepo,
        protected SampleRepository     $sampleRepo,
        protected SampleTestRepository $testRepo,
        protected ResultRepository     $resultRepo,
        protected ClientRepository     $clientRepo,
        protected NotificationService  $notifyService,
        protected AuditService        $auditService,
    ) {}

    // ── Dashboard — awaiting authorisation queue ───────────────────
    public function dashboard()
    {
        $pending          = $this->submissionRepo->getAwaitingAuthorisation();
        $flagged          = $this->getFlaggedCount();
        $authorised_today = $this->getAuthorisedToday();

        // Ensure the relationships the dashboard view reads are loaded
        $pending->loadMissing(['client.user', 'samples.sampleTests']);

        // Historical — authorised and completed submissions
        $history = \App\Models\Kstl\Submission::with(['client.user', 'result.authorisedBy', 'samples.sampleTests'])
            ->whereIn('status', [
                \App\Models\Kstl\Submission::STATUS_AUTHORISED,
                \App\Models\Kstl\Submission::STATUS_COMPLETED,
            ])
            ->orderByDesc('updated_at')
            ->limit(20)
            ->get();

        // Agreements awaiting the Director's countersignature
        $unsigned_agreements = $this->clientRepo->getPendingCountersign()->count();

        return view('kstl.director.dashboard',
            compact('pending', 'flagged', 'authorised_today', 'history', 'unsigned_agreements'));
    }

    // ── Show submission for review ─────────────────────────────────
    public function show(string $id)
    {
        $submission = $this->submissionRepo->getById($id);
        $samples    = $this->sampleRepo->getBySubmissionId($id);

        // Load tests per sample, with their supporting documents for review.
        $testsBySample = [];
        foreach ($samples as $sample) {
            $tests = $this->testRepo->getBySampleId($sample->id);
            $tests->loadMissing('attachments.uploadedBy');   // ← Improved: avoids N+1
            $testsBySample[$sample->id] = $tests;
        }

        $existingResult = $this->resultRepo->findBySubmissionId($id);

        return view('kstl.director.submissions.show',
            compact('submission', 'samples', 'testsBySample', 'existingResult'));
    }

    // ── Authorise submission ───────────────────────────────────────
    public function authorise(Request $request, string $id)
    {
        $submission = $this->submissionRepo->getById($id);

        if ($submission->status !== Submission::STATUS_AWAITING_AUTHORISATION) {
            return redirect()->back()
                ->with('error', 'This submission is not awaiting authorisation.');
        }

        $validated = $request->validate([
            'overall_outcome'   => ['required', 'in:pass,fail,inconclusive'],
            'director_comments' => ['nullable', 'string'],
        ]);

        Log::info('Director authorising submission', [
            'submission_id'  => $id,
            'outcome'        => $validated['overall_outcome'],
            'director_id'    => Auth::id(),
        ]);

        DB::transaction(function () use ($submission, $validated) {
            $this->resultRepo->authorise($submission->id, $validated);

            $this->submissionRepo->updateStatus(
                $submission->id,
                Submission::STATUS_AUTHORISED
            );

            $this->auditService->logStatusChange(
                $submission->fresh(),
                Submission::STATUS_AWAITING_AUTHORISATION,
                Submission::STATUS_AUTHORISED
            );

            $result = $this->resultRepo->findBySubmissionId($submission->id);
            if ($result) { 
                $this->auditService->logResultAuthorised($result->load('submission')); 
            }

            if ($result) {
                $this->notifyService->notifyResultsReady($submission, $result);
            }
        });

        return redirect()->route('director.dashboard')
            ->with('success', "Submission {$submission->reference_number} has been authorised. Overall outcome: " . ucfirst($validated['overall_outcome']));
    }

    // ── Query analyst — flag tests back for clarification ──────────
    public function queryAnalyst(Request $request, string $id)
    {
        $submission = $this->submissionRepo->getById($id);

        // A submission can only be queried while it is awaiting authorisation.
        if ($submission->status !== Submission::STATUS_AWAITING_AUTHORISATION) {
            return redirect()->back()
                ->with('error', 'This submission can no longer be queried — it is not awaiting authorisation.');
        }

        $validated = $request->validate([
            'test_ids'     => ['required', 'array', 'min:1'],
            'test_ids.*'   => ['string'],
            'query_notes'  => ['required', 'string', 'max:1000'],
        ]);

        DB::transaction(function () use ($validated, $id) {
            foreach ($validated['test_ids'] as $testId) {
                $test = $this->testRepo->getById($testId);
                $test->update([
                    'status'       => SampleTest::STATUS_FLAGGED,
                    'result_notes' => ($test->result_notes ? $test->result_notes . "\n\n" : '')
                        . '[Director query] ' . $validated['query_notes'],
                ]);
            }

            $this->submissionRepo->updateStatus($id, Submission::STATUS_TESTING);
        });

        $submission = $this->submissionRepo->getById($id);
        $this->auditService->logStatusChange(
            $submission,
            Submission::STATUS_AWAITING_AUTHORISATION,
            Submission::STATUS_TESTING
        );

        Log::info('Director queried analyst', [
            'submission_id' => $id,
            'test_count'    => count($validated['test_ids']),
            'director_id'   => Auth::id(),
        ]);

        return redirect()->route('director.dashboard')
            ->with('warning', "Query sent. {$submission->reference_number} returned to analyst for clarification.");
    }

    // ── Flagged tests — everything awaiting/needing Director attention ─
    public function flaggedIndex()
    {
        $flaggedTests = \App\Models\Kstl\SampleTest::where('status', SampleTest::STATUS_FLAGGED)
            ->with([
                'assignedTo',
                'attachments.uploadedBy',
                'sample.submission.client.user',
            ])
            ->orderByDesc('updated_at')
            ->get();

        // Group by submission for better context
        $grouped = $flaggedTests->groupBy(fn($test) => $test->sample->submission->id ?? 'unknown');

        return view('kstl.director.flagged.index', compact('flaggedTests', 'grouped'));
    }

    // ── Download agreement PDF ────────────────────────────────────
    public function agreementDownload(string $clientId)
    {
        $client = \App\Models\Kstl\Client::with('user')
            ->findOrFail($clientId);

        abort_if(! $client->service_agreement_signed_at, 404, 'Agreement not yet signed.');

        $director = \Auth::user();

        $user = $client->user;

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView(
            'kstl.client.agreement.pdf',
            compact('client', 'user', 'director')
        )->setPaper('a4', 'portrait');

        $filename = 'KSTL-Service-Agreement-'
            . str_replace(' ', '-', $client->company_name)
            . '-' . now()->format('Ymd')
            . '.pdf';

        \Log::info('Director downloaded service agreement PDF', [
            'client_id'   => $client->id,
            'director_id' => $director->id,
        ]);

        return $pdf->download($filename);
    }

    // ── Download a test's supporting document (review, read-only) ──
    public function downloadAttachment(string $attachment)
    {
        $att = SampleTestAttachment::findOrFail($attachment);

        abort_unless(
            Storage::disk('private')->exists($att->file_path),
            404,
            'File not found.'
        );

        Log::info('Director downloaded test attachment for review', [
            'attachment_id' => $att->id,
            'test_id'       => $att->sample_test_id,
            'user_id'       => Auth::id(),
        ]);

        return Storage::disk('private')->download($att->file_path, $att->original_filename);
    }

    // ── Helpers ────────────────────────────────────────────────────
    private function getFlaggedCount(): int
    {
        return \App\Models\Kstl\SampleTest::where('status', SampleTest::STATUS_FLAGGED)->count();
    }

    private function getAuthorisedToday(): int
    {
        return \App\Models\Kstl\Result::whereDate('authorised_at', today())->count();
    }

    // ── Agreements pending countersign ─────────────────────────────
    public function agreementsIndex()
    {
        $pending     = $this->clientRepo->getPendingCountersign();
        $totalSigned = \App\Models\Kstl\Client::whereNotNull('director_signed_at')->count();

        $executed = \App\Models\Kstl\Client::with('user')
            ->whereNotNull('service_agreement_signed_at')
            ->whereNotNull('director_signed_at')
            ->orderByDesc('director_signed_at')
            ->get();

        return view('kstl.director.agreements.index',
            compact('pending', 'totalSigned', 'executed'));
    }

    // ── Show agreement for countersigning ──────────────────────────
    public function agreementShow(string $clientId)
    {
        $client = $this->clientRepo->getById($clientId);

        abort_if(! $client, 404);
        abort_if(is_null($client->service_agreement_signed_at), 403,
            'Client has not signed the agreement yet.');

        return view('kstl.director.agreements.sign', compact('client'));
    }

    // ── Countersign agreement ──────────────────────────────────────
    public function agreementCountersign(Request $request, string $clientId)
    {
        Log::info('[COUNTERSIGN] Controller hit', [
            'client_id'      => $clientId,
            'director_id'    => Auth::id(),
            'signature_type' => $request->input('signature_type'),
            'has_drawn_data' => ! empty($request->input('signature_data')),
            'has_upload'     => $request->hasFile('signature_upload'),
        ]);

        $client = $this->clientRepo->getById($clientId);

        abort_if(! $client, 404);
        abort_if(! is_null($client->director_signed_at), 403,
            'Agreement has already been countersigned.');

        $validated = $request->validate([
            'signature_type' => ['required', 'in:drawn,draw,uploaded'],
            'signature_data' => ['required_if:signature_type,drawn', 'nullable', 'string'],
            'signature_upload' => ['required_if:signature_type,uploaded', 'nullable', 'file', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        $signatureData = null;
        $signatureType = $validated['signature_type'];
        if ($signatureType === 'draw') {
            $signatureType = 'drawn';
        }

        if (in_array($signatureType, ['drawn', 'draw'])) {
            $signatureData = $request->signature_data;
            if (! $signatureData || strlen($signatureData) < 100) {
                return redirect()->back()
                    ->with('error', 'Please draw your signature before submitting.');
            }
        } elseif ($signatureType === 'uploaded' && $request->hasFile('signature_upload')) {
            $file          = $request->file('signature_upload');
            $mime          = $file->getMimeType();
            $base64        = base64_encode(file_get_contents($file->getRealPath()));
            $signatureData = "data:{$mime};base64,{$base64}";
        }

        if (! $signatureData) {
            return redirect()->back()
                ->with('error', 'Signature is required.');
        }

        $director     = Auth::user();
        $directorName = trim($director->first_name . ' ' . $director->last_name);

        Log::info('[COUNTERSIGN] Calling repository', [
            'signature_size' => $signatureData ? strlen($signatureData) : 0,
            'signature_type' => $signatureType,
            'director_name'  => $directorName,
        ]);

        $this->clientRepo->countersignAgreement(
            clientId:      $clientId,
            directorName:  $directorName,
            directorUserId: $director->id,
            signatureData: $signatureData,
            signatureType: $signatureType,
            signedIp:      $request->ip(),
        );

        $this->auditService->logAgreementCountersigned($client->fresh());

        Log::info('Director countersigned agreement', [
            'client_id'   => $clientId,
            'director_id' => $director->id,
        ]);

        return redirect()->route('director.agreements.index')
            ->with('success', "Agreement for {$client->company_name} has been countersigned.");
    }

    // ── Audit Log ──────────────────────────────────────────────────
    public function auditIndex(Request $request)
    {
        $query = AuditLog::query()->orderByDesc('created_at');

        if ($request->filled('event')) {
            $query->where('event', $request->event);
        }
        if ($request->filled('user')) {
            $term = '%' . $request->user . '%';
            $query->where(function($q) use ($term) {
                $q->where('user_name', 'like', $term)
                  ->orWhere('user_id', 'like', $term);
            });
        }
        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        $logs = $query->paginate(50);

        return view('kstl.director.audit.index', compact('logs'));
    }
}