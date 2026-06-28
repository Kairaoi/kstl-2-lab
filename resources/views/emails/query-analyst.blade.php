<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Director Query — Action Required</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: #f9fafb; margin: 0; padding: 0; }
        .wrapper { max-width: 600px; margin: 40px auto; background: #ffffff; border-radius: 12px; overflow: hidden; border: 1px solid #e5e7eb; }
        .header { background: #92400e; padding: 32px 40px; }
        .header h1 { color: #ffffff; font-size: 20px; margin: 0; font-weight: 600; }
        .header p { color: #fde68a; font-size: 13px; margin: 4px 0 0; }
        .body { padding: 32px 40px; }
        .alert { background: #fffbeb; border-left: 4px solid #d97706; padding: 16px; border-radius: 6px; margin-bottom: 24px; }
        .alert p { margin: 0; color: #92400e; font-size: 14px; }
        .post-auth-badge { display: inline-block; background: #fef2f2; border: 1px solid #fca5a5; border-radius: 4px; padding: 4px 10px; font-size: 12px; font-weight: 600; color: #b91c1c; margin-bottom: 16px; }
        h2 { font-size: 16px; color: #111827; margin: 0 0 12px; }
        p { font-size: 14px; color: #374151; line-height: 1.6; margin: 0 0 16px; }
        .detail-row { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #f3f4f6; font-size: 14px; }
        .detail-row .label { color: #6b7280; }
        .detail-row .value { color: #111827; font-weight: 500; }
        .query-box { background: #fffbeb; border: 1px solid #fcd34d; border-left: 4px solid #d97706; border-radius: 6px; padding: 16px 20px; margin: 20px 0; }
        .query-box .query-label { font-size: 11px; font-weight: 700; letter-spacing: .1em; text-transform: uppercase; color: #92400e; margin: 0 0 8px; }
        .query-box .query-text { font-size: 15px; color: #1a2f4e; line-height: 1.6; margin: 0; }
        .tests-list { background: #f8fafc; border: 1px solid #e5e7eb; border-radius: 6px; padding: 12px 16px; margin: 16px 0; }
        .tests-list .tests-label { font-size: 11px; font-weight: 700; letter-spacing: .1em; text-transform: uppercase; color: #6b7280; margin: 0 0 8px; }
        .tests-list ul { margin: 0; padding-left: 18px; }
        .tests-list li { font-size: 13px; color: #374151; line-height: 1.8; }
        .btn-wrapper { text-align: center; margin: 28px 0; }
        .btn { display: inline-block; padding: 14px 32px; border-radius: 8px; font-size: 15px; font-weight: 600; text-decoration: none; background: #92400e; color: #ffffff; }
        .footer { background: #f9fafb; padding: 20px 40px; border-top: 1px solid #e5e7eb; text-align: center; }
        .footer p { font-size: 12px; color: #9ca3af; margin: 0; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <h1>Kiribati Seafood Toxicology Laboratory</h1>
            <p>Analyst — Director Query</p>
        </div>
        <div class="body">

            @if($postAuthorisation)
                <div class="post-auth-badge">Post-Authorisation Query</div>
            @endif

            <div class="alert">
                <p><strong>Action Required:</strong> The Director has returned test(s) for your clarification.</p>
            </div>

            <h2>Dear Analyst,</h2>

            <p>
                The Laboratory Director has reviewed the results for submission <strong>{{ $submission->reference_number }}</strong>
                and requires clarification on the test(s) listed below.
                @if($postAuthorisation)
                    This query was raised <strong>after the submission had already been authorised</strong>.
                    The authorisation has been withdrawn pending your response.
                @endif
                Please review the Director's query, amend your results as necessary, and save to resubmit for authorisation.
            </p>

            <div style="margin-bottom: 20px;">
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
                    <span class="label">Tests Flagged</span>
                    <span class="value">{{ count($testLabels) }}</span>
                </div>
            </div>

            <div class="tests-list">
                <div class="tests-label">Tests Requiring Clarification</div>
                <ul>
                    @foreach($testLabels as $label)
                        <li>{{ $label }}</li>
                    @endforeach
                </ul>
            </div>

            <div class="query-box">
                <div class="query-label">Director's Query</div>
                <div class="query-text">{{ $queryNote }}</div>
            </div>

            <div class="btn-wrapper">
                <a href="{{ url('/analyst/tests') }}" class="btn">
                    Open My Test Queue
                </a>
            </div>

            <p style="font-size: 13px; color: #6b7280; text-align: center;">
                Log in to the KSTL Analyst view, open each flagged test, review the Director's query, and save your updated result.
            </p>
        </div>
        <div class="footer">
            <p>Kiribati Seafood Toxicology Laboratory &nbsp;·&nbsp; Ministry of Fisheries and Ocean Resources, Tarawa, Kiribati</p>
            <p style="margin-top:4px;">This email was sent automatically. Please do not reply directly.</p>
        </div>
    </div>
</body>
</html>
