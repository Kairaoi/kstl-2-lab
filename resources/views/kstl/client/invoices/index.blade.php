{{-- resources/views/kstl/client/invoices/index.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">My Invoices</h2>
    </x-slot>

    <div style="background:#f1f5f9; min-height:100vh; padding:52px 0 56px;">
        <div style="max-width:80rem; margin:0 auto; padding:0 2rem;">

            {{-- Page Header --}}
            <div style="margin-bottom:24px;">
                <h1 style="font-family:'Georgia',serif; font-size:22px; font-weight:700; color:#1a2f4e; margin:0 0 4px;">
                    Invoices
                </h1>
                <p style="font-size:12.5px; color:#6b7280; margin:0;">
                    Tax invoices issued for your laboratory submissions
                </p>
            </div>

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
