<?php

namespace App\Http\Controllers;

use App\Services\Contracts\ReportServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ReportController extends Controller
{
    public function __construct(
        protected ReportServiceInterface $reportService
    ) {}

    /**
     * List reports the current user's roles allow, grouped by category.
     */
    public function index()
    {
        $roles = auth()->user()->getRoleNames()->all();

        $reports          = $this->reportService->getReportsGroupedByCategoryForRoles($roles);
        $recentExecutions = $this->reportService->getUserExecutionHistory(auth()->id(), 5);

        return view('reports.index', compact('reports', 'recentExecutions'));
    }

    /**
     * Execute a report and show its results.
     */
    public function execute(Request $request, string $code)
    {
        try {
            $parameters = $request->only(['start_date', 'end_date', 'filter']);

            $result = $this->reportService->executeReport($code, $parameters);

            $report = $this->reportService->findByCode($code);

            if ($request->wantsJson()) {
                return response()->json($result);
            }

            return view('reports.show', [
                'report'         => $report,
                'results'        => $result['data'] ?? [],
                'count'          => $result['count'] ?? 0,
                'execution_time' => $result['execution_time_ms'] ?? 0,
                'success'        => $result['success'] ?? true,
                'error'          => $result['error'] ?? null,
            ]);

        } catch (ValidationException $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'error'   => $e->getMessage(),
                    'errors'  => $e->errors(),
                ], 422);
            }

            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'error'   => 'An error occurred while executing the report.',
                ], 500);
            }

            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Export report results (CSV or JSON).
     */
    public function export(Request $request, string $code, string $format = 'csv')
    {
        try {
            $parameters = $request->only(['start_date', 'end_date', 'filter']);

            return $this->reportService->exportReport($code, $format, $parameters);

        } catch (ValidationException $e) {
            return back()->withErrors($e->errors());
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Export failed: ' . $e->getMessage()]);
        }
    }
}