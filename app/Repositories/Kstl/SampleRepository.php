<?php

namespace App\Repositories\Kstl;

use App\Models\Kstl\Sample;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SampleRepository extends BaseRepository
{
    public function model(): string
    {
        return Sample::class;
    }

    /**
     * Create sample rows from reception's receive form.
     * Called by ReceptionController when marking a submission as received.
     */
    public function createForSubmission(string $submissionId, array $samplesData): array
    {
        $created = [];

        foreach ($samplesData as $data) {
            $sample = $this->model->create([
                'submission_id'   => $submissionId,
                'sample_code'     => Sample::generateCode(),
                'sampling_date'   => $data['sampling_date'],
                'common_name'     => $data['common_name'],
                'scientific_name' => $data['scientific_name'] ?? null,
                'quantity'        => $data['quantity'],
                'quantity_unit'   => $data['quantity_unit'] ?? 'g',
                'status'          => Sample::STATUS_PENDING,
                'notes'           => $data['notes'] ?? null,
            ]);

            Log::info('Sample created', [
                'sample_id'    => $sample->id,
                'sample_code'  => $sample->sample_code,
                'submission_id'=> $submissionId,
                'created_by'   => Auth::id(),
            ]);

            $created[] = $sample;
        }

        return $created;
    }

    public function updateStatus(string $id, string $status): bool
    {
        $sample = $this->getById($id);
        return $sample->update(['status' => $status]);
    }

    public function getBySubmissionId(string $submissionId)
    {
        return $this->model->query()
            ->where('submission_id', $submissionId)
            ->with(['assessment', 'sampleTests'])
            ->get();
    }

    public function assignCode(string $id): Sample
    {
        $sample = $this->getById($id);
        $sample->update(['sample_code' => Sample::generateCode()]);
        return $sample->fresh();
    }
}