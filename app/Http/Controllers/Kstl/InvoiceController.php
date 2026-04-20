<?php

namespace App\Http\Controllers\Kstl;

use App\Http\Controllers\Controller;
use App\Services\AuditService;
use App\Services\NotificationService;
use App\Repositories\Kstl\InvoiceRepository;
use App\Repositories\Kstl\SubmissionRepository;
use App\Models\Kstl\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class InvoiceController extends Controller
{
    public function __construct(
        protected InvoiceRepository    $invoiceRepo,
        protected SubmissionRepository  $submissionRepo,
        protected NotificationService   $notifyService,
        protected AuditService         $auditService,
    ) {}

    // ── Generate invoice for a submission ─────────────────────────
    public function generate(Request $request, string $submissionId)
    {
        $submission = $this->submissionRepo->getById($submissionId);

        if (! in_array($submission->status, [
            Submission::STATUS_AUTHORISED,
            Submission::STATUS_COMPLETED,
        ])) {
            return redirect()->back()
                ->with('error', 'Invoice can only be generated for authorised submissions.');
        }

        // Check if invoice already exists
        $existing = $this->invoiceRepo->findBySubmissionId($submissionId);
        if ($existing) {
            return redirect()->route('director.invoices.show', $existing->id)
                ->with('info', 'An invoice already exists for this submission.');
        }

        $resultId = $submission->result?->id;
        $invoice  = $this->invoiceRepo->generateForSubmission($submissionId, $resultId);

        // Advance submission to completed
        $this->submissionRepo->updateStatus(
            $submissionId,
            Submission::STATUS_COMPLETED
        );

        // Audit + notify
        $this->auditService->logInvoiceGenerated($invoice);
        $this->notifyService->notifyInvoiceIssued($invoice);

        return redirect()->route('director.invoices.show', $invoice->id)
            ->with('success', "Invoice {$invoice->invoice_number} generated successfully.");
    }

    // ── Show invoice (director view) ───────────────────────────────
    public function show(string $id)
    {
        $invoice = $this->invoiceRepo->getById($id);
        $invoice->load(['items', 'issuedBy', 'paymentVerifiedBy', 'submission.client']);

        return view('kstl.director.invoices.show', compact('invoice'));
    }

    // ── List all invoices (director view) ──────────────────────────
    public function index()
    {
        $invoices = \App\Models\Kstl\Invoice::with(['submission.client', 'issuedBy'])
            ->orderByDesc('invoice_date')
            ->get();

        $unpaidCount  = $invoices->where('payment_status', 'unpaid')->count();
        $overdueCount = $invoices->where('payment_status', 'overdue')->count();
        $totalUnpaid  = $invoices->whereIn('payment_status', ['unpaid', 'overdue'])
            ->sum('total_amount_aud');

        return view('kstl.director.invoices.index',
            compact('invoices', 'unpaidCount', 'overdueCount', 'totalUnpaid'));
    }

    // ── Mark as paid ───────────────────────────────────────────────
    public function markPaid(Request $request, string $id)
    {
        $validated = $request->validate([
            'payment_reference' => ['required', 'string', 'max:100'],
        ]);

        $invoice = $this->invoiceRepo->markPaid($id, $validated['payment_reference']);

        return redirect()->route('director.invoices.show', $invoice->id)
            ->with('success', "Invoice {$invoice->invoice_number} marked as paid.");
    }
}