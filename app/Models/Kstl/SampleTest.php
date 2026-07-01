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
        // Water testing
        'e_coli_colilert'            => 'E. coli (Colilert)',
        'total_coliform_colilert'    => 'Total Coliform (Colilert)',
        'enterococci_enterolert'     => 'Enterococci (Enterolert)',
        // Microbiological — seafood / general
        'e_coli_coliform'            => 'E. coli & Coliform',
        'staph_aureus'               => 'Staphylococcus aureus',
        'apc'                        => 'Aerobic Plate Count (APC)',
        'yeast_mold'                 => 'Yeast & Mould',
        'salmonella_spp'             => 'Salmonella species',
        'listeria_spp'               => 'Listeria species',
        'clostridium'                => 'Clostridium',
        // Legacy microbiological (kept for existing records)
        'total_coliforms'            => 'Total Coliforms',
        'e_coli'                     => 'E. coli',
        'enterococci'                => 'Enterococci',
        'faecal_coliforms'           => 'Faecal Coliforms',
        'listeria_mono'              => 'Listeria monocytogenes',
        // Chemical
        'histamine'                  => 'Histamine',
        'moisture'                   => 'Moisture',
        // Physical
        'temperature'                => 'Temperature',
        'ph'                         => 'pH',
        'conductivity'               => 'Conductivity',
        'water_activity'             => 'Water Activity',
    ];

    const TEST_CATEGORIES = [
        // Water
        'e_coli_colilert'            => 'water',
        'total_coliform_colilert'    => 'water',
        'enterococci_enterolert'     => 'water',
        // Microbiological
        'e_coli_coliform'            => 'microbiological',
        'staph_aureus'               => 'microbiological',
        'apc'                        => 'microbiological',
        'yeast_mold'                 => 'microbiological',
        'salmonella_spp'             => 'microbiological',
        'listeria_spp'               => 'microbiological',
        'clostridium'                => 'microbiological',
        'total_coliforms'            => 'microbiological',
        'e_coli'                     => 'microbiological',
        'enterococci'                => 'microbiological',
        'faecal_coliforms'           => 'microbiological',
        'listeria_mono'              => 'microbiological',
        // Chemical
        'histamine'                  => 'chemical',
        'moisture'                   => 'chemical',
        // Physical
        'temperature'                => 'physical',
        'ph'                         => 'physical',
        'conductivity'               => 'physical',
        'water_activity'             => 'physical',
    ];

    const TEST_UNITS = [
        'e_coli_colilert'            => 'MPN/100mL',
        'total_coliform_colilert'    => 'MPN/100mL',
        'enterococci_enterolert'     => 'MPN/100mL',
        'e_coli_coliform'            => 'CFU/g',
        'staph_aureus'               => 'CFU/g',
        'apc'                        => 'CFU/g',
        'yeast_mold'                 => 'CFU/g',
        'salmonella_spp'             => 'P/A',
        'listeria_spp'               => 'P/A',
        'clostridium'                => 'CFU/g',
        'total_coliforms'            => 'CFU/g',
        'e_coli'                     => 'CFU/g',
        'enterococci'                => 'CFU/g',
        'faecal_coliforms'           => 'CFU/g',
        'listeria_mono'              => 'P/A',
        'histamine'                  => 'mg/kg',
        'moisture'                   => '%',
        'temperature'                => '°C',
        'ph'                         => '',
        'conductivity'               => 'µS/cm',
        'water_activity'             => '',
    ];

    // ── SOP reference codes per test ───────────────────────────────
    const TEST_SOPS = [
        'e_coli_colilert'            => 'MSOP03',
        'total_coliform_colilert'    => 'MSOP03',
        'enterococci_enterolert'     => 'MSOP04',
        'e_coli_coliform'            => 'MSOP09',
        'staph_aureus'               => 'MSOP07',
        'apc'                        => 'MSOP06',
        'yeast_mold'                 => 'MSOP08',
        'salmonella_spp'             => 'MSOP10',
        'listeria_spp'               => 'MSOP11',
        'clostridium'                => 'MSOP12',
        'total_coliforms'            => 'MSOP01',
        'e_coli'                     => 'MSOP01',
        'enterococci'                => 'MSOP02',
        'faecal_coliforms'           => 'MSOP02',
        'listeria_mono'              => 'MSOP11.A',
        'moisture'                   => 'CHMSOP01',
        'histamine'                  => 'CHMSOP02',
        'temperature'                => 'PHSOP01',
        'ph'                         => 'PHSOP02',
        'conductivity'               => 'PHSOP03',
        'water_activity'             => 'PHSOP04',
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
        $raw = self::TEST_CATEGORIES[$this->test_key] ?? $this->test_category ?? 'other';
        return $raw === 'microbiological' ? 'microbiology' : $raw;
    }

    public function isQueued(): bool      { return $this->status === self::STATUS_QUEUED; }
    public function isInProgress(): bool  { return $this->status === self::STATUS_IN_PROGRESS; }
    public function isCompleted(): bool   { return $this->status === self::STATUS_COMPLETED; }
    public function isFlagged(): bool     { return $this->status === self::STATUS_FLAGGED; }
    public function isPending(): bool     { return $this->result_qualifier === self::QUALIFIER_PENDING; }
}