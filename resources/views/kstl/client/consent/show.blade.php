{{-- resources/views/kstl/client/consent/show.blade.php --}}
{{-- Public page — no x-app-layout, no navigation --}}

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sample Assessment Decision — KSTL</title>
    @vite(['resources/css/app.css'])
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body style="background:#f1f5f9;min-height:100vh;margin:0;font-family:system-ui,-apple-system,sans-serif;">

    {{-- Header --}}
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

    <div style="max-width:36rem;margin:0 auto;padding:28px 1.5rem;">

        @if(session('error'))
            <div style="background:#fef2f2;border:1px solid #fecaca;border-left:4px solid #dc2626;border-radius:4px;padding:12px 16px;margin-bottom:20px;font-size:13px;color:#991b1b;">
                {{ session('error') }}
            </div>
        @endif

        {{-- Intro --}}
        <div style="background:#fff;border:1px solid #e2e8f0;border-radius:4px;padding:20px 24px;margin-bottom:16px;">
            <p style="font-size:10px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#64748b;margin:0 0 4px;">Reference</p>
            <p style="font-size:18px;font-weight:700;color:#1a2f4e;font-family:monospace;margin:0 0 16px;">{{ $submission->reference_number }}</p>

            <p style="font-size:13px;color:#374151;line-height:1.6;margin:0;">
                Dear <strong>{{ $submission->client->responsible_officer_name ?? $submission->client->company_name }}</strong>,
                the sample listed below did not pass our reception assessment.
                Please review the findings and indicate your decision.
            </p>
        </div>

        {{-- Sample Info --}}
        <div style="background:#fff;border:1px solid #e2e8f0;border-radius:4px;overflow:hidden;margin-bottom:16px;">
            <div style="padding:12px 20px;border-bottom:1px solid #e2e8f0;background:#f8fafc;">
                <h3 style="font-family:'Georgia',serif;font-size:13px;font-weight:700;color:#1a2f4e;margin:0;">Sample Details</h3>
            </div>
            <dl style="padding:0 20px;margin:0;">
                <div style="display:flex;justify-content:space-between;padding:10px 0;border-bottom:1px solid #f1f5f9;">
                    <dt style="font-size:12px;color:#64748b;">Sample Code</dt>
                    <dd style="font-family:monospace;font-weight:700;color:#1a2f4e;font-size:13px;margin:0;">{{ $sample->sample_code }}</dd>
                </div>
                <div style="display:flex;justify-content:space-between;padding:10px 0;border-bottom:1px solid #f1f5f9;">
                    <dt style="font-size:12px;color:#64748b;">Common Name</dt>
                    <dd style="font-weight:600;color:#1e293b;font-size:13px;margin:0;">{{ $sample->common_name }}</dd>
                </div>
                @if($sample->scientific_name)
                <div style="display:flex;justify-content:space-between;padding:10px 0;border-bottom:1px solid #f1f5f9;">
                    <dt style="font-size:12px;color:#64748b;">Scientific Name</dt>
                    <dd style="font-style:italic;color:#374151;font-size:13px;margin:0;">{{ $sample->scientific_name }}</dd>
                </div>
                @endif
                <div style="display:flex;justify-content:space-between;padding:10px 0;">
                    <dt style="font-size:12px;color:#64748b;">Quantity</dt>
                    <dd style="color:#374151;font-size:13px;margin:0;">{{ $sample->quantity }} {{ $sample->quantity_unit }}</dd>
                </div>
            </dl>
        </div>

        {{-- Assessment Criteria --}}
        <div style="background:#fff;border:1px solid #e2e8f0;border-radius:4px;overflow:hidden;margin-bottom:16px;">
            <div style="padding:12px 20px;border-bottom:1px solid #e2e8f0;background:#f8fafc;">
                <h3 style="font-family:'Georgia',serif;font-size:13px;font-weight:700;color:#1a2f4e;margin:0;">Assessment Results</h3>
            </div>
            <div style="padding:16px 20px;">
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
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:6px;margin-bottom:16px;">
                    @foreach($criteria as $label => $passed)
                        <div style="display:flex;align-items:center;gap:8px;font-size:13px;padding:4px 0;">
                            @if($passed)
                                <svg style="width:16px;height:16px;flex-shrink:0;color:#16a34a;" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/>
                                </svg>
                                <span style="color:#374151;">{{ $label }}</span>
                            @else
                                <svg style="width:16px;height:16px;flex-shrink:0;color:#dc2626;" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1-9a1 1 0 112 0v4a1 1 0 11-2 0V9zm1-5a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd"/>
                                </svg>
                                <span style="color:#dc2626;font-weight:700;">{{ $label }} — FAILED</span>
                            @endif
                        </div>
                    @endforeach
                </div>

                @if($assessment->rejection_reason)
                    <div style="background:#fef2f2;border:1px solid #fecaca;border-left:4px solid #dc2626;border-radius:4px;padding:10px 14px;">
                        <p style="font-size:10px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#991b1b;margin:0 0 4px;">Reason for Rejection</p>
                        <p style="font-size:13px;color:#7f1d1d;margin:0;">{{ $assessment->rejection_reason }}</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Decision Form --}}
        <div style="background:#fff;border:1px solid #e2e8f0;border-radius:4px;overflow:hidden;"
             x-data="{ decision: '' }">
            <div style="padding:12px 20px;border-bottom:1px solid #e2e8f0;background:#f8fafc;">
                <h3 style="font-family:'Georgia',serif;font-size:13px;font-weight:700;color:#1a2f4e;margin:0;">Your Decision</h3>
                <p style="font-size:11px;color:#64748b;margin:4px 0 0;">Please select one option and confirm.</p>
            </div>
            <div style="padding:20px;">

                <form method="POST" action="{{ route('client.consent.store', $token) }}">
                    @csrf

                    {{-- Option A --}}
                    <label style="display:flex;align-items:flex-start;gap:16px;padding:16px;border-radius:3px;cursor:pointer;margin-bottom:10px;"
                           :style="decision === 'consent_to_proceed' ? 'border:2px solid #b8922a;background:#fffbeb;' : 'border:2px solid #e2e8f0;background:#fff;'">
                        <input type="radio" name="decision" value="consent_to_proceed"
                               x-model="decision"
                               style="margin-top:2px;flex-shrink:0;accent-color:#b8922a;">
                        <div>
                            <p style="font-size:13px;font-weight:700;color:#1a2f4e;margin:0 0 4px;">Proceed with Testing</p>
                            <p style="font-size:12px;color:#64748b;margin:0;line-height:1.5;">
                                I acknowledge the assessment findings but request that testing proceeds.
                                I understand results will include a note about the sample condition.
                            </p>
                        </div>
                    </label>

                    {{-- Option B --}}
                    <label style="display:flex;align-items:flex-start;gap:16px;padding:16px;border-radius:3px;cursor:pointer;margin-bottom:20px;"
                           :style="decision === 'confirm_rejection' ? 'border:2px solid #dc2626;background:#fef2f2;' : 'border:2px solid #e2e8f0;background:#fff;'">
                        <input type="radio" name="decision" value="confirm_rejection"
                               x-model="decision"
                               style="margin-top:2px;flex-shrink:0;accent-color:#dc2626;">
                        <div>
                            <p style="font-size:13px;font-weight:700;color:#1a2f4e;margin:0 0 4px;">Cancel Submission</p>
                            <p style="font-size:12px;color:#64748b;margin:0;line-height:1.5;">
                                I accept the rejection. I will arrange to submit a new sample.
                                No testing will be conducted on this sample.
                            </p>
                        </div>
                    </label>

                    <button type="submit"
                            x-bind:disabled="!decision"
                            x-bind:style="!decision
                                ? 'background:#f1f5f9;color:#94a3b8;cursor:not-allowed;border:1px solid #e2e8f0;'
                                : (decision === 'consent_to_proceed'
                                    ? 'background:#b8922a;color:#fff;border:none;cursor:pointer;'
                                    : 'background:#dc2626;color:#fff;border:none;cursor:pointer;')"
                            style="width:100%;padding:12px;font-size:13px;font-weight:700;border-radius:3px;letter-spacing:.04em;"
                            onclick="return confirm('Are you sure? This decision cannot be changed.')">
                        <span x-text="!decision
                            ? 'Please select an option above'
                            : (decision === 'consent_to_proceed'
                                ? 'Confirm: Proceed with Testing'
                                : 'Confirm: Cancel Submission')">
                        </span>
                    </button>
                </form>

                <p style="font-size:11px;color:#94a3b8;text-align:center;margin:16px 0 0;">
                    This link expires {{ $assessment->consent_token_expires_at?->format('d M Y \a\t H:i') }}.
                    Contact <a href="mailto:{{ config('mail.from.address') }}" style="color:#1a2f4e;text-decoration:underline;">{{ config('mail.from.address') }}</a> if you need help.
                </p>
            </div>
        </div>

    </div>

    @vite(['resources/js/app.js'])
</body>
</html>