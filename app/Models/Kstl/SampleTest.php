<?php

namespace App\Models\Kstl;

use App\Models\User;
use App\Traits\HasAuditLogs;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SampleTest extends Model
{
    use HasUuids, HasAuditLogs;

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
        'director_outcome',
        'director_authorised_at',
        'director_authorised_by',
        'director_review_note',
    ];

    protected function casts(): array
    {
        return [
            'started_at'             => 'datetime',
            'completed_at'           => 'datetime',
            'director_authorised_at' => 'datetime',
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
        'total_coliforms'       => 'Total Coliforms',
        'e_coli'                => 'E. coli',
        'enterococci'           => 'Enterococci',
        'faecal_coliforms'      => 'Faecal Coliforms',
        'yeast_mold'            => 'Yeast & Mould',
        'apc'                   => 'APC (Aerobic Plate Count)',
        'e_coli_coliform'       => 'E. coli & Coliform (Petrifilm)',
        'staph_aureus'          => 'Staphylococcus aureus',
        'salmonella_spp'        => 'Salmonella species',
        'listeria_mono'         => 'Listeria monocytogenes',
        'listeria_spp'          => 'Listeria species',
        'e_coli_colilert'       => 'E. coli (Colilert)',
        'enterococci_enterolert' => 'Enterococci (Enterolert)',
        'histamine'             => 'ELISA Histamine Rapid Kit',
        'moisture'              => 'Moisture Content',
        'ph'                    => 'pH',
        'conductivity'          => 'Conductivity',
        'water_activity'        => 'Water Activity',
    ];

    const TEST_CATEGORIES = [
        'total_coliforms'        => 'microbiological',
        'e_coli'                 => 'microbiological',
        'enterococci'            => 'microbiological',
        'faecal_coliforms'       => 'microbiological',
        'yeast_mold'             => 'microbiological',
        'apc'                    => 'microbiological',
        'e_coli_coliform'        => 'microbiological',
        'staph_aureus'           => 'microbiological',
        'salmonella_spp'         => 'microbiological',
        'listeria_mono'          => 'microbiological',
        'listeria_spp'           => 'microbiological',
        'e_coli_colilert'        => 'microbiological',
        'enterococci_enterolert' => 'microbiological',
        'histamine'              => 'chemical',
        'moisture'               => 'chemical',
        'ph'                     => 'chemical',
        'conductivity'           => 'chemical',
        'water_activity'         => 'chemical',
    ];

    // ── SOP reference codes per test ───────────────────────────────
    const TEST_SOPS = [
        'total_coliforms'        => 'MSOP01',
        'e_coli'                 => 'MSOP01',
        'enterococci'            => 'MSOP02',
        'faecal_coliforms'       => 'MSOP02',
        'apc'                    => 'MSOP06',
        'staph_aureus'           => 'MSOP07',
        'yeast_mold'             => 'MSOP08',
        'e_coli_coliform'        => 'MSOP09',
        'salmonella_spp'         => 'MSOP10',
        'listeria_mono'          => 'MSOP11.A',
        'listeria_spp'           => 'MSOP11.B',
        'e_coli_colilert'        => 'MSOP03',
        'enterococci_enterolert' => 'MSOP04',
        'moisture'               => 'CHMSOP01',
        'histamine'              => 'CHMSOP02',
        'ph'                     => 'CHMSOP03',
        'conductivity'           => 'CHMSOP04',
        'water_activity'         => 'CHMSOP05',
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

    public function attachments(): HasMany
    {
        return $this->hasMany(SampleTestAttachment::class, 'sample_test_id')
                    ->latest();
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