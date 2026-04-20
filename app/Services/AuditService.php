<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;

class AuditService
{
    /**
     * Write an audit log entry.
     */
    public function log(
        string  $event,
        string  $description,
        ?Model  $auditable  = null,
        array   $oldValues  = [],
        array   $newValues  = [],
        ?string $userId     = null,
        ?string $userName   = null,
    ): void {
        try {
            $user     = Auth::user();
            $userId   = $userId   ?? $user?->id;
            $userName = $userName ?? ($user ? trim($user->first_name . ' ' . $user->last_name) : 'System');

            AuditLog::create([
                'user_id'        => $userId,
                'user_name'      => $userName,
                'event'          => $event,
                'description'    => $description,
                'auditable_type' => $auditable ? get_class($auditable) : null,
                'auditable_id'   => $auditable?->id,
                'old_values'     => empty($oldValues) ? null : $oldValues,
                'new_values'     => empty($newValues) ? null : $newValues,
                'ip_address'     => Request::ip(),
                'user_agent'     => Request::userAgent(),
            ]);

        } catch (\Exception $e) {
            // Never let audit logging break the main flow
            Log::error('[AUDIT] Failed to write audit log', [
                'event'       => $event,
                'description' => $description,
                'error'       => $e->getMessage(),
            ]);
        }
    }

    // ── Convenience helpers ────────────────────────────────────────

    public function logLogin(string $userId, string $userName): void
    {
        $this->log(
            event:       AuditLog::EVENT_LOGIN,
            description: "{$userName} logged in",
            userId:      $userId,
            userName:    $userName,
        );
    }

    public function logSubmissionCreated(\App\Models\Kstl\Submission $submission): void
    {
        $this->log(
            event:       AuditLog::EVENT_SUBMITTED,
            description: "Client submitted {$submission->reference_number}",
            auditable:   $submission,
            newValues:   [
                'reference_number' => $submission->reference_number,
                'sample_name'      => $submission->sample_name,
                'status'           => $submission->status,
            ],
        );
    }

    public function logStatusChange(\App\Models\Kstl\Submission $submission, string $from, string $to): void
    {
        $this->log(
            event:       AuditLog::EVENT_STATUS_CHANGED,
            description: "Submission {$submission->reference_number} status changed from {$from} to {$to}",
            auditable:   $submission,
            oldValues:   ['status' => $from],
            newValues:   ['status' => $to],
        );
    }

    public function logAgreementSigned(\App\Models\Kstl\Client $client): void
    {
        $this->log(
            event:       AuditLog::EVENT_SIGNED,
            description: "Service agreement signed by {$client->responsible_officer_name} for {$client->company_name}",
            auditable:   $client,
            newValues:   [
                'signed_by' => $client->responsible_officer_name,
                'signed_at' => now()->toDateTimeString(),
            ],
        );
    }

    public function logAgreementCountersigned(\App\Models\Kstl\Client $client): void
    {
        $this->log(
            event:       AuditLog::EVENT_COUNTERSIGNED,
            description: "Service agreement countersigned by Director {$client->director_signed_by} for {$client->company_name}",
            auditable:   $client,
            newValues:   [
                'countersigned_by' => $client->director_signed_by,
                'countersigned_at' => now()->toDateTimeString(),
            ],
        );
    }

    public function logResultAuthorised(\App\Models\Kstl\Result $result): void
    {
        $this->log(
            event:       AuditLog::EVENT_AUTHORISED,
            description: "Result authorised for submission {$result->submission->reference_number} — outcome: {$result->overall_outcome}",
            auditable:   $result,
            newValues:   [
                'overall_outcome' => $result->overall_outcome,
                'authorised_at'   => now()->toDateTimeString(),
            ],
        );
    }

    public function logInvoiceGenerated(\App\Models\Kstl\Invoice $invoice): void
    {
        $this->log(
            event:       AuditLog::EVENT_GENERATED,
            description: "Invoice {$invoice->invoice_number} generated — A$ {$invoice->total_amount_aud}",
            auditable:   $invoice,
            newValues:   [
                'invoice_number'   => $invoice->invoice_number,
                'total_amount_aud' => $invoice->total_amount_aud,
                'bill_to'          => $invoice->bill_to_company,
            ],
        );
    }

    public function logComplaintLodged(\App\Models\Kstl\Complaint $complaint): void
    {
        $this->log(
            event:       AuditLog::EVENT_CREATED,
            description: "Complaint lodged by {$complaint->complainant_name}: {$complaint->subject}",
            auditable:   $complaint,
            newValues:   [
                'subject' => $complaint->subject,
                'types'   => $complaint->complaint_types,
                'status'  => $complaint->status,
            ],
        );
    }

    public function logComplaintResponded(\App\Models\Kstl\Complaint $complaint, string $oldStatus): void
    {
        $this->log(
            event:       AuditLog::EVENT_RESPONDED,
            description: "Complaint '{$complaint->subject}' updated from {$oldStatus} to {$complaint->status}",
            auditable:   $complaint,
            oldValues:   ['status' => $oldStatus],
            newValues:   ['status' => $complaint->status],
        );
    }
}