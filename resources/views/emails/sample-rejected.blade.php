<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Action Required — Sample Not Accepted</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: #f9fafb; margin: 0; padding: 0; }
        .wrapper { max-width: 600px; margin: 40px auto; background: #ffffff; border-radius: 12px; overflow: hidden; border: 1px solid #e5e7eb; }
        .header { background: #1e3a5f; padding: 32px 40px; }
        .header h1 { color: #ffffff; font-size: 20px; margin: 0; font-weight: 600; }
        .header p { color: #93c5fd; font-size: 13px; margin: 6px 0 0; }
        .hold-banner { background: #fef2f2; border-bottom: 3px solid #ef4444; padding: 18px 40px; display: flex; align-items: flex-start; gap: 12px; }
        .hold-banner-icon { font-size: 22px; line-height: 1; flex-shrink: 0; margin-top: 1px; }
        .hold-banner-text { }
        .hold-banner-text strong { display: block; font-size: 15px; color: #991b1b; margin-bottom: 3px; }
        .hold-banner-text span { font-size: 13px; color: #b91c1c; line-height: 1.5; }
        .body { padding: 32px 40px; }
        h2 { font-size: 15px; color: #111827; margin: 0 0 12px; font-weight: 600; }
        p { font-size: 14px; color: #374151; line-height: 1.6; margin: 0 0 16px; }
        .detail-row { display: flex; justify-content: space-between; padding: 9px 0; border-bottom: 1px solid #f3f4f6; font-size: 14px; }
        .detail-row .label { color: #6b7280; }
        .detail-row .value { color: #111827; font-weight: 500; }
        .criteria-section { margin: 20px 0; }
        .criteria-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 4px 0; margin: 10px 0 0; }
        .criteria-item { display: flex; align-items: center; gap: 8px; font-size: 13px; padding: 5px 0; }
        .pass { color: #059669; }
        .fail { color: #dc2626; font-weight: 600; }
        .rejection-box { background: #fef2f2; border: 1px solid #fecaca; border-radius: 8px; padding: 16px; margin: 20px 0; }
        .rejection-box .rj-label { font-size: 12px; font-weight: 700; color: #991b1b; text-transform: uppercase; letter-spacing: .05em; margin-bottom: 6px; }
        .rejection-box p { color: #7f1d1d; margin: 0; font-size: 14px; }
        .decision-section { margin: 28px 0 20px; }
        .decision-section h2 { margin-bottom: 6px; }
        .decision-section > p { margin-bottom: 20px; }
        .btn-wrapper { display: flex; flex-direction: column; gap: 10px; }
        .btn { display: block; padding: 15px 28px; border-radius: 8px; font-size: 15px; font-weight: 600; text-decoration: none; text-align: center; }
        .btn-proceed { background: #d97706; color: #ffffff; }
        .btn-reject  { background: #6b7280; color: #ffffff; }
        .or-divider { text-align: center; color: #9ca3af; font-size: 12px; margin: 6px 0; }
        .link-fallback { text-align: center; margin: 20px 0 0; }
        .link-fallback a { color: #2563eb; font-size: 13px; word-break: break-all; }
        .expiry-box { background: #fffbeb; border: 1px solid #fcd34d; border-radius: 6px; padding: 12px 16px; margin: 20px 0; font-size: 13px; color: #92400e; }
        .note { background: #f9fafb; border-radius: 8px; padding: 16px; font-size: 13px; color: #6b7280; margin-top: 20px; line-height: 1.6; }
        .footer { background: #f9fafb; padding: 20px 40px; border-top: 1px solid #e5e7eb; text-align: center; }
        .footer p { font-size: 12px; color: #9ca3af; margin: 0; }
        .footer p + p { margin-top: 4px; }
    </style>
</head>
<body>
    <div class="wrapper">

        {{-- Header --}}
        <div class="header">
            <h1>Kiribati Seafood Toxicology Laboratory</h1>
            <p>Sample Assessment Notification — {{ $sample->submission->reference_number }}</p>
        </div>

        {{-- Testing on hold banner --}}
        <div class="hold-banner">
            <div class="hold-banner-icon">⏸</div>
            <div class="hold-banner-text">
                <strong>Testing is on hold — your consent is required</strong>
                <span>
                    We cannot proceed with testing until you respond to this notice.
                    Please review the assessment findings below and indicate your decision.
                </span>
            </div>
        </div>

        <div class="body">

            <p>Dear <strong>{{ $sample->submission->client->responsible_officer_name ?? $sample->submission->client->company_name }}</strong>,</p>

            <p>
                The sample you submitted under reference <strong>{{ $sample->submission->reference_number }}</strong>
                did not pass our reception assessment. <strong>Testing has been paused</strong> and will only continue
                after you provide your consent.
            </p>

            {{-- Submission Details --}}
            <div style="margin-bottom: 24px;">
                <div class="detail-row">
                    <span class="label">Reference</span>
                    <span class="value">{{ $sample->submission->reference_number }}</span>
                </div>
                <div class="detail-row">
                    <span class="label">Sample</span>
                    <span class="value">
                        {{ $sample->common_name }}
                        @if($sample->scientific_name)
                            &nbsp;<em style="font-weight:400; color:#6b7280;">({{ $sample->scientific_name }})</em>
                        @endif
                    </span>
                </div>
                <div class="detail-row">
                    <span class="label">Sample Code</span>
                    <span class="value" style="font-family: monospace;">{{ $sample->sample_code }}</span>
                </div>
                <div class="detail-row">
                    <span class="label">Assessment Date</span>
                    <span class="value">
                        {{ ($assessment->assessed_at ?? $assessment->created_at)->format('d M Y \a\t H:i') }}
                    </span>
                </div>
            </div>

            {{-- Assessment Criteria --}}
            <div class="criteria-section">
                <h2>Assessment Results</h2>
                @php
                    $criteria = [
                        'Temperature' => [$assessment->temperature_ok, $assessment->temperature_notes],
                        'Storage'     => [$assessment->storage_ok,     $assessment->storage_notes],
                        'Transport'   => [$assessment->transport_ok,   $assessment->transport_notes],
                        'Packaging'   => [$assessment->packaging_ok,   $assessment->packaging_notes],
                        'Colour'      => [$assessment->colour_ok,      $assessment->colour_notes],
                        'Odour'       => [$assessment->odour_ok,       $assessment->odour_notes],
                        'Weight'      => [$assessment->weight_ok,      $assessment->weight_notes],
                    ];
                @endphp
                <div class="criteria-grid">
                    @foreach($criteria as $label => [$passed, $notes])
                        <div class="criteria-item {{ $passed ? 'pass' : 'fail' }}">
                            {{ $passed ? '✓' : '✗' }}&nbsp;{{ $label }}
                            @if(! $passed && $notes)
                                <span style="font-weight:400; color:#6b7280; font-size:12px;"> — {{ $notes }}</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            @if($assessment->rejection_reason)
                <div class="rejection-box">
                    <div class="rj-label">Reason for Rejection</div>
                    <p>{{ $assessment->rejection_reason }}</p>
                </div>
            @endif

            @if($assessment->additional_observations)
                <div class="rejection-box" style="margin-top: -8px; background: #fff7ed; border-color: #fed7aa;">
                    <div class="rj-label" style="color: #9a3412;">Additional Observations</div>
                    <p style="color: #7c2d12;">{{ $assessment->additional_observations }}</p>
                </div>
            @endif

            {{-- Decision --}}
            <div class="decision-section">
                <h2>Your Consent is Required</h2>
                <p>
                    Please choose one of the options below. <strong>Testing will remain on hold until you respond.</strong>
                    This link is active for <strong>7 days</strong> from the date of this email.
                </p>

                <div class="btn-wrapper">
                    <a href="{{ $consentUrl }}?decision=consent_to_proceed" class="btn btn-proceed">
                        ✓ &nbsp; I consent — proceed with testing
                    </a>
                    <div class="or-divider">— or —</div>
                    <a href="{{ $consentUrl }}?decision=confirm_rejection" class="btn btn-reject">
                        ✗ &nbsp; Do not proceed — cancel this submission
                    </a>
                </div>

                <div class="link-fallback">
                    <p style="font-size:12px; color:#9ca3af; margin: 12px 0 4px;">Prefer to review first?</p>
                    <a href="{{ $consentUrl }}">{{ $consentUrl }}</a>
                </div>
            </div>

            @if($assessment->consent_token_expires_at)
                <div class="expiry-box">
                    ⏱ This link expires on
                    <strong>{{ \Carbon\Carbon::parse($assessment->consent_token_expires_at)->format('d M Y') }}</strong>.
                    After that date, please contact the lab directly at
                    <strong>{{ config('mail.from.address') }}</strong>.
                </div>
            @endif

            <div class="note">
                <strong>What happens next?</strong><br>
                If you choose to <strong>proceed</strong>: testing will continue and your results report
                will include a note about the sample condition at the time of receipt.<br><br>
                If you choose to <strong>cancel</strong>: no testing will be carried out and you are welcome
                to submit a fresh sample at any time.<br><br>
                If you have questions, contact us at <strong>{{ config('mail.from.address') }}</strong>
                or log in to your client to review this submission.
            </div>

        </div>

        <div class="footer">
            <p>Kiribati Seafood Toxicology Laboratory &nbsp;·&nbsp; South Tarawa, Kiribati</p>
            <p>This email was sent automatically. Please do not reply directly to this message.</p>
        </div>

    </div>
</body>
</html>
