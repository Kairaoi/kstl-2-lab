<?php

namespace App\Models\Kstl;

use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentVersion extends Model
{
    use HasUuids;

    protected $fillable = [
        'document_id',
        'version_number',
        'original_filename',
        'file_path',
        'mime_type',
        'file_size',
        'change_note',
        'uploaded_by',
    ];

    protected $casts = [
        'version_number' => 'integer',
        'file_size'      => 'integer',
    ];

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class, 'document_id');
    }

    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function getHumanSizeAttribute(): string
    {
        $bytes = $this->file_size;
        if ($bytes === null) {
            return '—';
        }
        if ($bytes < 1024) {
            return $bytes . ' B';
        }
        $value = $bytes / 1024;
        foreach (['KB', 'MB', 'GB'] as $unit) {
            if ($value < 1024 || $unit === 'GB') {
                return round($value, 1) . ' ' . $unit;
            }
            $value /= 1024;
        }
        return round($value, 1) . ' GB';
    }
}
