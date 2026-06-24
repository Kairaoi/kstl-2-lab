<?php

namespace App\Console\Commands;

use App\Models\Kstl\Submission;
use App\Models\User;
use App\Repositories\Kstl\InvoiceRepository;
use App\Repositories\Kstl\ResultRepository;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;

class GenerateMissingInvoices extends Command
{
    protected $signature   = 'kstl:generate-missing-invoices';
    protected $description = 'Generate invoices for authorised submissions that do not yet have one';

    public function __construct(
        protected InvoiceRepository $invoiceRepo,
        protected ResultRepository  $resultRepo,
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        // Need an authenticated user for issued_by — use first director
        $director = User::role('director')->first();
        if (! $director) {
            $this->error('No director user found.');
            return self::FAILURE;
        }
        Auth::loginUsingId($director->id);

        $submissions = Submission::whereIn('status', [
                Submission::STATUS_AUTHORISED,
                Submission::STATUS_COMPLETED,
            ])
            ->with(['samples.sampleTests', 'client.user'])
            ->get();

        $generated = 0;
        $skipped   = 0;

        foreach ($submissions as $submission) {
            if ($this->invoiceRepo->findBySubmissionId($submission->id)) {
                $skipped++;
                continue;
            }

            try {
                $resultId = $this->resultRepo->findBySubmissionId($submission->id)?->id;
                $invoice  = $this->invoiceRepo->generateForSubmission($submission->id, $resultId);
                $this->line("  ✓ {$submission->reference_number} → {$invoice->invoice_number} (A\$ {$invoice->total_amount_aud})");
                $generated++;
            } catch (\Throwable $e) {
                $this->warn("  ✗ {$submission->reference_number} — {$e->getMessage()}");
            }
        }

        $this->info("Done: {$generated} invoice(s) generated, {$skipped} already had one.");
        return self::SUCCESS;
    }
}
