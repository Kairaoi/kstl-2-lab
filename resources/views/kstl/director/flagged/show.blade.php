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
                                <th style="padding:10px 32px 10px 32px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">Description</th>
                                <th style="padding:10px 16px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">Category</th>
                                <th style="padding:10px 16px;text-align:right;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">Qty</th>
                                <th style="padding:10px 16px;text-align:right;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">Unit Price</th>
                                <th style="padding:10px 32px 10px 16px;text-align:right;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($invoice->items->groupBy('category') as $category => $items)
                                {{-- Category subheader --}}
                                @if($category)
                                    <tr style="background:#f8fafc;">
                                        <td colspan="5" style="padding:8px 32px;font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.08em;">
                                            {{ $category }}
                                        </td>
                                    </tr>
                                @endif
                                @foreach($items as $item)
                                    <tr style="border-bottom:1px solid #f1f5f9;{{ $loop->even ? 'background:#f8fafc;' : '' }}">
                                        <td style="padding:10px 32px;font-size:13px;color:#374151;">{{ $item->item_description }}</td>
                                        <td style="padding:10px 16px;font-size:12px;color:#64748b;">{{ $item->category ?? '—' }}</td>
                                        <td style="padding:10px 16px;text-align:right;font-size:13px;color:#374151;">{{ $item->quantity }}</td>
                                        <td style="padding:10px 16px;text-align:right;font-size:13px;color:#374151;">A$ {{ number_format($item->unit_price_aud, 2) }}</td>
                                        <td style="padding:10px 32px 10px 16px;text-align:right;font-size:13px;font-weight:600;color:#1e293b;">A$ {{ number_format($item->total_price_aud, 2) }}</td>
                                    </tr>
                                @endforeach
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
                            <p style="font-size:13px;font-weight:700;color:#166534;margin:0 0 4px;">&#10003; Payment Received</p>
                            <p style="font-size:12px;color:#16a34a;margin:0;">
                                Ref: {{ $invoice->payment_reference }}
                                &middot; {{ $invoice->payment_received_at?->format('d M Y \a\t H:i') }}
                                &middot; Verified by {{ $invoice->paymentVerifiedBy?->name }}
                            </p>
                        </div>
                    </div>
                @endif

            </div>

            {{-- ── Mark as Paid Form ────────────────────────────── --}}
            @if($invoice->isUnpaid() || $invoice->isOverdue())
                <div style="background:#fff;border:1px solid #e2e8f0;border-radius:4px;overflow:hidden;margin-bottom:24px;">
                    <div style="padding:16px 24px;border-bottom:1px solid #e2e8f0;">
                        <h3 style="font-family:'Georgia',serif;font-size:14px;font-weight:700;color:#1a2f4e;margin:0 0 4px;">Record Payment</h3>
                        <p style="font-size:12px;color:#94a3b8;margin:0;">Enter the client's bank transfer reference to mark as paid.</p>
                    </div>
                    <form method="POST"
                          action="{{ route('director.invoices.paid', $invoice->id) }}"
                          style="padding:20px 24px;"
                          x-data="{ ref: '' }">
                        @csrf
                        <div style="display:flex;gap:12px;">
                            <div style="flex:1;">
                                <label style="display:block;font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#475569;margin-bottom:6px;">Payment Reference</label>
                                <x-input type="text"
                                         name="payment_reference"
                                         x-model="ref"
                                         placeholder="e.g. TT-20260414-001"
                                         class="w-full"
                                         required/>
                                <x-input-error for="payment_reference" class="mt-1"/>
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
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
