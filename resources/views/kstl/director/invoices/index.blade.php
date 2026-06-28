{{-- resources/views/kstl/director/invoices/index.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <div style="position:relative;overflow:hidden;background:linear-gradient(135deg,#0f2240 0%,#1a2f4e 60%,#1e3a5f 100%);margin:-1px;">
            <div style="height:3px;background:linear-gradient(90deg,#1a2f4e,#b8922a 30%,#b8922a 70%,#1a2f4e);"></div>
            <div style="max-width:80rem;margin:0 auto;padding:28px 2rem 32px;">
                <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;">
                    <div style="display:flex;align-items:center;gap:20px;">
                        <img src="{{ asset('images/mfor-logo.png') }}" alt="MFOR" style="filter:brightness(0) invert(1);opacity:.92;width:56px;height:56px;flex-shrink:0;">
                        <div>
                            <p style="font-size:9px;font-weight:700;letter-spacing:.18em;text-transform:uppercase;color:#b8922a;margin:0 0 4px;">Director</p>
                            <h1 style="font-family:'Georgia',serif;font-size:22px;font-weight:700;color:#fff;margin:0 0 6px;line-height:1.2;">Invoices</h1>
                            <p style="font-size:12px;color:#94a3b8;margin:0;">Manage and track all laboratory invoices</p>
                        </div>
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

            {{-- Summary Cards --}}
            <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:24px;">
                <div style="background:#fff;border:1px solid #e2e8f0;border-radius:4px;border-left:4px solid #b8922a;padding:18px 20px;">
                    <p style="font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#64748b;margin:0 0 6px;">UNPAID</p>
                    <p style="font-size:28px;font-weight:700;color:#1a2f4e;margin:0 0 2px;line-height:1;">{{ $unpaidCount }}</p>
                    <p style="font-size:11px;color:#94a3b8;margin:0;">Awaiting payment</p>
                </div>
                <div style="background:#fff;border:1px solid #e2e8f0;border-radius:4px;border-left:4px solid #dc2626;padding:18px 20px;">
                    <p style="font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#64748b;margin:0 0 6px;">OVERDUE</p>
                    <p style="font-size:28px;font-weight:700;color:#dc2626;margin:0 0 2px;line-height:1;">{{ $overdueCount }}</p>
                    <p style="font-size:11px;color:#94a3b8;margin:0;">Past due date</p>
                </div>
                <div style="background:#fff;border:1px solid #e2e8f0;border-radius:4px;border-left:4px solid #1a2f4e;padding:18px 20px;">
                    <p style="font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#64748b;margin:0 0 6px;">TOTAL OUTSTANDING</p>
                    <p style="font-size:28px;font-weight:700;color:#1a2f4e;margin:0 0 2px;line-height:1;">A$ {{ number_format($totalUnpaid, 2) }}</p>
                    <p style="font-size:11px;color:#94a3b8;margin:0;">Unpaid + overdue</p>
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
                <div style="background:#fff;border:1px solid #fde68a;border-radius:4px;overflow:hidden;margin-bottom:24px;">
                    <div style="padding:16px 24px;border-bottom:1px solid #fde68a;background:#fffbeb;">
                        <h3 style="font-family:'Georgia',serif;font-size:14px;font-weight:700;color:#92400e;margin:0 0 4px;">
                            Awaiting Invoice Generation ({{ $needInvoice->count() }})
                        </h3>
                        <p style="font-size:12px;color:#b45309;margin:0;">These submissions have been authorised but no invoice has been generated yet.</p>
                    </div>
                    <div style="overflow-x:auto;">
                        <table style="width:100%;border-collapse:collapse;">
                            <thead>
                                <tr style="background:#1a2f4e;">
                                    <th style="padding:10px 16px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">Reference</th>
                                    <th style="padding:10px 16px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">Client</th>
                                    <th style="padding:10px 16px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">Status</th>
                                    <th style="padding:10px 16px;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($needInvoice as $sub)
                                    <tr style="border-bottom:1px solid #f1f5f9;{{ $loop->even ? 'background:#f8fafc;' : '' }}">
                                        <td style="padding:12px 16px;font-size:13px;color:#374151;font-family:monospace;font-weight:600;">
                                            {{ $sub->reference_number }}
                                        </td>
                                        <td style="padding:12px 16px;font-size:13px;color:#374151;">
                                            {{ $sub->client->company_name ?? '—' }}
                                        </td>
                                        <td style="padding:12px 16px;">
                                            <span style="display:inline-flex;padding:2px 8px;font-size:11px;font-weight:600;border-radius:9999px;background:#ccfbf1;color:#0f766e;text-transform:capitalize;">
                                                {{ $sub->status }}
                                            </span>
                                        </td>
                                        <td style="padding:12px 16px;text-align:right;">
                                            <form method="POST"
                                                  action="{{ route('director.invoices.generate', $sub->id) }}"
                                                  onsubmit="return confirm('Generate invoice for {{ $sub->reference_number }}?')">
                                                @csrf
                                                <button type="submit"
                                                        style="display:inline-flex;align-items:center;gap:8px;padding:7px 16px;background:#b8922a;color:#fff;font-size:12px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;border-radius:3px;border:none;cursor:pointer;">
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
            <div style="background:#fff;border:1px solid #e2e8f0;border-radius:4px;overflow:hidden;margin-bottom:24px;">
                <div style="padding:16px 24px;border-bottom:1px solid #e2e8f0;">
                    <h3 style="font-family:'Georgia',serif;font-size:14px;font-weight:700;color:#1a2f4e;margin:0;">All Invoices</h3>
                </div>

                @if($invoices->isEmpty())
                    <div style="padding:64px 24px;text-align:center;">
                        <p style="font-size:13px;color:#94a3b8;">No invoices generated yet.</p>
                    </div>
                @else
                    <div style="overflow-x:auto;">
                        <table style="width:100%;border-collapse:collapse;">
                            <thead>
                                <tr style="background:#1a2f4e;">
                                    <th style="padding:10px 16px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">Invoice No.</th>
                                    <th style="padding:10px 16px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">Client</th>
                                    <th style="padding:10px 16px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">Submission</th>
                                    <th style="padding:10px 16px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">Date</th>
                                    <th style="padding:10px 16px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">Due</th>
                                    <th style="padding:10px 16px;text-align:right;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">Amount</th>
                                    <th style="padding:10px 16px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">Status</th>
                                    <th style="padding:10px 16px;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($invoices as $invoice)
                                    @php
                                        $statusBadge = match($invoice->payment_status) {
                                            'unpaid'  => 'background:#fef9c3;color:#854d0e;',
                                            'paid'    => 'background:#dcfce7;color:#166534;',
                                            'overdue' => 'background:#fee2e2;color:#991b1b;',
                                            'waived'  => 'background:#f1f5f9;color:#64748b;',
                                            default   => 'background:#f1f5f9;color:#64748b;',
                                        };
                                    @endphp
                                    <tr style="border-bottom:1px solid #f1f5f9;{{ $loop->even ? 'background:#f8fafc;' : '' }}">
                                        <td style="padding:12px 16px;">
                                            <span style="font-family:monospace;font-size:12px;font-weight:600;color:#374151;">
                                                {{ $invoice->invoice_number }}
                                            </span>
                                        </td>
                                        <td style="padding:12px 16px;">
                                            <p style="font-size:13px;font-weight:600;color:#1e293b;margin:0;">{{ $invoice->bill_to_company }}</p>
                                        </td>
                                        <td style="padding:12px 16px;">
                                            <span style="font-family:monospace;font-size:12px;color:#64748b;">
                                                {{ $invoice->submission->reference_number }}
                                            </span>
                                        </td>
                                        <td style="padding:12px 16px;font-size:12px;color:#64748b;">
                                            {{ $invoice->invoice_date->format('d M Y') }}
                                        </td>
                                        <td style="padding:12px 16px;font-size:12px;{{ $invoice->isPaymentDue() ? 'color:#dc2626;font-weight:600;' : 'color:#64748b;' }}">
                                            {{ $invoice->payment_due_date->format('d M Y') }}
                                        </td>
                                        <td style="padding:12px 16px;text-align:right;font-size:13px;font-weight:600;color:#1e293b;">
                                            A$ {{ number_format($invoice->total_amount_aud, 2) }}
                                        </td>
                                        <td style="padding:12px 16px;">
                                            <span style="display:inline-flex;padding:2px 8px;font-size:11px;font-weight:600;border-radius:9999px;text-transform:capitalize;{{ $statusBadge }}">
                                                {{ $invoice->payment_status }}
                                            </span>
                                        </td>
                                        <td style="padding:12px 16px;text-align:right;">
                                            <a href="{{ route('director.invoices.show', $invoice->id) }}"
                                               style="display:inline-flex;align-items:center;gap:8px;padding:6px 14px;background:#fff;color:#1a2f4e;font-size:11px;font-weight:700;letter-spacing:.06em;border:1px solid #1a2f4e;border-radius:3px;text-decoration:none;">
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
