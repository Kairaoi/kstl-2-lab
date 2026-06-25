{{-- resources/views/kstl/client/consent/invalid.blade.php --}}

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invalid Link — KSTL</title>
    @vite(['resources/css/app.css'])
</head>
<body style="background:#f1f5f9;min-height:100vh;margin:0;font-family:system-ui,-apple-system,sans-serif;">

    <div style="background:linear-gradient(135deg,#0f2240 0%,#1a2f4e 60%,#1e3a5f 100%);">
        <div style="height:3px;background:linear-gradient(90deg,#1a2f4e,#b8922a 30%,#b8922a 70%,#1a2f4e);"></div>
        <div style="max-width:36rem;margin:0 auto;padding:18px 1.5rem;">
            <div style="display:flex;align-items:center;gap:14px;">
                <div style="width:36px;height:36px;background:rgba(184,146,42,.2);border:1px solid #b8922a;border-radius:3px;display:flex;align-items:center;justify-content:center;font-size:10px;font-weight:700;color:#b8922a;flex-shrink:0;letter-spacing:.06em;">KL</div>
                <div>
                    <p style="font-size:13px;font-weight:700;color:#fff;margin:0;">Kiribati Seafood Toxicology Laboratory</p>
                </div>
            </div>
        </div>
    </div>

    <div style="max-width:36rem;margin:0 auto;padding:56px 1.5rem;text-align:center;">
        <div style="width:64px;height:64px;background:#f1f5f9;border:2px solid #e2e8f0;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 24px;">
            <svg style="width:32px;height:32px;color:#94a3b8;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <h1 style="font-family:'Georgia',serif;font-size:24px;font-weight:700;color:#1a2f4e;margin:0 0 12px;">Invalid or Expired Link</h1>
        <p style="font-size:13px;color:#64748b;margin:0 0 32px;">{{ $reason }}</p>

        @if(isset($lab_email))
            <p style="font-size:13px;color:#64748b;margin:0;">
                Please contact us directly at
                <a href="mailto:{{ $lab_email }}" style="color:#1a2f4e;text-decoration:underline;font-weight:600;">{{ $lab_email }}</a>
                to record your decision.
            </p>
        @endif
    </div>

</body>
</html>