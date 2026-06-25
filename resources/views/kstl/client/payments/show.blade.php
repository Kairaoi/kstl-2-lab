{{-- resources/views/kstl/client/payments/show.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <div style="position:relative;overflow:hidden;background:linear-gradient(135deg,#0f2240 0%,#1a2f4e 60%,#1e3a5f 100%);">
            <div style="height:3px;background:linear-gradient(90deg,#1a2f4e,#b8922a 30%,#b8922a 70%,#1a2f4e);"></div>
            <div style="max-width:80rem;margin:0 auto;padding:28px 2rem 32px;">
                <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;">
                    <div style="display:flex;align-items:center;gap:20px;">
                        <img src="{{ asset('images/mfor-logo.png') }}" alt="MFOR" style="filter:brightness(0) invert(1);opacity:.92;width:56px;height:56px;flex-shrink:0;">
                        <div>
                            <p style="font-size:9px;font-weight:700;letter-spacing:.18em;text-transform:uppercase;color:#b8922a;margin:0 0 4px;">Client Portal</p>
                            <h1 style="font-family:'Georgia',serif;font-size:22px;font-weight:700;color:#fff;margin:0 0 6px;line-height:1.2;">Payment Details</h1>
                            <p style="font-size:12px;color:#94a3b8;margin:0;">Payment record for your invoice</p>
                        </div>
                    </div>
                    <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                        <a href="{{ route('client.invoices.index') }}" style="display:inline-flex;align-items:center;gap:8px;padding:8px 20px;background:#fff;color:#1a2f4e;font-size:12px;font-weight:700;letter-spacing:.06em;border:1px solid #1a2f4e;border-radius:3px;text-decoration:none;">
                            &#8592; Back to Invoices
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
        <div style="max-width:56rem;margin:0 auto;padding:0 2rem;">

            @if(session('success'))
                <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-left:4px solid #16a34a;border-radius:4px;padding:12px 16px;margin-bottom:20px;font-size:13px;color:#166534;">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div style="background:#fef2f2;border:1px solid #fecaca;border-left:4px solid #dc2626;border-radius:4px;padding:12px 16px;margin-bottom:20px;font-size:13px;color:#991b1b;">{{ session('error') }}</div>
            @endif

            @if(isset($invoice))
                {{-- Payment Status --}}
                @php
                    $payStatusStyles = match($invoice->payment_status ?? '') {
                        'unpaid'  => 'background:#fffbeb;color:#b8922a;',
                        'paid'    => 'background:#f0fdf4;color:#16a34a;',
                        'overdue' => 'background:#fef2f2;color:#dc2626;',
                        'waived'  => 'background:#f1f5f9;color:#64748b;',
                        default   => 'background:#f1f5f9;color:#64748b;',
                    };
                @endphp

                <div style="background:#fff;border:1px solid #e2e8f0;border-radius:4px;padding:20px 24px;display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
                    <div>
                        <p style="font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#64748b;margin:0 0 8px;">Payment Status</p>
                        <span style="display:inline-flex;align-items:center;padding:3px 12px;border-radius:20px;font-size:11px;font-weight:700;text-transform:uppercase;{{ $payStatusStyles }}">
                            {{ $invoice->payment_status ?? 'Unknown' }}
                        </span>
                    </div>
                    <div style="text-align:right;">
                        <p style="font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#64748b;margin:0 0 4px;">Total Amount</p>
                        <p style="font-size:22px;font-weight:700;color:#1a2f4e;margin:0;">A$ {{ number_format($invoice->total_amount_aud, 2) }}</p>
                    </div>
                </div>

                {{-- Invoice Summary --}}
                <div style="background:#fff;border:1px solid #e2e8f0;border-radius:4px;overflow:hidden;margin-bottom:16px;">
                    <div style="padding:16px 24px;border-bottom:1px solid #e2e8f0;">
                        <h3 style="font-family:'Georgia',serif;font-size:14px;font-weight:700;color:#1a2f4e;margin:0;">Invoice Summary</h3>
                    </div>
                    <div style="padding:20px 24px;">
                        <dl style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                            <div>
                                <dt style="font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#94a3b8;margin-bottom:4px;">Invoice Number</dt>
                                <dd style="margin:0;font-size:13px;color:#374151;font-family:monospace;font-weight:600;">{{ $invoice->invoice_number }}</dd>
                            </div>
                            <div>
                                <dt style="font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#94a3b8;margin-bottom:4px;">Invoice Date</dt>
                                <dd style="margin:0;font-size:13px;color:#374151;">{{ $invoice->invoice_date->format('d M Y') }}</dd>
                            </div>
                            <div>
                                <dt style="font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#94a3b8;margin-bottom:4px;">Payment Due</dt>
                                <dd style="margin:0;font-size:13px;color:{{ $invoice->isPaymentDue() ? '#dc2626' : '#374151' }};font-weight:{{ $invoice->isPaymentDue() ? '700' : '400' }};">{{ $invoice->payment_due_date->format('d M Y') }}</dd>
                            </div>
                            <div>
                                <dt style="font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#94a3b8;margin-bottom:4px;">Submission</dt>
                                <dd style="margin:0;font-size:13px;color:#374151;font-family:monospace;">{{ $invoice->submission->reference_number ?? '—' }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                {{-- Submit Payment Reference (if unpaid) --}}
                @if(isset($invoice->payment_status) && $invoice->payment_status === 'unpaid')
                    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:4px;overflow:hidden;margin-bottom:16px;">
                        <div style="padding:16px 24px;border-bottom:1px solid #e2e8f0;">
                            <h3 style="font-family:'Georgia',serif;font-size:14px;font-weight:700;color:#1a2f4e;margin:0;">Submit Payment Reference</h3>
                            <p style="font-size:12px;color:#94a3b8;margin:4px 0 0;">Enter your bank TT reference number after making payment.</p>
                        </div>
                        <div style="padding:20px 24px;">
                            <form method="POST" action="{{ route('client.invoices.payment-reference', $invoice->id) }}">
                                @csrf
                                @if($errors->any())
                                    <div style="background:#fef2f2;border:1px solid #fecaca;border-left:4px solid #dc2626;border-radius:4px;padding:12px 16px;margin-bottom:16px;">
                                        <ul style="margin:0;padding-left:16px;font-size:13px;color:#991b1b;">
                                            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                                        </ul>
                                    </div>
                                @endif
                                <div style="margin-bottom:16px;">
                                    <label for="tt_reference" style="display:block;font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#475569;margin-bottom:6px;">TT Reference Number *</label>
                                    <input type="text" id="tt_reference" name="tt_reference"
                                           value="{{ old('tt_reference') }}"
                                           placeholder="e.g. TT-2024-001234"
                                           style="width:100%;padding:8px 12px;border:1px solid #cbd5e1;border-radius:3px;font-size:13px;color:#1e293b;background:#fff;box-sizing:border-box;"
                                           required>
                                </div>
                                <div style="display:flex;justify-content:flex-end;">
                                    <button type="submit"
                                            style="display:inline-flex;align-items:center;gap:8px;padding:8px 20px;background:#0d9488;color:#fff;font-size:12px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;border-radius:3px;border:none;cursor:pointer;">
                                        Submit Payment Reference
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif

            @else
                <div style="background:#fff;border:1px solid #e2e8f0;border-radius:4px;padding:64px 24px;text-align:center;">
                    <p style="font-size:14px;color:#94a3b8;margin:0;">Payment record not found.</p>
                </div>
            @endif

            <div style="padding-bottom:32px;"></div>

        </div>
    </div>
</x-app-layout>
