<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sample Assessment Result</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: #f9fafb; margin: 0; padding: 0; }
        .wrapper { max-width: 600px; margin: 40px auto; background: #ffffff; border-radius: 12px; overflow: hidden; border: 1px solid #e5e7eb; }
        .header { background: #1e3a5f; padding: 32px 40px; }
        .header h1 { color: #ffffff; font-size: 20px; margin: 0; font-weight: 600; }
        .header p { color: #93c5fd; font-size: 13px; margin: 4px 0 0; }
        .body { padding: 32px 40px; }
        .alert { background: #fef2f2; border-left: 4px solid #ef4444; padding: 16px; border-radius: 6px; margin-bottom: 24px; }
        .alert p { margin: 0; color: #991b1b; font-size: 14px; }
        h2 { font-size: 16px; color: #111827; margin: 0 0 12px; }
        p { font-size: 14px; color: #374151; line-height: 1.6; margin: 0 0 16px; }
        .detail-row { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #f3f4f6; font-size: 14px; }
        .detail-row .label { color: #6b7280; }
        .detail-row .value { color: #111827; font-weight: 500; }
        .criteria-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin: 16px 0; }
        .criteria-item { display: flex; align-items: center; gap-8px; font-size: 13px; padding: 6px 0; }
        .pass { color: #059669; }
        .fail { color: #dc2626; font-weight: 600; }
        .rejection-box { background: #fef2f2; border: 1px solid #fecaca; border-radius: 8px; padding: 16px; margin: 16px 0; }
        .rejection-box p { color: #7f1d1d; margin: 0; font-size: 14px; }
        .btn-wrapper { text-align: center; margin: 32px 0; }
        .btn { display: inline-block; padding: 14px 32px; border-radius: 8px; font-size: 15px; font-weight: 600; text-decoration: none; }
        .btn-proceed { background: #d97706; color: #ffffff; margin-right: 12px; }
        .btn-reject { background: #dc2626; color: #ffffff; }
        .divider { text-align: center; color: #9ca3af; font-size: 12px; margin: 8px 0 24px; }
        .note { background: #f9fafb; border-radius: 8px; padding: 16px; font-size: 13px; color: #6b7280; margin-top: 24px; }
        .footer { background: #f9fafb; padding: 20px 40px; border-top: 1px solid #e5e7eb; text-align: center; }
        .footer p { font-size: 12px; color: #9ca3af; margin: 0; }
        .expiry { background: #fffbeb; border: 1px solid #fcd34d; border-radius: 6px; padding: 12px 16px; margin-top: 16px; font-size: 13px; color: #92400e; }
    </style>
</head>
<body>
    <div class="wrapper">

        {{-- Header --}}
        <div class="header">
            <h1>Kiribati Seafood Toxicology Laboratory</h1>
            <p>Sample Assessment Notification</p>
        </div>

        <div class="body">

            <div class="alert">
                <p><strong>Action Required:</strong> One of your submitted samples did not pass our assessment. Please review the details below and indicate your decision.</p>
            </div>

            <h2>Dear {{ $sample->submission->client->responsible_officer_name ?? $sample->submission->client->company_name }},</h2>

            <p>We have assessed the sample you submitted under reference <strong>{{ $sample->submission->reference_number }}</strong> and unfortunately it did not meet our acceptance criteria.</p>

            {{-- Submission Details --}}
            <div style="margin-bottom: 24px;">
                <div class="detail-row">
                    <span class="label">Reference</span>
                    <span class="value">{{ $sample->submission->reference_number }}</span>
                </div>
                <div class="detail-row">
                    <span class="label">Sample</span>
                    <span class="value">{{ $sample->common_name }}@if($sample->scientific_name) <em>({{ $sample->scientific_name }})</em>@endif</span>
                </div>
                <div class="detail-row">
                    <span class="label">Sample Code</span>
                    <span class="value">{{ $sample->sample_code }}</span>
                </div>
                <div class="detail-row">
                    <span class="label">Assessment Date</span>
                    <span class="value">{{ $assessment->assessed_at->format('d M Y \a\t H:i') }}</span>
                </div>
            </div>

            {{-- Assessment Results --}}
            <h2>Assessment Criteria</h2>
            @php
                $criteria = [
                    'Temperature'  => $assessment->temperature_ok,
                    'Storage'      => $assessment->storage_ok,
                    'Transport'    => $assessment->transport_ok,
                    'Packaging'    => $assessment->packaging_ok,
                    'Colour'       => $assessment->colour_ok,
                    'Odour'        => $assessment->odour_ok,
                    'Weight'       => $assessment->weight_ok,
                ];
            @endphp
            <div class="criteria-grid">
                @foreach($criteria as $label => $passed)
                    <div class="criteria-item {{ $passed ? 'pass' : 'fail' }}">
                        {{ $passed ? '✓' : '✗' }} {{ $label }}
                    </div>
                @endforeach
            </div>

            @if($assessment->rejection_reason)
                <div class="rejection-box">
                    <strong style="display:block; margin-bottom: 6px; color: #7f1d1d;">Reason for Rejection:</strong>
                    <p>{{ $assessment->rejection_reason }}</p>
                </div>
            @endif

            {{-- Decision Buttons --}}
            <h2>Your Decision</h2>
            <p>Please click one of the options below to record your decision. This link is valid for <strong>7 days</strong>.</p>

            <div class="btn-wrapper">
                <a href="{{ $consentUrl }}?decision=consent_to_proceed" class="btn btn-proceed">
                    Proceed with Testing
                </a>
                <a href="{{ $consentUrl }}?decision=confirm_rejection" class="btn btn-reject">
                    Cancel Submission
                </a>
            </div>

            <div class="divider">— or visit the link below to review and decide —</div>

            <p style="text-align:center; word-break: break-all;">
                <a href="{{ $consentUrl }}" style="color: #2563eb; font-size: 13px;">{{ $consentUrl }}</a>
            </p>

            <div class="expiry">
                ⏱ This link expires on <strong>{{ $assessment->consent_token_expires_at ? \Carbon\Carbon::parse($assessment->consent_token_expires_at)->format('d M Y') : '7 days from now' }}</strong>.
                If expired, please contact the lab directly.
            </div>

            <div class="note">
                <strong>Note:</strong> If you choose to proceed, testing will continue and results will include a note
                about the assessment findings. If you cancel, you are welcome to resubmit with a fresh sample.
                Contact us at <strong>{{ config('mail.from.address') }}</strong> if you have any questions.
            </div>

        </div>

        <div class="footer">
            <p>Kiribati Seafood Toxicology Laboratory &nbsp;·&nbsp; South Tarawa, Kiribati</p>
            <p style="margin-top: 4px;">This email was sent automatically. Please do not reply directly to this message.</p>
        </div>

    </div>
</body>
</html>