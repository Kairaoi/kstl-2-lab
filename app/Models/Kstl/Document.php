<?php

namespace App\Models\Kstl;

use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
    use HasUuids, SoftDeletes;

    protected $fillable = [
        'title',
        'category',
        'subcategory',
        'reference_code',
        'description',
        'current_version_id',
        'created_by',
    ];

    // ── Categories ─────────────────────────────────────────────────
    const CATEGORY_SOP                = 'sop';
    const CATEGORY_MANUAL             = 'manual';
    const CATEGORY_ASSESSMENT_RECORD  = 'assessment_record';
    const CATEGORY_TEMPLATE           = 'template';

    const CATEGORY_LABELS = [
        'sop'               => 'SOPs',
        'manual'            => 'Manuals',
        'assessment_record' => 'Assessment Records',
        'template'          => 'Templates',
    ];

    // ── Relationships ──────────────────────────────────────────────
    public function versions(): HasMany
    {
        return $this->hasMany(DocumentVersion::class, 'document_id')
                    ->orderByDesc('version_number');
    }

    public function currentVersion(): BelongsTo
    {
        return $this->belongsTo(DocumentVersion::class, 'current_version_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // ── Helpers ────────────────────────────────────────────────────
    public function getCategoryLabelAttribute(): string
    {
        return self::CATEGORY_LABELS[$this->category] ?? ucfirst($this->category);
    }

    public function getNextVersionNumberAttribute(): int
    {
        return (int) $this->versions()->max('version_number') + 1;
    }
}
