{{-- resources/views/kstl/client/payments/index.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <div style="position:relative;overflow:hidden;background:linear-gradient(135deg,#0f2240 0%,#1a2f4e 60%,#1e3a5f 100%);margin:-1px;">
            <div style="height:3px;background:linear-gradient(90deg,#1a2f4e,#b8922a 30%,#b8922a 70%,#1a2f4e);"></div>
            <div style="max-width:80rem;margin:0 auto;padding:28px 2rem 32px;">
                <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;">
                    <div style="display:flex;align-items:center;gap:20px;">
                        <img src="{{ asset('images/mfor-logo.png') }}" alt="MFOR" style="filter:brightness(0) invert(1);opacity:.92;width:56px;height:56px;flex-shrink:0;">
                        <div>
                            <p style="font-size:9px;font-weight:700;letter-spacing:.18em;text-transform:uppercase;color:#b8922a;margin:0 0 4px;">Client</p>
                            <h1 style="font-family:'Georgia',serif;font-size:22px;font-weight:700;color:#fff;margin:0 0 6px;line-height:1.2;">Payments</h1>
                            <p style="font-size:12px;color:#94a3b8;margin:0;">View and manage payments for your invoices</p>
                        </div>
                    </div>
                    <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                        <a href="{{ route('client.invoices.index') }}" style="display:inline-flex;align-items:center;gap:8px;padding:8px 20px;background:#fff;color:#1a2f4e;font-size:12px;font-weight:700;letter-spacing:.06em;border:1px solid #1a2f4e;border-radius:3px;text-decoration:none;">
                            View Invoices
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
        .app-main { padding-left:0 !important; padding-right:0 !important; padding-top:0 !important; max-width:100% !important; }
    </style>
    @endpush

    <div style="background:#f1f5f9;min-height:100vh;padding:0 0 56px;">
        <div style="max-width:80rem;margin:0 auto;padding:0 2rem;">

            @if(session('success'))
                <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-left:4px solid #16a34a;border-radius:4px;padding:12px 16px;margin-bottom:20px;font-size:13px;color:#166534;">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div style="background:#fef2f2;border:1px solid #fecaca;border-left:4px solid #dc2626;border-radius:4px;padding:12px 16px;margin-bottom:20px;font-size:13px;color:#991b1b;">{{ session('error') }}</div>
            @endif
            @if(session('info'))
                <div style="background:#eff6ff;border:1px solid #bfdbfe;border-left:4px solid #1a2f4e;border-radius:4px;padding:12px 16px;margin-bottom:20px;font-size:13px;color:#1e40af;">{{ session('info') }}</div>
            @endif

            @if(isset($invoices) && $invoices->isEmpty())
                <div style="background:#fff;border:1px solid #e2e8f0;border-radius:4px;padding:64px 24px;text-align:center;">
                    <svg style="width:40px;height:40px;color:#cbd5e1;margin:0 auto 12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <p style="font-size:14px;font-weight:600;color:#94a3b8;margin:0 0 4px;">No payments yet</p>
                    <p style="font-size:12px;color:#cbd5e1;margin:0;">Payments will appear here after invoices are issued.</p>
                </div>
            @elseif(isset($invoices))
                <div style="background:#fff;border:1px solid #e2e8f0;border-radius:4px;overflow:hidden;margin-bottom:24px;">
                    <div style="padding:16px 24px;border-bottom:1px solid #e2e8f0;">
                        <h3 style="font-family:'Georgia',serif;font-size:14px;font-weight:700;color:#1a2f4e;margin:0;">Payment History</h3>
                    </div>
                    <div style="overflow-x:auto;">
                        <table style="width:100%;border-collapse:collapse;">
                            <thead>
                                <tr style="background:#1a2f4e;">
                                    <th style="padding:10px 16px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">Invoice No.</th>
                                    <th style="padding:10px 16px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">Submission</th>
                                    <th style="padding:10px 16px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">Date</th>
                                    <th style="padding:10px 16px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">Due</th>
                                    <th style="padding:10px 16px;text-align:right;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">Amount</th>
                                    <th style="padding:10px 16px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">Status</th>
                                    <th style="padding:10px 16px;text-align:right;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($invoices as $invoice)
                                    @php
                                        $payStatusStyles = match($invoice->payment_status ?? '') {
                                            'unpaid'  => 'background:#fffbeb;color:#b8922a;',
                                            'paid'    => 'background:#f0fdf4;color:#16a34a;',
                                            'overdue' => 'background:#fef2f2;color:#dc2626;',
                                            'waived'  => 'background:#f1f5f9;color:#64748b;',
                                            default   => 'background:#f1f5f9;color:#64748b;',
                                        };
                                    @endphp
                                    <tr style="border-bottom:1px solid #f1f5f9;{{ $loop->even ? 'background:#f8fafc;' : '' }}">
                                        <td style="padding:12px 16px;font-size:12px;color:#374151;font-family:monospace;font-weight:600;">
                                            {{ $invoice->invoice_number }}
                                        </td>
                                        <td style="padding:12px 16px;font-size:12px;color:#64748b;font-family:monospace;">
                                            {{ $invoice->submission->reference_number }}
                                        </td>
                                        <td style="padding:12px 16px;font-size:12px;color:#64748b;">
                                            {{ $invoice->invoice_date->format('d M Y') }}
                                        </td>
                                        <td style="padding:12px 16px;font-size:12px;{{ $invoice->isPaymentDue() ? 'color:#dc2626;font-weight:600;' : 'color:#64748b;' }}">
                                            {{ $invoice->payment_due_date->format('d M Y') }}
                                            @if($invoice->isPaymentDue())
                                                <span style="margin-left:4px;">&#9888; Overdue</span>
                                            @endif
                                        </td>
                                        <td style="padding:12px 16px;text-align:right;font-size:13px;font-weight:700;color:#1a2f4e;">
                                            A$ {{ number_format($invoice->total_amount_aud, 2) }}
                                        </td>
                                        <td style="padding:12px 16px;">
                                            <span style="display:inline-flex;align-items:center;padding:3px 10px;border-radius:20px;font-size:10px;font-weight:700;text-transform:uppercase;{{ $payStatusStyles }}">
                                                {{ $invoice->payment_status }}
                                            </span>
                                        </td>
                                        <td style="padding:12px 16px;text-align:right;">
                                            <a href="{{ route('client.invoices.show', $invoice->id) }}"
                                               style="display:inline-flex;align-items:center;gap:8px;padding:6px 14px;background:#fff;color:#1a2f4e;font-size:11px;font-weight:700;letter-spacing:.06em;border:1px solid #1a2f4e;border-radius:3px;text-decoration:none;">
                                                View Invoice
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                <div style="background:#fff;border:1px solid #e2e8f0;border-radius:4px;padding:64px 24px;text-align:center;">
                    <p style="font-size:14px;color:#94a3b8;margin:0;">No payment records found.</p>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
