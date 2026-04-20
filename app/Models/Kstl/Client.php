<?php

namespace App\Models\Kstl;

use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use HasUuids, SoftDeletes;

    protected $fillable = [
        'user_id',
        'company_name',
        'address',
        'company_phone',
        'responsible_officer_name',
        'responsible_officer_email',
        'responsible_officer_phone',
        'service_agreement_signed_at',
        'service_agreement_file',
        'signature_data',
        'signature_type',
        'signature_captured_at',
        'internal_notes',
        'director_signature_data',
        'director_signature_type',
        'director_signed_by',
        'director_signed_by_id',
        'director_signed_at',
        'director_signed_ip',
    ];

    protected function casts(): array
    {
        return [
            'service_agreement_signed_at' => 'datetime',
            'signature_captured_at'       => 'datetime',
            'director_signed_at' => 'datetime',
        ];
    }

    // ── Relationships ──────────────────────────────────────────────────────────

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function submissions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Submission::class);
    }

    // ── Agreement Helpers ──────────────────────────────────────────────────────

    public function hasSignedAgreement(): bool
    {
        return ! is_null($this->service_agreement_signed_at);
    }

    public function hasExpiredAgreement(): bool
    {
        if (! $this->service_agreement_signed_at) {
            return false;
        }
        return $this->service_agreement_signed_at->addYear()->isPast();
    }

    public function agreementExpiresAt(): ?\Carbon\Carbon
    {
        return $this->service_agreement_signed_at?->addYear();
    }

    public function daysUntilAgreementExpiry(): ?int
    {
        if (! $this->service_agreement_signed_at) {
            return null;
        }
        return (int) now()->diffInDays($this->service_agreement_signed_at->addYear(), false);
    }
}