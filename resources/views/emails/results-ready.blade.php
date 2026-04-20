<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Results Are Ready</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: #f9fafb; margin: 0; padding: 0; }
        .wrapper { max-width: 600px; margin: 40px auto; background: #ffffff; border-radius: 12px; overflow: hidden; border: 1px solid #e5e7eb; }
        .header { background: #1e3a5f; padding: 32px 40px; }
        .header h1 { color: #ffffff; font-size: 20px; margin: 0; font-weight: 600; }
        .header p { color: #93c5fd; font-size: 13px; margin: 4px 0 0; }
        .body { padding: 32px 40px; }
        .outcome-badge { display: inline-block; padding: 8px 20px; border-radius: 6px; font-size: 15px; font-weight: 700; margin: 16px 0; text-transform: uppercase; letter-spacing: 1px; }
        .outcome-pass { background: #d1fae5; color: #065f46; }
        .outcome-fail { background: #fee2e2; color: #991b1b; }
        .outcome-inconclusive { background: #fef3c7; color: #92400e; }
        h2 { font-size: 16px; color: #111827; margin: 0 0 12px; }
        p { font-size: 14px; color: #374151; line-height: 1.6; margin: 0 0 16px; }
        .detail-row { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #f3f4f6; font-size: 14px; }
        .detail-row .label { color: #6b7280; }
        .detail-row .value { color: #111827; font-weight: 500; }
        .btn-wrapper { text-align: center; margin: 32px 0; }
        .btn { display: inline-block; padding: 14px 32px; border-radius: 8px; font-size: 15px; font-weight: 600; text-decoration: none; background: #1e3a5f; color: #ffffff; }
        .note { background: #f9fafb; border-radius: 8px; padding: 16px; font-size: 13px; color: #6b7280; margin-top: 24px; }
        .footer { background: #f9fafb; padding: 20px 40px; border-top: 1px solid #e5e7eb; text-align: center; }
        .footer p { font-size: 12px; color: #9ca3af; margin: 0; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <h1>Kiribati Seafood Toxicology Laboratory</h1>
            <p>Test Results Notification</p>
        </div>
        <div class="body">
            <h2>Dear {{ $submission->client->responsible_officer_name ?? $submission->client->company_name }},</h2>

            <p>
                Your test results for submission <strong>{{ $submission->reference_number }}</strong>
                have been reviewed and authorised by the Laboratory Director.
            </p>

            @php
                $outcomeClass = match($result->overall_outcome) {
                    'pass'         => 'outcome-pass',
                    'fail'         => 'outcome-fail',
                    default        => 'outcome-inconclusive',
                };
                $outcomeLabel = match($result->overall_outcome) {
                    'pass'         => '✓ Pass',
                    'fail'         => '✗ Fail',
                    default        => '⚠ Inconclusive',
                };
            @endphp

            <div class="outcome-badge {{ $outcomeClass }}">{{ $outcomeLabel }}</div>

            <div style="margin-bottom: 24px;">
                <div class="detail-row">
                    <span class="label">Reference</span>
                    <span class="value">{{ $submission->reference_number }}</span>
                </div>
                <div class="detail-row">
                    <span class="label">Sample</span>
                    <span class="value">{{ $submission->sample_name }}</span>
                </div>
                <div class="detail-row">
                    <span class="label">Overall Outcome</span>
                    <span class="value" style="text-transform: capitalize;">{{ $result->overall_outcome }}</span>
                </div>
                <div class="detail-row">
                    <span class="label">Authorised By</span>
                    <span class="value">{{ $result->authorisedBy?->name ?? 'Laboratory Director' }}</span>
                </div>
                <div class="detail-row">
                    <span class="label">Authorised On</span>
                    <span class="value">{{ $result->authorised_at?->format('d M Y \a\t H:i') }}</span>
                </div>
            </div>

            @if($result->director_comments)
                <div style="background:#f0f9ff; border-left:4px solid #0ea5e9; padding:14px; border-radius:4px; margin-bottom:20px;">
                    <p style="margin:0; font-size:13px; color:#0c4a6e;"><strong>Director Comments:</strong><br>{{ $result->director_comments }}</p>
                </div>
            @endif

            <div class="btn-wrapper">
                <a href="{{ url('/client/results/' . $submission->id) }}" class="btn">
                    View Full Results
                </a>
            </div>

            <div class="note">
                Log in to the KSTL portal to view your complete test results, download your report,
                and check your invoice. Contact us at <strong>{{ config('mail.from.address') }}</strong>
                if you have any questions.
            </div>
        </div>
        <div class="footer">
            <p>Kiribati Seafood Toxicology Laboratory &nbsp;·&nbsp; South Tarawa, Kiribati</p>
            <p style="margin-top:4px;">This email was sent automatically. Please do not reply directly.</p>
        </div>
    </div>
</body>
</html>