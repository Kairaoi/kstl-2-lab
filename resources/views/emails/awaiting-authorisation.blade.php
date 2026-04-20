<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Results Awaiting Authorisation</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: #f9fafb; margin: 0; padding: 0; }
        .wrapper { max-width: 600px; margin: 40px auto; background: #ffffff; border-radius: 12px; overflow: hidden; border: 1px solid #e5e7eb; }
        .header { background: #0f4c81; padding: 32px 40px; }
        .header h1 { color: #ffffff; font-size: 20px; margin: 0; font-weight: 600; }
        .header p { color: #93c5fd; font-size: 13px; margin: 4px 0 0; }
        .body { padding: 32px 40px; }
        .alert { background: #fef3c7; border-left: 4px solid #d97706; padding: 16px; border-radius: 6px; margin-bottom: 24px; }
        .alert p { margin: 0; color: #92400e; font-size: 14px; }
        h2 { font-size: 16px; color: #111827; margin: 0 0 12px; }
        p { font-size: 14px; color: #374151; line-height: 1.6; margin: 0 0 16px; }
        .detail-row { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #f3f4f6; font-size: 14px; }
        .detail-row .label { color: #6b7280; }
        .detail-row .value { color: #111827; font-weight: 500; }
        .btn-wrapper { text-align: center; margin: 28px 0; }
        .btn { display: inline-block; padding: 14px 32px; border-radius: 8px; font-size: 15px; font-weight: 600; text-decoration: none; background: #0f4c81; color: #ffffff; }
        .footer { background: #f9fafb; padding: 20px 40px; border-top: 1px solid #e5e7eb; text-align: center; }
        .footer p { font-size: 12px; color: #9ca3af; margin: 0; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <h1>Kiribati Seafood Toxicology Laboratory</h1>
            <p>Director — Action Required</p>
        </div>
        <div class="body">
            <div class="alert">
                <p><strong>Action Required:</strong> A submission is awaiting your authorisation.</p>
            </div>

            <h2>Dear Director,</h2>

            <p>
                All tests for the submission below have been completed by the analyst team
                and the results are now awaiting your review and authorisation.
            </p>

            <div style="margin-bottom: 24px;">
                <div class="detail-row">
                    <span class="label">Reference</span>
                    <span class="value" style="font-family: monospace;">{{ $submission->reference_number }}</span>
                </div>
                <div class="detail-row">
                    <span class="label">Client</span>
                    <span class="value">{{ $submission->client->company_name }}</span>
                </div>
                <div class="detail-row">
                    <span class="label">Sample</span>
                    <span class="value">{{ $submission->sample_name }}</span>
                </div>
                <div class="detail-row">
                    <span class="label">Priority</span>
                    <span class="value" style="text-transform: capitalize;">{{ $submission->priority ?? 'Routine' }}</span>
                </div>
                <div class="detail-row">
                    <span class="label">Submitted</span>
                    <span class="value">{{ $submission->submitted_at?->format('d M Y') }}</span>
                </div>
                @if($submission->results_required_by)
                <div class="detail-row">
                    <span class="label">Required By</span>
                    <span class="value" style="color: #dc2626;">{{ $submission->results_required_by->format('d M Y') }}</span>
                </div>
                @endif
            </div>

            <div class="btn-wrapper">
                <a href="{{ url('/director/submissions/' . $submission->id) }}" class="btn">
                    Review &amp; Authorise
                </a>
            </div>

            <p style="font-size: 13px; color: #6b7280; text-align: center;">
                Log in to the KSTL Director portal to review all test results and authorise or query the analyst.
            </p>
        </div>
        <div class="footer">
            <p>Kiribati Seafood Toxicology Laboratory &nbsp;·&nbsp; South Tarawa, Kiribati</p>
            <p style="margin-top:4px;">This email was sent automatically. Please do not reply directly.</p>
        </div>
    </div>
</body>
</html>