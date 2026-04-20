<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Complaint Lodged</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: #f9fafb; margin: 0; padding: 0; }
        .wrapper { max-width: 600px; margin: 40px auto; background: #ffffff; border-radius: 12px; overflow: hidden; border: 1px solid #e5e7eb; }
        .header { background: #7f1d1d; padding: 32px 40px; }
        .header h1 { color: #ffffff; font-size: 20px; margin: 0; font-weight: 600; }
        .header p { color: #fca5a5; font-size: 13px; margin: 4px 0 0; }
        .body { padding: 32px 40px; }
        .alert { background: #fef2f2; border-left: 4px solid #dc2626; padding: 16px; border-radius: 6px; margin-bottom: 24px; }
        .alert p { margin: 0; color: #991b1b; font-size: 14px; }
        h2 { font-size: 16px; color: #111827; margin: 0 0 12px; }
        p { font-size: 14px; color: #374151; line-height: 1.6; margin: 0 0 16px; }
        .detail-row { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #f3f4f6; font-size: 14px; }
        .detail-row .label { color: #6b7280; }
        .detail-row .value { color: #111827; font-weight: 500; text-align: right; max-width: 60%; }
        .description-box { background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; padding: 16px; margin: 20px 0; font-size: 14px; color: #374151; line-height: 1.6; }
        .btn-wrapper { text-align: center; margin: 28px 0; }
        .btn { display: inline-block; padding: 14px 32px; border-radius: 8px; font-size: 15px; font-weight: 600; text-decoration: none; background: #7f1d1d; color: #ffffff; }
        .footer { background: #f9fafb; padding: 20px 40px; border-top: 1px solid #e5e7eb; text-align: center; }
        .footer p { font-size: 12px; color: #9ca3af; margin: 0; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <h1>Kiribati Seafood Toxicology Laboratory</h1>
            <p>Director — New Complaint Received</p>
        </div>
        <div class="body">
            <div class="alert">
                <p><strong>Action Required:</strong> A new complaint has been lodged and requires your attention.</p>
            </div>

            <h2>Dear Director,</h2>

            <p>A complaint has been submitted through the KSTL client portal. Please review and respond within 5 working days.</p>

            <div style="margin-bottom: 24px;">
                <div class="detail-row">
                    <span class="label">Subject</span>
                    <span class="value">{{ $complaint->subject }}</span>
                </div>
                <div class="detail-row">
                    <span class="label">From</span>
                    <span class="value">{{ $complaint->complainant_name }}</span>
                </div>
                <div class="detail-row">
                    <span class="label">Organisation</span>
                    <span class="value">{{ $complaint->complainant_organisation ?? '—' }}</span>
                </div>
                <div class="detail-row">
                    <span class="label">Type(s)</span>
                    <span class="value">{{ implode(', ', $complaint->getComplaintTypeLabels()) }}</span>
                </div>
                <div class="detail-row">
                    <span class="label">Incident Date</span>
                    <span class="value">{{ $complaint->incident_date->format('d M Y') }}</span>
                </div>
                @if($complaint->submission)
                    <div class="detail-row">
                        <span class="label">Related Submission</span>
                        <span class="value" style="font-family: monospace;">{{ $complaint->submission->reference_number }}</span>
                    </div>
                @endif
                <div class="detail-row">
                    <span class="label">Lodged On</span>
                    <span class="value">{{ $complaint->created_at->format('d M Y \a\t H:i') }}</span>
                </div>
            </div>

            <p style="font-size: 13px; color: #6b7280; font-weight: 600; margin-bottom: 6px;">Description:</p>
            <div class="description-box">
                {{ Str::limit($complaint->description, 400) }}
            </div>

            <div class="btn-wrapper">
                <a href="{{ url('/director/complaints/' . $complaint->id) }}" class="btn">
                    Review &amp; Respond
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