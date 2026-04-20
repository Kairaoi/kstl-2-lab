<?php

namespace App\Models\Kstl;

use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Result extends Model
{
    use HasUuids;

    protected $table = 'results';

    protected $fillable = [
        'submission_id',
        'authorised_by',
        'overall_outcome',
        'director_comments',
        'result_query_notes',
        'authorised_at',
        'signed_report_file',
        'report_uploaded_at',
        'client_notified_at',
        'client_collected_at',
        'collected_by_name',
    ];

    protected function casts(): array
    {
        return [
            'authorised_at'       => 'datetime',
            'report_uploaded_at'  => 'datetime',
            'client_notified_at'  => 'datetime',
            'client_collected_at' => 'datetime',
        ];
    }

    const OUTCOME_PASS         = 'pass';
    const OUTCOME_FAIL         = 'fail';
    const OUTCOME_INCONCLUSIVE = 'inconclusive';

    // ── Relationships ──────────────────────────────────────────────
    public function submission(): BelongsTo
    {
        return $this->belongsTo(Submission::class);
    }

    public function authorisedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'authorised_by');
    }

    // ── Helpers ────────────────────────────────────────────────────
    public function isPass(): bool         { return $this->overall_outcome === self::OUTCOME_PASS; }
    public function isFail(): bool         { return $this->overall_outcome === self::OUTCOME_FAIL; }
    public function isInconclusive(): bool { return $this->overall_outcome === self::OUTCOME_INCONCLUSIVE; }
}