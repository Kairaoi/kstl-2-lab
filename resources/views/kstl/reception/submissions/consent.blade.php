{{-- resources/views/kstl/reception/submissions/consent.blade.php --}}
{{--
    Shown when a submission status = 'rejected'.
    Reception contacts the client, records their decision:
      - consent_to_proceed  → sample moves to testing despite issues
      - confirm_rejection   → sample is formally rejected, no testing
--}}

<x-app-layout>
    <x-slot name="header">
        <div style="position:relative;overflow:hidden;background:linear-gradient(135deg,#0f2240 0%,#1a2f4e 60%,#1e3a5f 100%);margin:-1px;">
            <div style="height:3px;background:linear-gradient(90deg,#1a2f4e,#b8922a 30%,#b8922a 70%,#1a2f4e);"></div>
            <div style="max-width:80rem;margin:0 auto;padding:28px 2rem 32px;">
                <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;">
                    <div style="display:flex;align-items:center;gap:20px;">
                        <img src="{{ asset('images/mfor-logo.png') }}" alt="MFOR" style="filter:brightness(0) invert(1);opacity:.92;width:56px;height:56px;flex-shrink:0;">
                        <div>
                            <p style="font-size:9px;font-weight:700;letter-spacing:.18em;text-transform:uppercase;color:#b8922a;margin:0 0 4px;">Consent to Proceed</p>
                            <h1 style="font-family:'Georgia',serif;font-size:22px;font-weight:700;color:#fff;margin:0 0 6px;line-height:1.2;">Client Consent &mdash; {{ $submission->reference_number }}</h1>
                            <p style="font-size:12px;color:#94a3b8;margin:0;">Record client decision for rejected sample(s)</p>
                        </div>
                    </div>
                    <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                        <span style="display:inline-flex;align-items:center;padding:5px 12px;background:#dc2626;color:#fff;font-size:10px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;border-radius:20px;">
                            Awaiting Client Decision
                        </span>
                        <a href="{{ route('reception.dashboard') }}"
                           style="display:inline-flex;align-items:center;gap:8px;padding:8px 20px;background:rgba(255,255,255,.12);color:#e2e8f0;font-size:12px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;border-radius:3px;text-decoration:none;border:1px solid rgba(255,255,255,.2);">
                            &larr; Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    @push('styles')
    <style>
        .page-hdr { padding: 0 !important; position: static !important; }
        .page-hdr-inner { max-width: 100% !important; padding: 0 !important; }
        .app-main { padding-left:0 !important; padding-right:0 !important; padding-top:0 !important; max-width:100% !important; }
    </style>
    @endpush

    <div style="background:#f1f5f9;min-height:100vh;padding:0 0 56px;">
        <div style="max-width:80rem;margin:0 auto;padding:0 2rem;">

            {{-- Flash --}}
            @if(session('success'))
                <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-left:4px solid #16a34a;border-radius:4px;padding:12px 16px;margin-bottom:20px;font-size:13px;color:#166534;">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div style="background:#fef2f2;border:1px solid #fecaca;border-left:4px solid #dc2626;border-radius:4px;padding:12px 16px;margin-bottom:20px;font-size:13px;color:#991b1b;">{{ session('error') }}</div>
            @endif

            {{-- ── Instructions Banner ──────────────────────────────── --}}
            <div style="background:#fffbeb;border:1px solid #fde68a;border-left:4px solid #d97706;border-radius:4px;padding:16px 20px;margin-bottom:24px;">
                <div style="display:flex;align-items:flex-start;gap:12px;">
                    <svg style="width:18px;height:18px;color:#d97706;flex-shrink:0;margin-top:2px;" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <p style="font-size:13px;font-weight:700;color:#92400e;margin:0 0 4px;">Action Required &mdash; Contact Client</p>
                        <p style="font-size:13px;color:#78350f;margin:0;">
                            One or more samples failed the assessment. Contact
                            <span style="font-weight:700;">{{ $submission->client->responsible_officer_name ?? $submission->client->company_name }}</span>
                            at <span style="font-weight:700;">{{ $submission->client->company_phone ?? $submission->client->user->email ?? '—' }}</span>
                            to inform them of the rejection and record their decision below.
                        </p>
                    </div>
                </div>
            </div>

            <div style="display:grid;grid-template-columns:1fr 2fr;gap:24px;align-items:flex-start;">

                {{-- ── Left: Submission Summary ──────────────────────── --}}
                <div style="display:flex;flex-direction:column;gap:20px;">

                    {{-- Client contact card --}}
                    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:4px;overflow:hidden;">
                        <div style="padding:14px 20px;border-bottom:1px solid #e2e8f0;">
                            <h3 style="font-family:'Georgia',serif;font-size:14px;font-weight:700;color:#1a2f4e;margin:0;">Client Contact</h3>
                        </div>
                        <dl style="padding:16px 20px;display:flex;flex-direction:column;gap:12px;">
                            <div>
                                <dt style="font-size:10px;font-weight:600;letter-spacing:.07em;text-transform:uppercase;color:#64748b;margin-bottom:3px;">Company</dt>
                                <dd style="font-size:13px;font-weight:600;color:#1e293b;margin:0;">{{ $submission->client->company_name }}</dd>
                            </div>
                            <div>
                                <dt style="font-size:10px;font-weight:600;letter-spacing:.07em;text-transform:uppercase;color:#64748b;margin-bottom:3px;">Officer</dt>
                                <dd style="font-size:13px;color:#374151;margin:0;">{{ $submission->client->responsible_officer_name ?? '—' }}</dd>
                            </div>
                            <div>
                                <dt style="font-size:10px;font-weight:600;letter-spacing:.07em;text-transform:uppercase;color:#64748b;margin-bottom:3px;">Phone</dt>
                                <dd style="font-size:13px;color:#374151;margin:0;">{{ $submission->client->company_phone ?? '—' }}</dd>
                            </div>
                            <div>
                                <dt style="font-size:10px;font-weight:600;letter-spacing:.07em;text-transform:uppercase;color:#64748b;margin-bottom:3px;">Email</dt>
                                <dd style="font-size:13px;color:#374151;margin:0;word-break:break-all;">{{ $submission->client->user->email ?? '—' }}</dd>
                            </div>
                        </dl>
                    </div>

                    {{-- Submission ref --}}
                    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:4px;overflow:hidden;">
                        <div style="padding:14px 20px;border-bottom:1px solid #e2e8f0;">
                            <h3 style="font-family:'Georgia',serif;font-size:14px;font-weight:700;color:#1a2f4e;margin:0;">Submission</h3>
                        </div>
                        <dl style="padding:16px 20px;display:flex;flex-direction:column;gap:12px;">
                            <div>
                                <dt style="font-size:10px;font-weight:600;letter-spacing:.07em;text-transform:uppercase;color:#64748b;margin-bottom:3px;">Reference</dt>
                                <dd style="font-family:monospace;font-size:13px;font-weight:600;color:#1e293b;margin:0;">{{ $submission->reference_number }}</dd>
                            </div>
                            <div>
                                <dt style="font-size:10px;font-weight:600;letter-spacing:.07em;text-transform:uppercase;color:#64748b;margin-bottom:3px;">Sample</dt>
                                <dd style="font-size:13px;color:#374151;margin:0;">{{ $submission->sample_name }}</dd>
                            </div>
                            <div>
                                <dt style="font-size:10px;font-weight:600;letter-spacing:.07em;text-transform:uppercase;color:#64748b;margin-bottom:3px;">Submitted</dt>
                                <dd style="font-size:13px;color:#374151;margin:0;">
                                    {{ $submission->submitted_at?->format('d M Y') ?? '—' }}
                                </dd>
                            </div>
                            <div>
                                <dt style="font-size:10px;font-weight:600;letter-spacing:.07em;text-transform:uppercase;color:#64748b;margin-bottom:3px;">Priority</dt>
                                <dd style="margin:0;">
                                    @php
                                        $pc = ['routine' => 'background:#f1f5f9;color:#475569;', 'urgent' => 'background:#fffbeb;color:#92400e;', 'emergency' => 'background:#fef2f2;color:#991b1b;'];
                                    @endphp
                                    <span style="display:inline-flex;padding:3px 10px;border-radius:20px;font-size:10px;font-weight:700;letter-spacing:.08em;text-transform:capitalize;{{ $pc[$submission->priority ?? 'routine'] ?? 'background:#f1f5f9;color:#475569;' }}">
                                        {{ $submission->priority ?? 'Routine' }}
                                    </span>
                                </dd>
                            </div>
                        </dl>
                    </div>

                </div>

                {{-- ── Right: Rejected Samples + Consent Forms ──────── --}}
                <div style="display:flex;flex-direction:column;gap:20px;">

                    @foreach($samples as $sample)
                        @if($sample->status === 'rejected' && $sample->assessment)

                            <div style="background:#fff;border:1px solid #fecaca;border-radius:4px;overflow:hidden;">

                                {{-- Sample header --}}
                                <div style="padding:16px 24px;background:#fef2f2;border-bottom:1px solid #fecaca;display:flex;align-items:center;justify-content:space-between;">
                                    <div>
                                        <h3 style="font-size:14px;font-weight:700;color:#1e293b;margin:0 0 3px;">
                                            {{ $sample->sample_code }} &mdash; {{ $sample->common_name }}
                                        </h3>
                                        <p style="font-size:12px;color:#64748b;margin:0;">
                                            {{ $sample->quantity }} {{ $sample->quantity_unit }}
                                            @if($sample->scientific_name)
                                                &middot; <em>{{ $sample->scientific_name }}</em>
                                            @endif
                                        </p>
                                    </div>
                                    <span style="display:inline-flex;align-items:center;padding:3px 10px;border-radius:20px;font-size:10px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;background:#fecaca;color:#991b1b;">
                                        Rejected
                                    </span>
                                </div>

                                <div style="padding:20px 24px;display:flex;flex-direction:column;gap:20px;">

                                    {{-- Assessment summary --}}
                                    <div>
                                        <p style="font-size:10px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#64748b;margin:0 0 12px;">Assessment Results</p>
                                        @php
                                            $a = $sample->assessment;
                                            $criteria = [
                                                'Temperature'  => $a->temperature_ok,
                                                'Storage'      => $a->storage_ok,
                                                'Transport'    => $a->transport_ok,
                                                'Packaging'    => $a->packaging_ok,
                                                'Colour'       => $a->colour_ok,
                                                'Odour'        => $a->odour_ok,
                                                'Weight'       => $a->weight_ok,
                                            ];
                                        @endphp
                                        <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:8px;">
                                            @foreach($criteria as $label => $passed)
                                                <div style="display:flex;align-items:center;gap:6px;font-size:12px;">
                                                    @if($passed)
                                                        <svg style="width:14px;height:14px;color:#16a34a;flex-shrink:0;" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/>
                                                        </svg>
                                                        <span style="color:#374151;">{{ $label }}</span>
                                                    @else
                                                        <svg style="width:14px;height:14px;color:#dc2626;flex-shrink:0;" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1-9a1 1 0 112 0v4a1 1 0 11-2 0V9zm1-5a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd"/>
                                                        </svg>
                                                        <span style="color:#dc2626;font-weight:700;">{{ $label }}</span>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>

                                        @if($a->rejection_reason)
                                            <div style="margin-top:12px;background:#fef2f2;border-radius:3px;padding:10px 12px;">
                                                <p style="font-size:11px;font-weight:700;color:#dc2626;margin:0 0 4px;">Rejection Reason</p>
                                                <p style="font-size:13px;color:#991b1b;margin:0;">{{ $a->rejection_reason }}</p>
                                            </div>
                                        @endif

                                        @if($a->additional_observations)
                                            <div style="margin-top:8px;background:#f8fafc;border-radius:3px;padding:10px 12px;">
                                                <p style="font-size:11px;font-weight:700;color:#64748b;margin:0 0 4px;">Additional Observations</p>
                                                <p style="font-size:13px;color:#374151;margin:0;">{{ $a->additional_observations }}</p>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Client Decision --}}
                                    @if($a->client_decision)
                                        {{-- Already recorded --}}
                                        <div style="border-radius:4px;border:1px solid {{ $a->client_decision === 'consent_to_proceed' ? '#fed7aa' : '#e2e8f0' }};background:{{ $a->client_decision === 'consent_to_proceed' ? '#fff7ed' : '#f8fafc' }};padding:16px;">
                                            <div style="display:flex;align-items:center;justify-content:space-between;">
                                                <div>
                                                    <p style="font-size:13px;font-weight:700;color:{{ $a->client_decision === 'consent_to_proceed' ? '#9a3412' : '#374151' }};margin:0 0 3px;">
                                                        @if($a->client_decision === 'consent_to_proceed')
                                                            &#10003; Client consented to proceed despite rejection
                                                        @else
                                                            &#10007; Client confirmed rejection &mdash; no testing
                                                        @endif
                                                    </p>
                                                    <p style="font-size:11px;color:#94a3b8;margin:0;">
                                                        Recorded {{ $a->client_decision_at?->format('d M Y \a\t H:i') ?? '—' }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                    @else
                                        {{-- Send Email Button --}}
                                        <div style="padding:16px;background:#eff6ff;border:1px solid #bfdbfe;border-radius:4px;">
                                            <p style="font-size:13px;font-weight:700;color:#1e40af;margin:0 0 4px;">Notify Client by Email</p>
                                            <p style="font-size:12px;color:#64748b;margin:0 0 12px;">
                                                Send an email to <strong>{{ $submission->client->user->email ?? '—' }}</strong>
                                                with a secure link for the client to record their own decision.
                                            </p>
                                            @if($a->consent_notified_at)
                                                <div style="display:flex;align-items:center;justify-content:space-between;">
                                                    <span style="font-size:12px;color:#16a34a;font-weight:600;">
                                                        &#10003; Email sent {{ $a->consent_notified_at->format('d M Y \a\t H:i') }}
                                                    </span>
                                                    <form method="POST" action="{{ route('reception.assessments.notify', $a->id) }}">
                                                        @csrf
                                                        <button type="submit" style="font-size:12px;color:#1d4ed8;background:none;border:none;cursor:pointer;text-decoration:underline;">Resend</button>
                                                    </form>
                                                </div>
                                            @else
                                                <form method="POST" action="{{ route('reception.assessments.notify', $a->id) }}">
                                                    @csrf
                                                    <button type="submit"
                                                            style="display:inline-flex;align-items:center;gap:8px;padding:8px 20px;background:#1a2f4e;color:#fff;font-size:12px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;border-radius:3px;border:none;cursor:pointer;">
                                                        <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                                        </svg>
                                                        Send Email to Client
                                                    </button>
                                                </form>
                                            @endif
                                        </div>

                                        <div style="position:relative;margin:4px 0;">
                                            <div style="position:absolute;inset:0;display:flex;align-items:center;">
                                                <div style="width:100%;border-top:1px solid #e2e8f0;"></div>
                                            </div>
                                            <div style="position:relative;display:flex;justify-content:center;">
                                                <span style="background:#fff;padding:0 12px;font-size:11px;color:#94a3b8;">or record manually after phone/in-person contact</span>
                                            </div>
                                        </div>

                                        {{-- Consent form --}}
                                        <div>
                                            <p style="font-size:10px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#64748b;margin:0 0 12px;">
                                                Record Client Decision
                                            </p>

                                            <form method="POST"
                                                  action="{{ route('reception.assessments.consent', $a->id) }}"
                                                  x-data="{ decision: '' }">
                                                @csrf

                                                <div style="display:flex;flex-direction:column;gap:10px;margin-bottom:16px;">

                                                    {{-- Option 1: Consent to proceed --}}
                                                    <label style="display:flex;align-items:flex-start;gap:12px;padding:16px;border-radius:3px;border:2px solid transparent;cursor:pointer;transition:border-color .15s;"
                                                           :style="decision === 'consent_to_proceed'
                                                               ? 'border-color:#ea580c;background:#fff7ed;'
                                                               : 'border-color:#e2e8f0;background:#fff;'">
                                                        <input type="radio"
                                                               name="decision"
                                                               value="consent_to_proceed"
                                                               x-model="decision"
                                                               style="margin-top:2px;accent-color:#ea580c;width:16px;height:16px;">
                                                        <div>
                                                            <p style="font-size:13px;font-weight:700;color:#1e293b;margin:0 0 3px;">
                                                                Consent to Proceed
                                                            </p>
                                                            <p style="font-size:12px;color:#64748b;margin:0;">
                                                                Client acknowledges the issue but requests testing continues.
                                                                Results will include a note about the assessment findings.
                                                            </p>
                                                        </div>
                                                    </label>

                                                    {{-- Option 2: Confirm rejection --}}
                                                    <label style="display:flex;align-items:flex-start;gap:12px;padding:16px;border-radius:3px;border:2px solid transparent;cursor:pointer;transition:border-color .15s;"
                                                           :style="decision === 'confirm_rejection'
                                                               ? 'border-color:#dc2626;background:#fef2f2;'
                                                               : 'border-color:#e2e8f0;background:#fff;'">
                                                        <input type="radio"
                                                               name="decision"
                                                               value="confirm_rejection"
                                                               x-model="decision"
                                                               style="margin-top:2px;accent-color:#dc2626;width:16px;height:16px;">
                                                        <div>
                                                            <p style="font-size:13px;font-weight:700;color:#1e293b;margin:0 0 3px;">
                                                                Confirm Rejection
                                                            </p>
                                                            <p style="font-size:12px;color:#64748b;margin:0;">
                                                                Client accepts the rejection. Sample will not be tested.
                                                                Client will need to resubmit with a new sample.
                                                            </p>
                                                        </div>
                                                    </label>

                                                </div>

                                                <button type="submit"
                                                        x-bind:disabled="!decision"
                                                        :style="decision
                                                            ? (decision === 'consent_to_proceed'
                                                                ? 'background:#b8922a;color:#fff;cursor:pointer;'
                                                                : 'background:#dc2626;color:#fff;cursor:pointer;')
                                                            : 'background:#f1f5f9;color:#94a3b8;cursor:not-allowed;'"
                                                        style="width:100%;padding:10px;font-size:13px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;border-radius:3px;border:none;transition:background .15s;">
                                                    <span x-text="decision === 'consent_to_proceed'
                                                        ? 'Record: Consent to Proceed'
                                                        : (decision === 'confirm_rejection'
                                                            ? 'Record: Confirm Rejection'
                                                            : 'Select a decision above')">
                                                    </span>
                                                </button>

                                            </form>
                                        </div>
                                    @endif

                                </div>
                            </div>

                        @elseif($sample->status === 'accepted')
                            {{-- Show accepted samples briefly --}}
                            <div style="background:#fff;border:1px solid #bbf7d0;border-radius:4px;padding:16px 24px;display:flex;align-items:center;justify-content:space-between;">
                                <div>
                                    <p style="font-size:13px;font-weight:600;color:#1e293b;margin:0 0 2px;">
                                        {{ $sample->sample_code }} &mdash; {{ $sample->common_name }}
                                    </p>
                                    <p style="font-size:11px;color:#94a3b8;margin:0;">{{ $sample->quantity }} {{ $sample->quantity_unit }}</p>
                                </div>
                                <span style="display:inline-flex;align-items:center;padding:3px 10px;border-radius:20px;font-size:10px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;background:#dcfce7;color:#166534;">
                                    Accepted &#10003;
                                </span>
                            </div>
                        @endif
                    @endforeach

                    {{-- Actions --}}
                    <div style="display:flex;align-items:center;justify-content:space-between;padding-top:8px;padding-bottom:16px;">
                        <a href="{{ route('reception.dashboard') }}"
                           style="display:inline-flex;align-items:center;gap:8px;padding:8px 20px;background:#fff;color:#1a2f4e;font-size:12px;font-weight:700;letter-spacing:.06em;border:1px solid #1a2f4e;border-radius:3px;text-decoration:none;">
                            &larr; Back to Dashboard
                        </a>

                        @php
                            $pendingDecisions = $samples->filter(fn($s) =>
                                $s->status === 'rejected' && $s->assessment && !$s->assessment->client_decision
                            )->count();
                        @endphp

                        @if($pendingDecisions === 0)
                            @php
                                $hasConsent = $samples->contains(fn($s) =>
                                    $s->assessment && $s->assessment->client_decision === 'consent_to_proceed'
                                );
                            @endphp
                            @if($hasConsent && $submission->status !== 'testing')
                                <form method="POST"
                                      action="{{ route('reception.submissions.send-to-testing', $submission->id) }}">
                                    @csrf
                                    <button type="submit"
                                            onclick="return confirm('Send this submission to the testing queue?')"
                                            style="display:inline-flex;align-items:center;gap:8px;padding:8px 20px;background:#0d9488;color:#fff;font-size:12px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;border-radius:3px;border:none;cursor:pointer;">
                                        <svg style="width:16px;height:16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                        </svg>
                                        Send to Testing Queue
                                    </button>
                                </form>
                            @elseif($submission->status === 'testing')
                                <span style="display:inline-flex;align-items:center;gap:8px;font-size:13px;color:#0d9488;font-weight:700;">
                                    <svg style="width:16px;height:16px;" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/>
                                    </svg>
                                    Sent to testing queue
                                </span>
                            @else
                                <span style="display:inline-flex;align-items:center;gap:8px;font-size:13px;color:#16a34a;font-weight:700;">
                                    <svg style="width:16px;height:16px;" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/>
                                    </svg>
                                    All decisions recorded
                                </span>
                            @endif
                        @else
                            <span style="font-size:12px;color:#d97706;font-weight:600;">
                                {{ $pendingDecisions }} decision{{ $pendingDecisions !== 1 ? 's' : '' }} pending
                            </span>
                        @endif
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
