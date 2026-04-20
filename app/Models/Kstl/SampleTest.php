<?php

namespace App\Models\Kstl;

use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SampleTest extends Model
{
    use HasUuids;

    protected $table = 'sample_tests';

    protected $fillable = [
        'sample_id',
        'test_key',
        'test_label',
        'test_category',
        'assigned_to',
        'price_aud_snapshot',
        'result_value',
        'result_unit',
        'result_qualifier',
        'result_notes',
        'result_file',
        'started_at',
        'completed_at',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'started_at'   => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    // ── Status constants ───────────────────────────────────────────
    const STATUS_QUEUED      = 'queued';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_COMPLETED   = 'completed';
    const STATUS_FLAGGED     = 'flagged';

    // ── Result qualifier constants ─────────────────────────────────
    const QUALIFIER_DETECTED     = 'detected';
    const QUALIFIER_NOT_DETECTED = 'not_detected';
    const QUALIFIER_PASS         = 'pass';
    const QUALIFIER_FAIL         = 'fail';
    const QUALIFIER_LESS_THAN    = 'less_than';
    const QUALIFIER_GREATER_THAN = 'greater_than';
    const QUALIFIER_EQUAL_TO     = 'equal_to';
    const QUALIFIER_PENDING      = 'pending';

    // ── Test label map (matches create blade keys) ─────────────────
    const TEST_LABELS = [
        'total_coliforms' => 'Total Coliforms',
        'e_coli'          => 'E. coli (Colilert)',
        'enterococci'     => 'Enterococci & Faecal Coliforms',
        'yeast_mold'      => 'Yeast & Mold',
        'apc'             => 'APC (Aerobic Plate Count)',
        'e_coli_coliform' => 'E. coli & Coliform (Petrifilm)',
        'staph_aureus'    => 'Staph. aureus',
        'histamine'       => 'Histamine — Rapid Kit',
        'moisture'        => 'Moisture',
        'ph'              => 'pH',
        'conductivity'    => 'Conductivity',
        'water_activity'  => 'Water Activity',
    ];

    const TEST_CATEGORIES = [
        'total_coliforms' => 'microbiological',
        'e_coli'          => 'microbiological',
        'enterococci'     => 'microbiological',
        'yeast_mold'      => 'microbiological',
        'apc'             => 'microbiological',
        'e_coli_coliform' => 'microbiological',
        'staph_aureus'    => 'microbiological',
        'histamine'       => 'chemical',
        'moisture'        => 'chemical',
        'ph'              => 'chemical',
        'conductivity'    => 'chemical',
        'water_activity'  => 'chemical',
    ];

    // ── Relationships ──────────────────────────────────────────────
    public function sample(): BelongsTo
    {
        return $this->belongsTo(Sample::class);
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    // ── Helpers ────────────────────────────────────────────────────
    public function getDisplayLabel(): string
    {
        return self::TEST_LABELS[$this->test_key]
            ?? $this->test_label
            ?? str_replace('_', ' ', ucfirst($this->test_key));
    }

    public function getDisplayCategory(): string
    {
        return self::TEST_CATEGORIES[$this->test_key] ?? $this->test_category ?? 'other';
    }

    public function isQueued(): bool      { return $this->status === self::STATUS_QUEUED; }
    public function isInProgress(): bool  { return $this->status === self::STATUS_IN_PROGRESS; }
    public function isCompleted(): bool   { return $this->status === self::STATUS_COMPLETED; }
    public function isFlagged(): bool     { return $this->status === self::STATUS_FLAGGED; }
    public function isPending(): bool     { return $this->result_qualifier === self::QUALIFIER_PENDING; }
}