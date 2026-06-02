<?php
// app/Repositories/Kstl/Audit/AuditReportExecutionRepository.php

namespace App\Repositories\Kstl\Audit;

use App\Models\AuditReportExecution;
use App\Repositories\Eloquent\BaseRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Carbon;

class AuditReportExecutionRepository extends BaseRepository
{
    public function __construct(AuditReportExecution $model)
    {
        parent::__construct($model);
    }

    // ── User history ──────────────────────────────────────────────────────

    public function findByUser(string $userId, int $limit = 20): Collection
    {
        return $this->model
            ->where('user_id', $userId)
            ->with('reportQuery')
            ->orderByDesc('executed_at')
            ->limit($limit)
            ->get();
    }

    public function paginateByUser(string $userId, int $perPage = 20): LengthAwarePaginator
    {
        return $this->model
            ->where('user_id', $userId)
            ->with('reportQuery')
            ->orderByDesc('executed_at')
            ->paginate($perPage);
    }

    // ── Report history ────────────────────────────────────────────────────

    public function findByReport(string $reportQueryId, int $limit = 50): Collection
    {
        return $this->model
            ->where('report_query_id', $reportQueryId)
            ->with('user')
            ->orderByDesc('executed_at')
            ->limit($limit)
            ->get();
    }

    public function getLastExecution(string $reportQueryId): ?AuditReportExecution
    {
        return $this->model
            ->where('report_query_id', $reportQueryId)
            ->with('user')
            ->orderByDesc('executed_at')
            ->first();
    }

    // ── Performance analytics ─────────────────────────────────────────────

    public function getAverageExecutionTime(string $reportQueryId): ?float
    {
        return $this->model
            ->where('report_query_id', $reportQueryId)
            ->avg('execution_time_ms');
    }

    public function getSlowestExecutions(int $limit = 10): Collection
    {
        return $this->model
            ->with(['reportQuery', 'user'])
            ->orderByDesc('execution_time_ms')
            ->limit($limit)
            ->get();
    }

    // ── Compliance tracking ───────────────────────────────────────────────

    public function getExecutionsInPeriod(Carbon $start, Carbon $end): Collection
    {
        return $this->model
            ->whereBetween('executed_at', [$start, $end])
            ->with(['reportQuery', 'user'])
            ->orderByDesc('executed_at')
            ->get();
    }

    public function getMostRunReports(int $days = 30, int $limit = 10): Collection
    {
        return $this->model
            ->selectRaw('report_query_id, COUNT(*) as run_count')
            ->where('executed_at', '>=', now()->subDays($days))
            ->groupBy('report_query_id')
            ->with('reportQuery')
            ->orderByDesc('run_count')
            ->limit($limit)
            ->get();
    }

    // ── Log an execution ───────────────────────────────────────────────────

    /**
     * Persist one execution-log row. Called by ReportService after a report
     * runs (success or failure).
     */
    public function logExecution(array $data): AuditReportExecution
    {
        return $this->create([
            'report_query_id'   => $data['report_query_id'],
            'user_id'           => $data['user_id'] ?? null,
            'parameters'        => $data['parameters'] ?? null,
            'result_count'      => $data['result_count'] ?? 0,
            'execution_time_ms' => $data['execution_time_ms'] ?? 0,
            'executed_at'       => $data['executed_at'] ?? now(),
        ]);
    }
}