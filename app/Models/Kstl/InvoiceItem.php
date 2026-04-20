<?php

namespace App\Models\Kstl;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvoiceItem extends Model
{
    use HasUuids;

    protected $table = 'invoice_items';

    protected $fillable = [
        'invoice_id',
        'sample_test_id',
        'item_description',
        'category',
        'unit_price_aud',
        'quantity',
        'total_price_aud',
    ];

    protected function casts(): array
    {
        return [
            'unit_price_aud'  => 'decimal:2',
            'total_price_aud' => 'decimal:2',
        ];
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function sampleTest(): BelongsTo
    {
        return $this->belongsTo(SampleTest::class);
    }
}