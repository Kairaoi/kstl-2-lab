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
                            Issued by: {{ $invoice->issuedBy?->name ?? 'System' }}
                        </p>
                    </div>
                </div>

                {{-- Line Items --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 border-b border-gray-100">
                            <tr>
                                <th class="text-left px-8 py-3 text-xs font-medium text-gray-500 uppercase tracking-wide">Test Description</th>
                                <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wide">Category</th>
                                <th class="text-right px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wide">Qty</th>
                                <th class="text-right px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wide">Unit Price</th>
                                <th class="text-right px-8 py-3 text-xs font-medium text-gray-500 uppercase tracking-wide">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @php
                                $itemsBySample = $invoice->items->groupBy(fn($i) => $i->sampleTest?->sample_id ?? 'other');
                                $multiSample   = $itemsBySample->count() > 1;
                            @endphp
                            @foreach($itemsBySample as $sampleId => $items)
                                @php $sample = $items->first()->sampleTest?->sample; @endphp
                                {{-- Sample header --}}
                                <tr class="bg-gray-50 border-t border-gray-100">
                                    <td colspan="5" class="px-8 py-2.5">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <span class="font-semibold text-gray-800 text-sm">
                                                {{ $sample?->common_name ?? 'Other Charges' }}
                                            </span>
                                            @if($sample?->scientific_name)
                                                <span class="text-xs italic text-gray-500">{{ $sample->scientific_name }}</span>
                                            @endif
                                            @if($sample?->sample_code)
                                                <span class="font-mono text-xs text-gray-400 bg-gray-100 px-1.5 py-0.5 rounded">{{ $sample->sample_code }}</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @foreach($items as $item)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-8 py-2.5 pl-12 text-gray-700">{{ $item->item_description }}</td>
                                        <td class="px-4 py-2.5 text-gray-500 text-xs">{{ $item->category ?? '—' }}</td>
                                        <td class="px-4 py-2.5 text-right text-gray-700">{{ $item->quantity }}</td>
                                        <td class="px-4 py-2.5 text-right text-gray-700">A$ {{ number_format($item->unit_price_aud, 2) }}</td>
                                        <td class="px-8 py-2.5 text-right font-medium text-gray-800">A$ {{ number_format($item->total_price_aud, 2) }}</td>
                                    </tr>
                                @endforeach
                                @if($multiSample)
                                    <tr class="bg-blue-50/30">
                                        <td colspan="4" class="px-8 py-1.5 text-xs text-gray-500 text-right italic">
                                            Subtotal — {{ $sample?->common_name ?? 'Other' }}
                                        </td>
                                        <td class="px-8 py-1.5 text-right text-sm font-semibold text-gray-700">
                                            A$ {{ number_format($items->sum('total_price_aud'), 2) }}
                                        </td>
                                    </tr>
                                @endif
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
                            <p class="text-sm font-semibold text-green-800">✓ Payment Confirmed</p>
                            <p class="text-xs text-green-600 mt-0.5">
                                Ref: {{ $invoice->payment_reference }}
                                · {{ $invoice->payment_received_at?->format('d M Y \a\t H:i') }}
                                · Confirmed by {{ $invoice->paymentVerifiedBy?->name }}
                            </p>
                        </div>
                    </div>
                @elseif($invoice->hasSubmittedPayment())
                    <div class="px-8 py-4 border-t border-gray-100 bg-blue-50 flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-blue-800">⏳ Client Submitted Payment Details</p>
                            <p class="text-xs text-blue-600 mt-0.5">
                                TT Reference: <span class="font-mono font-semibold">{{ $invoice->payment_submitted_reference }}</span>
                                · Submitted by {{ $invoice->paymentSubmittedBy?->name }}
                                on {{ $invoice->payment_submitted_at?->format('d M Y \a\t H:i') }}
                            </p>
                        </div>
                    </div>
                @endif

            </div>

            {{-- ── Payment section ──────────────────────────────────── --}}
            @if($invoice->isUnpaid() || $invoice->isOverdue())
                <div class="rounded-xl overflow-hidden {{ $invoice->hasSubmittedPayment() ? 'border-2 border-teal-400 bg-teal-50' : 'border border-gray-100 bg-white' }}">

                    {{-- Status banner --}}
                    @if($invoice->hasSubmittedPayment())
                        <div class="px-6 py-4 bg-teal-500 flex items-start gap-3">
                            <svg class="w-5 h-5 text-white mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div>
                                <p class="text-sm font-bold text-white">Client Payment Reference Received</p>
                                <p class="text-xs text-teal-100 mt-0.5">
                                    {{ $invoice->bill_to_company }} submitted this reference on
                                    {{ $invoice->payment_submitted_at?->format('d M Y \a\t H:i') }}.
                                    Verify it against your bank records before confirming.
                                </p>
                            </div>
                        </div>

                        {{-- Submitted reference display --}}
                        <div class="px-6 py-4 border-b border-teal-200 flex items-center gap-4">
                            <div class="flex-1">
                                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Client's TT Reference</p>
                                <p class="font-mono text-xl font-bold text-gray-900 tracking-wide">
                                    {{ $invoice->payment_submitted_reference }}
                                </p>
                                @if($invoice->paymentSubmittedBy)
                                    <p class="text-xs text-gray-400 mt-1">Submitted by {{ $invoice->paymentSubmittedBy->name }}</p>
                                @endif
                            </div>
                        </div>

                        {{-- Verify & confirm form --}}
                        <form method="POST"
                              action="{{ route('director.invoices.paid', $invoice->id) }}"
                              class="px-6 py-5"
                              x-data="{ ref: '{{ $invoice->payment_submitted_reference }}' }">
                            @csrf
                            <p class="text-xs text-gray-500 mb-3">The reference is pre-filled. You may edit it if needed before confirming.</p>
                            <div class="flex gap-3 items-end">
                                <div class="flex-1">
                                    <label class="block text-xs font-medium text-gray-600 mb-1">TT Reference to Record</label>
                                    <input type="text"
                                           name="payment_reference"
                                           x-model="ref"
                                           class="w-full border-teal-300 rounded-lg text-sm font-mono focus:border-teal-500 focus:ring-teal-500"
                                           required/>
                                    @error('payment_reference')
                                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <button type="submit"
                                        :disabled="ref.trim().length < 3"
                                        @click.prevent="if(ref.trim().length >= 3 && confirm('Confirm payment for {{ $invoice->invoice_number }} with reference ' + ref.trim() + '?')) $el.closest('form').submit()"
                                        :class="ref.trim().length >= 3
                                            ? 'bg-teal-600 hover:bg-teal-700 text-white cursor-pointer'
                                            : 'bg-gray-200 text-gray-400 cursor-not-allowed'"
                                        class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold rounded-lg transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Confirm &amp; Mark as Paid
                                </button>
                            </div>
                        </form>

                    @else
                        {{-- No client reference yet — awaiting + manual fallback --}}
                        <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
                            <svg class="w-5 h-5 text-amber-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div>
                                <p class="text-sm font-semibold text-gray-800">Awaiting Client Payment Reference</p>
                                <p class="text-xs text-gray-400 mt-0.5">
                                    The client has not yet submitted their TT reference on the portal.
                                    You may record payment manually below if payment was received outside the portal.
                                </p>
                            </div>
                        </div>
                        <form method="POST"
                              action="{{ route('director.invoices.paid', $invoice->id) }}"
                              class="px-6 py-5"
                              x-data="{ ref: '' }">
                            @csrf
                            <div class="flex gap-3">
                                <div class="flex-1">
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Payment Reference (manual entry)</label>
                                    <input type="text"
                                           name="payment_reference"
                                           x-model="ref"
                                           placeholder="e.g. TT-20260608-001"
                                           class="w-full border-gray-300 rounded-lg text-sm font-mono focus:border-teal-500 focus:ring-teal-500"
                                           required/>
                                    @error('payment_reference')
                                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <button type="submit"
                                        :disabled="ref.trim().length < 3"
                                        @click.prevent="if(ref.trim().length >= 3 && confirm('Mark {{ $invoice->invoice_number }} as paid with reference ' + ref.trim() + '?')) $el.closest('form').submit()"
                                        :class="ref.trim().length >= 3
                                            ? 'bg-green-600 hover:bg-green-700 text-white cursor-pointer'
                                            : 'bg-gray-200 text-gray-400 cursor-not-allowed'"
                                        class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-medium rounded-lg transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Mark as Paid
                                </button>
                            </div>
                        </form>
                    @endif

                </div>
            @endif

            <div class="pb-8"></div>

        </div>
    </div>
</x-app-layout>