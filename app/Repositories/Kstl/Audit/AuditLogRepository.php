<?php
// app/Repositories/Kstl/Audit/AuditLogRepository.php

namespace App\Repositories\Kstl\Audit;

use App\Models\AuditLog;
use App\Repositories\Eloquent\BaseRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Carbon;

class AuditLogRepository extends BaseRepository
{
    public function __construct(AuditLog $model)
    {
        parent::__construct($model);
    }

    // ── User activity - uses UUID ─────────────────────────────────────────

    public function findByUser(string $userId, int $limit = 50): Collection
    {
        return $this->model
            ->where('user_id', $userId)
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();
    }

    // ── Entity history - uses UUID ───────────────────────────────────────

    public function findByAuditable(string $auditableType, string $auditableId): Collection
    {
        return $this->model
            ->where('auditable_type', $auditableType)
            ->where('auditable_id', $auditableId)
            ->with('user')
            ->orderBy('created_at') // Timeline view
            ->get();
    }

    // ── Event filtering using your constants ─────────────────────────────

    public function findByEvent(string $event, int $limit = 100): Collection
    {
        return $this->model
            ->where('event', $event)
            ->with(['user', 'auditable'])
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();
    }

    public function getStatusChanges(string $auditableType, string $auditableId): Collection
    {
        return $this->model
            ->where('auditable_type', $auditableType)
            ->where('auditable_id', $auditableId)
            ->where('event', AuditLog::EVENT_STATUS_CHANGED)
            ->orderBy('created_at')
            ->get();
    }

    public function getSignatures(string $auditableType, string $auditableId): Collection
    {
        return $this->model
            ->where('auditable_type', $auditableType)
            ->where('auditable_id', $auditableId)
            ->whereIn('event', [
                AuditLog::EVENT_SIGNED, 
                AuditLog::EVENT_COUNTERSIGNED, 
                AuditLog::EVENT_AUTHORISED
            ])
            ->with('user')
            ->orderBy('created_at')
            ->get();
    }

    // ── Compliance queries ───────────────────────────────────────────────

    public function getLoginAttempts(int $days = 7): Collection
    {
        return $this->model
            ->whereIn('event', [AuditLog::EVENT_LOGIN, 'login_failed'])
            ->where('created_at', '>=', now()->subDays($days))
            ->orderByDesc('created_at')
            ->get();
    }

    public function getDataChangesByDateRange(Carbon $start, Carbon $end): Collection
    {
        return $this->model
            ->whereIn('event', [
                AuditLog::EVENT_CREATED,
                AuditLog::EVENT_UPDATED, 
                AuditLog::EVENT_DELETED
            ])
            ->whereBetween('created_at', [$start, $end])
            ->with('user')
            ->orderByDesc('created_at')
            ->get();
    }

    // ── Diff helper for your old_values/new_values ───────────────────────

    public function getChangedFields(AuditLog $log): array
    {
        if (!$log->old_values || !$log->new_values) {
            return [];
        }

        $changed = [];
        foreach ($log->new_values as $key => $newValue) {
            $oldValue = $log->old_values[$key] ?? null;
            if ($oldValue !== $newValue) {
                $changed[$key] = [
                    'old' => $oldValue,
                    'new' => $newValue,
                ];
            }
        }
        return $changed;
    }

    // ── Search using your indexes ────────────────────────────────────────

    public function search(string $term, int $limit = 50): Collection
    {
        return $this->model
            ->where(function ($q) use ($term) {
                $q->where('description', 'like', "%{$term}%")
                  ->orWhere('user_name', 'like', "%{$term}%")
                  ->orWhere('event', 'like', "%{$term}%")
                  ->orWhere('ip_address', 'like', "%{$term}%");
            })
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();
    }
}