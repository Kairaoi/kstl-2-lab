<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Issued</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: #f9fafb; margin: 0; padding: 0; }
        .wrapper { max-width: 600px; margin: 40px auto; background: #ffffff; border-radius: 12px; overflow: hidden; border: 1px solid #e5e7eb; }
        .header { background: #1e3a5f; padding: 32px 40px; }
        .header h1 { color: #ffffff; font-size: 20px; margin: 0; font-weight: 600; }
        .header p { color: #93c5fd; font-size: 13px; margin: 4px 0 0; }
        .body { padding: 32px 40px; }
        h2 { font-size: 16px; color: #111827; margin: 0 0 12px; }
        p { font-size: 14px; color: #374151; line-height: 1.6; margin: 0 0 16px; }
        .detail-row { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #f3f4f6; font-size: 14px; }
        .detail-row .label { color: #6b7280; }
        .detail-row .value { color: #111827; font-weight: 500; }
        .total-row { display: flex; justify-content: space-between; padding: 14px 0; font-size: 16px; font-weight: 700; border-top: 2px solid #e5e7eb; margin-top: 4px; }
        .payment-box { background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 8px; padding: 16px; margin: 20px 0; font-size: 14px; }
        .payment-box p { margin: 0; color: #1e40af; }
        .btn-wrapper { text-align: center; margin: 28px 0; }
        .btn { display: inline-block; padding: 14px 32px; border-radius: 8px; font-size: 15px; font-weight: 600; text-decoration: none; background: #1e3a5f; color: #ffffff; }
        .footer { background: #f9fafb; padding: 20px 40px; border-top: 1px solid #e5e7eb; text-align: center; }
        .footer p { font-size: 12px; color: #9ca3af; margin: 0; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <h1>Kiribati Seafood Toxicology Laboratory</h1>
            <p>Invoice Notification</p>
        </div>
        <div class="body">
            <h2>Dear {{ $invoice->bill_to_company }},</h2>

            <p>
                An invoice has been issued for testing services provided by the Kiribati Seafood
                Toxicology Laboratory. Please review the details below and arrange payment
                within <strong>10 working days</strong>.
            </p>

            <div style="margin-bottom: 8px;">
                <div class="detail-row">
                    <span class="label">Invoice Number</span>
                    <span class="value" style="font-family: monospace;">{{ $invoice->invoice_number }}</span>
                </div>
                <div class="detail-row">
                    <span class="label">Submission</span>
                    <span class="value" style="font-family: monospace;">{{ $invoice->submission->reference_number ?? '—' }}</span>
                </div>
                <div class="detail-row">
                    <span class="label">Invoice Date</span>
                    <span class="value">{{ $invoice->invoice_date->format('d M Y') }}</span>
                </div>
                <div class="detail-row">
                    <span class="label">Payment Due</span>
                    <span class="value">{{ $invoice->payment_due_date->format('d M Y') }}</span>
                </div>
                <div class="detail-row">
                    <span class="label">Number of Tests</span>
                    <span class="value">{{ $invoice->items->count() }}</span>
                </div>
                <div class="total-row">
                    <span>Total Amount (AUD)</span>
                    <span>A$ {{ number_format($invoice->total_amount_aud, 2) }}</span>
                </div>
            </div>

            <div class="payment-box">
                <p><strong>Payment Instructions:</strong><br>
                Please pay by bank transfer and include
                <strong>{{ $invoice->invoice_number }}</strong> as your payment reference.
                Contact the laboratory for bank account details.</p>
            </div>

            <div class="btn-wrapper">
                <a href="{{ url('/client/invoices/' . $invoice->id) }}" class="btn">
                    View Invoice
                </a>
            </div>
        </div>
        <div class="footer">
            <p>Kiribati Seafood Toxicology Laboratory &nbsp;·&nbsp; South Tarawa, Kiribati</p>
            <p style="margin-top:4px;">This email was sent automatically. Please do not reply directly.</p>
        </div>
    </div>
</body>
</html>