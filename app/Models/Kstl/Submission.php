<?php

namespace App\Models\Kstl;

use App\Models\Kstl\Invoice;
use App\Models\Kstl\Result;
use App\Models\Kstl\Sample;
use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Submission extends Model
{
    use HasUuids, SoftDeletes, HasFactory;

    protected $table = 'submissions';

    protected $fillable = [
        // Core
        'reference_number',
        'client_id',
        'received_by',

        // Sample Information (Schedule 1: Sample table)
        'sample_name',           // Common Name
        'scientific_name',       // Scientific Name
        'sample_description',
        'sample_type',           // fish, shellfish, seaweed, water, sediment, other
        'sample_quantity',
        'sample_quantity_unit',
        'collected_at',          // Sampling Date
        'collection_location',

        // Tests Requested (Schedule 1: Chemical / Microbiology)
        'tests_requested',
        'tests_other',

        // Transport Method (Schedule 1: Frozen / Chill / Fresh)
        'transport_method',
        'transport_detail',      // Specific method e.g. air_freight_frozen, road_chilled_van

        // Priority & Instructions
        'priority',
        'special_instructions',
        'results_required_by',

        // Schedule 1 header fields
        'service_mode',
        'application_date',

        // Declaration / Submitter
        'submitter_name',
        'submitter_designation',

        // Timestamps & Status
        'submitted_at',
        'received_at',
        'status',

        // Notes
        'client_notes',
        'lab_notes',
    ];

    protected function casts(): array
    {
        return [
            'tests_requested'    => 'array',
            'application_date'   => 'date',
            'collected_at'       => 'date',
            'results_required_by'=> 'date',
            'submitted_at'       => 'datetime',
            'received_at'        => 'datetime',
        ];
    }

    // ── Status Constants ───────────────────────────────────────────────────────

    const STATUS_SUBMITTED              = 'submitted';
    const STATUS_RECEIVED               = 'received';
    const STATUS_ASSESSING              = 'assessing';
    const STATUS_ACCEPTED               = 'accepted';
    const STATUS_REJECTED               = 'rejected';
    const STATUS_CONSENT_TO_PROCEED     = 'consent_to_proceed';
    const STATUS_TESTING                = 'testing';
    const STATUS_AWAITING_AUTHORISATION = 'awaiting_authorisation';
    const STATUS_AUTHORISED             = 'authorised';
    const STATUS_COMPLETED              = 'completed';
    const STATUS_CANCELLED              = 'cancelled';

    public static function statuses(): array
    {
        return [
            self::STATUS_SUBMITTED,
            self::STATUS_RECEIVED,
            self::STATUS_ASSESSING,
            self::STATUS_ACCEPTED,
            self::STATUS_REJECTED,
            self::STATUS_CONSENT_TO_PROCEED,
            self::STATUS_TESTING,
            self::STATUS_AWAITING_AUTHORISATION,
            self::STATUS_AUTHORISED,
            self::STATUS_COMPLETED,
            self::STATUS_CANCELLED,
        ];
    }

    public static function statusLabels(): array
    {
        return [
            self::STATUS_SUBMITTED              => 'Submitted',
            self::STATUS_RECEIVED               => 'Received',
            self::STATUS_ASSESSING              => 'Assessing',
            self::STATUS_ACCEPTED               => 'Accepted',
            self::STATUS_REJECTED               => 'Rejected',
            self::STATUS_CONSENT_TO_PROCEED     => 'Consent to Proceed',
            self::STATUS_TESTING                => 'Testing',
            self::STATUS_AWAITING_AUTHORISATION => 'Awaiting Authorisation',
            self::STATUS_AUTHORISED             => 'Authorised',
            self::STATUS_COMPLETED              => 'Completed',
            self::STATUS_CANCELLED              => 'Cancelled',
        ];
    }

    // ── Relationships ──────────────────────────────────────────────────────────

    public function client(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function receivedBy(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    public function samples(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Sample::class);
    }

    public function result(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Result::class);
    }

    public function invoice(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Invoice::class);
    }

    // ── Accessors ──────────────────────────────────────────────────────────────

    public function getStatusLabelAttribute(): string
    {
        return self::statusLabels()[$this->status] ?? ucfirst($this->status);
    }

    public function getShortReferenceAttribute(): string
    {
        return $this->reference_number;
    }

    // ── Status Helpers ─────────────────────────────────────────────────────────

    public function isSubmitted(): bool             { return $this->status === self::STATUS_SUBMITTED; }
    public function isReceived(): bool              { return $this->status === self::STATUS_RECEIVED; }
    public function isAssessing(): bool             { return $this->status === self::STATUS_ASSESSING; }
    public function isAccepted(): bool              { return $this->status === self::STATUS_ACCEPTED; }
    public function isRejected(): bool              { return $this->status === self::STATUS_REJECTED; }
    public function isConsentToProceed(): bool      { return $this->status === self::STATUS_CONSENT_TO_PROCEED; }
    public function isTesting(): bool               { return $this->status === self::STATUS_TESTING; }
    public function isAwaitingAuthorisation(): bool { return $this->status === self::STATUS_AWAITING_AUTHORISATION; }
    public function isAuthorised(): bool            { return $this->status === self::STATUS_AUTHORISED; }
    public function isCompleted(): bool             { return $this->status === self::STATUS_COMPLETED; }
    public function isCancelled(): bool             { return $this->status === self::STATUS_CANCELLED; }

    public function isEditable(): bool
    {
        return $this->status === self::STATUS_SUBMITTED;
    }

    public function isCancellable(): bool
    {
        return in_array($this->status, [
            self::STATUS_SUBMITTED,
            self::STATUS_RECEIVED,
            self::STATUS_ASSESSING,
        ]);
    }

    public function hasResult(): bool
    {
        return in_array($this->status, [
            self::STATUS_AUTHORISED,
            self::STATUS_COMPLETED,
        ]);
    }

    // ── Reference Number Generator ─────────────────────────────────────────────

    public static function generateReference(): string
    {
        $year   = now()->year;
        $prefix = "KSTL-{$year}-";

        $last = static::withTrashed()
            ->where('reference_number', 'like', $prefix . '%')
            ->orderByDesc('reference_number')
            ->value('reference_number');

        $sequence = $last
            ? (int) substr($last, strlen($prefix)) + 1
            : 1;

        return $prefix . str_pad($sequence, 5, '0', STR_PAD_LEFT);
    }

    // ── Scopes ─────────────────────────────────────────────────────────────────

    public function scopeForClient($query, string $clientId)
    {
        return $query->where('client_id', $clientId);
    }

    public function scopeWithStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function scopePending($query)
    {
        return $query->whereIn('status', [
            self::STATUS_SUBMITTED,
            self::STATUS_RECEIVED,
            self::STATUS_ASSESSING,
        ]);
    }

    public function scopeInProgress($query)
    {
        return $query->whereIn('status', [
            self::STATUS_ACCEPTED,
            self::STATUS_CONSENT_TO_PROCEED,
            self::STATUS_TESTING,
            self::STATUS_AWAITING_AUTHORISATION,
        ]);
    }

    public function scopeCompleted($query)
    {
        return $query->whereIn('status', [
            self::STATUS_AUTHORISED,
            self::STATUS_COMPLETED,
        ]);
    }
}