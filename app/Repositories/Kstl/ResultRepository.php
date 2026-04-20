<?php

namespace App\Repositories\Kstl;

use App\Models\Kstl\Result;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ResultRepository extends BaseRepository
{
    public function model(): string
    {
        return Result::class;
    }

    /**
     * Create the authorised result record for a submission.
     */
    public function authorise(string $submissionId, array $input): Result
    {
        $result = $this->model->create([
            'submission_id'      => $submissionId,
            'authorised_by'      => Auth::id(),
            'overall_outcome'    => $input['overall_outcome'],
            'director_comments'  => $input['director_comments'] ?? null,
            'authorised_at'      => now(),
        ]);

        Log::info('Result authorised by Director', [
            'result_id'       => $result->id,
            'submission_id'   => $submissionId,
            'overall_outcome' => $result->overall_outcome,
            'authorised_by'   => Auth::id(),
        ]);

        return $result;
    }

    /**
     * Find result by submission ID.
     */
    public function findBySubmissionId(string $submissionId): ?Result
    {
        return $this->model
            ->where('submission_id', $submissionId)
            ->with('authorisedBy')
            ->first();
    }

    /**
     * Mark client as notified.
     */
    public function markClientNotified(string $id): bool
    {
        return $this->getById($id)->update([
            'client_notified_at' => now(),
        ]);
    }
}