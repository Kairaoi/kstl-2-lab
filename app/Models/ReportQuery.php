<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * ReportQuery — a saved, role-scoped report definition.
 *
 * Replaces the example's `ministry` scoping with Spatie-role scoping:
 *   allowed_roles = ["director","auditor","admin","super_admin"]
 * A user may see a report if they hold ANY of its allowed roles. An empty /
 * null allowed_roles means any authenticated user may see it.
 */
class ReportQuery extends Model
{
    use HasUuids;

    protected $fillable = [
        'code',
        'name',
        'description',
        'sql_query',
        'category',
        'allowed_roles',
        'parameters',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'allowed_roles' => 'array',
        'parameters'    => 'array',
        'is_active'     => 'boolean',
    ];

    /**
     * Limit to reports visible to a user holding the given role names.
     * Reports with no allowed_roles are visible to everyone.
     */
    public function scopeVisibleToRoles(Builder $query, array $roleNames): Builder
    {
        return $query->where('is_active', true)
            ->where(function ($q) use ($roleNames) {
                $q->whereNull('allowed_roles')
                  ->orWhere('allowed_roles', '[]');

                foreach ($roleNames as $role) {
                    // JSON array contains this role name
                    $q->orWhereJsonContains('allowed_roles', $role);
                }
            });
    }

    /** Does this report permit a user holding any of these roles? */
    public function allowsAnyRole(array $roleNames): bool
    {
        $allowed = $this->allowed_roles ?? [];
        if (empty($allowed)) {
            return true; // unrestricted
        }
        return count(array_intersect($allowed, $roleNames)) > 0;
    }

    // ── Relationships ──────────────────────────────────────────────
    public function executions()
    {
        return $this->hasMany(ReportExecution::class);
    }

    public function latestExecution()
    {
        return $this->hasOne(ReportExecution::class)->latestOfMany();
    }

    // ── Scopes ─────────────────────────────────────────────────────
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeInCategory(Builder $query, string $category): Builder
    {
        return $query->where('category', $category);
    }
}