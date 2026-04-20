{{-- resources/views/kstl/director/invoices/index.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Invoices</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-lg text-sm text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Summary Cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-white rounded-xl border border-gray-100 p-5">
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Unpaid</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $unpaidCount }}</p>
                    <p class="text-xs text-gray-400 mt-1">Awaiting payment</p>
                </div>
                <div class="bg-white rounded-xl border border-gray-100 p-5">
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Overdue</p>
                    <p class="text-3xl font-bold text-red-600">{{ $overdueCount }}</p>
                    <p class="text-xs text-gray-400 mt-1">Past due date</p>
                </div>
                <div class="bg-white rounded-xl border border-gray-100 p-5">
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Total Outstanding</p>
                    <p class="text-3xl font-bold text-gray-900">A$ {{ number_format($totalUnpaid, 2) }}</p>
                    <p class="text-xs text-gray-400 mt-1">Unpaid + overdue</p>
                </div>
            </div>

            {{-- ── Authorised submissions needing invoices ─── --}}
            @php
                $needInvoice = \App\Models\Kstl\Submission::whereIn('status', ['authorised','completed'])
                    ->whereDoesntHave('invoice')
                    ->with('client')
                    ->get();
            @endphp
            @if($needInvoice->isNotEmpty())
                <div class="bg-white rounded-xl border border-amber-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-amber-100 bg-amber-50">
                        <h3 class="text-sm font-medium text-amber-800">
                            Awaiting Invoice Generation ({{ $needInvoice->count() }})
                        </h3>
                        <p class="text-xs text-amber-600 mt-0.5">These submissions have been authorised but no invoice has been generated yet.</p>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 border-b border-gray-100">
                                <tr>
                                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Reference</th>
                                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Client</th>
                                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-6 py-3"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @foreach($needInvoice as $sub)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 font-mono text-xs font-medium text-gray-700">
                                            {{ $sub->reference_number }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-700">
                                            {{ $sub->client->company_name ?? '—' }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex px-2 py-0.5 text-xs bg-teal-50 text-teal-700 rounded-full capitalize">
                                                {{ $sub->status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <form method="POST"
                                                  action="{{ route('director.invoices.generate', $sub->id) }}"
                                                  onsubmit="return confirm('Generate invoice for {{ $sub->reference_number }}?')">
                                                @csrf
                                                <button type="submit"
                                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-600 text-white text-xs font-medium rounded-lg hover:bg-blue-700 transition">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/>
                                                    </svg>
                                                    Generate Invoice
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            {{-- Invoices Table --}}
            <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="text-sm font-medium text-gray-800">All Invoices</h3>
                </div>

                @if($invoices->isEmpty())
                    <div class="px-6 py-16 text-center">
                        <p class="text-sm text-gray-400">No invoices generated yet.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 border-b border-gray-100">
                                <tr>
                                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Invoice No.</th>
                                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Client</th>
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
                                            <p class="font-medium text-gray-800">{{ $invoice->bill_to_company }}</p>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="font-mono text-xs text-gray-500">
                                                {{ $invoice->submission->reference_number }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-gray-600 text-xs">
                                            {{ $invoice->invoice_date->format('d M Y') }}
                                        </td>
                                        <td class="px-6 py-4 text-xs {{ $invoice->isPaymentDue() ? 'text-red-600 font-medium' : 'text-gray-600' }}">
                                            {{ $invoice->payment_due_date->format('d M Y') }}
                                        </td>
                                        <td class="px-6 py-4 text-right font-medium text-gray-800">
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
                                            <a href="{{ route('director.invoices.show', $invoice->id) }}"
                                               class="text-xs text-blue-600 px-3 py-1.5 border border-blue-200 rounded-lg hover:bg-blue-50 transition">
                                                View
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>