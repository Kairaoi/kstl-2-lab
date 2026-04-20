<?php

namespace App\Http\Controllers\Kstl;

use App\Http\Controllers\Controller;
use App\Repositories\Kstl\SubmissionRepository;
use App\Repositories\Kstl\SampleRepository;
use App\Repositories\Kstl\SampleAssessmentRepository;
use App\Models\Kstl\Submission;
use App\Models\Kstl\Sample;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Mail\SampleRejectedMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ReceptionController extends Controller
{
    public function __construct(
        protected SubmissionRepository       $submissionRepo,
        protected SampleRepository           $sampleRepo,
        protected SampleAssessmentRepository $assessmentRepo,
    ) {}

    // ── Dashboard — pending submissions queue ──────────────────────
    public function dashboard()
    {
        $pending = $this->submissionRepo->getPendingReceipt();

        // Historical records — submissions already processed by reception
        $processed = \App\Models\Kstl\Submission::with(['client.user'])
            ->whereIn('status', [
                \App\Models\Kstl\Submission::STATUS_TESTING,
                \App\Models\Kstl\Submission::STATUS_AWAITING_AUTHORISATION,
                \App\Models\Kstl\Submission::STATUS_AUTHORISED,
                \App\Models\Kstl\Submission::STATUS_COMPLETED,
                \App\Models\Kstl\Submission::STATUS_REJECTED,
                \App\Models\Kstl\Submission::STATUS_CANCELLED,
            ])
            ->orderByDesc('updated_at')
            ->limit(20)
            ->get();

        return view('kstl.reception.dashboard', compact('pending', 'processed'));
    }

    // ── Show a submission detail ───────────────────────────────────
    public function show(string $id)
    {
        $submission = $this->submissionRepo->getById($id);
        $samples    = $this->sampleRepo->getBySubmissionId($id);

        return view('kstl.reception.submissions.show',
            compact('submission', 'samples'));
    }

    // ── Mark submission as received — log physical arrival ─────────
    public function markReceived(Request $request, string $id)
    {
        $submission = $this->submissionRepo->getById($id);

        if ($submission->status !== Submission::STATUS_SUBMITTED) {
            return redirect()->back()
                ->with('error', 'This submission has already been received.');
        }

        $validated = $request->validate([
            'samples'                   => ['required', 'array', 'min:1'],
            'samples.*.sampling_date'   => ['required', 'date'],
            'samples.*.common_name'     => ['required', 'string', 'max:255'],
            'samples.*.scientific_name' => ['nullable', 'string', 'max:255'],
            'samples.*.quantity'        => ['required', 'numeric', 'min:0'],
            'samples.*.quantity_unit'   => ['required', 'in:g,kg,ml,L,pcs'],
            'samples.*.notes'           => ['nullable', 'string', 'max:1000'],
        ]);

        DB::transaction(function () use ($submission, $validated) {
            $this->sampleRepo->createForSubmission(
                $submission->id,
                $validated['samples']
            );

            $this->submissionRepo->markReceived(
                $submission->id,
                Auth::id()
            );
        });

        Log::info('Submission marked as received', [
            'submission_id' => $submission->id,
            'received_by'   => Auth::id(),
            'sample_count'  => count($validated['samples']),
        ]);

        return redirect()->route('reception.submissions.assess', $submission->id)
            ->with('success', 'Submission received. Please complete the sample assessment.');
    }

    // ── Show assessment form ───────────────────────────────────────
    public function assessForm(string $id)
    {
        $submission = $this->submissionRepo->getById($id);
        $samples    = $this->sampleRepo->getBySubmissionId($id);

        return view('kstl.reception.submissions.assess',
            compact('submission', 'samples'));
    }

    // ── Save assessment ────────────────────────────────────────────
    public function assessStore(Request $request, string $id)
    {
        Log::info('=== Assessment store started ===', [
            'submission_id' => $id,
            'user_id'       => Auth::id(),
            'raw_input'     => $request->except('_token'),
        ]);

        $submission = $this->submissionRepo->getById($id);

        $validated = $request->validate([
            'assessments'                           => ['required', 'array'],
            'assessments.*.sample_id'               => ['required', 'string'],
            'assessments.*.temperature_ok'          => ['required', 'in:0,1'],
            'assessments.*.temperature_notes'       => ['nullable', 'string'],
            'assessments.*.storage_ok'              => ['required', 'in:0,1'],
            'assessments.*.storage_notes'           => ['nullable', 'string'],
            'assessments.*.transport_ok'            => ['required', 'in:0,1'],
            'assessments.*.transport_notes'         => ['nullable', 'string'],
            'assessments.*.packaging_ok'            => ['required', 'in:0,1'],
            'assessments.*.packaging_notes'         => ['nullable', 'string'],
            'assessments.*.colour_ok'               => ['required', 'in:0,1'],
            'assessments.*.colour_notes'            => ['nullable', 'string'],
            'assessments.*.odour_ok'                => ['required', 'in:0,1'],
            'assessments.*.odour_notes'             => ['nullable', 'string'],
            'assessments.*.weight_ok'               => ['required', 'in:0,1'],
            'assessments.*.weight_notes'            => ['nullable', 'string'],
            'assessments.*.additional_observations' => ['nullable', 'string'],
            'assessments.*.rejection_reason'        => ['nullable', 'string'],
        ]);

        $allAccepted = true;

        DB::transaction(function () use ($validated, $submission, &$allAccepted) {
            foreach ($validated['assessments'] as $data) {
                $sample = $this->sampleRepo->getById($data['sample_id']);

                // Cast "1"/"0" strings to booleans
                foreach (['temperature_ok','storage_ok','transport_ok','packaging_ok','colour_ok','odour_ok','weight_ok'] as $f) {
                    $data[$f] = (bool) $data[$f];
                }

                $criteria = [
                    $data['temperature_ok'], $data['storage_ok'],
                    $data['transport_ok'],   $data['packaging_ok'],
                    $data['colour_ok'],      $data['odour_ok'],
                    $data['weight_ok'],
                ];

                $hasFail         = in_array(false, $criteria, true);
                $data['outcome'] = $hasFail ? 'rejected' : 'accepted';

                $this->assessmentRepo->createForSample($sample->id, $data);

                $this->sampleRepo->updateStatus(
                    $sample->id,
                    $hasFail ? Sample::STATUS_REJECTED : Sample::STATUS_ACCEPTED
                );

                if ($hasFail) $allAccepted = false;
            }

            $newStatus = $allAccepted
                ? Submission::STATUS_ACCEPTED
                : Submission::STATUS_REJECTED;

            $this->submissionRepo->updateStatus($submission->id, $newStatus);
        });

        $msg = $allAccepted
            ? 'All samples accepted. Submission moved to testing queue.'
            : 'One or more samples rejected. Client will be notified.';

        return redirect()->route('reception.dashboard')
            ->with($allAccepted ? 'success' : 'warning', $msg);
    }

    // ── Send submission to testing queue ──────────────────────────
    public function sendToTesting(Request $request, string $id)
    {
        $submission = $this->submissionRepo->getById($id);

        if (! in_array($submission->status, [
            Submission::STATUS_ACCEPTED,
            Submission::STATUS_CONSENT_TO_PROCEED,
        ])) {
            return redirect()->back()
                ->with('error', 'This submission cannot be sent to testing at this stage.');
        }

        $samples = $this->sampleRepo->getBySubmissionId($id);

        DB::transaction(function () use ($submission, $samples) {
            foreach ($samples as $sample) {
                if (in_array($sample->status, [
                    Sample::STATUS_ACCEPTED,
                    Sample::STATUS_CONSENT_TO_PROCEED,
                ])) {
                    $tests = is_array($submission->tests_requested)
                        ? $submission->tests_requested
                        : json_decode($submission->tests_requested ?? '[]', true) ?? [];

                    foreach ($tests as $testKey) {
                        \App\Models\Kstl\SampleTest::firstOrCreate(
                            ['sample_id' => $sample->id, 'test_key' => $testKey],
                            ['status' => 'queued']
                        );
                    }

                    $this->sampleRepo->updateStatus($sample->id, Sample::STATUS_TESTING);
                }
            }

            $this->submissionRepo->updateStatus($submission->id, Submission::STATUS_TESTING);
        });

        Log::info('Submission sent to testing queue', [
            'submission_id' => $submission->id,
            'reference'     => $submission->reference_number,
            'sent_by'       => Auth::id(),
        ]);

        return redirect()->route('reception.dashboard')
            ->with('success', "Submission {$submission->reference_number} sent to testing queue.");
    }

    // ── Send consent email to client ──────────────────────────────
    public function notifyClient(Request $request, string $assessmentId)
    {
        $assessment = $this->assessmentRepo->getById($assessmentId);
        $sample     = $this->sampleRepo->getById($assessment->sample_id);
        $client     = $sample->submission->client;

        if (! $assessment->consent_token) {
            $this->assessmentRepo->generateConsentToken($assessmentId);
            $assessment->refresh();
        }

        Mail::to($client->user->email)
            ->send(new SampleRejectedMail($sample, $assessment));

        $this->assessmentRepo->markNotified($assessmentId);

        Log::info('Consent email sent to client', [
            'assessment_id' => $assessmentId,
            'client_email'  => $client->user->email,
            'sent_by'       => Auth::id(),
        ]);

        return redirect()->back()
            ->with('success', 'Consent email sent to ' . $client->user->email);
    }

    // ── Show consent form ─────────────────────────────────────────
    public function consentForm(string $id)
    {
        $submission = $this->submissionRepo->getById($id);
        $samples    = $this->sampleRepo->getBySubmissionId($id);

        Log::info('Consent form loaded', [
            'submission_id' => $id,
            'user_id'       => Auth::id(),
        ]);

        return view('kstl.reception.submissions.consent',
            compact('submission', 'samples'));
    }

    // ── Record client consent to proceed after rejection ───────────
    public function recordConsent(Request $request, string $assessmentId)
    {
        $validated = $request->validate([
            'decision' => ['required', 'in:consent_to_proceed,confirm_rejection'],
        ]);

        $this->assessmentRepo->recordClientDecision(
            $assessmentId,
            $validated['decision']
        );

        if ($validated['decision'] === 'consent_to_proceed') {
            $assessment = $this->assessmentRepo->getById($assessmentId);

            $this->sampleRepo->updateStatus(
                $assessment->sample_id,
                Sample::STATUS_CONSENT_TO_PROCEED
            );

            $submission = $assessment->sample->submission;
            $pending    = $submission->samples()
                ->where('status', Sample::STATUS_REJECTED)
                ->whereDoesntHave('assessment', fn($q) =>
                    $q->whereNotNull('client_decision'))
                ->count();

            if ($pending === 0) {
                $this->submissionRepo->updateStatus(
                    $submission->id,
                    Submission::STATUS_CONSENT_TO_PROCEED
                );
            }
        }

        return redirect()->back()
            ->with('success', 'Client decision recorded.');
    }
}