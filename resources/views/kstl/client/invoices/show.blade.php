{{-- resources/views/kstl/client/invoices/show.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <div style="position:relative;overflow:hidden;background:linear-gradient(135deg,#0f2240 0%,#1a2f4e 60%,#1e3a5f 100%);margin:-1px;">
            <div style="position:absolute;inset:0;opacity:.04;background-image:repeating-linear-gradient(45deg,#fff 0,#fff 1px,transparent 0,transparent 50%);background-size:12px 12px;pointer-events:none;"></div>
            <div style="position:absolute;top:0;left:0;right:0;height:3px;background:linear-gradient(90deg,#1a2f4e,#b8922a 30%,#b8922a 70%,#1a2f4e);"></div>
            <div style="max-width:80rem;margin:0 auto;padding:28px 2rem;">
                <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:20px;position:relative;">
                    <div style="display:flex;align-items:center;gap:18px;">
                        <img src="{{ asset('images/mfor-logo.png') }}" alt="Ministry of Fisheries &amp; Ocean Resources" style="width:56px;height:56px;object-fit:contain;filter:brightness(0) invert(1);opacity:.92;">
                        <div>
                            <p style="font-size:8.5px;font-weight:700;letter-spacing:.22em;text-transform:uppercase;color:#b8922a;margin-bottom:5px;">
                                Client &nbsp;·&nbsp; Seafood Toxicology Laboratory
                            </p>
                            <h1 style="font-family:'Georgia',serif;font-size:22px;font-weight:700;color:#ffffff;line-height:1.2;margin:0;">
                                Tax Invoice
                            </h1>
                            <p style="font-size:11px;color:#94a3b8;margin-top:4px;font-family:monospace;">
                                {{ $invoice->invoice_number }}
                            </p>
                        </div>
                    </div>
                    <div style="text-align:right;">
                        <p style="font-size:9px;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:#64748b;margin-bottom:4px;">Invoice Date</p>
                        <p style="font-size:13px;font-weight:600;color:#e2e8f0;">{{ $invoice->invoice_date->format('d F Y') }}</p>
                        <a href="{{ route('client.invoices.index') }}" style="display:inline-flex;align-items:center;gap:5px;margin-top:8px;font-size:11px;font-weight:600;color:#94a3b8;text-decoration:none;">
                            <svg style="width:12px;height:12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                            Back to Invoices
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    @push('styles')
    <style>
        .page-hdr { padding: 0 !important; position: static !important; }
        .page-hdr-inner { max-width: 100% !important; padding: 0 !important; }
        .app-main { padding-left: 0 !important; padding-right: 0 !important;
                    padding-top: 0 !important; max-width: 100% !important; }
    </style>
    @endpush

    <div style="background:#f1f5f9; min-height:100vh; padding:0 0 56px;">
        <div style="max-width:80rem; margin:0 auto; padding:0 2rem; display:flex; flex-direction:column; gap:24px;">

            @if(session('warning'))
                <div style="border-left:4px solid #f59e0b; padding:12px 18px; border-radius:0 4px 4px 0; background:#fffbeb; color:#92400e; font-size:12.5px; margin-bottom:20px; display:flex; align-items:flex-start; gap:10px;">
                    <svg style="width:16px; height:16px; flex-shrink:0; margin-top:1px; color:#d97706;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                    </svg>
                    <p style="margin:0;">{{ session('warning') }}</p>
                </div>
            @endif

            {{-- Invoice Document --}}
            <div style="background:#fff; border:1px solid #e2e8f0; border-radius:4px; overflow:hidden; margin-bottom:20px;">

                {{-- Invoice Header --}}
                <div style="padding:24px 28px; border-bottom:1px solid #e2e8f0; display:flex; align-items:flex-start; justify-content:space-between; flex-wrap:wrap; gap:16px;">
                    <div>
                        <p style="font-size:9px; font-weight:700; letter-spacing:.14em; text-transform:uppercase; color:#9ca3af; margin:0 0 6px;">
                            Kiribati Seafood Toxicology Laboratory
                        </p>
                        <h2 style="font-family:'Georgia',serif; font-size:20px; font-weight:700; color:#1a2f4e; margin:0;">TAX INVOICE</h2>
                    </div>
                    <div style="text-align:right;">
                        <p style="font-family:monospace; font-size:16px; font-weight:700; color:#1a2f4e; margin:0 0 4px;">{{ $invoice->invoice_number }}</p>
                        <p style="font-size:12px; color:#6b7280; margin:0 0 2px;">Date: {{ $invoice->invoice_date->format('d M Y') }}</p>
                        <p style="font-size:12px; margin:0; {{ $invoice->isPaymentDue() ? 'color:#dc2626; font-weight:600;' : 'color:#6b7280;' }}">
                            Due: {{ $invoice->payment_due_date->format('d M Y') }}
                            @if($invoice->isPaymentDue()) &#9888; Overdue @endif
                        </p>
                    </div>
                </div>

                {{-- Bill To / Reference --}}
                <div style="padding:20px 28px; border-bottom:1px solid #e2e8f0; display:grid; grid-template-columns:1fr 1fr; gap:32px;">
                    <div>
                        <p style="font-size:9px; font-weight:700; letter-spacing:.14em; text-transform:uppercase; color:#9ca3af; margin:0 0 8px;">Bill To</p>
                        <p style="font-size:13px; font-weight:700; color:#1a2f4e; margin:0 0 4px;">{{ $invoice->bill_to_company }}</p>
                        <p style="font-size:12px; color:#6b7280; margin:0; white-space:pre-line;">{{ $invoice->bill_to_address }}</p>
                    </div>
                    <div>
                        <p style="font-size:9px; font-weight:700; letter-spacing:.14em; text-transform:uppercase; color:#9ca3af; margin:0 0 8px;">Submission Reference</p>
                        <p style="font-family:monospace; font-size:13px; font-weight:600; color:#1a2f4e; margin:0 0 4px;">
                            {{ $invoice->submission->reference_number }}
                        </p>
                        <p style="font-size:12px; color:#6b7280; margin:0;">{{ $invoice->submission->sample_name }}</p>
                    </div>
                </div>

                {{-- Line Items --}}
                <div style="overflow-x:auto;">
                    <table style="width:100%; border-collapse:collapse;">
                        <thead>
                            <tr style="background:#1a2f4e;">
                                <th style="text-align:left; padding:9px 28px; font-size:9px; font-weight:700; letter-spacing:.12em; text-transform:uppercase; color:#e2e8f0;">Test Description</th>
                                <th style="text-align:left; padding:9px 16px; font-size:9px; font-weight:700; letter-spacing:.12em; text-transform:uppercase; color:#e2e8f0;">Category</th>
                                <th style="text-align:right; padding:9px 16px; font-size:9px; font-weight:700; letter-spacing:.12em; text-transform:uppercase; color:#e2e8f0;">Qty</th>
                                <th style="text-align:right; padding:9px 16px; font-size:9px; font-weight:700; letter-spacing:.12em; text-transform:uppercase; color:#e2e8f0;">Unit Price</th>
                                <th style="text-align:right; padding:9px 28px; font-size:9px; font-weight:700; letter-spacing:.12em; text-transform:uppercase; color:#e2e8f0;">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $itemsBySample = $invoice->items->groupBy(fn($i) => $i->sampleTest?->sample_id ?? 'other');
                                $multiSample   = $itemsBySample->count() > 1;
                                $groupIndex    = 0;
                            @endphp
                            @foreach($itemsBySample as $sampleId => $items)
                                @php
                                    $sample = $items->first()->sampleTest?->sample;
                                    $groupIndex++;
                                @endphp
                                {{-- Sample group header --}}
                                <tr style="background:#f8fafc; border-top:1px solid #e2e8f0; border-bottom:1px solid #e2e8f0;">
                                    <td colspan="5" style="padding:8px 28px;">
                                        <div style="display:flex; flex-wrap:wrap; align-items:center; gap:8px;">
                                            <span style="font-size:12.5px; font-weight:700; color:#1a2f4e;">
                                                {{ $sample?->common_name ?? 'Other Charges' }}
                                            </span>
                                            @if($sample?->scientific_name)
                                                <span style="font-size:11px; font-style:italic; color:#6b7280;">{{ $sample->scientific_name }}</span>
                                            @endif
                                            @if($sample?->sample_code)
                                                <span style="font-family:monospace; font-size:10px; color:#9ca3af; background:#f3f4f6; padding:1px 6px; border-radius:2px; border:1px solid #e2e8f0;">{{ $sample->sample_code }}</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @foreach($items as $j => $item)
                                    @php $itemBg = $j % 2 === 0 ? '#fff' : '#f8fafc'; @endphp
                                    <tr style="background:{{ $itemBg }}; border-bottom:1px solid #f1f5f9;">
                                        <td style="padding:10px 28px 10px 40px; font-size:12.5px; color:#374151;">{{ $item->item_description }}</td>
                                        <td style="padding:10px 16px; font-size:11.5px; color:#6b7280;">{{ $item->category ?? '—' }}</td>
                                        <td style="padding:10px 16px; font-size:12.5px; text-align:right; color:#374151;">{{ $item->quantity }}</td>
                                        <td style="padding:10px 16px; font-size:12.5px; text-align:right; color:#374151;">A$ {{ number_format($item->unit_price_aud, 2) }}</td>
                                        <td style="padding:10px 28px; font-size:12.5px; text-align:right; font-weight:600; color:#1a2f4e;">A$ {{ number_format($item->total_price_aud, 2) }}</td>
                                    </tr>
                                @endforeach
                                @if($multiSample)
                                    <tr style="background:#f0f9ff; border-bottom:1px solid #e2e8f0;">
                                        <td colspan="4" style="padding:7px 28px; font-size:11px; color:#6b7280; text-align:right; font-style:italic;">
                                            Subtotal — {{ $sample?->common_name ?? 'Other' }}
                                        </td>
                                        <td style="padding:7px 28px; text-align:right; font-size:12.5px; font-weight:700; color:#1a2f4e;">
                                            A$ {{ number_format($items->sum('total_price_aud'), 2) }}
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr style="background:#1a2f4e; border-top:2px solid #b8922a;">
                                <td colspan="4" style="padding:14px 28px; font-size:11px; font-weight:700; letter-spacing:.12em; text-transform:uppercase; color:#e2e8f0; text-align:right;">Total (AUD)</td>
                                <td style="padding:14px 28px; text-align:right; font-size:18px; font-weight:700; color:#fff;">
                                    A$ {{ number_format($invoice->total_amount_aud, 2) }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                {{-- Payment Instructions --}}
                <div style="padding:18px 28px; border-top:1px solid #e2e8f0; background:#f8fafc;">
                    <p style="font-size:9px; font-weight:700; letter-spacing:.14em; text-transform:uppercase; color:#9ca3af; margin:0 0 6px;">How to Pay</p>
                    <p style="font-size:12.5px; color:#374151; margin:0;">
                        Please pay by bank transfer within <strong>10 working days</strong>.
                        Use <strong>{{ $invoice->invoice_number }}</strong> as your payment reference.
                        Contact the lab for bank account details.
                    </p>
                </div>

                {{-- Payment status notices --}}
                @if($invoice->isPaid())
                    <div style="border-left:4px solid #16a34a; padding:12px 18px; border-radius:0 4px 4px 0; background:#f0fdf4; margin:0 28px 20px; color:#15803d;">
                        <p style="font-size:13px; font-weight:700; margin:0 0 3px;">&#10003; Payment Confirmed</p>
                        <p style="font-size:11.5px; margin:0; color:#16a34a;">
                            Ref: {{ $invoice->payment_reference }}
                            &middot; {{ $invoice->payment_received_at?->format('d M Y') }}
                        </p>
                    </div>
                @elseif($invoice->hasSubmittedPayment())
                    <div style="border-left:4px solid #3b82f6; padding:12px 18px; border-radius:0 4px 4px 0; background:#eff6ff; margin:0 28px 20px; color:#1d4ed8;">
                        <p style="font-size:13px; font-weight:700; margin:0 0 3px;">Payment Details Submitted — Awaiting Confirmation</p>
                        <p style="font-size:11.5px; margin:0 0 2px; color:#2563eb;">
                            TT Reference: <span style="font-family:monospace; font-weight:700;">{{ $invoice->payment_submitted_reference }}</span>
                            &middot; Submitted {{ $invoice->payment_submitted_at?->format('d M Y \a\t H:i') }}
                        </p>
                        <p style="font-size:11px; margin:0; color:#3b82f6;">The laboratory will verify your payment and confirm shortly.</p>
                    </div>
                @endif

            </div>

            {{-- Submit Payment Details --}}
            @if(! $invoice->isPaid() && ! $invoice->hasSubmittedPayment())
                <div style="background:#fff; border:1px solid #e2e8f0; border-radius:4px; overflow:hidden; margin-bottom:20px;">

                    <div style="padding:16px 24px 14px;">
                        <h3 style="font-family:'Georgia',serif; font-size:15px; font-weight:700; color:#1a2f4e; border-bottom:2px solid #b8922a; padding-bottom:8px; margin-bottom:8px;">
                            Submit Payment Details
                        </h3>
                        <p style="font-size:12px; color:#6b7280; margin:0;">
                            After completing your bank transfer, enter your Telegraphic Transfer (TT) reference number below.
                            The laboratory will verify and confirm receipt.
                        </p>
                    </div>

                    @if(session('success'))
                        <div style="border-left:4px solid #16a34a; padding:12px 18px; border-radius:0 4px 4px 0; background:#f0fdf4; color:#15803d; font-size:12.5px; margin:0 24px 12px;">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form method="POST"
                          action="{{ route('client.invoices.payment-reference', $invoice->id) }}"
                          style="padding:16px 24px 20px;">
                        @csrf
                        <div style="display:flex; gap:12px; align-items:flex-end; flex-wrap:wrap;">
                            <div style="flex:1; min-width:220px;">
                                <label style="display:block; font-size:9px; font-weight:700; letter-spacing:.14em; text-transform:uppercase; color:#9ca3af; margin-bottom:6px;">
                                    TT Reference Number <span style="color:#dc2626;">*</span>
                                </label>
                                <input type="text"
                                       name="tt_reference"
                                       value="{{ old('tt_reference') }}"
                                       placeholder="e.g. TT-20260608-001"
                                       required
                                       style="width:100%; box-sizing:border-box; border:1px solid #e2e8f0; border-radius:3px; padding:9px 12px; font-size:12.5px; font-family:monospace; color:#374151; outline:none; @error('tt_reference') border-color:#dc2626; @enderror"
                                       class="focus:border-teal-500 focus:ring-teal-500 @error('tt_reference') border-red-400 @enderror"/>
                                @error('tt_reference')
                                    <p style="font-size:11px; color:#dc2626; margin:4px 0 0;">{{ $message }}</p>
                                @enderror
                                <p style="font-size:11px; color:#9ca3af; margin:5px 0 0;">
                                    Use invoice number <strong style="color:#374151;">{{ $invoice->invoice_number }}</strong> as your payment reference when making the transfer.
                                </p>
                            </div>
                            <div>
                                <button type="submit"
                                        onclick="return confirm('Submit TT reference {{ $invoice->invoice_number }}?')"
                                        style="background:#0d9488; color:#fff; padding:9px 20px; border-radius:3px; font-size:12px; font-weight:600; border:none; cursor:pointer; display:inline-flex; align-items:center; gap:6px;">
                                    <svg style="width:13px; height:13px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                    </svg>
                                    Submit Payment Details
                                </button>
                            </div>
                        </div>
                    </form>

                </div>
            @endif

        </div>
    </div>
</x-app-layout>
