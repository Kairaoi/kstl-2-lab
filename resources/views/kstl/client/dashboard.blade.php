{{-- resources/views/kstl/client/dashboard.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        {{-- Government hero banner --}}
        <div style="background:linear-gradient(135deg,#0f2240 0%,#1a2f4e 60%,#1e3a5f 100%); margin:-1px; padding:28px 2rem; position:relative; overflow:hidden;">
            {{-- Subtle pattern overlay --}}
            <div style="position:absolute;inset:0;opacity:.04;background-image:repeating-linear-gradient(45deg,#fff 0,#fff 1px,transparent 0,transparent 50%);background-size:12px 12px;pointer-events:none;"></div>

            {{-- Gold top accent bar --}}
            <div style="position:absolute;top:0;left:0;right:0;height:3px;background:linear-gradient(90deg,#1a2f4e,#b8922a 30%,#b8922a 70%,#1a2f4e);"></div>

            <div style="max-width:80rem;margin:0 auto;width:100%;display:flex;align-items:center;justify-content:space-between;gap:20px;flex-wrap:wrap;position:relative;">
                <div style="display:flex;align-items:center;gap:18px;">
                    {{-- MFOR logo --}}
                    <img src="{{ asset('images/mfor-logo.png') }}"
                         alt="Ministry of Fisheries &amp; Ocean Resources"
                         style="width:56px;height:56px;object-fit:contain;filter:brightness(0) invert(1);opacity:.92;">

                    <div>
                        <p style="font-size:8.5px;font-weight:700;letter-spacing:.22em;text-transform:uppercase;color:#b8922a;margin-bottom:5px;">
                            Client &nbsp;·&nbsp; Seafood Toxicology Laboratory
                        </p>
                        <h1 style="font-family:'Georgia',serif;font-size:22px;font-weight:700;color:#ffffff;line-height:1.2;margin:0;">
                            Welcome back, {{ $user->first_name }}
                        </h1>
                        @if($client)
                            <p style="font-size:11px;color:#93c5fd;margin-top:4px;">
                                {{ $client->company_name }}
                            </p>
                        @endif
                    </div>
                </div>

                <div style="text-align:right;">
                    <p style="font-size:9px;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:#64748b;margin-bottom:4px;">Today</p>
                    <p style="font-size:13px;font-weight:600;color:#e2e8f0;">{{ now()->format('d F Y') }}</p>
                    @if($client && $client->service_agreement_signed_at)
                        <span style="display:inline-flex;align-items:center;gap:5px;margin-top:6px;background:rgba(16,185,129,.15);border:1px solid rgba(16,185,129,.3);border-radius:4px;padding:3px 10px;font-size:9px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:#6ee7b7;">
                            <span style="width:5px;height:5px;background:#34d399;border-radius:50%;"></span>
                            Authorised Client
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </x-slot>

    @push('styles')
    <style>
        /* Remove page-hdr centering so the hero spans the full nav width */
        .page-hdr { padding: 0 !important; position: static !important; }
        .page-hdr-inner { max-width: 100% !important; padding: 0 !important; }
        /* Remove app-main padding — dashboard handles its own layout */
        .app-main { padding-left: 0 !important; padding-right: 0 !important;
                    padding-top: 0 !important; max-width: 100% !important; }
        /* ── Gov palette ─────────────────────────────────── */
        :root {
            --gov-navy:   #1a2f4e;
            --gov-gold:   #b8922a;
            --gov-teal:   #0d9488;
            --gov-text:   #1f2937;
            --gov-muted:  #6b7280;
            --gov-light:  #f8fafc;
            --gov-border: #e2e8f0;
        }

        /* ── Stat cards ──────────────────────────────────── */
        .gov-stat {
            background: #fff;
            border: 1px solid var(--gov-border);
            border-left: 4px solid var(--gov-gold);
            border-radius: 4px;
            padding: 18px 20px;
            text-decoration: none;
            display: block;
            transition: border-left-color .15s, box-shadow .15s;
        }
        .gov-stat:hover {
            border-left-color: var(--gov-navy);
            box-shadow: 0 2px 12px rgba(0,0,0,.08);
        }
        .gov-stat-label {
            font-size: 9px; font-weight: 700; letter-spacing: .16em;
            text-transform: uppercase; color: var(--gov-muted); margin-bottom: 10px;
            display: flex; align-items: center; justify-content: space-between;
        }
        .gov-stat-num {
            font-family: 'Georgia', serif; font-size: 36px; font-weight: 700;
            color: var(--gov-navy); line-height: 1;
        }
        .gov-stat-sub {
            font-size: 11px; color: var(--gov-muted); margin-top: 5px;
        }

        /* ── Section heading ─────────────────────────────── */
        .gov-heading {
            font-family: 'Georgia', serif; font-size: 15px; font-weight: 700;
            color: var(--gov-navy); padding-bottom: 10px;
            border-bottom: 2px solid var(--gov-gold);
            margin-bottom: 16px; display: flex; align-items: center;
            justify-content: space-between;
        }

        /* ── Action list ─────────────────────────────────── */
        .gov-action {
            display: flex; align-items: center; gap: 14px;
            padding: 14px 16px; border: 1px solid var(--gov-border);
            border-left: 3px solid transparent; background: #fff;
            text-decoration: none; transition: border-left-color .15s, background .15s;
            border-radius: 3px; margin-bottom: 6px;
        }
        .gov-action:hover { border-left-color: var(--gov-gold); background: #fafaf9; }
        .gov-action-icon {
            width: 36px; height: 36px; border-radius: 4px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .gov-action-primary {
            background: var(--gov-navy); border-left-color: var(--gov-navy);
        }
        .gov-action-primary:hover { background: #0f2240; border-left-color: #b8922a; }
        .gov-action-primary .gov-action-text { color: #fff; }
        .gov-action-primary .gov-action-sub  { color: #93c5fd; }
        .gov-action-text { font-size: 13px; font-weight: 600; color: var(--gov-text); }
        .gov-action-sub  { font-size: 10px; color: var(--gov-muted); margin-top: 1px; }
        .gov-action-arrow { margin-left: auto; color: var(--gov-muted); flex-shrink: 0; }
        .gov-action-primary .gov-action-arrow { color: #93c5fd; }

        /* ── Info card ───────────────────────────────────── */
        .gov-card {
            background: #fff; border: 1px solid var(--gov-border);
            border-radius: 4px; overflow: hidden;
        }
        .gov-card-body { padding: 20px 24px; }
        .gov-field-label {
            font-size: 9px; font-weight: 700; letter-spacing: .14em;
            text-transform: uppercase; color: var(--gov-muted);
            margin-bottom: 3px;
        }
        .gov-field-val { font-size: 13px; color: var(--gov-text); font-weight: 500; }

        /* ── Status badges ───────────────────────────────── */
        .gov-badge-green {
            display: inline-flex; align-items: center; gap: 5px;
            background: #f0fdf4; border: 1px solid #bbf7d0;
            color: #166534; border-radius: 3px; padding: 3px 10px;
            font-size: 10px; font-weight: 700; letter-spacing: .06em;
        }
        .gov-badge-amber {
            display: inline-flex; align-items: center; gap: 5px;
            background: #fffbeb; border: 1px solid #fde68a;
            color: #92400e; border-radius: 3px; padding: 3px 10px;
            font-size: 10px; font-weight: 700; letter-spacing: .06em;
        }

        /* ── Notice banners ──────────────────────────────── */
        .gov-notice {
            border-left: 4px solid; padding: 14px 18px; border-radius: 0 4px 4px 0;
            display: flex; align-items: flex-start; gap: 12px;
        }
        .gov-notice-blue   { background: #eff6ff; border-color: #3b82f6; }
        .gov-notice-yellow { background: #fffbeb; border-color: #f59e0b; }
        .gov-notice-red    { background: #fef2f2; border-color: #ef4444; }
        .gov-notice-green  { background: #f0fdf4; border-color: #22c55e; }

        /* ── Disabled action ─────────────────────────────── */
        .gov-action-disabled {
            display: flex; align-items: center; gap: 14px;
            padding: 14px 16px; border: 1px solid #f1f5f9;
            border-left: 3px solid #e2e8f0; background: #fafafa;
            border-radius: 3px; margin-bottom: 6px; cursor: not-allowed;
            opacity: .55;
        }
    </style>
    @endpush

    <div style="background:#f1f5f9;min-height:100vh;padding:0 0 56px;">
        <div style="max-width:80rem;margin:0 auto;padding:0 2rem;display:flex;flex-direction:column;gap:24px;">

            {{-- ── Flash messages ─────────────────────────────────────────────── --}}
            @foreach(['success' => ['#f0fdf4','#22c55e','#166534'], 'info' => ['#eff6ff','#3b82f6','#1e40af'], 'warning' => ['#fffbeb','#f59e0b','#92400e']] as $type => [$bg,$border,$text])
                @if(session($type))
                    <div style="background:{{ $bg }};border-left:4px solid {{ $border }};padding:12px 18px;border-radius:0 4px 4px 0;">
                        <p style="font-size:13px;color:{{ $text }};">{{ session($type) }}</p>
                    </div>
                @endif
            @endforeach

            {{-- ── Pending consents ─────────────────────────────────────────────── --}}
            @if(isset($pendingConsents) && $pendingConsents->isNotEmpty())
                <div class="gov-card" style="border-left:4px solid #ef4444;">
                    <div style="background:#fef2f2;padding:12px 20px;border-bottom:1px solid #fee2e2;display:flex;align-items:center;gap:10px;">
                        <svg style="width:18px;height:18px;color:#dc2626;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                        </svg>
                        <p style="font-size:13px;font-weight:700;color:#991b1b;">
                            Action Required — {{ $pendingConsents->count() }} Sample{{ $pendingConsents->count() > 1 ? 's' : '' }} Require{{ $pendingConsents->count() === 1 ? 's' : '' }} Your Decision
                        </p>
                    </div>
                    @foreach($pendingConsents as $consent)
                        @php $sample = $consent->sample; $submission = $sample->submission; @endphp
                        <div style="padding:14px 20px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;border-bottom:1px solid #fee2e2;">
                            <div>
                                <p style="font-size:13px;font-weight:600;color:#7f1d1d;">{{ $sample->common_name }}</p>
                                <p style="font-size:11px;color:#b91c1c;margin-top:3px;">
                                    Ref: <span style="font-family:monospace;">{{ $submission->reference_number }}</span>
                                    @if($consent->rejection_reason)
                                        &nbsp;·&nbsp; "{{ $consent->rejection_reason }}"
                                    @endif
                                </p>
                            </div>
                            <a href="{{ route('client.consent.show', $consent->consent_token) }}"
                               style="display:inline-flex;align-items:center;gap:6px;background:#dc2626;color:#fff;padding:8px 16px;border-radius:3px;font-size:12px;font-weight:600;text-decoration:none;">
                                Review &amp; Decide →
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- ── Onboarding notices ───────────────────────────────────────────── --}}
            @if(! $client)
                <div class="gov-notice gov-notice-blue">
                    <svg style="width:18px;height:18px;color:#3b82f6;flex-shrink:0;margin-top:1px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div style="flex:1;">
                        <p style="font-size:13px;font-weight:700;color:#1e40af;">Step 1 of 2 — Complete Your Company Profile</p>
                        <p style="font-size:12px;color:#1d4ed8;margin-top:3px;">Provide your organisation's details before signing the service agreement.</p>
                    </div>
                    <a href="{{ route('client.profile.company.show') }}"
                       style="flex-shrink:0;display:inline-flex;align-items:center;gap:6px;background:#1d4ed8;color:#fff;padding:8px 18px;border-radius:3px;font-size:12px;font-weight:600;text-decoration:none;">
                        Complete Profile →
                    </a>
                </div>
            @elseif($client && ! $client->service_agreement_signed_at)
                <div class="gov-notice gov-notice-yellow">
                    <svg style="width:18px;height:18px;color:#d97706;flex-shrink:0;margin-top:1px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 13l6.586-6.586a2 2 0 112.828 2.828L11.828 15.828a2 2 0 01-1.414.586H9v-2a2 2 0 01.586-1.414z"/>
                    </svg>
                    <div style="flex:1;">
                        <p style="font-size:13px;font-weight:700;color:#92400e;">Step 2 of 2 — Sign the Service Agreement</p>
                        <p style="font-size:12px;color:#b45309;margin-top:3px;">Your company details are saved. Sign the agreement to unlock full access.</p>
                    </div>
                    <a href="{{ route('client.agreement.show') }}"
                       style="flex-shrink:0;display:inline-flex;align-items:center;gap:6px;background:#d97706;color:#fff;padding:8px 18px;border-radius:3px;font-size:12px;font-weight:600;text-decoration:none;">
                        Sign Agreement →
                    </a>
                </div>
            @endif

            {{-- ── Statistics ────────────────────────────────────────────────────── --}}
            <div style="margin-top:24px;">
                <p style="font-size:9px;font-weight:700;letter-spacing:.18em;text-transform:uppercase;color:#9ca3af;margin-bottom:12px;">Submission Overview</p>
                <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(190px,1fr));gap:12px;">

                    <a href="{{ route('client.submissions.index') }}"
                       class="gov-stat {{ !$client || !$client->service_agreement_signed_at ? 'pointer-events-none opacity-50' : '' }}">
                        <div class="gov-stat-label">
                            Submissions
                            <svg style="width:16px;height:16px;color:#3b82f6;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <p class="gov-stat-num">{{ $summary['total_submissions'] }}</p>
                        <p class="gov-stat-sub">Total submitted</p>
                    </a>

                    <a href="{{ route('client.submissions.index') }}"
                       class="gov-stat {{ !$client || !$client->service_agreement_signed_at ? 'pointer-events-none opacity-50' : '' }}"
                       style="border-left-color:#f59e0b;">
                        <div class="gov-stat-label">
                            Pending
                            <svg style="width:16px;height:16px;color:#f59e0b;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <p class="gov-stat-num">{{ $summary['pending_submissions'] }}</p>
                        <p class="gov-stat-sub">Awaiting processing</p>
                    </a>

                    <a href="{{ route('client.results.index') }}"
                       class="gov-stat {{ !$client || !$client->service_agreement_signed_at ? 'pointer-events-none opacity-50' : '' }}"
                       style="border-left-color:#0d9488;">
                        <div class="gov-stat-label">
                            Results
                            <svg style="width:16px;height:16px;color:#0d9488;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <p class="gov-stat-num">{{ $summary['results_ready'] }}</p>
                        <p class="gov-stat-sub">Ready to view</p>
                    </a>

                    <a href="{{ route('client.invoices.index') }}"
                       class="gov-stat {{ !$client || !$client->service_agreement_signed_at ? 'pointer-events-none opacity-50' : '' }}"
                       style="border-left-color:{{ $summary['unpaid_invoices'] > 0 ? '#ef4444' : '#22c55e' }};">
                        <div class="gov-stat-label">
                            Invoices
                            <svg style="width:16px;height:16px;color:{{ $summary['unpaid_invoices'] > 0 ? '#ef4444' : '#22c55e' }};" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <p class="gov-stat-num">{{ $summary['unpaid_invoices'] }}</p>
                        <p class="gov-stat-sub">{{ $summary['unpaid_invoices'] > 0 ? 'Unpaid / Overdue' : 'All settled' }}</p>
                    </a>

                </div>
            </div>

            {{-- ── Main row: Quick Actions + Company Details ────────────────────── --}}
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;align-items:start;margin-top:16px;">

                {{-- Quick Actions --}}
                <div class="gov-card">
                    <div style="padding:16px 20px;border-bottom:1px solid #e2e8f0;">
                        <h3 style="font-family:'Georgia',serif;font-size:15px;font-weight:700;color:#1a2f4e;border-bottom:2px solid #b8922a;padding-bottom:8px;margin:0;">Quick Actions</h3>
                    </div>
                    <div style="padding:16px 20px;">

                        @if(! $client)
                            <a href="{{ route('client.profile.company.show') }}" class="gov-action gov-action-primary">
                                <div class="gov-action-icon" style="background:rgba(255,255,255,.15);">
                                    <svg style="width:18px;height:18px;color:#fff;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                                <div><p class="gov-action-text">Complete Company Profile</p><p class="gov-action-sub">Required before submission</p></div>
                                <svg class="gov-action-arrow" style="width:16px;height:16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                            <div class="gov-action-disabled">
                                <div class="gov-action-icon" style="background:#f1f5f9;"><svg style="width:18px;height:18px;color:#94a3b8;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg></div>
                                <div><p class="gov-action-text">New Submission</p><p class="gov-action-sub">Complete profile first</p></div>
                            </div>

                        @elseif(! $client->service_agreement_signed_at)
                            <a href="{{ route('client.agreement.show') }}" class="gov-action gov-action-primary">
                                <div class="gov-action-icon" style="background:rgba(255,255,255,.15);">
                                    <svg style="width:18px;height:18px;color:#fff;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 13l6.586-6.586a2 2 0 112.828 2.828L11.828 15.828a2 2 0 01-1.414.586H9v-2a2 2 0 01.586-1.414z"/>
                                    </svg>
                                </div>
                                <div><p class="gov-action-text">Sign Service Agreement</p><p class="gov-action-sub">Required to unlock submissions</p></div>
                                <svg class="gov-action-arrow" style="width:16px;height:16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                            <a href="{{ route('client.profile.company.show') }}" class="gov-action">
                                <div class="gov-action-icon" style="background:#f8fafc;"><svg style="width:18px;height:18px;color:#64748b;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg></div>
                                <div><p class="gov-action-text">Edit Company Details</p><p class="gov-action-sub">Update your profile</p></div>
                                <svg class="gov-action-arrow" style="width:16px;height:16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                            <div class="gov-action-disabled">
                                <div class="gov-action-icon" style="background:#f1f5f9;"><svg style="width:18px;height:18px;color:#94a3b8;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg></div>
                                <div><p class="gov-action-text">New Submission</p><p class="gov-action-sub">Sign agreement first</p></div>
                            </div>

                        @else
                            <a href="{{ route('client.submissions.create') }}" class="gov-action gov-action-primary">
                                <div class="gov-action-icon" style="background:rgba(255,255,255,.15);">
                                    <svg style="width:18px;height:18px;color:#fff;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                </div>
                                <div><p class="gov-action-text">New Sample Submission</p><p class="gov-action-sub">Submit samples for testing</p></div>
                                <svg class="gov-action-arrow" style="width:16px;height:16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                            <a href="{{ route('client.results.index') }}" class="gov-action">
                                <div class="gov-action-icon" style="background:#f0fdf4;">
                                    <svg style="width:18px;height:18px;color:#16a34a;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <div><p class="gov-action-text">View Test Results</p><p class="gov-action-sub">Download certificates of analysis</p></div>
                                <svg class="gov-action-arrow" style="width:16px;height:16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                            <a href="{{ route('client.invoices.index') }}" class="gov-action">
                                <div class="gov-action-icon" style="background:#eff6ff;">
                                    <svg style="width:18px;height:18px;color:#3b82f6;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                </div>
                                <div><p class="gov-action-text">Pay Invoice</p><p class="gov-action-sub">View and settle outstanding fees</p></div>
                                <svg class="gov-action-arrow" style="width:16px;height:16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                            <a href="{{ route('client.complaints.create') }}" class="gov-action">
                                <div class="gov-action-icon" style="background:#faf5ff;">
                                    <svg style="width:18px;height:18px;color:#7c3aed;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
                                </div>
                                <div><p class="gov-action-text">Lodge a Complaint</p><p class="gov-action-sub">Submit formal feedback or concern</p></div>
                                <svg class="gov-action-arrow" style="width:16px;height:16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        @endif

                    </div>
                </div>

                {{-- Company Details --}}
                @if($client)
                <div class="gov-card">
                    <div style="padding:16px 20px;border-bottom:1px solid #e2e8f0;display:flex;align-items:center;justify-content:space-between;">
                        <h3 style="font-family:'Georgia',serif;font-size:15px;font-weight:700;color:#1a2f4e;border-bottom:2px solid #b8922a;padding-bottom:8px;margin:0;">Organisation Details</h3>
                        <a href="{{ route('client.profile.company.show') }}"
                           style="font-size:11px;color:#6b7280;text-decoration:none;border:1px solid #e2e8f0;padding:4px 10px;border-radius:3px;"
                           onmouseover="this.style.color='#1a2f4e'" onmouseout="this.style.color='#6b7280'">
                            Edit
                        </a>
                    </div>
                    <div style="padding:20px 24px;">

                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:16px;">
                            <div>
                                <p class="gov-field-label">Organisation</p>
                                <p class="gov-field-val">{{ $client->company_name }}</p>
                            </div>
                            @if($client->company_phone)
                            <div>
                                <p class="gov-field-label">Phone</p>
                                <p class="gov-field-val">{{ $client->company_phone }}</p>
                            </div>
                            @endif
                            @if($client->address)
                            <div style="grid-column:span 2;">
                                <p class="gov-field-label">Address</p>
                                <p class="gov-field-val">{{ $client->address }}</p>
                            </div>
                            @endif
                        </div>

                        {{-- Service Agreement --}}
                        <div style="border-top:1px solid #f1f5f9;padding-top:14px;">
                            <p class="gov-field-label" style="margin-bottom:10px;">Service Agreement</p>

                            @if($client->service_agreement_signed_at)
                                @php
                                    $expiresAt = $client->service_agreement_signed_at->addYear();
                                    $daysLeft  = now()->diffInDays($expiresAt, false);
                                @endphp
                                <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px;margin-bottom:10px;">
                                    <span class="gov-badge-green">
                                        <svg style="width:10px;height:10px;" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/></svg>
                                        Signed
                                    </span>
                                    <span style="font-size:11px;color:#9ca3af;">{{ $client->service_agreement_signed_at->format('d M Y') }}</span>
                                </div>

                                @if($daysLeft <= 0)
                                    <p style="font-size:11px;color:#dc2626;margin-bottom:8px;">âš  Agreement has expired. Contact the lab to renew.</p>
                                @elseif($daysLeft <= 30)
                                    <p style="font-size:11px;color:#d97706;margin-bottom:8px;">âš  Expires in {{ $daysLeft }} day{{ $daysLeft === 1 ? '' : 's' }}. Contact the lab to renew.</p>
                                @else
                                    <p style="font-size:11px;color:#9ca3af;margin-bottom:8px;">Valid until {{ $expiresAt->format('d M Y') }}</p>
                                @endif

                                <div style="display:flex;align-items:center;gap:12px;margin-bottom:10px;">
                                    <a href="{{ route('client.agreement.download') }}"
                                       style="display:inline-flex;align-items:center;gap:5px;font-size:11px;font-weight:600;color:#1d4ed8;text-decoration:none;">
                                        <svg style="width:13px;height:13px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        Download PDF
                                    </a>
                                    <span style="color:#e2e8f0;">|</span>
                                    <a href="{{ route('client.agreement.show') }}"
                                       style="font-size:11px;color:#6b7280;text-decoration:none;">View Agreement</a>
                                </div>

                                @if($client->director_signed_at)
                                    <div style="display:flex;align-items:center;gap:6px;font-size:11px;color:#16a34a;background:#f0fdf4;border:1px solid #bbf7d0;padding:6px 10px;border-radius:3px;">
                                        <svg style="width:13px;height:13px;" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/></svg>
                                        Countersigned by Director on {{ $client->director_signed_at->format('d M Y') }}
                                    </div>
                                @else
                                    <div style="display:flex;align-items:center;gap:6px;font-size:11px;color:#92400e;background:#fffbeb;border:1px solid #fde68a;padding:6px 10px;border-radius:3px;">
                                        <svg style="width:13px;height:13px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        Awaiting Director countersignature
                                    </div>
                                @endif

                            @else
                                <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px;">
                                    <span class="gov-badge-amber">
                                        <svg style="width:10px;height:10px;" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/></svg>
                                        Not Signed
                                    </span>
                                    <a href="{{ route('client.agreement.show') }}"
                                       style="font-size:11px;font-weight:600;color:#d97706;text-decoration:none;">
                                        Sign now →
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif

            </div>

        </div>
    </div>
</x-app-layout>
