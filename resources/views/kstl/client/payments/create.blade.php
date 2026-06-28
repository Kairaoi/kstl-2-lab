{{-- resources/views/kstl/client/payments/create.blade.php --}}
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
                            <h1 style="font-family:'Georgia',serif;font-size:22px;font-weight:700;color:#fff;margin:0 0 6px;line-height:1.2;">Submit Payment Proof</h1>
                            <p style="font-size:12px;color:#94a3b8;margin:0;">
                                Invoice #{{ $invoice->invoice_number }}
                                &nbsp;&bull;&nbsp;
                                AUD ${{ number_format($invoice->total_amount_aud, 2) }}
                            </p>
                        </div>
                    </div>
                    <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                        <a href="{{ route('client.invoices.show', $invoice->id) }}" style="display:inline-flex;align-items:center;gap:8px;padding:8px 20px;background:#fff;color:#1a2f4e;font-size:12px;font-weight:700;letter-spacing:.06em;border:1px solid rgba(255,255,255,.5);border-radius:3px;text-decoration:none;">
                            &larr; Back to Invoice
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
        <div style="max-width:48rem;margin:0 auto;padding:0 2rem;">

            @if(session('success'))
                <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-left:4px solid #16a34a;border-radius:4px;padding:12px 16px;margin-bottom:20px;font-size:13px;color:#166534;">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div style="background:#fef2f2;border:1px solid #fecaca;border-left:4px solid #dc2626;border-radius:4px;padding:12px 16px;margin-bottom:20px;font-size:13px;color:#991b1b;">{{ session('error') }}</div>
            @endif

            @if($errors->any())
                <div style="background:#fef2f2;border:1px solid #fecaca;border-left:4px solid #dc2626;border-radius:4px;padding:12px 16px;margin-bottom:20px;">
                    <ul style="margin:0;padding-left:16px;font-size:13px;color:#991b1b;">
                        @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
            @endif

            {{-- Invoice summary --}}
            <div style="background:#fff;border:1px solid #e2e8f0;border-radius:4px;overflow:hidden;margin-bottom:24px;">
                <div style="padding:16px 24px;border-bottom:1px solid #e2e8f0;">
                    <h3 style="font-family:'Georgia',serif;font-size:14px;font-weight:700;color:#1a2f4e;margin:0;">Invoice Summary</h3>
                </div>
                <dl style="padding:0 24px;margin:0;">
                    <div style="display:flex;justify-content:space-between;align-items:center;padding:10px 0;border-bottom:1px solid #f1f5f9;">
                        <dt style="font-size:12px;color:#64748b;">Invoice Number</dt>
                        <dd style="font-family:monospace;font-weight:700;color:#1a2f4e;font-size:13px;margin:0;">{{ $invoice->invoice_number }}</dd>
                    </div>
                    <div style="display:flex;justify-content:space-between;align-items:center;padding:10px 0;border-bottom:1px solid #f1f5f9;">
                        <dt style="font-size:12px;color:#64748b;">Billed To</dt>
                        <dd style="font-weight:600;color:#1e293b;font-size:13px;margin:0;">{{ $invoice->bill_to_company }}</dd>
                    </div>
                    <div style="display:flex;justify-content:space-between;align-items:center;padding:10px 0;border-bottom:1px solid #f1f5f9;">
                        <dt style="font-size:12px;color:#64748b;">Amount Due</dt>
                        <dd style="font-weight:700;color:#1a2f4e;font-size:14px;margin:0;">AUD ${{ number_format($invoice->total_amount_aud, 2) }}</dd>
                    </div>
                    <div style="display:flex;justify-content:space-between;align-items:center;padding:10px 0;">
                        <dt style="font-size:12px;color:#64748b;">Due Date</dt>
                        <dd style="font-size:13px;color:#374151;margin:0;">{{ $invoice->payment_due_date?->format('d M Y') }}</dd>
                    </div>
                </dl>
            </div>

            {{-- Upload form --}}
            <form method="POST"
                  action="{{ route('client.payments.proof.store', $invoice->id) }}"
                  enctype="multipart/form-data">
                @csrf

                <div style="background:#fff;border:1px solid #e2e8f0;border-radius:4px;overflow:hidden;margin-bottom:24px;">
                    <div style="padding:16px 24px;border-bottom:1px solid #e2e8f0;">
                        <h3 style="font-family:'Georgia',serif;font-size:14px;font-weight:700;color:#1a2f4e;margin:0;">Payment Details</h3>
                    </div>
                    <div style="padding:20px 24px;">
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">

                            <div>
                                <label style="display:block;font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#475569;margin-bottom:6px;">
                                    Bank / Transaction Reference <span style="color:#dc2626;">*</span>
                                </label>
                                <input type="text" name="bank_reference_number"
                                       value="{{ old('bank_reference_number') }}"
                                       maxlength="255" required
                                       placeholder="e.g. TXN123456"
                                       style="width:100%;padding:8px 12px;border:1px solid #cbd5e1;border-radius:3px;font-size:13px;color:#1e293b;background:#fff;box-sizing:border-box;">
                                <p style="font-size:11px;color:#94a3b8;margin:4px 0 0;">The reference number shown on your bank receipt or transfer confirmation.</p>
                            </div>

                            <div>
                                <label style="display:block;font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#475569;margin-bottom:6px;">
                                    Payment Method
                                </label>
                                <select name="payment_method"
                                        style="width:100%;padding:8px 12px;border:1px solid #cbd5e1;border-radius:3px;font-size:13px;color:#1e293b;background:#fff;box-sizing:border-box;">
                                    <option value="">— Select —</option>
                                    <option value="bank_transfer" {{ old('payment_method') === 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                    <option value="mobile_money" {{ old('payment_method') === 'mobile_money' ? 'selected' : '' }}>Mobile Money</option>
                                    <option value="cash" {{ old('payment_method') === 'cash' ? 'selected' : '' }}>Cash</option>
                                </select>
                            </div>

                            <div style="grid-column:1/-1;">
                                <label style="display:block;font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#475569;margin-bottom:6px;">
                                    Proof of Payment <span style="color:#dc2626;">*</span>
                                </label>
                                <div style="border:2px dashed #cbd5e1;border-radius:4px;padding:24px;text-align:center;background:#f8fafc;">
                                    <svg style="width:32px;height:32px;color:#94a3b8;margin:0 auto 12px;display:block;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <input type="file" name="proof_file" id="proof_file"
                                           accept=".pdf,.jpg,.jpeg,.png"
                                           required
                                           style="display:none;"
                                           onchange="document.getElementById('file-label').textContent = this.files[0]?.name ?? 'No file chosen'">
                                    <label for="proof_file" style="display:inline-flex;align-items:center;gap:8px;padding:7px 16px;background:#1a2f4e;color:#fff;font-size:12px;font-weight:700;letter-spacing:.06em;border-radius:3px;cursor:pointer;text-transform:uppercase;">
                                        Choose File
                                    </label>
                                    <p id="file-label" style="font-size:12px;color:#64748b;margin:8px 0 0;">No file chosen</p>
                                    <p style="font-size:11px;color:#94a3b8;margin:4px 0 0;">PDF, JPG or PNG &mdash; max 10 MB</p>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div style="display:flex;align-items:center;justify-content:space-between;gap:12px;">
                    <a href="{{ route('client.invoices.show', $invoice->id) }}" style="display:inline-flex;align-items:center;gap:8px;padding:8px 20px;background:#fff;color:#1a2f4e;font-size:12px;font-weight:700;letter-spacing:.06em;border:1px solid #1a2f4e;border-radius:3px;text-decoration:none;">
                        Cancel
                    </a>
                    <button type="submit" style="display:inline-flex;align-items:center;gap:8px;padding:8px 20px;background:#b8922a;color:#fff;font-size:12px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;border-radius:3px;border:none;cursor:pointer;">
                        Submit Proof of Payment
                    </button>
                </div>

            </form>
        </div>
    </div>
</x-app-layout>