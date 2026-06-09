<?php

namespace App\Services\Reports;

use App\Services\Contracts\ReportServiceInterface;
use App\Models\ReportQuery;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ReportService implements ReportServiceInterface
{
    // ── Listing ─────────────────────────────────────────────────────

    public function getReportsGroupedByCategoryForRoles(array $roleNames): Collection
    {
        $reports = ReportQuery::where('is_active', true)
            ->where(function ($query) use ($roleNames) {
                $query->whereJsonContains('allowed_roles', $roleNames)
                      ->orWhereRaw('JSON_LENGTH(allowed_roles) = 0');
            })
            ->orderBy('sort_order')
            ->get();

        return $reports->groupBy('category');
    }

    public function getReportsForRoles(array $roleNames): Collection
    {
        return ReportQuery::where('is_active', true)
            ->where(function ($query) use ($roleNames) {
                $query->whereJsonContains('allowed_roles', $roleNames)
                      ->orWhereRaw('JSON_LENGTH(allowed_roles) = 0');
            })
            ->orderBy('sort_order')
            ->get();
    }

    public function findByCode(string $code): ?ReportQuery
    {
        return ReportQuery::where('code', $code)->first();
    }

    public function canAccessReport(string $code, array $roleNames): bool
    {
        $report = $this->findByCode($code);
        if (!$report) return false;

        $allowed = $report->allowed_roles ?? [];
        return empty($allowed) || collect($allowed)->intersect($roleNames)->isNotEmpty();
    }

    // ── Execution ───────────────────────────────────────────────────

    public function executeReport(string $code, array $parameters = []): array
    {
        $report = $this->findByCode($code);

        if (!$report) {
            throw ValidationException::withMessages(['code' => ['Report not found']]);
        }

        if (auth()->check() && !$this->canAccessReport($code, auth()->user()->getRoleNames()->all())) {
            throw ValidationException::withMessages(['code' => ['Access denied']]);
        }

        $startTime = microtime(true);

        try {
            $query = $this->prepareQuery($report->sql_query, $parameters);
            $results = DB::select($query);

            $executionTime = (microtime(true) - $startTime) * 1000;

            return [
                'success'           => true,
                'data'              => $results,
                'count'             => count($results),
                'execution_time_ms' => round($executionTime, 2),
            ];

        } catch (\Exception $e) {
            $executionTime = (microtime(true) - $startTime) * 1000;

            return [
                'success'           => false,
                'data'              => [],
                'count'             => 0,
                'execution_time_ms' => round($executionTime, 2),
                'error'             => $e->getMessage(),
            ];
        }
    }

    // ── Export ──────────────────────────────────────────────────────

    public function exportReport(string $code, string $format = 'csv', array $parameters = []): mixed
    {
        $result = $this->executeReport($code, $parameters);

        if (!$result['success']) {
            throw ValidationException::withMessages(['export' => [$result['error'] ?? 'Failed']]);
        }

        $report = $this->findByCode($code);
        $data = $result['data'];

        if ($format === 'csv') {
            return $this->exportCsv($report->name ?? $code, $data);
        }

        return response()->json($data);
    }

    // ── History Stubs ───────────────────────────────────────────────

    public function getUserExecutionHistory(string $userId, int $limit = 10): Collection
    {
        return collect([]);
    }

    public function getReportExecutionHistory(string $code, int $limit = 50): Collection
    {
        return collect([]);
    }

    public function getReportPerformanceStats(string $code): array
    {
        return [];
    }

    public function getMostExecutedReports(int $limit = 10): Collection
    {
        return collect([]);
    }

    public function activateReport(string $code): bool
    {
        $report = $this->findByCode($code);
        return $report ? $report->update(['is_active' => true]) : false;
    }

    public function deactivateReport(string $code): bool
    {
        $report = $this->findByCode($code);
        return $report ? $report->update(['is_active' => false]) : false;
    }

    // ── Helpers ─────────────────────────────────────────────────────

    protected function prepareQuery(string $query, array $parameters = []): string
    {
        foreach ($parameters as $key => $value) {
            $replacement = ($value === null || $value === '')
                ? 'NULL'
                : DB::connection()->getPdo()->quote($value);
            // \b ensures :transport never matches inside :transport_methods
            $query = preg_replace('/:' . preg_quote($key, '/') . '\b/', $replacement, $query);
        }
        // Any remaining :param tokens not supplied → NULL
        $query = preg_replace('/:[a-z_]+/', 'NULL', $query);
        return $query;
    }

    protected function exportCsv(string $filename, array $data)
    {
        if (empty($data)) {
            throw ValidationException::withMessages(['export' => ['No data to export']]);
        }

        $filename = str_replace(' ', '_', $filename) . '_' . date('Y-m-d_His') . '.csv';

        return response()->stream(function () use ($data) {
            $file = fopen('php://output', 'w');
            fputcsv($file, array_keys((array)$data[0]));
            foreach ($data as $row) {
                fputcsv($file, (array)$row);
            }
            fclose($file);
        }, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }
}