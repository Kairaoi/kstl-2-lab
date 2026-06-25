{{-- resources/views/kstl/client/consent/confirmed.blade.php --}}

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Decision Recorded — KSTL</title>
    @vite(['resources/css/app.css'])
</head>
<body style="background:#f1f5f9;min-height:100vh;margin:0;font-family:system-ui,-apple-system,sans-serif;">

    <div style="background:linear-gradient(135deg,#0f2240 0%,#1a2f4e 60%,#1e3a5f 100%);">
        <div style="height:3px;background:linear-gradient(90deg,#1a2f4e,#b8922a 30%,#b8922a 70%,#1a2f4e);"></div>
        <div style="max-width:36rem;margin:0 auto;padding:18px 1.5rem;">
            <div style="display:flex;align-items:center;gap:14px;">
                <div style="width:36px;height:36px;background:rgba(184,146,42,.2);border:1px solid #b8922a;border-radius:3px;display:flex;align-items:center;justify-content:center;font-size:10px;font-weight:700;color:#b8922a;flex-shrink:0;letter-spacing:.06em;">KL</div>
                <div>
                    <p style="font-size:13px;font-weight:700;color:#fff;margin:0 0 2px;">Kiribati Seafood Toxicology Laboratory</p>
                    <p style="font-size:10px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#b8922a;margin:0;">Laboratory Services &nbsp;&bull;&nbsp; Sample Assessment — Client Response</p>
                </div>
            </div>
        </div>
    </div>

    <div style="max-width:36rem;margin:0 auto;padding:56px 1.5rem;text-align:center;">

        @if($assessment->client_decision === 'consent_to_proceed')
            <div style="width:64px;height:64px;background:#fffbeb;border:2px solid #b8922a;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 24px;">
                <svg style="width:32px;height:32px;color:#b8922a;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h1 style="font-family:'Georgia',serif;font-size:24px;font-weight:700;color:#1a2f4e;margin:0 0 12px;">Decision Recorded</h1>
            <p style="font-size:14px;color:#374151;margin:0 0 8px;">
                Thank you. We have recorded your consent to proceed with testing
                despite the assessment findings.
            </p>
            <p style="font-size:13px;color:#64748b;margin:0;">
                Our lab team will commence testing and you will be notified when results are ready.
            </p>
        @else
            <div style="width:64px;height:64px;background:#fef2f2;border:2px solid #dc2626;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 24px;">
                <svg style="width:32px;height:32px;color:#dc2626;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </div>
            <h1 style="font-family:'Georgia',serif;font-size:24px;font-weight:700;color:#1a2f4e;margin:0 0 12px;">Rejection Confirmed</h1>
            <p style="font-size:14px;color:#374151;margin:0 0 8px;">
                Thank you. We have recorded your decision to cancel this submission.
            </p>
            <p style="font-size:13px;color:#64748b;margin:0;">
                You are welcome to resubmit with a fresh sample. Please contact us if you need assistance.
            </p>
        @endif

        <div style="margin-top:32px;background:#fff;border:1px solid #e2e8f0;border-radius:4px;padding:4px 20px;text-align:left;">
            <div style="display:flex;justify-content:space-between;align-items:center;padding:12px 0;border-bottom:1px solid #f1f5f9;">
                <span style="font-size:12px;color:#64748b;">Reference</span>
                <span style="font-family:monospace;font-weight:700;color:#1a2f4e;font-size:13px;">{{ $assessment->sample->submission->reference_number }}</span>
            </div>
            <div style="display:flex;justify-content:space-between;align-items:center;padding:12px 0;border-bottom:1px solid #f1f5f9;">
                <span style="font-size:12px;color:#64748b;">Sample</span>
                <span style="font-weight:600;color:#1e293b;font-size:13px;">{{ $assessment->sample->common_name }}</span>
            </div>
            <div style="display:flex;justify-content:space-between;align-items:center;padding:12px 0;border-bottom:1px solid #f1f5f9;">
                <span style="font-size:12px;color:#64748b;">Decision</span>
                <span style="font-weight:700;font-size:13px;{{ $assessment->client_decision === 'consent_to_proceed' ? 'color:#b8922a;' : 'color:#dc2626;' }}">
                    {{ $assessment->client_decision === 'consent_to_proceed' ? 'Consent to Proceed' : 'Confirmed Rejection' }}
                </span>
            </div>
            <div style="display:flex;justify-content:space-between;align-items:center;padding:12px 0;">
                <span style="font-size:12px;color:#64748b;">Recorded</span>
                <span style="font-size:13px;color:#374151;">{{ $assessment->client_decision_at?->format('d M Y \a\t H:i') }}</span>
            </div>
        </div>

        <p style="margin-top:32px;font-size:13px;color:#94a3b8;">
            Questions? Contact us at
            <a href="mailto:{{ config('mail.from.address') }}" style="color:#1a2f4e;text-decoration:underline;">
                {{ config('mail.from.address') }}
            </a>
        </p>

    </div>

</body>
</html>