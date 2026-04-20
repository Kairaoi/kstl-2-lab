<?php

namespace App\Models\Kstl;

use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    use HasUuids;

    protected $table = 'invoices';

    protected $fillable = [
        'invoice_number',
        'submission_id',
        'result_id',
        'issued_by',
        'bill_to_company',
        'bill_to_address',
        'bill_to_phone',
        'bill_to_email',
        'total_amount_aud',
        'invoice_date',
        'payment_due_date',
        'payment_status',
        'payment_reference',
        'payment_received_at',
        'payment_verified_by',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'invoice_date'        => 'date',
            'payment_due_date'    => 'date',
            'payment_received_at' => 'datetime',
            'total_amount_aud'    => 'decimal:2',
        ];
    }

    const STATUS_UNPAID  = 'unpaid';
    const STATUS_PAID    = 'paid';
    const STATUS_OVERDUE = 'overdue';
    const STATUS_WAIVED  = 'waived';

    // ── Price list from Schedule 2 ─────────────────────────────────
    const TEST_PRICES = [
        'total_coliforms' => 100.00,
        'e_coli'          => 100.00,
        'enterococci'     => 100.00,
        'e_coli_coliform' => 65.00,
        'staph_aureus'    => 65.00,
        'apc'             => 50.00,
        'yeast_mold'      => 50.00,
        'histamine'       => 85.00,
        'ph'              => 50.00,
        'conductivity'    => 50.00,
        'water_activity'  => 50.00,
        'moisture'        => 50.00,
    ];

    // ── Relationships ──────────────────────────────────────────────
    public function submission(): BelongsTo
    {
        return $this->belongsTo(Submission::class);
    }

    public function result(): BelongsTo
    {
        return $this->belongsTo(Result::class);
    }

    public function issuedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'issued_by');
    }

    public function paymentVerifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'payment_verified_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    // ── Helpers ────────────────────────────────────────────────────
    public function isPaid(): bool    { return $this->payment_status === self::STATUS_PAID; }
    public function isUnpaid(): bool  { return $this->payment_status === self::STATUS_UNPAID; }
    public function isOverdue(): bool { return $this->payment_status === self::STATUS_OVERDUE; }
    public function isWaived(): bool  { return $this->payment_status === self::STATUS_WAIVED; }

    public function isPaymentDue(): bool
    {
        return $this->isUnpaid() && $this->payment_due_date->isPast();
    }

    public static function generateNumber(): string
    {
        $last = static::orderByDesc('invoice_number')->value('invoice_number');
        $seq  = $last ? ((int) substr($last, -5)) + 1 : 1;
        return 'INV-' . now()->year . '-' . str_pad($seq, 5, '0', STR_PAD_LEFT);
    }

    // Add 10 working days (skip weekends)
    public static function calculateDueDate(\Carbon\Carbon $from): \Carbon\Carbon
    {
        $date  = $from->copy();
        $added = 0;
        while ($added < 10) {
            $date->addDay();
            if (! $date->isWeekend()) {
                $added++;
            }
        }
        return $date;
    }
}