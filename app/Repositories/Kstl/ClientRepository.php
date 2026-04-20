<?php

namespace App\Repositories\Kstl;

use App\Models\Kstl\Client;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ClientRepository extends BaseRepository
{
    /**
     * Specify the model class.
     */
    public function model(): string
    {
        return Client::class;
    }

    // ── Custom Finders ─────────────────────────────────────────────────────────

    public function findByUserId(string $userId): ?Client
    {
        return $this->model
            ->where('user_id', $userId)
            ->with('user')
            ->first();
    }

    public function findByIdWithTrashed(string $id): ?Client
    {
        return $this->model
            ->withTrashed()
            ->with('user')
            ->find($id);
    }

    public function existsForUser(string $userId): bool
    {
        return $this->model
            ->where('user_id', $userId)
            ->exists();
    }

    // ── Create & Update ────────────────────────────────────────────────────────

    public function create(array $input)
    {
        $client = $this->model->create([
            'user_id'                     => $input['user_id'],
            'company_name'                => $input['company_name'],
            'address'                     => $input['address'],
            'company_phone'               => $input['company_phone']               ?? null,
            // responsible_officer_name is set from the authenticated user's name
            'responsible_officer_name'    => $input['responsible_officer_name']    ?? null,
            'service_agreement_signed_at' => $input['service_agreement_signed_at'] ?? null,
            'service_agreement_file'      => $input['service_agreement_file']      ?? null,
            'internal_notes'              => $input['internal_notes']              ?? null,
        ]);

        Log::info('Client profile created', [
            'client_id'    => $client->id,
            'user_id'      => $client->user_id,
            'company_name' => $client->company_name,
            'created_by'   => Auth::id(),
        ]);

        return $client;
    }

    public function updateById($id, array $input, array $options = [])
    {
        $client = $this->getById($id);

        $before = $client->only([
            'company_name', 'address', 'company_phone',
            'responsible_officer_name', 'internal_notes',
        ]);

        $client->update([
            'company_name'             => $input['company_name']             ?? $client->company_name,
            'address'                  => $input['address']                  ?? $client->address,
            'company_phone'            => $input['company_phone']            ?? $client->company_phone,
            // responsible_officer_name kept in sync with user's display name
            'responsible_officer_name' => $input['responsible_officer_name'] ?? $client->responsible_officer_name,
            'service_agreement_file'   => $input['service_agreement_file']   ?? $client->service_agreement_file,
            'internal_notes'           => $input['internal_notes']           ?? $client->internal_notes,
        ]);

        Log::info('Client profile updated', [
            'client_id'  => $client->id,
            'user_id'    => $client->user_id,
            'before'     => $before,
            'after'      => $input,
            'updated_by' => Auth::id(),
        ]);

        return $client;
    }

    // ── Service Agreement Signing ──────────────────────────────────────────────

    /**
     * Mark the service agreement as digitally signed.
     */
    public function signServiceAgreement(
        string  $id,
        ?string $signatureData   = null,
        ?string $signatureType   = null,
        ?string $signedIp        = null,
        ?string $signedUserAgent = null,
        ?string $filePath        = null,
    ): bool {
        $client = $this->getById($id);

        if (!$client) {
            Log::error('Client not found for agreement signing', ['client_id' => $id]);
            return false;
        }

        $result = $client->update([
            'service_agreement_signed_at' => now(),
            'service_agreement_file'      => $filePath,
            'signature_data'              => $signatureData,
            'signature_type'              => $signatureType,
            'signature_captured_at'       => now(),
        ]);

        if ($result) {
            Log::info('Service agreement digitally signed successfully', [
                'client_id'            => $client->id,
                'user_id'              => $client->user_id,
                'signature_type'       => $signatureType,
                'signature_data_size'  => $signatureData ? strlen($signatureData) : 0,
                'signed_at'            => now()->toDateTimeString(),
                'signed_ip'            => $signedIp,
                'signed_user_agent'    => $signedUserAgent,
            ]);
        } else {
            Log::error('Failed to update client with signed agreement', ['client_id' => $client->id]);
        }

        return $result;
    }

    /**
     * Check if a client has signed the service agreement.
     */
    public function hasSignedAgreement(string $id): bool
    {
        $client = $this->getById($id);
        return ! is_null($client?->service_agreement_signed_at);
    }

    /**
     * Check if a client's service agreement has expired (older than 1 year).
     */
    public function hasExpiredAgreement(string $id): bool
    {
        $client = $this->getById($id);

        if (is_null($client?->service_agreement_signed_at)) {
            return false;
        }

        return $client->service_agreement_signed_at->addYear()->isPast();
    }

    /**
     * Get the number of days until the service agreement expires.
     */
    public function daysUntilAgreementExpiry(string $id): ?int
    {
        $client = $this->getById($id);

        if (is_null($client?->service_agreement_signed_at)) {
            return null;
        }

        return (int) now()->diffInDays(
            $client->service_agreement_signed_at->addYear(),
            false
        );
    }

    // ── Agreement Queries ──────────────────────────────────────────────────────

    public function getPendingAgreement()
    {
        return $this->model->query()
            ->whereNull('service_agreement_signed_at')
            ->with('user')
            ->get();
    }

    public function getWithSignedAgreement()
    {
        return $this->model->query()
            ->whereNotNull('service_agreement_signed_at')
            ->with('user')
            ->get();
    }

    public function getExpiringAgreements(int $withinDays = 30)
    {
        return $this->model->query()
            ->whereNotNull('service_agreement_signed_at')
            ->whereRaw('DATE_ADD(service_agreement_signed_at, INTERVAL 1 YEAR) <= ?', [now()->addDays($withinDays)])
            ->whereRaw('DATE_ADD(service_agreement_signed_at, INTERVAL 1 YEAR) >= ?', [now()])
            ->with('user')
            ->get();
    }

    public function getExpiredAgreements()
    {
        return $this->model->query()
            ->whereNotNull('service_agreement_signed_at')
            ->whereRaw('DATE_ADD(service_agreement_signed_at, INTERVAL 1 YEAR) < ?', [now()])
            ->with('user')
            ->get();
    }

    // ── Soft Delete / Restore / Force Delete ───────────────────────────────────

    public function deleteById($id): bool
    {
        $client = $this->getById($id);
        $result = $client->delete();

        if ($result) {
            Log::info('Client profile soft deleted', [
                'client_id'  => $client->id,
                'user_id'    => $client->user_id,
                'deleted_by' => Auth::id(),
            ]);
        }

        return $result;
    }

    public function restore(string $id): bool
    {
        $client = $this->findByIdWithTrashed($id);
        $result = $client->restore();

        if ($result) {
            Log::info('Client profile restored', [
                'client_id'   => $client->id,
                'user_id'     => $client->user_id,
                'restored_by' => Auth::id(),
            ]);
        }

        return $result;
    }

    public function forceDelete(string $id): bool
    {
        $client = $this->findByIdWithTrashed($id);

        Log::warning('Client profile permanently deleted', [
            'client_id'        => $client->id,
            'user_id'          => $client->user_id,
            'company_name'     => $client->company_name,
            'force_deleted_by' => Auth::id(),
        ]);

        return $client->forceDelete();
    }

    // ── DataTable ──────────────────────────────────────────────────────────────

    public function getForDataTable(
        string $search   = '',
        string $order_by = 'created_at',
        string $sort     = 'desc',
        bool   $trashed  = false
    ) {
        $query = $this->model->query()
            ->select([
                'clients.*',
                'users.email as user_email',
            ])
            ->join('users', 'clients.user_id', '=', 'users.id');

        if (! empty($search)) {
            $term = '%' . strtolower($search) . '%';
            $query->where(function ($q) use ($term) {
                $q->whereRaw('LOWER(clients.company_name) LIKE ?', [$term])
                  ->orWhereRaw('LOWER(clients.address) LIKE ?', [$term])
                  ->orWhereRaw('LOWER(clients.responsible_officer_name) LIKE ?', [$term])
                  ->orWhereRaw('LOWER(users.email) LIKE ?', [$term]);
            });
        }

        if ($trashed) {
            $query->onlyTrashed();
        }

        $query->orderBy('clients.' . $order_by, $sort);

        return $query;
    }

    /**
     * Record the Director's countersignature on the service agreement.
     */
    public function countersignAgreement(
        string  $clientId,
        string  $directorName,
        string  $directorUserId,
        ?string $signatureData = null,
        ?string $signatureType = null,
        ?string $signedIp      = null,
    ): bool {
        $client = $this->getById($clientId);

        if (!$client) {
            Log::error('Client not found for director countersign', ['client_id' => $clientId]);
            return false;
        }

        Log::info('[COUNTERSIGN] Attempting to save', [
            'client_id'       => $clientId,
            'director_name'   => $directorName,
            'director_user_id'=> $directorUserId,
            'signature_type'  => $signatureType,
            'signature_size'  => $signatureData ? strlen($signatureData) : 0,
            'fillable'        => $client->getFillable(),
        ]);

        $result = $client->update([
            'director_signature_data' => $signatureData,
            'director_signature_type' => $signatureType,
            'director_signed_by'      => $directorName,
            'director_signed_by_id'   => $directorUserId,
            'director_signed_at'      => now(),
            'director_signed_ip'      => $signedIp,
        ]);

        $client->refresh();

        Log::info('[COUNTERSIGN] After update', [
            'update_result'        => $result,
            'director_signed_at'   => $client->director_signed_at,
            'director_signed_by'   => $client->director_signed_by,
            'has_signature_data'   => ! is_null($client->director_signature_data),
            'director_signed_by_id'=> $client->director_signed_by_id,
        ]);

        if ($result) {
            Log::info('[COUNTERSIGN] Success — Director countersigned agreement', [
                'client_id'    => $clientId,
                'director_id'  => $directorUserId,
                'director_name'=> $directorName,
                'signed_at'    => $client->director_signed_at,
            ]);
        } else {
            Log::error('[COUNTERSIGN] FAILED — update() returned false', [
                'client_id' => $clientId,
            ]);
        }

        return $result;
    }

    /**
     * Check if a client's agreement has been countersigned by the Director.
     */
    public function hasDirectorCountersigned(string $clientId): bool
    {
        $client = $this->getById($clientId);
        return $client && ! is_null($client->director_signed_at);
    }

    /**
     * Get clients whose agreements are signed by client but not yet countersigned.
     */
    public function getPendingCountersign()
    {
        return $this->model->query()
            ->whereNotNull('service_agreement_signed_at')
            ->whereNull('director_signed_at')
            ->with('user')
            ->orderBy('service_agreement_signed_at')
            ->get();
    }
}