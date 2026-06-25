{{-- resources/views/kstl/director/invoices/show.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <div style="position:relative;overflow:hidden;background:linear-gradient(135deg,#0f2240 0%,#1a2f4e 60%,#1e3a5f 100%);">
            <div style="height:3px;background:linear-gradient(90deg,#1a2f4e,#b8922a 30%,#b8922a 70%,#1a2f4e);"></div>
            <div style="max-width:80rem;margin:0 auto;padding:28px 2rem 32px;">
                <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;">
                    <div style="display:flex;align-items:center;gap:20px;">
                        <img src="{{ asset('images/mfor-logo.png') }}" alt="MFOR" style="filter:brightness(0) invert(1);opacity:.92;width:56px;height:56px;flex-shrink:0;">
                        <div>
                            <p style="font-size:9px;font-weight:700;letter-spacing:.18em;text-transform:uppercase;color:#b8922a;margin:0 0 4px;">Director Portal</p>
                            <h1 style="font-family:'Georgia',serif;font-size:22px;font-weight:700;color:#fff;margin:0 0 6px;line-height:1.2;">{{ $invoice->invoice_number }}</h1>
                            <p style="font-size:12px;color:#94a3b8;margin:0;">Issued {{ $invoice->invoice_date->format('d M Y') }} &middot; Due {{ $invoice->payment_due_date->format('d M Y') }}</p>
                        </div>
                    </div>
                    <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                        @php
                            $sc = [
                                'unpaid'  => 'background:#fef9c3;color:#854d0e;border:1px solid #fde047;',
                                'paid'    => 'background:#dcfce7;color:#166534;border:1px solid #86efac;',
                                'overdue' => 'background:#fee2e2;color:#991b1b;border:1px solid #fca5a5;',
                                'waived'  => 'background:#f1f5f9;color:#64748b;border:1px solid #cbd5e1;',
                            ];
                        @endphp
                        <span style="display:inline-flex;align-items:center;padding:5px 14px;border-radius:3px;font-size:11px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;{{ $sc[$invoice->payment_status] ?? 'background:#f1f5f9;color:#64748b;border:1px solid #cbd5e1;' }}">
                            {{ $invoice->payment_status }}
                        </span>
                        <a href="{{ route('director.invoices.index') }}"
                           style="display:inline-flex;align-items:center;gap:8px;padding:8px 20px;background:#fff;color:#1a2f4e;font-size:12px;font-weight:700;letter-spacing:.06em;border:1px solid #1a2f4e;border-radius:3px;text-decoration:none;">
                            &larr; All Invoices
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    @push('styles')
    <style>
        .page-hdr { padding: 0 !important; }
        .page-hdr-inner { max-width: 100% !important; padding: 0 !important; }
        .app-main { padding-left:0 !important; padding-right:0 !important; padding-top:0 !important; max-width:100% !important; }
    </style>
    @endpush

    <div style="background:#f1f5f9;min-height:100vh;padding:52px 0 56px;">
        <div style="max-width:80rem;margin:0 auto;padding:0 2rem;">

            @if(session('success'))
                <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-left:4px solid #16a34a;border-radius:4px;padding:12px 16px;margin-bottom:20px;font-size:13px;color:#166534;">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div style="background:#fef2f2;border:1px solid #fecaca;border-left:4px solid #dc2626;border-radius:4px;padding:12px 16px;margin-bottom:20px;font-size:13px;color:#991b1b;">{{ session('error') }}</div>
            @endif
            @if(session('info'))
                <div style="background:#eff6ff;border:1px solid #bfdbfe;border-left:4px solid #2563eb;border-radius:4px;padding:12px 16px;margin-bottom:20px;font-size:13px;color:#1e40af;">{{ session('info') }}</div>
            @endif

            {{-- ── Invoice Document ─────────────────────────────── --}}
            <div style="background:#fff;border:1px solid #e2e8f0;border-radius:4px;overflow:hidden;margin-bottom:24px;">

                {{-- Header --}}
                <div style="padding:24px 32px;border-bottom:1px solid #e2e8f0;display:flex;align-items:flex-start;justify-content:space-between;">
                    <div>
                        <p style="font-size:9px;font-weight:700;letter-spacing:.18em;text-transform:uppercase;color:#b8922a;margin:0 0 6px;">Kiribati Seafood Toxicology Laboratory</p>
                        <h2 style="font-family:'Georgia',serif;font-size:24px;font-weight:700;color:#1a2f4e;margin:0;">TAX INVOICE</h2>
                    </div>
                    <div style="text-align:right;">
                        <p style="font-family:monospace;font-size:18px;font-weight:700;color:#1a2f4e;margin:0 0 6px;">{{ $invoice->invoice_number }}</p>
                        <p style="font-size:12px;color:#64748b;margin:0 0 2px;">Date: {{ $invoice->invoice_date->format('d M Y') }}</p>
                        <p style="font-size:12px;color:#64748b;margin:0;">Due: {{ $invoice->payment_due_date->format('d M Y') }}</p>
                    </div>
                </div>

                {{-- Bill To --}}
                <div style="padding:20px 32px;border-bottom:1px solid #e2e8f0;display:grid;grid-template-columns:1fr 1fr;gap:32px;">
                    <div>
                        <p style="font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#64748b;margin:0 0 8px;">Bill To</p>
                        <p style="font-size:14px;font-weight:700;color:#1e293b;margin:0 0 4px;">{{ $invoice->bill_to_company }}</p>
                        <p style="font-size:13px;color:#475569;margin:0 0 4px;white-space:pre-line;">{{ $invoice->bill_to_address }}</p>
                        @if($invoice->bill_to_phone)
                            <p style="font-size:13px;color:#64748b;margin:0 0 2px;">{{ $invoice->bill_to_phone }}</p>
                        @endif
                        @if($invoice->bill_to_email)
                            <p style="font-size:13px;color:#64748b;margin:0;">{{ $invoice->bill_to_email }}</p>
                        @endif
                    </div>
                    <div>
                        <p style="font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#64748b;margin:0 0 8px;">Submission</p>
                        <p style="font-family:monospace;font-size:13px;font-weight:600;color:#1e293b;margin:0 0 4px;">
                            {{ $invoice->submission->reference_number }}
                        </p>
                        <p style="font-size:13px;color:#475569;margin:0 0 4px;">{{ $invoice->submission->sample_name }}</p>
                        <p style="font-size:11px;color:#94a3b8;margin:0;">
                            Issued by: {{ $invoice->issuedBy?->name ?? 'System' }}
                        </p>
                    </div>
                </div>

                {{-- Line Items --}}
                <div style="overflow-x:auto;">
                    <table style="width:100%;border-collapse:collapse;">
                        <thead>
                            <tr style="background:#1a2f4e;">
                                <th style="padding:10px 32px 10px 32px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">Test Description</th>
                                <th style="padding:10px 16px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">Category</th>
                                <th style="padding:10px 16px;text-align:right;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">Qty</th>
                                <th style="padding:10px 16px;text-align:right;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">Unit Price</th>
                                <th style="padding:10px 32px 10px 16px;text-align:right;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $itemsBySample = $invoice->items->groupBy(fn($i) => $i->sampleTest?->sample_id ?? 'other');
                                $multiSample   = $itemsBySample->count() > 1;
                            @endphp
                            @foreach($itemsBySample as $sampleId => $items)
                                @php $sample = $items->first()->sampleTest?->sample; @endphp
                                {{-- Sample header --}}
                                <tr style="background:#f8fafc;border-top:1px solid #e2e8f0;">
                                    <td colspan="5" style="padding:10px 32px;">
                                        <span style="font-size:13px;font-weight:700;color:#1a2f4e;">
                                            {{ $sample?->common_name ?? 'Other Charges' }}
                                        </span>
                                        @if($sample?->scientific_name)
                                            <span style="font-size:12px;font-style:italic;color:#64748b;margin-left:8px;">{{ $sample->scientific_name }}</span>
                                        @endif
                                        @if($sample?->sample_code)
                                            <span style="font-family:monospace;font-size:11px;color:#94a3b8;background:#e2e8f0;padding:1px 6px;border-radius:2px;margin-left:8px;">{{ $sample->sample_code }}</span>
                                        @endif
                                    </td>
                                </tr>
                                @foreach($items as $item)
                                    <tr style="border-bottom:1px solid #f1f5f9;">
                                        <td style="padding:10px 32px 10px 48px;font-size:13px;color:#374151;">{{ $item->item_description }}</td>
                                        <td style="padding:10px 16px;font-size:12px;color:#64748b;">{{ $item->category ?? '—' }}</td>
                                        <td style="padding:10px 16px;text-align:right;font-size:13px;color:#374151;">{{ $item->quantity }}</td>
                                        <td style="padding:10px 16px;text-align:right;font-size:13px;color:#374151;">A$ {{ number_format($item->unit_price_aud, 2) }}</td>
                                        <td style="padding:10px 32px 10px 16px;text-align:right;font-size:13px;font-weight:600;color:#1e293b;">A$ {{ number_format($item->total_price_aud, 2) }}</td>
                                    </tr>
                                @endforeach
                                @if($multiSample)
                                    <tr style="background:#eff6ff;">
                                        <td colspan="4" style="padding:6px 32px;font-size:12px;color:#64748b;text-align:right;font-style:italic;">
                                            Subtotal — {{ $sample?->common_name ?? 'Other' }}
                                        </td>
                                        <td style="padding:6px 32px 6px 16px;text-align:right;font-size:13px;font-weight:700;color:#374151;">
                                            A$ {{ number_format($items->sum('total_price_aud'), 2) }}
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                        <tfoot style="border-top:2px solid #e2e8f0;">
                            <tr style="background:#f8fafc;">
                                <td colspan="4" style="padding:16px 32px;font-size:13px;font-weight:700;color:#374151;text-align:right;text-transform:uppercase;letter-spacing:.06em;">
                                    TOTAL (AUD)
                                </td>
                                <td style="padding:16px 32px 16px 16px;text-align:right;font-size:22px;font-weight:700;color:#1a2f4e;">
                                    A$ {{ number_format($invoice->total_amount_aud, 2) }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                {{-- Payment Instructions --}}
                <div style="padding:20px 32px;border-top:1px solid #e2e8f0;background:#f8fafc;">
                    <p style="font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#475569;margin:0 0 8px;">Payment Instructions</p>
                    <p style="font-size:13px;color:#475569;margin:0;">
                        Payment is due within <strong>10 working days</strong> by bank transfer.
                        Please include the invoice number <strong>{{ $invoice->invoice_number }}</strong> as your payment reference.
                    </p>
                    @if($invoice->notes)
                        <p style="font-size:13px;color:#64748b;margin:8px 0 0;font-style:italic;">{{ $invoice->notes }}</p>
                    @endif
                </div>

                {{-- Payment Status --}}
                @if($invoice->isPaid())
                    <div style="padding:16px 32px;border-top:1px solid #e2e8f0;background:#f0fdf4;display:flex;align-items:center;justify-content:space-between;">
                        <div>
                            <p style="font-size:13px;font-weight:700;color:#166534;margin:0 0 4px;">&#10003; Payment Confirmed</p>
                            <p style="font-size:12px;color:#16a34a;margin:0;">
                                Ref: {{ $invoice->payment_reference }}
                                &middot; {{ $invoice->payment_received_at?->format('d M Y \a\t H:i') }}
                                &middot; Confirmed by {{ $invoice->paymentVerifiedBy?->name }}
                            </p>
                        </div>
                    </div>
                @elseif($invoice->hasSubmittedPayment())
                    <div style="padding:16px 32px;border-top:1px solid #e2e8f0;background:#eff6ff;display:flex;align-items:center;justify-content:space-between;">
                        <div>
                            <p style="font-size:13px;font-weight:700;color:#1e40af;margin:0 0 4px;">&#8987; Client Submitted Payment Details</p>
                            <p style="font-size:12px;color:#2563eb;margin:0;">
                                TT Reference: <span style="font-family:monospace;font-weight:700;">{{ $invoice->payment_submitted_reference }}</span>
                                &middot; Submitted by {{ $invoice->paymentSubmittedBy?->name }}
                                on {{ $invoice->payment_submitted_at?->format('d M Y \a\t H:i') }}
                            </p>
                        </div>
                    </div>
                @endif

            </div>

            {{-- ── Payment section ──────────────────────────────────── --}}
            @if($invoice->isUnpaid() || $invoice->isOverdue())
                <div style="background:#fff;border:{{ $invoice->hasSubmittedPayment() ? '2px solid #0d9488' : '1px solid #e2e8f0' }};border-radius:4px;overflow:hidden;margin-bottom:24px;">

                    {{-- Status banner --}}
                    @if($invoice->hasSubmittedPayment())
                        <div style="padding:16px 24px;background:#0d9488;display:flex;align-items:flex-start;gap:12px;">
                            <div>
                                <p style="font-size:13px;font-weight:700;color:#fff;margin:0 0 4px;">Client Payment Reference Received</p>
                                <p style="font-size:12px;color:#ccfbf1;margin:0;">
                                    {{ $invoice->bill_to_company }} submitted this reference on
                                    {{ $invoice->payment_submitted_at?->format('d M Y \a\t H:i') }}.
                                    Verify it against your bank records before confirming.
                                </p>
                            </div>
                        </div>

                        {{-- Submitted reference display --}}
                        <div style="padding:16px 24px;border-bottom:1px solid #99f6e4;display:flex;align-items:center;gap:16px;">
                            <div style="flex:1;">
                                <p style="font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#64748b;margin:0 0 6px;">Client's TT Reference</p>
                                <p style="font-family:monospace;font-size:20px;font-weight:700;color:#1e293b;letter-spacing:.04em;margin:0;">
                                    {{ $invoice->payment_submitted_reference }}
                                </p>
                                @if($invoice->paymentSubmittedBy)
                                    <p style="font-size:11px;color:#94a3b8;margin:4px 0 0;">Submitted by {{ $invoice->paymentSubmittedBy->name }}</p>
                                @endif
                            </div>
                        </div>

                        {{-- Verify & confirm form --}}
                        <form method="POST"
                              action="{{ route('director.invoices.paid', $invoice->id) }}"
                              style="padding:20px 24px;"
                              x-data="{ ref: '{{ $invoice->payment_submitted_reference }}' }">
                            @csrf
                            <p style="font-size:12px;color:#64748b;margin:0 0 12px;">The reference is pre-filled. You may edit it if needed before confirming.</p>
                            <div style="display:flex;gap:12px;align-items:flex-end;">
                                <div style="flex:1;">
                                    <label style="display:block;font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#475569;margin-bottom:6px;">TT Reference to Record</label>
                                    <input type="text"
                                           name="payment_reference"
                                           x-model="ref"
                                           style="width:100%;padding:8px 12px;border:1px solid #0d9488;border-radius:3px;font-size:13px;color:#1e293b;background:#fff;font-family:monospace;"
                                           required/>
                                    @error('payment_reference')
                                        <p style="font-size:12px;color:#dc2626;margin:4px 0 0;">{{ $message }}</p>
                                    @enderror
                                </div>
                                <button type="submit"
                                        :disabled="ref.trim().length < 3"
                                        @click.prevent="if(ref.trim().length >= 3 && confirm('Confirm payment for {{ $invoice->invoice_number }} with reference ' + ref.trim() + '?')) $el.closest('form').submit()"
                                        :style="ref.trim().length >= 3
                                            ? 'background:#0d9488;color:#fff;cursor:pointer;'
                                            : 'background:#e2e8f0;color:#94a3b8;cursor:not-allowed;'"
                                        style="display:inline-flex;align-items:center;gap:8px;padding:9px 20px;font-size:12px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;border-radius:3px;border:none;">
                                    Confirm &amp; Mark as Paid
                                </button>
                            </div>
                        </form>

                    @else
                        {{-- No client reference yet — awaiting + manual fallback --}}
                        <div style="padding:16px 24px;border-bottom:1px solid #e2e8f0;display:flex;align-items:center;gap:12px;">
                            <div>
                                <p style="font-size:13px;font-weight:600;color:#1e293b;margin:0 0 4px;">Awaiting Client Payment Reference</p>
                                <p style="font-size:12px;color:#94a3b8;margin:0;">
                                    The client has not yet submitted their TT reference on the portal.
                                    You may record payment manually below if payment was received outside the portal.
                                </p>
                            </div>
                        </div>
                        <form method="POST"
                              action="{{ route('director.invoices.paid', $invoice->id) }}"
                              style="padding:20px 24px;"
                              x-data="{ ref: '' }">
                            @csrf
                            <div style="display:flex;gap:12px;">
                                <div style="flex:1;">
                                    <label style="display:block;font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#475569;margin-bottom:6px;">Payment Reference (manual entry)</label>
                                    <input type="text"
                                           name="payment_reference"
                                           x-model="ref"
                                           placeholder="e.g. TT-20260608-001"
                                           style="width:100%;padding:8px 12px;border:1px solid #cbd5e1;border-radius:3px;font-size:13px;color:#1e293b;background:#fff;font-family:monospace;"
                                           required/>
                                    @error('payment_reference')
                                        <p style="font-size:12px;color:#dc2626;margin:4px 0 0;">{{ $message }}</p>
                                    @enderror
                                </div>
                                <button type="submit"
                                        :disabled="ref.trim().length < 3"
                                        @click.prevent="if(ref.trim().length >= 3 && confirm('Mark {{ $invoice->invoice_number }} as paid with reference ' + ref.trim() + '?')) $el.closest('form').submit()"
                                        :style="ref.trim().length >= 3
                                            ? 'background:#16a34a;color:#fff;cursor:pointer;'
                                            : 'background:#e2e8f0;color:#94a3b8;cursor:not-allowed;'"
                                        style="display:inline-flex;align-items:center;gap:8px;padding:9px 20px;font-size:12px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;border-radius:3px;border:none;align-self:flex-end;">
                                    Mark as Paid
                                </button>
                            </div>
                        </form>
                    @endif

                </div>
            @endif

        </div>
    </div>
</x-app-layout>
