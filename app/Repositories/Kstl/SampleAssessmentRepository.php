<?php

namespace App\Repositories\Kstl;

use App\Models\Kstl\SampleAssessment;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class SampleAssessmentRepository extends BaseRepository
{
    public function model(): string
    {
        return SampleAssessment::class;
    }

    public function createForSample(string $sampleId, array $input): SampleAssessment
    {
        $assessment = $this->model->create([
            'sample_id'               => $sampleId,
            'assessed_by'             => Auth::id(),
            'assessed_at'             => now(),

            'temperature_ok'          => $input['temperature_ok'],
            'temperature_notes'       => $input['temperature_notes']       ?? null,
            'storage_ok'              => $input['storage_ok'],
            'storage_notes'           => $input['storage_notes']           ?? null,
            'transport_ok'            => $input['transport_ok'],
            'transport_notes'         => $input['transport_notes']         ?? null,
            'packaging_ok'            => $input['packaging_ok'],
            'packaging_notes'         => $input['packaging_notes']         ?? null,
            'colour_ok'               => $input['colour_ok'],
            'colour_notes'            => $input['colour_notes']            ?? null,
            'odour_ok'                => $input['odour_ok'],
            'odour_notes'             => $input['odour_notes']             ?? null,
            'weight_ok'               => $input['weight_ok'],
            'weight_notes'            => $input['weight_notes']            ?? null,
            'additional_observations' => $input['additional_observations'] ?? null,
            'outcome'                 => $input['outcome'],
            'rejection_reason'        => $input['rejection_reason']        ?? null,
        ]);

        Log::info('Sample assessment recorded', [
            'sample_id'   => $sampleId,
            'outcome'     => $assessment->outcome,
            'assessed_by' => Auth::id(),
        ]);

        return $assessment;
    }

    public function recordClientDecision(string $assessmentId, string $decision): bool
    {
        $assessment = $this->getById($assessmentId);

        return $assessment->update([
            'client_decision'             => $decision,
            'client_decision_at'          => now(),
            'client_decision_recorded_by' => Auth::id(),
        ]);
    }

    public function findBySampleId(string $sampleId): ?SampleAssessment
    {
        return $this->model->where('sample_id', $sampleId)->first();
    }

    /**
     * Generate a unique consent token for this assessment.
     * Called when reception clicks "Send Email to Client".
     */
    public function generateConsentToken(string $assessmentId): string
    {
        $token = Str::random(64);

        $this->getById($assessmentId)->update([
            'consent_token'            => $token,
            'consent_token_expires_at' => now()->addDays(7),
        ]);

        Log::info('Consent token generated', [
            'assessment_id' => $assessmentId,
            'expires_at'    => now()->addDays(7)->toDateTimeString(),
            'generated_by'  => Auth::id(),
        ]);

        return $token;
    }

    /**
     * Mark that the system email was sent to the client.
     */
    public function markNotified(string $assessmentId): bool
    {
        return $this->getById($assessmentId)->update([
            'consent_notified_at' => now(),
        ]);
    }

    /**
     * Record client decision via system link (client clicked email).
     */
    public function recordSystemDecision(string $token, string $decision): bool
    {
        $assessment = \App\Models\Kstl\SampleAssessment::findByToken($token);

        if (! $assessment || $assessment->isTokenExpired()) {
            return false;
        }

        return $assessment->update([
            'client_decision'             => $decision,
            'client_decision_at'          => now(),
            'client_decision_recorded_by' => null, // client did it themselves
            'consent_method'              => 'system',
        ]);
    }

    /**
     * Record client decision manually (reception logged it after phone call).
     */
    public function recordManualDecision(string $assessmentId, string $decision): bool
    {
        $assessment = $this->getById($assessmentId);

        return $assessment->update([
            'client_decision'             => $decision,
            'client_decision_at'          => now(),
            'client_decision_recorded_by' => Auth::id(),
            'consent_method'              => 'manual',
        ]);
    }
}