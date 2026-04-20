<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class AuditLog extends Model
{
    use HasUuids;

    protected $table = 'audit_logs';

    // Append-only — never update
    public $timestamps  = false;
    const CREATED_AT    = 'created_at';

    protected $fillable = [
        'user_id',
        'user_name',
        'event',
        'description',
        'auditable_type',
        'auditable_id',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'old_values' => 'array',
            'new_values' => 'array',
            'created_at' => 'datetime',
        ];
    }

    // ── Event constants ────────────────────────────────────────────
    const EVENT_CREATED      = 'created';
    const EVENT_UPDATED      = 'updated';
    const EVENT_DELETED      = 'deleted';
    const EVENT_RESTORED     = 'restored';
    const EVENT_LOGIN        = 'login';
    const EVENT_LOGOUT       = 'logout';
    const EVENT_SIGNED       = 'signed';
    const EVENT_COUNTERSIGNED = 'countersigned';
    const EVENT_AUTHORISED   = 'authorised';
    const EVENT_QUERIED      = 'queried';
    const EVENT_SUBMITTED    = 'submitted';
    const EVENT_STATUS_CHANGED = 'status_changed';
    const EVENT_GENERATED    = 'generated';
    const EVENT_RESPONDED    = 'responded';

    // ── Relationships ──────────────────────────────────────────────
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function auditable(): MorphTo
    {
        return $this->morphTo();
    }

    // ── Prevent updates ────────────────────────────────────────────
    public function save(array $options = []): bool
    {
        if (! $this->exists) {
            $this->created_at = now();
            return parent::save($options);
        }
        // Never allow updates to audit logs
        return false;
    }
}