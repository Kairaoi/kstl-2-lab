<?php

namespace App\Http\Controllers\Kstl;

use App\Http\Controllers\Controller;
use App\Repositories\Kstl\ClientRepository;
use App\Repositories\Kstl\InvoiceRepository;
use App\Models\Kstl\Invoice;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ClientInvoiceController extends Controller
{
    public function __construct(
        protected InvoiceRepository    $invoiceRepo,
        protected ClientRepository     $clientRepo,
        protected NotificationService  $notifyService,
    ) {}

    public function index()
    {
        $user   = Auth::user();
        $client = $this->clientRepo->findByUserId($user->id);

        $invoices = $client
            ? Invoice::whereHas('submission', fn($q) => $q->where('client_id', $client->id))
                ->with(['items', 'submission'])
                ->orderByDesc('invoice_date')
                ->get()
            : collect();

        return view('kstl.client.invoices.index',
            compact('client', 'user', 'invoices'));
    }

    public function show(string $id)
    {
        $user   = Auth::user();
        $client = $this->clientRepo->findByUserId($user->id);

        abort_if(! $client, 403);

        $invoice = Invoice::with(['items.sampleTest.sample', 'issuedBy', 'submission'])
            ->findOrFail($id);

        // Security — only the owning client
        abort_if($invoice->submission->client_id !== $client->id, 403);

        return view('kstl.client.invoices.show',
            compact('invoice', 'client', 'user'));
    }

    public function submitPaymentReference(Request $request, string $id)
    {
        $user   = Auth::user();
        $client = $this->clientRepo->findByUserId($user->id);

        abort_if(! $client, 403);

        $invoice = Invoice::with('submission')->findOrFail($id);
        abort_if($invoice->submission->client_id !== $client->id, 403);
        abort_if($invoice->isPaid(), 422, 'Invoice is already marked as paid.');

        $validated = $request->validate([
            'tt_reference' => ['required', 'string', 'max:100'],
        ]);

        $invoice->update([
            'payment_submitted_reference' => $validated['tt_reference'],
            'payment_submitted_at'        => now(),
            'payment_submitted_by'        => $user->id,
        ]);

        Log::info('Client submitted TT reference', [
            'invoice_id' => $invoice->id,
            'user_id'    => $user->id,
            'reference'  => $validated['tt_reference'],
        ]);

        $this->notifyService->notifyPaymentSubmitted($invoice);

        return redirect()->route('client.invoices.show', $id)
            ->with('success', 'Payment details submitted. The laboratory will verify and confirm your payment shortly.');
    }
}