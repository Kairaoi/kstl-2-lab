{{-- resources/views/kstl/client/invoices/index.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">My Invoices</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if($invoices->isEmpty())
                <div class="bg-white rounded-xl border border-gray-100 px-6 py-16 text-center">
                    <svg class="w-10 h-10 text-gray-200 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <p class="text-sm font-medium text-gray-400">No invoices yet</p>
                    <p class="text-xs text-gray-300 mt-1">Invoices will appear here after your test results are authorised.</p>
                </div>
            @else
                <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h3 class="text-sm font-medium text-gray-800">All Invoices</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 border-b border-gray-100">
                                <tr>
                                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Invoice No.</th>
                                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Submission</th>
                                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Date</th>
                                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Due</th>
                                    <th class="text-right px-6 py-3 text-xs font-medium text-gray-500 uppercase">Amount</th>
                                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-6 py-3"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @foreach($invoices as $invoice)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4">
                                            <span class="font-mono text-xs font-medium text-gray-700">
                                                {{ $invoice->invoice_number }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="font-mono text-xs text-gray-500">
                                                {{ $invoice->submission->reference_number }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-xs text-gray-600">
                                            {{ $invoice->invoice_date->format('d M Y') }}
                                        </td>
                                        <td class="px-6 py-4 text-xs {{ $invoice->isPaymentDue() ? 'text-red-600 font-medium' : 'text-gray-600' }}">
                                            {{ $invoice->payment_due_date->format('d M Y') }}
                                            @if($invoice->isPaymentDue())
                                                <span class="ml-1">⚠ Overdue</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-right font-semibold text-gray-800">
                                            A$ {{ number_format($invoice->total_amount_aud, 2) }}
                                        </td>
                                        <td class="px-6 py-4">
                                            @php
                                                $sc = [
                                                    'unpaid'  => 'bg-yellow-50 text-yellow-700',
                                                    'paid'    => 'bg-green-50 text-green-700',
                                                    'overdue' => 'bg-red-50 text-red-700',
                                                    'waived'  => 'bg-gray-100 text-gray-500',
                                                ];
                                            @endphp
                                            <span class="inline-flex px-2 py-0.5 text-xs font-medium rounded-full capitalize {{ $sc[$invoice->payment_status] ?? 'bg-gray-100 text-gray-500' }}">
                                                {{ $invoice->payment_status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <a href="{{ route('client.invoices.show', $invoice->id) }}"
                                               class="text-xs text-gray-600 px-3 py-1.5 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                                                View
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>