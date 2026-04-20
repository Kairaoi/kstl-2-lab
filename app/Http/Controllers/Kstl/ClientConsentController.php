<?php

namespace App\Http\Controllers\Kstl;

use App\Http\Controllers\Controller;
use App\Models\Kstl\SampleAssessment;
use App\Models\Kstl\Sample;
use App\Repositories\Kstl\SampleAssessmentRepository;
use App\Repositories\Kstl\SampleRepository;
use App\Repositories\Kstl\SubmissionRepository;
use App\Models\Kstl\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ClientConsentController extends Controller
{
    public function __construct(
        protected SampleAssessmentRepository $assessmentRepo,
        protected SampleRepository           $sampleRepo,
        protected SubmissionRepository       $submissionRepo,
    ) {}

    /**
     * Show the client-facing consent page.
     * No authentication required — access is via signed token.
     */
    public function show(Request $request, string $token)
    {
        $assessment = SampleAssessment::findByToken($token);

        // Token not found
        if (! $assessment) {
            return view('kstl.client.consent.invalid', [
                'reason' => 'This consent link is invalid or has already been used.',
            ]);
        }

        // Token expired
        if ($assessment->isTokenExpired()) {
            return view('kstl.client.consent.invalid', [
                'reason' => 'This consent link has expired. Please contact the lab directly.',
                'lab_email' => config('mail.from.address'),
            ]);
        }

        // Already decided
        if ($assessment->client_decision) {
            return view('kstl.client.consent.confirmed', compact('assessment'));
        }

        $sample     = $assessment->sample;
        $submission = $sample->submission;

        // Pre-select decision if passed via query string (from email buttons)
        $preselect = $request->query('decision');
        if ($preselect && in_array($preselect, ['consent_to_proceed', 'confirm_rejection'])) {
            // Auto-submit if coming directly from email button
            return $this->store($request->merge(['decision' => $preselect]), $token);
        }

        Log::info('Client consent page visited', [
            'token'         => substr($token, 0, 8) . '...',
            'assessment_id' => $assessment->id,
            'sample_code'   => $sample->sample_code,
        ]);

        return view('kstl.client.consent.show', compact('assessment', 'sample', 'submission', 'token'));
    }

    /**
     * Record the client's decision via the token link.
     */
    public function store(Request $request, string $token)
    {
        $assessment = SampleAssessment::findByToken($token);

        if (! $assessment || $assessment->isTokenExpired()) {
            return redirect()->route('client.consent.show', $token)
                ->with('error', 'This link is invalid or has expired.');
        }

        if ($assessment->client_decision) {
            return view('kstl.client.consent.confirmed', compact('assessment'));
        }

        $validated = $request->validate([
            'decision' => ['required', 'in:consent_to_proceed,confirm_rejection'],
        ]);

        // Record the decision
        $this->assessmentRepo->recordSystemDecision($token, $validated['decision']);

        $assessment->refresh();
        $sample     = $assessment->sample;
        $submission = $sample->submission;

        // Update sample status
        $newSampleStatus = $validated['decision'] === 'consent_to_proceed'
            ? Sample::STATUS_CONSENT_TO_PROCEED
            : Sample::STATUS_REJECTED;

        $this->sampleRepo->updateStatus($sample->id, $newSampleStatus);

        // Check if all rejected samples now have decisions
        if ($validated['decision'] === 'consent_to_proceed') {
            $pendingDecisions = $submission->samples()
                ->where('status', Sample::STATUS_REJECTED)
                ->whereDoesntHave('assessment', fn($q) =>
                    $q->whereNotNull('client_decision'))
                ->count();

            if ($pendingDecisions === 0) {
                $this->submissionRepo->updateStatus(
                    $submission->id,
                    Submission::STATUS_CONSENT_TO_PROCEED
                );
            }
        }

        Log::info('Client consent recorded via system link', [
            'assessment_id' => $assessment->id,
            'sample_code'   => $sample->sample_code,
            'decision'      => $validated['decision'],
            'method'        => 'system',
        ]);

        return view('kstl.client.consent.confirmed', compact('assessment'));
    }
}