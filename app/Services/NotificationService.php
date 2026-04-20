<?php

namespace App\Services;

use App\Mail\AwaitingAuthorisationMail;
use App\Mail\InvoiceIssuedMail;
use App\Mail\ResultsReadyMail;
use App\Models\Kstl\Invoice;
use App\Models\Kstl\Result;
use App\Models\Kstl\Submission;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class NotificationService
{
    /**
     * Notify client their results are ready.
     * Called after Director authorises.
     */
    public function notifyResultsReady(Submission $submission, Result $result): void
    {
        $client = $submission->client()->with('user')->first();
        $email  = $client?->user?->email;

        if (! $email) {
            Log::warning('[NOTIFY] No email for client — results_ready skipped', [
                'submission_id' => $submission->id,
            ]);
            return;
        }

        try {
            Mail::to($email)->send(new ResultsReadyMail($submission, $result));

            // Record in-app notification
            $this->createInAppNotification(
                userId:           $client->user->id,
                type:             'results_ready',
                subject:          "Your results are ready — {$submission->reference_number}",
                message:          "The Director has authorised your test results. Overall outcome: " . ucfirst($result->overall_outcome) . ".",
                notifiable:       $submission,
            );

            // Mark result as client notified
            $result->update(['client_notified_at' => now()]);

            Log::info('[NOTIFY] results_ready sent', [
                'submission_id' => $submission->id,
                'email'         => $email,
            ]);

        } catch (\Exception $e) {
            Log::error('[NOTIFY] results_ready FAILED', [
                'submission_id' => $submission->id,
                'error'         => $e->getMessage(),
            ]);
        }
    }

    /**
     * Notify client an invoice has been issued.
     * Called after invoice is generated.
     */
    public function notifyInvoiceIssued(Invoice $invoice): void
    {
        $email = $invoice->bill_to_email;

        if (! $email) {
            Log::warning('[NOTIFY] No email — invoice_issued skipped', [
                'invoice_id' => $invoice->id,
            ]);
            return;
        }

        try {
            $invoice->load(['items', 'submission.client.user']);

            Mail::to($email)->send(new InvoiceIssuedMail($invoice));

            // Record in-app notification for the client
            $clientUser = $invoice->submission?->client?->user;
            if ($clientUser) {
                $this->createInAppNotification(
                    userId:     $clientUser->id,
                    type:       'invoice_issued',
                    subject:    "Invoice {$invoice->invoice_number} has been issued",
                    message:    "A new invoice of A$ " . number_format($invoice->total_amount_aud, 2) . " has been issued. Payment is due by " . $invoice->payment_due_date->format('d M Y') . ".",
                    notifiable: $invoice,
                );
            }

            Log::info('[NOTIFY] invoice_issued sent', [
                'invoice_id' => $invoice->id,
                'email'      => $email,
            ]);

        } catch (\Exception $e) {
            Log::error('[NOTIFY] invoice_issued FAILED', [
                'invoice_id' => $invoice->id,
                'error'      => $e->getMessage(),
            ]);
        }
    }

    /**
     * Notify Director a submission is awaiting authorisation.
     * Called when all analyst tests are complete.
     */
    public function notifyDirectorAwaitingAuthorisation(Submission $submission): void
    {
        // Find all users with director role
        $directors = User::role('director')->get();

        if ($directors->isEmpty()) {
            Log::warning('[NOTIFY] No directors found — awaiting_authorisation skipped', [
                'submission_id' => $submission->id,
            ]);
            return;
        }

        $submission->load(['client', 'samples.sampleTests']);

        foreach ($directors as $director) {
            try {
                Mail::to($director->email)->send(
                    new AwaitingAuthorisationMail($submission)
                );

                $this->createInAppNotification(
                    userId:     $director->id,
                    type:       'awaiting_authorisation',
                    subject:    "Results awaiting authorisation — {$submission->reference_number}",
                    message:    "All tests for {$submission->reference_number} ({$submission->client->company_name}) are complete and awaiting your authorisation.",
                    notifiable: $submission,
                );

                Log::info('[NOTIFY] awaiting_authorisation sent to Director', [
                    'submission_id' => $submission->id,
                    'director_id'   => $director->id,
                    'email'         => $director->email,
                ]);

            } catch (\Exception $e) {
                Log::error('[NOTIFY] awaiting_authorisation FAILED', [
                    'submission_id' => $submission->id,
                    'director_id'   => $director->id,
                    'error'         => $e->getMessage(),
                ]);
            }
        }
    }

    /**
     * Notify Director a complaint has been lodged.
     * Called when a client submits a new complaint.
     */
    public function notifyComplaintReceived(\App\Models\Kstl\Complaint $complaint): void
    {
        $directors = User::role('director')->get();

        if ($directors->isEmpty()) {
            Log::warning('[NOTIFY] No directors found — complaint_received skipped', [
                'complaint_id' => $complaint->id,
            ]);
            return;
        }

        foreach ($directors as $director) {
            try {
                \Mail::to($director->email)->send(
                    new \App\Mail\ComplaintReceivedMail($complaint)
                );

                $this->createInAppNotification(
                    userId:     $director->id,
                    type:       'complaint_received',
                    subject:    "New complaint lodged — {$complaint->subject}",
                    message:    "A complaint has been lodged by {$complaint->complainant_name} ({$complaint->complainant_organisation}). Type: " . implode(', ', $complaint->getComplaintTypeLabels()) . ".",
                    notifiable: $complaint,
                );

                Log::info('[NOTIFY] complaint_received sent to Director', [
                    'complaint_id' => $complaint->id,
                    'director_id'  => $director->id,
                ]);

            } catch (\Exception $e) {
                Log::error('[NOTIFY] complaint_received FAILED', [
                    'complaint_id' => $complaint->id,
                    'error'        => $e->getMessage(),
                ]);
            }
        }
    }

    /**
     * Create an in-app notification record.
     */
    private function createInAppNotification(
        string $userId,
        string $type,
        string $subject,
        string $message,
        mixed  $notifiable = null,
    ): void {
        try {
            \DB::table('notifications')->insert([
                'id'              => \Illuminate\Support\Str::uuid()->toString(),
                'type'            => 'App\\Notifications\\Kstl\\KstlNotification',
                'notifiable_type' => 'App\\Models\\User',
                'notifiable_id'   => $userId,
                'data'            => json_encode([
                    'notification_type' => $type,
                    'subject'           => $subject,
                    'message'           => $message,
                    'related_id'        => $notifiable?->id,
                    'related_type'      => $notifiable ? get_class($notifiable) : null,
                ]),
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);
        } catch (\Exception $e) {
            Log::error('[NOTIFY] createInAppNotification FAILED', [
                'user_id' => $userId,
                'type'    => $type,
                'error'   => $e->getMessage(),
            ]);
        }
    }
}