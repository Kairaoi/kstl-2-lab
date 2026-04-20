<?php

namespace App\Models\Kstl;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Sample extends Model
{
    use HasUuids;

    protected $table = 'samples';

    protected $fillable = [
        'submission_id',
        'sample_code',
        'sampling_date',
        'common_name',
        'scientific_name',
        'quantity',
        'quantity_unit',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'sampling_date' => 'date',
        ];
    }

    const STATUS_PENDING            = 'pending';
    const STATUS_ACCEPTED           = 'accepted';
    const STATUS_REJECTED           = 'rejected';
    const STATUS_CONSENT_TO_PROCEED = 'consent_to_proceed';
    const STATUS_TESTING            = 'testing';
    const STATUS_COMPLETED          = 'completed';

    // ── Relationships ──────────────────────────────────────────────
    public function submission(): BelongsTo
    {
        return $this->belongsTo(Submission::class);
    }

    public function sampleTests(): HasMany
    {
        return $this->hasMany(SampleTest::class);
    }

    public function assessment(): HasOne
    {
        return $this->hasOne(SampleAssessment::class);
    }

    // ── Helpers ────────────────────────────────────────────────────
    public function isAccepted(): bool { return $this->status === self::STATUS_ACCEPTED; }
    public function isRejected(): bool { return $this->status === self::STATUS_REJECTED; }
    public function isPending(): bool  { return $this->status === self::STATUS_PENDING; }

    public static function generateCode(): string
    {
        $last = static::whereNotNull('sample_code')
            ->orderByDesc('sample_code')
            ->value('sample_code');

        $seq = $last ? ((int) substr($last, 7)) + 1 : 1;

        return 'KSTL-S-' . str_pad($seq, 5, '0', STR_PAD_LEFT);
    }
}