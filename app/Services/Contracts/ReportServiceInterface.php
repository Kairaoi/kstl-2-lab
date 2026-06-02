<?php

namespace App\Services\Contracts;

use App\Models\ReportQuery;
use Illuminate\Support\Collection;

/**
 * Contract for the reporting service.
 *
 * Scoping is by Spatie role (not ministry): the *ForRoles methods take an array
 * of the current user's role names, and canAccessReport() checks the report's
 * allowed_roles against them.
 *
 * IDs are UUID strings (users + report tables all use uuid), so id-style
 * parameters are typed string, not int.
 */
interface ReportServiceInterface
{
    // ── Listing (role-scoped) ──────────────────────────────────────
    public function getReportsGroupedByCategoryForRoles(array $roleNames): Collection;

    public function getReportsForRoles(array $roleNames): Collection;

    // ── Lookup ─────────────────────────────────────────────────────
    public function findByCode(string $code): ?ReportQuery;

    public function canAccessReport(string $code, array $roleNames): bool;

    // ── Execution & export ─────────────────────────────────────────
    public function executeReport(string $code, array $parameters = []): array;

    public function exportReport(string $code, string $format = 'csv', array $parameters = []): mixed;

    // ── History & analytics ────────────────────────────────────────
    public function getUserExecutionHistory(string $userId, int $limit = 10): Collection;

    public function getReportExecutionHistory(string $code, int $limit = 50): Collection;

    public function getReportPerformanceStats(string $code): array;

    public function getMostExecutedReports(int $limit = 10): Collection;

    // ── Status management ──────────────────────────────────────────
    public function activateReport(string $code): bool;

    public function deactivateReport(string $code): bool;
}