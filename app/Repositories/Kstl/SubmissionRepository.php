<?php

namespace App\Repositories\Kstl;

use App\Models\Kstl\Submission;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SubmissionRepository extends BaseRepository
{
    /**
     * Specify the model class.
     */
    public function model(): string
    {
        return Submission::class;
    }

    // ── Custom Finders ─────────────────────────────────────────────────────────

    /**
     * Find a submission by ID for a specific client (prevents cross-client access).
     */
    public function findByIdForClient(string $id, string $clientId): ?Submission
    {
        return $this->model
            ->where('id', $id)
            ->where('client_id', $clientId)
            ->first();
    }

    /**
     * Find by reference number.
     */
    public function findByReference(string $reference): ?Submission
    {
        return $this->model
            ->where('reference_number', $reference)
            ->first();
    }

    // ── Create ─────────────────────────────────────────────────────────────────

    /**
     * Create a new submission.
     * Automatically generates a unique reference number.
     */
    public function create(array $input)
    {
        $submission = $this->model->create([
            'reference_number'      => Submission::generateReference(),
            'client_id'             => $input['client_id'],
            'received_by'           => $input['received_by']           ?? null,

            // Sample info
            'sample_name'           => $input['sample_name'],
            'scientific_name'       => $input['scientific_name']       ?? null,
            'sample_description'    => $input['sample_description']    ?? null,
            'sample_type'           => $input['sample_type'],
            'sample_quantity'       => $input['sample_quantity']       ?? null,
            'sample_quantity_unit'  => $input['sample_quantity_unit']  ?? null,
            'collected_at'          => $input['collected_at']          ?? null,
            'collection_location'   => $input['collection_location']   ?? null,

            // Tests
            'tests_requested'       => $input['tests_requested']       ?? [],
            'tests_other'           => $input['tests_other']           ?? null,

            // Transport & instructions
            'transport_method'      => $input['transport_method']      ?? null,
            'transport_detail'      => $input['transport_detail']      ?? null,
            'priority'              => $input['priority']              ?? 'routine',
            'special_instructions'  => $input['special_instructions']  ?? null,
            'results_required_by'   => $input['results_required_by']   ?? null,

            // Schedule 1 header
            'service_mode'          => $input['service_mode']          ?? 'lab_to_client',
            'application_date'      => $input['application_date']      ?? now()->toDateString(),

            // Submitter
            'submitter_name'        => $input['submitter_name']        ?? null,

            // Notes & status
            'submitted_at'          => now(),
            'status'                => Submission::STATUS_SUBMITTED,
            'client_notes'          => $input['client_notes']          ?? null,
            'lab_notes'             => $input['lab_notes']             ?? null,
        ]);

        Log::info('Submission created', [
            'submission_id'    => $submission->id,
            'reference_number' => $submission->reference_number,
            'client_id'        => $submission->client_id,
            'sample_name'      => $submission->sample_name,
            'sample_type'      => $submission->sample_type,
            'transport_method' => $submission->transport_method,
            'created_by'       => Auth::id(),
        ]);

        return $submission;
    }

    // ── Update ─────────────────────────────────────────────────────────────────

    /**
     * Update a submission's client-editable fields.
     * Only allowed while status = 'submitted'.
     */
    public function updateById($id, array $input, array $options = [])
    {
        $submission = $this->getById($id);

        $before = $submission->only([
            'sample_name', 'scientific_name', 'sample_type',
            'transport_method', 'transport_detail', 'collected_at', 'priority',
        ]);

        $submission->update([
            'sample_name'           => $input['sample_name']           ?? $submission->sample_name,
            'scientific_name'       => $input['scientific_name']       ?? $submission->scientific_name,
            'sample_description'    => $input['sample_description']    ?? $submission->sample_description,
            'sample_type'           => $input['sample_type']           ?? $submission->sample_type,
            'sample_quantity'       => $input['sample_quantity']       ?? $submission->sample_quantity,
            'sample_quantity_unit'  => $input['sample_quantity_unit']  ?? $submission->sample_quantity_unit,
            'collected_at'          => $input['collected_at']          ?? $submission->collected_at,
            'collection_location'   => $input['collection_location']   ?? $submission->collection_location,
            'tests_requested'       => $input['tests_requested']       ?? $submission->tests_requested,
            'tests_other'           => $input['tests_other']           ?? $submission->tests_other,
            'transport_method'      => $input['transport_method']      ?? $submission->transport_method,
            'transport_detail'      => $input['transport_detail']      ?? $submission->transport_detail,
            'priority'              => $input['priority']              ?? $submission->priority,
            'special_instructions'  => $input['special_instructions']  ?? $submission->special_instructions,
            'results_required_by'   => $input['results_required_by']   ?? $submission->results_required_by,
            'submitter_name'        => $input['submitter_name']        ?? $submission->submitter_name,
            'client_notes'          => $input['client_notes']          ?? $submission->client_notes,
        ]);

        Log::info('Submission updated', [
            'submission_id' => $submission->id,
            'reference'     => $submission->reference_number,
            'before'        => $before,
            'after'         => array_intersect_key($input, array_flip(array_keys($before))),
            'updated_by'    => Auth::id(),
        ]);

        return $submission->fresh();
    }

    /**
     * Update lab-only fields (notes, received_by, received_at).
     * Called by Reception/Analyst roles only.
     */
    public function updateLabFields(string $id, array $input)
    {
        $submission = $this->getById($id);

        $submission->update([
            'lab_notes'   => $input['lab_notes']   ?? $submission->lab_notes,
            'received_by' => $input['received_by']  ?? $submission->received_by,
            'received_at' => $input['received_at']  ?? $submission->received_at,
        ]);

        Log::info('Submission lab fields updated', [
            'submission_id' => $submission->id,
            'reference'     => $submission->reference_number,
            'updated_by'    => Auth::id(),
        ]);

        return $submission->fresh();
    }

    // ── Status Transitions ─────────────────────────────────────────────────────

    /**
     * Advance submission to a new status.
     * Logs every transition for audit trail.
     */
    public function updateStatus(string $id, string $newStatus): bool
    {
        $submission = $this->getById($id);
        $oldStatus  = $submission->status;

        $result = $submission->update(['status' => $newStatus]);

        if ($result) {
            Log::info('Submission status updated', [
                'submission_id' => $submission->id,
                'reference'     => $submission->reference_number,
                'from_status'   => $oldStatus,
                'to_status'     => $newStatus,
                'updated_by'    => Auth::id(),
            ]);
        } else {
            Log::error('Submission status update failed', [
                'submission_id' => $submission->id,
                'to_status'     => $newStatus,
            ]);
        }

        return $result;
    }

    /**
     * Mark as received by reception staff.
     */
    public function markReceived(string $id, string $receivedByUserId): bool
    {
        $submission = $this->getById($id);

        $result = $submission->update([
            'status'      => Submission::STATUS_RECEIVED,
            'received_by' => $receivedByUserId,
            'received_at' => now(),
        ]);

        Log::info('Submission marked as received', [
            'submission_id'   => $submission->id,
            'reference'       => $submission->reference_number,
            'received_by'     => $receivedByUserId,
            'received_at'     => now()->toDateTimeString(),
        ]);

        return $result;
    }

    /**
     * Cancel a submission (client or lab).
     */
    public function cancel(string $id, string $reason = ''): bool
    {
        $submission = $this->getById($id);

        $result = $submission->update([
            'status'    => Submission::STATUS_CANCELLED,
            'lab_notes' => $submission->lab_notes
                ? $submission->lab_notes . "\n[Cancelled] " . $reason
                : "[Cancelled] " . $reason,
        ]);

        Log::info('Submission cancelled', [
            'submission_id' => $submission->id,
            'reference'     => $submission->reference_number,
            'reason'        => $reason,
            'cancelled_by'  => Auth::id(),
        ]);

        return $result;
    }

    // ── Client Queries ─────────────────────────────────────────────────────────

    /**
     * Get all submissions for a client with optional filters.
     */
    public function getByClientId(
        string  $clientId,
        ?string $status  = null,
        ?string $search  = null,
        string  $orderBy = 'submitted_at',
        string  $sort    = 'desc',
        int     $perPage = 15
    ) {
        $query = $this->model->query()
            ->where('client_id', $clientId);

        if ($status) {
            $query->where('status', $status);
        }

        if ($search) {
            $term = '%' . strtolower($search) . '%';
            $query->where(function ($q) use ($term) {
                $q->whereRaw('LOWER(reference_number) LIKE ?', [$term])
                  ->orWhereRaw('LOWER(sample_name) LIKE ?', [$term])
                  ->orWhereRaw('LOWER(scientific_name) LIKE ?', [$term])
                  ->orWhereRaw('LOWER(client_notes) LIKE ?', [$term]);
            });
        }

        return $query->orderBy($orderBy, $sort)->paginate($perPage);
    }

    /**
     * Count submissions by status for a client (used in dashboard summary).
     */
    public function countByClientId(string $clientId): array
    {
        $counts = $this->model->query()
            ->where('client_id', $clientId)
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $pending = array_sum(array_intersect_key($counts, array_flip([
            Submission::STATUS_SUBMITTED,
            Submission::STATUS_RECEIVED,
            Submission::STATUS_ASSESSING,
        ])));

        $resultsReady = array_sum(array_intersect_key($counts, array_flip([
            Submission::STATUS_AUTHORISED,
            Submission::STATUS_COMPLETED,
        ])));

        return [
            'total'        => array_sum($counts),
            'pending'      => $pending,
            'in_progress'  => $counts[Submission::STATUS_TESTING] ?? 0,
            'results_ready'=> $resultsReady,
            'cancelled'    => $counts[Submission::STATUS_CANCELLED] ?? 0,
        ];
    }

    // ── Lab / Reception Queries ────────────────────────────────────────────────

    /**
     * Get all submissions for DataTable (lab staff view).
     */
    public function getForDataTable(
        string  $search   = '',
        ?string $status   = null,
        string  $order_by = 'submitted_at',
        string  $sort     = 'desc',
        bool    $trashed  = false
    ) {
        $query = $this->model->query()
            ->select([
                'submissions.*',
                'clients.company_name as client_company',
            ])
            ->join('clients', 'submissions.client_id', '=', 'clients.id');

        if (! empty($search)) {
            $term = '%' . strtolower($search) . '%';
            $query->where(function ($q) use ($term) {
                $q->whereRaw('LOWER(submissions.reference_number) LIKE ?', [$term])
                  ->orWhereRaw('LOWER(clients.company_name) LIKE ?', [$term]);
            });
        }

        if ($status) {
            $query->where('submissions.status', $status);
        }

        if ($trashed) {
            $query->onlyTrashed();
        }

        return $query->orderBy('submissions.' . $order_by, $sort);
    }

    /**
     * Get submissions pending reception action.
     */
    // In SubmissionRepository::getPendingReceipt()
    public function getPendingReceipt()
    {
        return $this->model->query()
            ->whereIn('status', [
                Submission::STATUS_SUBMITTED,
                Submission::STATUS_RECEIVED,
                Submission::STATUS_ASSESSING,
                Submission::STATUS_ACCEPTED,
                Submission::STATUS_REJECTED,
                Submission::STATUS_CONSENT_TO_PROCEED,
            ])
            ->with('client')
            ->orderByRaw("FIELD(status, 'submitted', 'received', 'assessing', 'rejected', 'consent_to_proceed')")
            ->orderByRaw("FIELD(priority, 'emergency', 'urgent', 'routine')")
            ->orderByRaw("FIELD(status, 'submitted','received','assessing','accepted','rejected','consent_to_proceed')")
            ->orderByRaw("FIELD(priority, 'emergency','urgent','routine')")
            ->orderBy('submitted_at')
            ->get();
    }

    /**
     * Get submissions awaiting director authorisation.
     */
    public function getAwaitingAuthorisation()
    {
        return $this->model->query()
            ->where('status', Submission::STATUS_AWAITING_AUTHORISATION)
            ->with(['client', 'samples.sampleTests'])
            ->orderByRaw("FIELD(priority, 'emergency','urgent','routine')")
            ->orderBy('submitted_at')
            ->get();
    }

    // ── Soft Delete ────────────────────────────────────────────────────────────

    public function deleteById($id): bool
    {
        $submission = $this->getById($id);
        $result     = $submission->delete();

        if ($result) {
            Log::info('Submission soft deleted', [
                'submission_id' => $submission->id,
                'reference'     => $submission->reference_number,
                'deleted_by'    => Auth::id(),
            ]);
        }

        return $result;
    }

    public function restore(string $id): bool
    {
        $submission = $this->model->withTrashed()->find($id);
        $result     = $submission->restore();

        if ($result) {
            Log::info('Submission restored', [
                'submission_id' => $submission->id,
                'reference'     => $submission->reference_number,
                'restored_by'   => Auth::id(),
            ]);
        }

        return $result;
    }
}