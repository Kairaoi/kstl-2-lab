<?php

namespace App\Models\Kstl;

use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SampleAssessment extends Model
{
    use HasUuids;

    protected $table = 'sample_assessments';

    protected $fillable = [
        'sample_id',
        'assessed_by',
        'assessed_at',
        'temperature_ok',    'temperature_notes',
        'storage_ok',        'storage_notes',
        'transport_ok',      'transport_notes',
        'packaging_ok',      'packaging_notes',
        'colour_ok',         'colour_notes',
        'odour_ok',          'odour_notes',
        'weight_ok',         'weight_notes',
        'additional_observations',
        'outcome',
        'rejection_reason',
        'client_decision',
        'client_decision_at',
        'client_decision_recorded_by',
        'consent_token',
        'consent_notified_at',
        'consent_token_expires_at',
        'consent_method',
    ];

    protected function casts(): array
    {
        return [
            'assessed_at'                  => 'datetime',
            'client_decision_at'           => 'datetime',
            'consent_notified_at'          => 'datetime',
            'consent_token_expires_at'     => 'datetime',
            'temperature_ok'     => 'boolean',
            'storage_ok'         => 'boolean',
            'transport_ok'       => 'boolean',
            'packaging_ok'       => 'boolean',
            'colour_ok'          => 'boolean',
            'odour_ok'           => 'boolean',
            'weight_ok'          => 'boolean',
        ];
    }

    const OUTCOME_ACCEPTED      = 'accepted';
    const OUTCOME_ACCEPTED_NOTE = 'accepted_with_note';
    const OUTCOME_REJECTED      = 'rejected';

    // ── Relationships ──────────────────────────────────────────────
    public function sample(): BelongsTo
    {
        return $this->belongsTo(Sample::class);
    }

    public function assessedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assessed_by');
    }

    public function clientDecisionRecordedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_decision_recorded_by');
    }

    // ── Helpers ────────────────────────────────────────────────────
    public function allCriteriaPassed(): bool
    {
        return $this->temperature_ok && $this->storage_ok &&
               $this->transport_ok   && $this->packaging_ok &&
               $this->colour_ok      && $this->odour_ok &&
               $this->weight_ok;
    }

    public function anyCriteriaFailed(): bool
    {
        return in_array(false, [
            $this->temperature_ok, $this->storage_ok,
            $this->transport_ok,   $this->packaging_ok,
            $this->colour_ok,      $this->odour_ok,
            $this->weight_ok,
        ], true);
    }

    // ── Consent token helpers ──────────────────────────────────────
    public function hasBeenNotified(): bool
    {
        return ! is_null($this->consent_notified_at);
    }

    public function isTokenExpired(): bool
    {
        if (is_null($this->consent_token_expires_at)) {
            return true;
        }
        return $this->consent_token_expires_at->isPast();
    }

    public function isTokenValid(string $token): bool
    {
        return $this->consent_token === $token && ! $this->isTokenExpired();
    }

    public static function findByToken(string $token): ?self
    {
        return static::where('consent_token', $token)->first();
    }
}