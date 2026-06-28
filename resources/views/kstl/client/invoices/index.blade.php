{{-- resources/views/kstl/client/invoices/index.blade.php --}}

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
                                My Invoices
                            </h1>
                            <p style="font-size:11px;color:#94a3b8;margin-top:4px;">
                                Tax invoices issued for your laboratory submissions
                            </p>
                        </div>
                    </div>
                    <div style="text-align:right;">
                        <p style="font-size:9px;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:#64748b;margin-bottom:4px;">Today</p>
                        <p style="font-size:13px;font-weight:600;color:#e2e8f0;">{{ now()->format('d F Y') }}</p>
                        <a href="{{ route('client.dashboard') }}" style="display:inline-flex;align-items:center;gap:5px;margin-top:8px;font-size:11px;font-weight:600;color:#94a3b8;text-decoration:none;">
                            <svg style="width:12px;height:12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                            Back to Dashboard
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

            @if(session('info'))
                <div style="border-left:4px solid #3b82f6; padding:12px 18px; border-radius:0 4px 4px 0; background:#eff6ff; color:#1d4ed8; font-size:12.5px; margin-bottom:20px;">
                    {{ session('info') }}
                </div>
            @endif

            @if($invoices->isEmpty())
                <div style="background:#fff; border:1px solid #e2e8f0; border-radius:4px; padding:56px 24px; text-align:center;">
                    <svg style="width:40px; height:40px; color:#e2e8f0; margin:0 auto 12px; display:block;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <p style="font-size:13px; color:#9ca3af; margin:0 0 4px;">No invoices yet</p>
                    <p style="font-size:11.5px; color:#d1d5db; margin:0;">Invoices will appear here after your test results are authorised.</p>
                </div>
            @else
                <div style="background:#fff; border:1px solid #e2e8f0; border-radius:4px; overflow:hidden;">

                    <div style="padding:16px 24px 14px;">
                        <h3 style="font-family:'Georgia',serif; font-size:15px; font-weight:700; color:#1a2f4e; border-bottom:2px solid #b8922a; padding-bottom:8px; margin-bottom:0;">
                            All Invoices
                        </h3>
                    </div>

                    <div style="overflow-x:auto;">
                        <table style="width:100%; border-collapse:collapse;">
                            <thead>
                                <tr style="background:#1a2f4e;">
                                    <th style="text-align:left; padding:9px 16px; font-size:9px; font-weight:700; letter-spacing:.12em; text-transform:uppercase; color:#e2e8f0;">Invoice No.</th>
                                    <th style="text-align:left; padding:9px 16px; font-size:9px; font-weight:700; letter-spacing:.12em; text-transform:uppercase; color:#e2e8f0;">Submission</th>
                                    <th style="text-align:left; padding:9px 16px; font-size:9px; font-weight:700; letter-spacing:.12em; text-transform:uppercase; color:#e2e8f0;">Date</th>
                                    <th style="text-align:left; padding:9px 16px; font-size:9px; font-weight:700; letter-spacing:.12em; text-transform:uppercase; color:#e2e8f0;">Due</th>
                                    <th style="text-align:right; padding:9px 16px; font-size:9px; font-weight:700; letter-spacing:.12em; text-transform:uppercase; color:#e2e8f0;">Amount</th>
                                    <th style="text-align:left; padding:9px 16px; font-size:9px; font-weight:700; letter-spacing:.12em; text-transform:uppercase; color:#e2e8f0;">Status</th>
                                    <th style="padding:9px 16px;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($invoices as $i => $invoice)
                                    @php $rowBg = $i % 2 === 0 ? '#fff' : '#f8fafc'; @endphp
                                    <tr style="background:{{ $rowBg }}; border-bottom:1px solid #f1f5f9;">
                                        <td style="padding:11px 16px; font-size:12.5px;">
                                            <span style="font-family:monospace; font-size:11.5px; font-weight:600; color:#374151;">
                                                {{ $invoice->invoice_number }}
                                            </span>
                                        </td>
                                        <td style="padding:11px 16px; font-size:12.5px;">
                                            <span style="font-family:monospace; font-size:11.5px; color:#6b7280;">
                                                {{ $invoice->submission->reference_number }}
                                            </span>
                                        </td>
                                        <td style="padding:11px 16px; font-size:12.5px; color:#374151;">
                                            {{ $invoice->invoice_date->format('d M Y') }}
                                        </td>
                                        <td style="padding:11px 16px; font-size:12.5px; {{ $invoice->isPaymentDue() ? 'color:#dc2626; font-weight:600;' : 'color:#374151;' }}">
                                            {{ $invoice->payment_due_date->format('d M Y') }}
                                            @if($invoice->isPaymentDue())
                                                <span style="font-size:10px; margin-left:4px;">&#9888; Overdue</span>
                                            @endif
                                        </td>
                                        <td style="padding:11px 16px; font-size:12.5px; text-align:right; font-weight:700; color:#1a2f4e;">
                                            A$ {{ number_format($invoice->total_amount_aud, 2) }}
                                        </td>
                                        <td style="padding:11px 16px; font-size:12.5px;">
                                            @php
                                                $sc = [
                                                    'unpaid'  => 'background:#fef9c3; color:#ca8a04;',
                                                    'paid'    => 'background:#dcfce7; color:#15803d;',
                                                    'overdue' => 'background:#fee2e2; color:#dc2626;',
                                                    'waived'  => 'background:#f3f4f6; color:#6b7280;',
                                                ];
                                                $badgeStyle = $sc[$invoice->payment_status] ?? 'background:#f3f4f6; color:#6b7280;';
                                            @endphp
                                            <span style="border-radius:999px; padding:2px 12px; font-size:10px; font-weight:700; text-transform:capitalize; display:inline-block; {{ $badgeStyle }}">
                                                {{ $invoice->payment_status }}
                                            </span>
                                        </td>
                                        <td style="padding:11px 16px; text-align:right;">
                                            <a href="{{ route('client.invoices.show', $invoice->id) }}"
                                               style="background:#1a2f4e; color:#fff; padding:8px 18px; border-radius:3px; font-size:12px; font-weight:600; text-decoration:none; display:inline-flex; align-items:center; gap:6px;">
                                                <svg style="width:12px; height:12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
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
