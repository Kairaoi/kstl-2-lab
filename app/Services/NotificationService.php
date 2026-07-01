<?php

namespace App\Services;

use App\Mail\AwaitingAuthorisationMail;
use App\Mail\InvoiceIssuedMail;
use App\Mail\QueryAnalystMail;
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

            // Record in-app notification — wording is intentionally neutral; outcome
            // is communicated through the formal Certificate of Analysis, not here.
            $this->createInAppNotification(
                userId:           $client->user->id,
                type:             'results_ready',
                subject:          "Laboratory assessment complete — {$submission->reference_number}",
                message:          "The laboratory has completed the review of your submitted samples for {$submission->reference_number}. A formal report will be provided to you. Please contact the laboratory if you have any questions.",
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
     * Notify analyst(s) the Director has queried their test(s).
     * Called after Director flags tests via queryAnalyst().
     */
    public function notifyAnalystQueried(Submission $submission, array $testIds, string $queryNote, bool $postAuthorisation = false): void
    {
        // Collect unique analysts assigned to the flagged tests
        $analysts = \App\Models\Kstl\SampleTest::whereIn('id', $testIds)
            ->with('assignedTo')
            ->get()
            ->pluck('assignedTo')
            ->filter()
            ->unique('id');

        // Fall back to all users with analyst role if no tests have an assigned analyst
        if ($analysts->isEmpty()) {
            $analysts = User::role('analyst')->get();
        }

        if ($analysts->isEmpty()) {
            Log::warning('[NOTIFY] No analysts found — query_analyst skipped', [
                'submission_id' => $submission->id,
            ]);
            return;
        }

        $testLabels = \App\Models\Kstl\SampleTest::whereIn('id', $testIds)
            ->get()
            ->map(fn($t) => $t->getDisplayLabel())
            ->values()
            ->all();

        $submission->loadMissing('client');

        foreach ($analysts as $analyst) {
            try {
                Mail::to($analyst->email)->send(
                    new QueryAnalystMail($submission, $queryNote, $testLabels, $postAuthorisation)
                );

                $suffix = $postAuthorisation ? ' (post-authorisation)' : '';
                $this->createInAppNotification(
                    userId:     $analyst->id,
                    type:       'director_query',
                    subject:    "Director query{$suffix} — {$submission->reference_number}",
                    message:    "The Director has returned " . count($testIds) . " test(s) for {$submission->reference_number} ({$submission->client->company_name}) with a query. Open your test queue to review and resubmit.",
                    notifiable: $submission,
                );

                Log::info('[NOTIFY] query_analyst sent', [
                    'submission_id'    => $submission->id,
                    'analyst_id'       => $analyst->id,
                    'email'            => $analyst->email,
                    'post_auth'        => $postAuthorisation,
                ]);

            } catch (\Exception $e) {
                Log::error('[NOTIFY] query_analyst FAILED', [
                    'submission_id' => $submission->id,
                    'analyst_id'    => $analyst->id,
                    'error'         => $e->getMessage(),
                ]);
            }
        }
    }

    /**
     * Notify Director a client has submitted payment details (TT reference).
     */
    public function notifyPaymentSubmitted(Invoice $invoice): void
    {
        $directors = User::role('director')->get();

        if ($directors->isEmpty()) {
            Log::warning('[NOTIFY] No directors — payment_submitted skipped', [
                'invoice_id' => $invoice->id,
            ]);
            return;
        }

        foreach ($directors as $director) {
            try {
                $this->createInAppNotification(
                    userId:     $director->id,
                    type:       'payment_submitted',
                    subject:    "Payment details submitted — {$invoice->invoice_number}",
                    message:    "{$invoice->bill_to_company} has submitted TT reference \"{$invoice->payment_submitted_reference}\" for invoice {$invoice->invoice_number} (A$ " . number_format($invoice->total_amount_aud, 2) . "). Please verify and confirm.",
                    notifiable: $invoice,
                );

                Log::info('[NOTIFY] payment_submitted sent to Director', [
                    'invoice_id'  => $invoice->id,
                    'director_id' => $director->id,
                ]);

            } catch (\Exception $e) {
                Log::error('[NOTIFY] payment_submitted FAILED', [
                    'invoice_id' => $invoice->id,
                    'error'      => $e->getMessage(),
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
     * Notify client their samples have been physically received by reception.
     * Called when reception marks a submission as received.
     */
    public function notifyClientSamplesReceived(Submission $submission): void
    {
        $client = $submission->client()->with('user')->first();
        $email  = $client?->user?->email;

        if (! $email) {
            Log::warning('[NOTIFY] No email for client — samples_received skipped', [
                'submission_id' => $submission->id,
            ]);
            return;
        }

        try {
            $this->createInAppNotification(
                userId:     $client->user->id,
                type:       'samples_received',
                subject:    "Your samples have been received — {$submission->reference_number}",
                message:    "Your samples for submission {$submission->reference_number} have been received and logged by our reception team. The assessment process has begun. We will notify you when the next stage is complete.",
                notifiable: $submission,
            );

            Log::info('[NOTIFY] samples_received sent to client', [
                'submission_id' => $submission->id,
                'client_id'     => $client->id,
            ]);

        } catch (\Exception $e) {
            Log::error('[NOTIFY] samples_received FAILED', [
                'submission_id' => $submission->id,
                'error'         => $e->getMessage(),
            ]);
        }
    }

    /**
     * Notify reception staff a new submission has been lodged by a client.
     * Called immediately after a client creates a submission.
     */
    public function notifyReceptionNewSubmission(Submission $submission): void
    {
        $receptionUsers = User::role('reception')->get();

        if ($receptionUsers->isEmpty()) {
            Log::warning('[NOTIFY] No reception users — new_submission skipped', [
                'submission_id' => $submission->id,
            ]);
            return;
        }

        $submission->loadMissing('client');

        foreach ($receptionUsers as $staff) {
            try {
                $this->createInAppNotification(
                    userId:     $staff->id,
                    type:       'new_submission',
                    subject:    "New submission received — {$submission->reference_number}",
                    message:    "{$submission->client->company_name} has submitted a new sample request ({$submission->reference_number}). Please review and process the submission.",
                    notifiable: $submission,
                );

                Log::info('[NOTIFY] new_submission sent to reception', [
                    'submission_id' => $submission->id,
                    'staff_id'      => $staff->id,
                ]);

            } catch (\Exception $e) {
                Log::error('[NOTIFY] new_submission FAILED', [
                    'submission_id' => $submission->id,
                    'staff_id'      => $staff->id,
                    'error'         => $e->getMessage(),
                ]);
            }
        }
    }

    /**
     * Notify analysts new tests have been queued for a submission.
     * Called when reception sends a submission to the testing queue.
     */
    public function notifyAnalystNewTests(Submission $submission): void
    {
        $analysts = User::role('analyst')->get();

        if ($analysts->isEmpty()) {
            Log::warning('[NOTIFY] No analysts — new_tests_queued skipped', [
                'submission_id' => $submission->id,
            ]);
            return;
        }

        $submission->loadMissing('client');

        foreach ($analysts as $analyst) {
            try {
                $this->createInAppNotification(
                    userId:     $analyst->id,
                    type:       'new_tests_queued',
                    subject:    "New tests queued — {$submission->reference_number}",
                    message:    "Tests for {$submission->reference_number} ({$submission->client->company_name}) have been added to your queue. Open the test queue to begin analysis.",
                    notifiable: $submission,
                );

                Log::info('[NOTIFY] new_tests_queued sent to analyst', [
                    'submission_id' => $submission->id,
                    'analyst_id'    => $analyst->id,
                ]);

            } catch (\Exception $e) {
                Log::error('[NOTIFY] new_tests_queued FAILED', [
                    'submission_id' => $submission->id,
                    'analyst_id'    => $analyst->id,
                    'error'         => $e->getMessage(),
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