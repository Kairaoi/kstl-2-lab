<?php

namespace App\Repositories\Kstl;

use App\Models\Kstl\Invoice;
use App\Models\Kstl\InvoiceItem;
use App\Models\Kstl\SampleTest;
use App\Models\Kstl\Submission;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InvoiceRepository extends BaseRepository
{
    public function model(): string
    {
        return Invoice::class;
    }

    /**
     * Generate invoice from all completed sample tests in a submission.
     * Reads prices from Invoice::TEST_PRICES (Schedule 2).
     */
    public function generateForSubmission(string $submissionId, ?string $resultId = null): Invoice
    {
        $submission = Submission::with([
            'client.user',
            'samples.sampleTests',
        ])->findOrFail($submissionId);

        $client = $submission->client;

        // Collect all completed/flagged tests
        $tests = collect();
        foreach ($submission->samples as $sample) {
            foreach ($sample->sampleTests as $test) {
                if (in_array($test->status, ['completed', 'flagged'])) {
                    $tests->push($test);
                }
            }
        }

        $invoice = DB::transaction(function () use ($submission, $client, $tests, $resultId) {

            $invoiceDate = now()->toDateString();
            $dueDate     = Invoice::calculateDueDate(now());

            // Create invoice header
            $invoice = $this->model->create([
                'invoice_number'   => Invoice::generateNumber(),
                'submission_id'    => $submission->id,
                'result_id'        => $resultId,
                'issued_by'        => Auth::id(),
                'bill_to_company'  => $client->company_name,
                'bill_to_address'  => $client->address,
                'bill_to_phone'    => $client->company_phone,
                'bill_to_email'    => $client->user->email,
                'invoice_date'     => $invoiceDate,
                'payment_due_date' => $dueDate,
                'payment_status'   => Invoice::STATUS_UNPAID,
                'total_amount_aud' => 0,
            ]);

            // Create line items from tests
            $total = 0;
            foreach ($tests as $test) {
                $price = Invoice::TEST_PRICES[$test->test_key] ?? 0;
                $label = SampleTest::TEST_LABELS[$test->test_key]
                    ?? str_replace('_', ' ', ucfirst($test->test_key));
                $cat   = SampleTest::TEST_CATEGORIES[$test->test_key] ?? null;

                InvoiceItem::create([
                    'invoice_id'       => $invoice->id,
                    'sample_test_id'   => $test->id,
                    'item_description' => $label,
                    'category'         => $cat ? ucfirst($cat) : null,
                    'unit_price_aud'   => $price,
                    'quantity'         => 1,
                    'total_price_aud'  => $price,
                ]);

                // Update price snapshot on sample_test
                $test->update(['price_aud_snapshot' => $price]);

                $total += $price;
            }

            // Update total
            $invoice->update(['total_amount_aud' => $total]);

            return $invoice;
        });

        Log::info('Invoice generated', [
            'invoice_id'     => $invoice->id,
            'invoice_number' => $invoice->invoice_number,
            'submission_id'  => $submissionId,
            'total_aud'      => $invoice->total_amount_aud,
            'items'          => $tests->count(),
            'issued_by'      => Auth::id(),
        ]);

        return $invoice->load('items');
    }

    /**
     * Mark invoice as paid.
     */
    public function markPaid(string $id, string $reference): Invoice
    {
        $invoice = $this->getById($id);

        $invoice->update([
            'payment_status'      => Invoice::STATUS_PAID,
            'payment_reference'   => $reference,
            'payment_received_at' => now(),
            'payment_verified_by' => Auth::id(),
        ]);

        Log::info('Invoice marked as paid', [
            'invoice_id'       => $invoice->id,
            'invoice_number'   => $invoice->invoice_number,
            'payment_reference'=> $reference,
            'verified_by'      => Auth::id(),
        ]);

        return $invoice->fresh();
    }

    /**
     * Get invoices for a client.
     */
    public function getByClientId(string $clientId)
    {
        return $this->model->query()
            ->whereHas('submission', fn($q) => $q->where('client_id', $clientId))
            ->with(['items', 'issuedBy'])
            ->orderByDesc('invoice_date')
            ->get();
    }

    /**
     * Find invoice by submission ID.
     */
    public function findBySubmissionId(string $submissionId): ?Invoice
    {
        return $this->model
            ->where('submission_id', $submissionId)
            ->with(['items', 'issuedBy', 'paymentVerifiedBy'])
            ->first();
    }

    /**
     * Count unpaid invoices for a client.
     */
    public function countUnpaidByClientId(string $clientId): int
    {
        return $this->model->query()
            ->whereHas('submission', fn($q) => $q->where('client_id', $clientId))
            ->where('payment_status', Invoice::STATUS_UNPAID)
            ->count();
    }
}