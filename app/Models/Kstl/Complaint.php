<?php

namespace App\Models\Kstl;

use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Complaint extends Model
{
    use HasUuids, SoftDeletes;

    protected $table = 'complaints';

    protected $fillable = [
        'complainant_user_id',
        'complainant_name',
        'complainant_contact',
        'complainant_email',
        'complainant_organisation',
        'incident_date',
        'subject',
        'complaint_types',
        'other_complaint_type',
        'description',
        'submission_id',
        'assigned_to',
        'lab_response',
        'action_taken',
        'resolved_by',
        'resolved_at',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'complaint_types' => 'array',
            'incident_date'   => 'date',
            'resolved_at'     => 'datetime',
        ];
    }

    const STATUS_OPEN                = 'open';
    const STATUS_UNDER_INVESTIGATION = 'under_investigation';
    const STATUS_RESOLVED            = 'resolved';
    const STATUS_CLOSED              = 'closed';

    const COMPLAINT_TYPES = [
        'sample_handling'     => 'Sample Handling',
        'staff_conduct'       => 'Staff Conduct',
        'delay_in_results'    => 'Delay in Results',
        'poor_customer_service' => 'Poor Customer Service',
        'billing'             => 'Billing Issue',
        'other'               => 'Other',
    ];

    // ── Relationships ──────────────────────────────────────────────
    public function complainant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'complainant_user_id');
    }

    public function submission(): BelongsTo
    {
        return $this->belongsTo(Submission::class);
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function resolvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }

    // ── Helpers ────────────────────────────────────────────────────
    public function isOpen(): bool                { return $this->status === self::STATUS_OPEN; }
    public function isUnderInvestigation(): bool  { return $this->status === self::STATUS_UNDER_INVESTIGATION; }
    public function isResolved(): bool            { return $this->status === self::STATUS_RESOLVED; }
    public function isClosed(): bool              { return $this->status === self::STATUS_CLOSED; }

    public function getComplaintTypeLabels(): array
    {
        return collect($this->complaint_types ?? [])
            ->map(fn($t) => self::COMPLAINT_TYPES[$t] ?? ucfirst($t))
            ->toArray();
    }

    public function getStatusColour(): string
    {
        return match($this->status) {
            self::STATUS_OPEN                => 'bg-yellow-50 text-yellow-700',
            self::STATUS_UNDER_INVESTIGATION => 'bg-blue-50 text-blue-700',
            self::STATUS_RESOLVED            => 'bg-green-50 text-green-700',
            self::STATUS_CLOSED              => 'bg-gray-100 text-gray-500',
            default                          => 'bg-gray-100 text-gray-500',
        };
    }
}