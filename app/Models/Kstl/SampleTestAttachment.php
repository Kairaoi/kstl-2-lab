<?php

namespace App\Models\Kstl;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class SampleTestAttachment extends Model
{
    use HasUuids;

    protected $fillable = [
        'sample_test_id',
        'uploaded_by',
        'original_filename',
        'file_path',
        'mime_type',
        'file_size',
        'description',
    ];

    protected $casts = [
        'file_size' => 'integer',
    ];

    public function sampleTest(): BelongsTo
    {
        return $this->belongsTo(SampleTest::class, 'sample_test_id');
    }

    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * Human-readable file size, e.g. "1.4 MB".
     */
    public function getHumanSizeAttribute(): string
    {
        $bytes = $this->file_size;
        if ($bytes === null) {
            return '—';
        }
        if ($bytes < 1024) {
            return $bytes . ' B';
        }
        $units = ['KB', 'MB', 'GB'];
        $value = $bytes / 1024;
        foreach ($units as $unit) {
            if ($value < 1024 || $unit === 'GB') {
                return round($value, 1) . ' ' . $unit;
            }
            $value /= 1024;
        }
        return round($value, 1) . ' GB';
    }
}
