<?php

namespace App\Http\Controllers\Kstl;

use App\Http\Controllers\Controller;
use App\Repositories\Kstl\ClientRepository;
use App\Repositories\Kstl\InvoiceRepository;
use App\Models\Kstl\Invoice;
use Illuminate\Support\Facades\Auth;

class ClientInvoiceController extends Controller
{
    public function __construct(
        protected InvoiceRepository $invoiceRepo,
        protected ClientRepository  $clientRepo,
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

        $invoice = Invoice::with(['items', 'issuedBy', 'submission'])
            ->findOrFail($id);

        // Security — only the owning client
        abort_if($invoice->submission->client_id !== $client->id, 403);

        return view('kstl.client.invoices.show',
            compact('invoice', 'client', 'user'));
    }
}