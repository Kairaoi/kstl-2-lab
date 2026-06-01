{{-- resources/views/kstl/director/invoices/show.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('director.invoices.index') }}"
                   class="text-gray-400 hover:text-gray-600 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <div>
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        {{ $invoice->invoice_number }}
                    </h2>
                    <p class="text-sm text-gray-500 mt-0.5">
                        Issued {{ $invoice->invoice_date->format('d M Y') }}
                        · Due {{ $invoice->payment_due_date->format('d M Y') }}
                    </p>
                </div>
            </div>
            @php
                $sc = [
                    'unpaid'  => 'bg-yellow-50 text-yellow-700 ring-yellow-600/20',
                    'paid'    => 'bg-green-50 text-green-700 ring-green-600/20',
                    'overdue' => 'bg-red-50 text-red-700 ring-red-600/20',
                    'waived'  => 'bg-gray-50 text-gray-500 ring-gray-500/20',
                ];
            @endphp
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium ring-1 ring-inset {{ $sc[$invoice->payment_status] ?? 'bg-gray-50 text-gray-500' }} capitalize">
                {{ $invoice->payment_status }}
            </span>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-lg text-sm text-green-800">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('info'))
                <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-lg text-sm text-blue-800">
                    {{ session('info') }}
                </div>
            @endif

            {{-- ── Invoice Document ─────────────────────────────── --}}
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">

                {{-- Header --}}
                <div class="px-8 py-6 border-b border-gray-100 flex items-start justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-400 uppercase tracking-widest mb-1">
                            Kiribati Seafood Toxicology Laboratory
                        </p>
                        <h2 class="text-2xl font-bold text-gray-900">TAX INVOICE</h2>
                    </div>
                    <div class="text-right text-sm">
                        <p class="font-mono font-bold text-lg text-gray-800">{{ $invoice->invoice_number }}</p>
                        <p class="text-gray-500 mt-1">Date: {{ $invoice->invoice_date->format('d M Y') }}</p>
                        <p class="text-gray-500">Due: {{ $invoice->payment_due_date->format('d M Y') }}</p>
                    </div>
                </div>

                {{-- Bill To --}}
                <div class="px-8 py-5 border-b border-gray-100 grid grid-cols-2 gap-8">
                    <div>
                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-2">Bill To</p>
                        <p class="font-semibold text-gray-900">{{ $invoice->bill_to_company }}</p>
                        <p class="text-sm text-gray-600 mt-1 whitespace-pre-line">{{ $invoice->bill_to_address }}</p>
                        @if($invoice->bill_to_phone)
                            <p class="text-sm text-gray-500 mt-1">{{ $invoice->bill_to_phone }}</p>
                        @endif
                        @if($invoice->bill_to_email)
                            <p class="text-sm text-gray-500">{{ $invoice->bill_to_email }}</p>
                        @endif
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-2">Submission</p>
                        <p class="font-mono text-sm font-medium text-gray-800">
                            {{ $invoice->submission->reference_number }}
                        </p>
                        <p class="text-sm text-gray-600 mt-1">{{ $invoice->submission->sample_name }}</p>
                        <p class="text-xs text-gray-400 mt-1">
                            Issued by: {{ $invoice->issuedBy?->name }}
                        </p>
                    </div>
                </div>

                {{-- Line Items --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 border-b border-gray-100">
                            <tr>
                                <th class="text-left px-8 py-3 text-xs font-medium text-gray-500 uppercase tracking-wide">Description</th>
                                <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wide">Category</th>
                                <th class="text-right px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wide">Qty</th>
                                <th class="text-right px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wide">Unit Price</th>
                                <th class="text-right px-8 py-3 text-xs font-medium text-gray-500 uppercase tracking-wide">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($invoice->items->groupBy('category') as $category => $items)
                                {{-- Category subheader --}}
                                @if($category)
                                    <tr class="bg-gray-50/50">
                                        <td colspan="5" class="px-8 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                            {{ $category }}
                                        </td>
                                    </tr>
                                @endif
                                @foreach($items as $item)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-8 py-3 text-gray-800">{{ $item->item_description }}</td>
                                        <td class="px-4 py-3 text-gray-500 text-xs">{{ $item->category ?? '—' }}</td>
                                        <td class="px-4 py-3 text-right text-gray-700">{{ $item->quantity }}</td>
                                        <td class="px-4 py-3 text-right text-gray-700">A$ {{ number_format($item->unit_price_aud, 2) }}</td>
                                        <td class="px-8 py-3 text-right font-medium text-gray-800">A$ {{ number_format($item->total_price_aud, 2) }}</td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                        <tfoot class="border-t-2 border-gray-200">
                            <tr class="bg-gray-50">
                                <td colspan="4" class="px-8 py-4 text-sm font-semibold text-gray-700 text-right">
                                    TOTAL (AUD)
                                </td>
                                <td class="px-8 py-4 text-right text-xl font-bold text-gray-900">
                                    A$ {{ number_format($invoice->total_amount_aud, 2) }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                {{-- Payment Instructions --}}
                <div class="px-8 py-5 border-t border-gray-100 bg-blue-50/30">
                    <p class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-2">Payment Instructions</p>
                    <p class="text-sm text-gray-600">
                        Payment is due within <strong>10 working days</strong> by bank transfer.
                        Please include the invoice number <strong>{{ $invoice->invoice_number }}</strong> as your payment reference.
                    </p>
                    @if($invoice->notes)
                        <p class="text-sm text-gray-500 mt-2 italic">{{ $invoice->notes }}</p>
                    @endif
                </div>

                {{-- Payment Status --}}
                @if($invoice->isPaid())
                    <div class="px-8 py-4 border-t border-gray-100 bg-green-50 flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-green-800">✓ Payment Received</p>
                            <p class="text-xs text-green-600 mt-0.5">
                                Ref: {{ $invoice->payment_reference }}
                                · {{ $invoice->payment_received_at?->format('d M Y \a\t H:i') }}
                                · Verified by {{ $invoice->paymentVerifiedBy?->name }}
                            </p>
                        </div>
                    </div>
                @endif

            </div>

            {{-- ── Mark as Paid Form ────────────────────────────── --}}
            @if($invoice->isUnpaid() || $invoice->isOverdue())
                <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h3 class="text-sm font-medium text-gray-800">Record Payment</h3>
                        <p class="text-xs text-gray-400 mt-0.5">Enter the client's bank transfer reference to mark as paid.</p>
                    </div>
                    <form method="POST"
                          action="{{ route('director.invoices.paid', $invoice->id) }}"
                          class="px-6 py-5">
                        @csrf
                        <div class="flex gap-3">
                            <div class="flex-1">
                                <x-input type="text"
                                         name="payment_reference"
                                         placeholder="e.g. TT-20260414-001"
                                         class="w-full"
                                         required/>
                                <x-input-error for="payment_reference" class="mt-1"/>
                            </div>
                            <button type="submit"
                                    onclick="return confirm('Mark this invoice as paid?')"
                                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Mark as Paid
                            </button>
                        </div>
                    </form>
                </div>
            @endif

            <div class="pb-8"></div>

        </div>
    </div>
</x-app-layout>