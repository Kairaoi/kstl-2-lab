<?php
// app/Repositories/Kstl/Audit/ReportQueryRepository.php

namespace App\Repositories\Kstl\Audit;

use App\Models\ReportQuery;
use App\Repositories\Eloquent\BaseRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ReportQueryRepository extends BaseRepository
{
    public function __construct(ReportQuery $model)
    {
        parent::__construct($model);
    }

    // ── Lookup ────────────────────────────────────────────────────────────

    /**
     * Find an active report by its code (e.g. 'audit_failed_logins').
     */
    public function findByCode(string $code): ?ReportQuery
    {
        return $this->model
            ->where('code', strtolower(trim($code)))
            ->where('is_active', true)
            ->first();
    }

    // ── Role-scoped listing ───────────────────────────────────────────────

    /**
     * Active reports visible to a user holding any of the given role names.
     * Uses ReportQuery::scopeVisibleToRoles(); reports with no allowed_roles
     * are visible to everyone.
     */
    public function findVisibleToRoles(array $roleNames): Collection
    {
        return $this->model
            ->visibleToRoles($roleNames)
            ->orderBy('category')
            ->orderBy('sort_order')
            ->get();
    }

    // ── Category listing ──────────────────────────────────────────────────

    public function findActiveByCategory(string $category): Collection
    {
        return $this->model
            ->where('category', $category)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
    }

    public function findActiveGroupedByCategory(array $roleNames): Collection
    {
        return $this->findVisibleToRoles($roleNames)->groupBy('category');
    }

    // ── Pagination ────────────────────────────────────────────────────────

    public function paginateAll(int $perPage = 20): LengthAwarePaginator
    {
        return $this->model
            ->orderBy('category')
            ->orderBy('sort_order')
            ->paginate($perPage);
    }

    // ── Status management ─────────────────────────────────────────────────

    public function activate(string $id): ReportQuery
    {
        $query = $this->findOrFail($id);
        $query->update(['is_active' => true]);
        return $query->fresh();
    }

    public function deactivate(string $id): ReportQuery
    {
        $query = $this->findOrFail($id);
        $query->update(['is_active' => false]);
        return $query->fresh();
    }

    // ── Statistics ────────────────────────────────────────────────────────

    public function countByCategory(): Collection
    {
        return $this->model
            ->selectRaw('category, COUNT(*) as count')
            ->where('is_active', true)
            ->groupBy('category')
            ->get();
    }
}