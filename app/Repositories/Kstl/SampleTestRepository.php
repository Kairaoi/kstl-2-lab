<?php

namespace App\Repositories\Kstl;

use App\Models\Kstl\SampleTest;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SampleTestRepository extends BaseRepository
{
    public function model(): string
    {
        return SampleTest::class;
    }

    /**
     * Get all queued/in-progress tests for the analyst queue.
     */
    public function getQueue(string $analystId = null)
    {
        $query = $this->model->query()
            ->with(['sample.submission.client', 'assignedTo'])
            ->whereIn('status', [SampleTest::STATUS_QUEUED, SampleTest::STATUS_IN_PROGRESS])
            ->whereHas('sample', fn($q) => $q->whereIn('status', ['testing', 'consent_to_proceed']))
            ->orderByRaw("FIELD(status, 'in_progress', 'queued')")
            ->orderBy('created_at');

        if ($analystId) {
            $query->where(fn($q) =>
                $q->where('assigned_to', $analystId)
                  ->orWhereNull('assigned_to')
            );
        }

        return $query->get();
    }

    /**
     * Get tests grouped by sample for a submission.
     */
    public function getBySampleId(string $sampleId)
    {
        return $this->model->query()
            ->where('sample_id', $sampleId)
            ->with('assignedTo')
            ->orderBy('test_category')
            ->orderBy('test_key')
            ->get();
    }

    /**
     * Assign a test to an analyst and mark as in_progress.
     */
    public function startTest(string $id): SampleTest
    {
        $test = $this->getById($id);

        $test->update([
            'assigned_to' => Auth::id(),
            'started_at'  => now(),
            'status'      => SampleTest::STATUS_IN_PROGRESS,
        ]);

        Log::info('Sample test started', [
            'test_id'     => $test->id,
            'test_key'    => $test->test_key,
            'sample_id'   => $test->sample_id,
            'analyst_id'  => Auth::id(),
        ]);

        return $test->fresh();
    }

    /**
     * Save result for a test.
     */
    public function saveResult(string $id, array $input): SampleTest
    {
        $test = $this->getById($id);

        $test->update([
            'result_value'     => $input['result_value']     ?? null,
            'result_unit'      => $input['result_unit']      ?? null,
            'result_qualifier' => $input['result_qualifier'],
            'result_notes'     => $input['result_notes']     ?? null,
            'completed_at'     => now(),
            'status'           => $input['flag']
                ? SampleTest::STATUS_FLAGGED
                : SampleTest::STATUS_COMPLETED,
        ]);

        Log::info('Sample test result saved', [
            'test_id'          => $test->id,
            'test_key'         => $test->test_key,
            'result_qualifier' => $input['result_qualifier'],
            'flagged'          => (bool) ($input['flag'] ?? false),
            'analyst_id'       => Auth::id(),
        ]);

        return $test->fresh();
    }

    /**
     * Count tests by status for dashboard summary.
     */
    public function countByStatus(): array
    {
        $counts = $this->model->query()
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        return [
            'queued'      => $counts[SampleTest::STATUS_QUEUED]      ?? 0,
            'in_progress' => $counts[SampleTest::STATUS_IN_PROGRESS] ?? 0,
            'completed'   => $counts[SampleTest::STATUS_COMPLETED]   ?? 0,
            'flagged'     => $counts[SampleTest::STATUS_FLAGGED]     ?? 0,
        ];
    }

    /**
     * Check if all tests for a sample are completed.
     * Used to advance sample → submission status.
     */
    public function allCompletedForSample(string $sampleId): bool
    {
        return ! $this->model->query()
            ->where('sample_id', $sampleId)
            ->whereNotIn('status', [
                SampleTest::STATUS_COMPLETED,
                SampleTest::STATUS_FLAGGED,
            ])
            ->exists();
    }
}