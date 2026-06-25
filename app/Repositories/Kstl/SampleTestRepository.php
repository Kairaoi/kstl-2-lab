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
     * Get all queued/in-progress/completed/flagged tests for the analyst queue.
     * Shows all tests assigned to or available to the analyst.
     */
    public function getQueue(string $analystId = null)
    {
        $query = $this->model->query()
            ->with(['sample.submission.client', 'assignedTo'])
            ->whereIn('status', [
                SampleTest::STATUS_QUEUED, 
                SampleTest::STATUS_IN_PROGRESS,
                SampleTest::STATUS_COMPLETED,
                SampleTest::STATUS_FLAGGED
            ])
            ->whereHas('sample', fn($q) => $q->whereIn('status', ['testing', 'consent_to_proceed', 'completed']))
            ->orderByRaw("FIELD(status, 'in_progress', 'queued', 'flagged', 'completed')")
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

        // ── Lock finalised work ───────────────────────────────────────
        // A completed test is finalised for audit and must not be re-edited.
        // (Flagged tests stay editable so the analyst can answer a Director query.)
        if ($test->status === SampleTest::STATUS_COMPLETED) {
            Log::warning('Blocked edit attempt on a finalised test result', [
                'test_id'    => $test->id,
                'analyst_id' => Auth::id(),
            ]);
            abort(403, 'This test has been finalised and can no longer be edited.');
        }

        // Once the parent submission is authorised (or completed), the whole
        // result is locked — no test under it may be changed, even via a
        // direct request that bypasses the UI.
        $submissionStatus = $test->sample?->submission?->status;
        if (in_array($submissionStatus, ['authorised', 'completed'], true)) {
            Log::warning('Blocked edit attempt on a test under an authorised submission', [
                'test_id'           => $test->id,
                'submission_status' => $submissionStatus,
                'analyst_id'        => Auth::id(),
            ]);
            abort(403, 'This submission has been authorised and its results are locked.');
        }

        // Preserve any [Director query] note so the audit trail is not lost
        // when the analyst re-submits in response to a director query.
        $analystNotes = $input['result_notes'] ?? null;
        if (preg_match('/(\[Director query\].+?)(?:\n\n[^\[]|$)/s', $test->result_notes ?? '', $m)) {
            $queryFragment = trim($m[1]);
            $analystNotes  = ($analystNotes ? $analystNotes . "\n\n" : '') . $queryFragment;
        }

        $test->update([
            'result_value'     => $input['result_value']     ?? null,
            'result_unit'      => $input['result_unit']      ?? null,
            'result_qualifier' => $input['result_qualifier'],
            'result_notes'     => $analystNotes,
            'completed_at'     => now(),
            'status'           => ($input['flag'] ?? false)
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