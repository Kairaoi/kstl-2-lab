{{-- resources/views/kstl/client/invoices/show.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('client.invoices.index') }}"
               class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $invoice->invoice_number }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Invoice Document --}}
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">

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
                        <p class="{{ $invoice->isPaymentDue() ? 'text-red-600 font-semibold' : 'text-gray-500' }}">
                            Due: {{ $invoice->payment_due_date->format('d M Y') }}
                            @if($invoice->isPaymentDue()) ⚠ Overdue @endif
                        </p>
                    </div>
                </div>

                <div class="px-8 py-5 border-b border-gray-100 grid grid-cols-2 gap-8">
                    <div>
                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-2">Bill To</p>
                        <p class="font-semibold text-gray-900">{{ $invoice->bill_to_company }}</p>
                        <p class="text-sm text-gray-600 mt-1 whitespace-pre-line">{{ $invoice->bill_to_address }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-2">Reference</p>
                        <p class="font-mono text-sm font-medium text-gray-800">
                            {{ $invoice->submission->reference_number }}
                        </p>
                        <p class="text-sm text-gray-600 mt-1">{{ $invoice->submission->sample_name }}</p>
                    </div>
                </div>

                {{-- Line Items --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 border-b border-gray-100">
                            <tr>
                                <th class="text-left px-8 py-3 text-xs font-medium text-gray-500 uppercase">Description</th>
                                <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 uppercase">Category</th>
                                <th class="text-right px-4 py-3 text-xs font-medium text-gray-500 uppercase">Qty</th>
                                <th class="text-right px-4 py-3 text-xs font-medium text-gray-500 uppercase">Unit Price</th>
                                <th class="text-right px-8 py-3 text-xs font-medium text-gray-500 uppercase">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($invoice->items->groupBy('category') as $category => $items)
                                @if($category)
                                    <tr class="bg-gray-50/50">
                                        <td colspan="5" class="px-8 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                            {{ $category }}
                                        </td>
                                    </tr>
                                @endif
                                @foreach($items as $item)
                                    <tr>
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
                                <td colspan="4" class="px-8 py-4 text-sm font-semibold text-gray-700 text-right">TOTAL (AUD)</td>
                                <td class="px-8 py-4 text-right text-xl font-bold text-gray-900">
                                    A$ {{ number_format($invoice->total_amount_aud, 2) }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                {{-- Payment Instructions --}}
                <div class="px-8 py-5 border-t border-gray-100 bg-blue-50/30">
                    <p class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-2">How to Pay</p>
                    <p class="text-sm text-gray-600">
                        Please pay by bank transfer within <strong>10 working days</strong>.
                        Use <strong>{{ $invoice->invoice_number }}</strong> as your payment reference.
                        Contact the lab for bank account details.
                    </p>
                </div>

                {{-- Paid notice --}}
                @if($invoice->isPaid())
                    <div class="px-8 py-4 border-t border-gray-100 bg-green-50">
                        <p class="text-sm font-semibold text-green-800">✓ Payment Received</p>
                        <p class="text-xs text-green-600 mt-0.5">
                            Ref: {{ $invoice->payment_reference }}
                            · {{ $invoice->payment_received_at?->format('d M Y') }}
                        </p>
                    </div>
                @endif

            </div>

            <div class="pb-8"></div>

        </div>
    </div>
</x-app-layout>