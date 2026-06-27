{{-- resources/views/kstl/reception/dashboard.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <div style="position:relative;overflow:hidden;background:linear-gradient(135deg,#0f2240 0%,#1a2f4e 60%,#1e3a5f 100%);margin:-1px;">
            <div style="position:absolute;inset:0;opacity:.04;background-image:repeating-linear-gradient(45deg,#fff 0,#fff 1px,transparent 0,transparent 50%);background-size:12px 12px;pointer-events:none;"></div>
            <div style="position:absolute;top:0;left:0;right:0;height:3px;background:linear-gradient(90deg,#1a2f4e,#b8922a 30%,#b8922a 70%,#1a2f4e);"></div>
            <div style="max-width:80rem;margin:0 auto;padding:28px 2rem;position:relative;">
                <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;">
                    <div style="display:flex;align-items:center;gap:20px;">
                        <img src="{{ asset('images/mfor-logo.png') }}" alt="MFOR" style="filter:brightness(0) invert(1);opacity:.92;width:56px;height:56px;flex-shrink:0;">
                        <div>
                            <p style="font-size:9px;font-weight:700;letter-spacing:.18em;text-transform:uppercase;color:#b8922a;margin:0 0 4px;">Reception Portal</p>
                            <h1 style="font-family:'Georgia',serif;font-size:22px;font-weight:700;color:#fff;margin:0 0 6px;line-height:1.2;">Reception Dashboard</h1>
                            <p style="font-size:12px;color:#94a3b8;margin:0;">Welcome back, {{ auth()->user()->first_name }} &mdash; {{ now()->format('l, d F Y') }}</p>
                        </div>
                    </div>
                    <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                        @php
                            $awaitingAction = $pending->count();
                            $urgentPending  = $pending->whereIn('priority', ['urgent', 'emergency'])->count();
                        @endphp
                        @if($urgentPending > 0)
                            <span style="display:inline-flex;align-items:center;gap:6px;padding:5px 12px;background:#dc2626;color:#fff;font-size:10px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;border-radius:20px;">
                                {{ $urgentPending }} urgent
                            </span>
                        @endif
                        <span style="display:inline-flex;align-items:center;gap:6px;padding:5px 12px;background:rgba(255,255,255,.12);color:#e2e8f0;font-size:10px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;border-radius:20px;">
                            {{ $awaitingAction }} awaiting action
                        </span>
                        <span style="display:inline-flex;align-items:center;gap:6px;padding:5px 12px;background:rgba(255,255,255,.12);color:#e2e8f0;font-size:10px;font-weight:700;letter-spacing:.08em;border-radius:20px;">
                            <span style="width:7px;height:7px;background:#0d9488;border-radius:50%;display:inline-block;"></span>
                            Reception
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    @push('styles')
    <style>
        .page-hdr { padding: 0 !important; }
        .page-hdr-inner { max-width: 100% !important; padding: 0 !important; }
        .app-main { padding-left:0 !important; padding-right:0 !important; padding-top:0 !important; max-width:100% !important; }
        :root {
            --gov-navy:#1a2f4e; --gov-gold:#b8922a; --gov-teal:#0d9488;
            --gov-text:#1f2937; --gov-muted:#6b7280; --gov-border:#e2e8f0;
        }
        .gov-stat { background:#fff; border:1px solid var(--gov-border); border-left:4px solid var(--gov-gold); border-radius:4px; padding:18px 20px; text-decoration:none; display:block; transition:border-left-color .15s,box-shadow .15s; }
        .gov-stat:hover { border-left-color:var(--gov-navy); box-shadow:0 2px 12px rgba(0,0,0,.08); }
        .gov-stat-label { font-size:9px; font-weight:700; letter-spacing:.16em; text-transform:uppercase; color:var(--gov-muted); margin-bottom:10px; display:flex; align-items:center; justify-content:space-between; }
        .gov-stat-num { font-family:'Georgia',serif; font-size:36px; font-weight:700; color:var(--gov-navy); line-height:1; }
        .gov-stat-sub { font-size:11px; color:var(--gov-muted); margin-top:5px; }
        .gov-section-label { font-size:9px; font-weight:700; letter-spacing:.18em; text-transform:uppercase; color:#9ca3af; margin-bottom:12px; }
        .gov-card-hdr { padding:16px 24px; border-bottom:2px solid var(--gov-gold); display:flex; align-items:center; justify-content:space-between; }
        .gov-card-hdr h3 { font-family:'Georgia',serif; font-size:15px; font-weight:700; color:var(--gov-navy); margin:0; }
    </style>
    @endpush

    <div style="background:#f1f5f9;min-height:100vh;padding:52px 0 56px;">
        <div style="max-width:80rem;margin:0 auto;padding:0 2rem;display:flex;flex-direction:column;gap:24px;">

            {{-- Flash Messages --}}
            @foreach(['success' => ['#f0fdf4','#22c55e','#166534'], 'error' => ['#fef2f2','#ef4444','#991b1b'], 'info' => ['#eff6ff','#3b82f6','#1e40af'], 'warning' => ['#fffbeb','#f59e0b','#92400e']] as $type => [$bg,$border,$text])
                @if(session($type))
                    <div style="background:{{ $bg }};border-left:4px solid {{ $border }};padding:12px 18px;border-radius:0 4px 4px 0;">
                        <p style="font-size:13px;color:{{ $text }};margin:0;">{{ session($type) }}</p>
                    </div>
                @endif
            @endforeach

            {{-- ── Summary Cards ─────────────────────────────────────────── --}}
            <div style="margin-top:24px;">
                <p class="gov-section-label">Submission Overview</p>
                <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:12px;">

                    <div class="gov-stat" style="border-left-color:#b8922a;">
                        <div class="gov-stat-label">Awaiting Receipt</div>
                        <p class="gov-stat-num">{{ $pending->where('status', 'submitted')->count() }}</p>
                        <p class="gov-stat-sub">Submitted by client</p>
                    </div>

                    <div class="gov-stat" style="border-left-color:#0d9488;">
                        <div class="gov-stat-label">Assessing</div>
                        <p class="gov-stat-num">{{ $pending->where('status', 'assessing')->count() }}</p>
                        <p class="gov-stat-sub">Sample assessment</p>
                    </div>

                    <div class="gov-stat" style="border-left-color:#dc2626;">
                        <div class="gov-stat-label">Rejected</div>
                        <p class="gov-stat-num">{{ $pending->where('status', 'rejected')->count() }}</p>
                        <p class="gov-stat-sub">Awaiting client decision</p>
                    </div>

                    <div class="gov-stat" style="border-left-color:#1a2f4e;">
                        <div class="gov-stat-label">Received Today</div>
                        <p class="gov-stat-num">{{ $receivedToday ?? 0 }}</p>
                        <p class="gov-stat-sub">Logged today</p>
                    </div>

                </div>
            </div>

            {{-- ── Emergency / Urgent callout ──────────────────────────────── --}}
            @php
                $urgent = $pending->whereIn('priority', ['urgent', 'emergency'])
                                  ->sortBy(fn($s) => $s->priority === 'emergency' ? 0 : 1);
            @endphp
            @if($urgent->isNotEmpty())
                <div style="background:#fef2f2;border:1px solid #fecaca;border-left:4px solid #dc2626;border-radius:4px;padding:16px 20px;margin-bottom:24px;">
                    <div style="display:flex;align-items:flex-start;gap:12px;">
                        <svg style="width:18px;height:18px;color:#dc2626;flex-shrink:0;margin-top:2px;" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                        </svg>
                        <div style="flex:1;">
                            <p style="font-size:13px;font-weight:700;color:#991b1b;margin:0 0 8px;">
                                {{ $urgent->count() }} urgent / emergency submission{{ $urgent->count() > 1 ? 's' : '' }} require immediate attention
                            </p>
                            <div>
                                @foreach($urgent as $u)
                                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:4px;">
                                        <span style="font-size:12px;color:#dc2626;">
                                            <span style="font-family:monospace;font-weight:600;">{{ $u->reference_number }}</span>
                                            &mdash; {{ $u->client->company_name ?? '?' }}
                                            <span style="display:inline-flex;align-items:center;padding:2px 8px;border-radius:20px;font-size:10px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;background:#fecaca;color:#991b1b;margin-left:6px;">{{ $u->priority }}</span>
                                        </span>
                                        <a href="{{ route('reception.submissions.show', $u->id) }}"
                                           style="font-size:12px;color:#dc2626;font-weight:600;text-decoration:none;">
                                            Action &rarr;
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- ── Pending Submissions Queue ──────────────────────────────── --}}
            <div style="background:#fff;border:1px solid #e2e8f0;border-radius:4px;overflow:hidden;">
                <div class="gov-card-hdr">
                    <div>
                        <h3>Submissions Queue</h3>
                        <p style="font-size:11px;color:#94a3b8;margin:4px 0 0;">Submissions requiring reception action &mdash; most urgent first</p>
                    </div>
                    <span style="font-size:11px;color:#64748b;background:#f1f5f9;padding:4px 10px;border-radius:20px;border:1px solid #e2e8f0;">
                        {{ $pending->count() }} total
                    </span>
                </div>

                @if($pending->isEmpty())
                    <div style="padding:48px 24px;text-align:center;">
                        <svg style="width:40px;height:40px;color:#cbd5e1;margin:0 auto 12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p style="font-size:13px;font-weight:600;color:#94a3b8;margin:0 0 4px;">No submissions pending</p>
                        <p style="font-size:11px;color:#cbd5e1;margin:0;">All submissions have been processed.</p>
                    </div>
                @else
                    @php
                        // Triage order: emergency → urgent → routine, then oldest-waiting first.
                        $priorityRank = ['emergency' => 0, 'urgent' => 1, 'routine' => 2];
                        $queue = $pending->sortBy(fn($s) => [
                            $priorityRank[$s->priority ?? 'routine'] ?? 2,
                            optional($s->submitted_at ?? $s->created_at)->timestamp,
                        ])->values();
                    @endphp
                    <div style="overflow-x:auto;">
                        <table style="width:100%;border-collapse:collapse;">
                            <thead>
                                <tr style="background:#1a2f4e;">
                                    <th style="padding:10px 16px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">Reference</th>
                                    <th style="padding:10px 16px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">Client</th>
                                    <th style="padding:10px 16px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">Sample Type</th>
                                    <th style="padding:10px 16px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">Submitted</th>
                                    <th style="padding:10px 16px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">Priority</th>
                                    <th style="padding:10px 16px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">Status</th>
                                    <th style="padding:10px 16px;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($queue as $submission)
                                    @php
                                        $isEmergency = ($submission->priority ?? '') === 'emergency';
                                        $isUrgent    = ($submission->priority ?? '') === 'urgent';
                                        $rowBg       = $isEmergency ? '#fff5f5'
                                                     : ($isUrgent  ? '#fffbeb'
                                                                   : ($loop->even ? '#f8fafc' : '#fff'));
                                        $accentBorder = $isEmergency ? 'border-left:3px solid #dc2626;'
                                                      : ($isUrgent  ? 'border-left:3px solid #d97706;'
                                                                    : 'border-left:3px solid transparent;');
                                    @endphp
                                    <tr style="border-bottom:1px solid #f1f5f9;background:{{ $rowBg }};">

                                        {{-- Reference (with priority accent bar) --}}
                                        <td style="padding:12px 16px;font-size:13px;color:#374151;{{ $accentBorder }}">
                                            <span style="font-family:monospace;font-size:12px;font-weight:600;color:#374151;">
                                                {{ $submission->reference_number }}
                                            </span>
                                        </td>

                                        {{-- Client --}}
                                        <td style="padding:12px 16px;font-size:13px;color:#374151;">
                                            <p style="font-size:13px;font-weight:600;color:#1e293b;margin:0 0 2px;">
                                                {{ $submission->client->company_name ?? '—' }}
                                            </p>
                                            <p style="font-size:11px;color:#94a3b8;margin:0;">
                                                {{ $submission->client->responsible_officer_name ?? '' }}
                                            </p>
                                        </td>

                                        {{-- Samples --}}
                                        <td style="padding:12px 16px;font-size:13px;color:#374151;">
                                            @if($submission->sample_items && count($submission->sample_items))
                                                @php $items = $submission->sample_items; @endphp
                                                <p style="font-size:13px;font-weight:600;color:#1e293b;margin:0 0 2px;">{{ $items[0]['name'] ?? '—' }}</p>
                                                @if(count($items) > 1)
                                                    <p style="font-size:11px;color:#0d9488;margin:0;">+{{ count($items) - 1 }} more sample{{ count($items) - 1 > 1 ? 's' : '' }}</p>
                                                @elseif(!empty($items[0]['type']))
                                                    <p style="font-size:11px;color:#94a3b8;text-transform:capitalize;margin:0;">{{ $items[0]['type'] }}</p>
                                                @endif
                                            @elseif($submission->sample_name)
                                                <p style="font-size:13px;color:#1e293b;margin:0 0 2px;">{{ $submission->sample_name }}</p>
                                                @if($submission->sample_type)
                                                    <p style="font-size:11px;color:#94a3b8;text-transform:capitalize;margin:0;">{{ $submission->sample_type }}</p>
                                                @endif
                                            @else
                                                <span style="font-size:13px;color:#94a3b8;">&mdash;</span>
                                            @endif
                                        </td>

                                        {{-- Submitted --}}
                                        <td style="padding:12px 16px;font-size:13px;color:#374151;">
                                            <p style="font-size:12px;color:#374151;margin:0 0 2px;">
                                                {{ $submission->submitted_at?->format('d M Y') ?? $submission->created_at->format('d M Y') }}
                                            </p>
                                            @php
                                                $daysWaiting = (int) floor(abs($submission->submitted_at?->diffInDays(now()) ?? 0));
                                            @endphp
                                            @if($daysWaiting >= 2)
                                                <span style="font-size:11px;color:#d97706;display:inline-flex;align-items:center;gap:3px;">
                                                    {{ $daysWaiting }} days waiting
                                                </span>
                                            @else
                                                <p style="font-size:11px;color:#94a3b8;margin:0;">{{ $submission->submitted_at?->diffForHumans() ?? '' }}</p>
                                            @endif
                                        </td>

                                        {{-- Priority --}}
                                        <td style="padding:12px 16px;">
                                            <x-kstl.priority-badge :priority="$submission->priority" />
                                        </td>

                                        {{-- Status --}}
                                        <td style="padding:12px 16px;">
                                            <x-kstl.status-badge :status="$submission->status" />
                                        </td>

                                        {{-- Actions --}}
                                        <td style="padding:12px 16px;text-align:right;">
                                            @if($submission->status === 'submitted')
                                                <a href="{{ route('reception.submissions.show', $submission->id) }}"
                                                   style="display:inline-flex;align-items:center;gap:6px;padding:6px 14px;background:#1a2f4e;color:#fff;font-size:11px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;border-radius:3px;text-decoration:none;">
                                                    Receive
                                                </a>
                                            @elseif($submission->status === 'received')
                                                <a href="{{ route('reception.submissions.assess', $submission->id) }}"
                                                   style="display:inline-flex;align-items:center;gap:6px;padding:6px 14px;background:#b8922a;color:#fff;font-size:11px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;border-radius:3px;text-decoration:none;">
                                                    Assess
                                                </a>
                                            @elseif($submission->status === 'accepted')
                                                <div style="display:flex;align-items:center;gap:6px;justify-content:flex-end;">
                                                    <a href="{{ route('reception.submissions.show', $submission->id) }}"
                                                       style="display:inline-flex;align-items:center;gap:6px;padding:6px 14px;background:#fff;color:#1a2f4e;font-size:11px;font-weight:700;letter-spacing:.06em;border:1px solid #1a2f4e;border-radius:3px;text-decoration:none;">
                                                        View
                                                    </a>
                                                    <form method="POST"
                                                          action="{{ route('reception.submissions.send-to-testing', $submission->id) }}"
                                                          onsubmit="return confirm('Send all {{ count($submission->sample_items ?? []) ?: 1 }} sample(s) to the testing queue?')">
                                                        @csrf
                                                        <button type="submit"
                                                                style="display:inline-flex;align-items:center;gap:6px;padding:6px 14px;background:#0d9488;color:#fff;font-size:11px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;border-radius:3px;border:none;cursor:pointer;">
                                                            Send to Testing
                                                        </button>
                                                    </form>
                                                </div>
                                            @elseif($submission->status === 'consent_to_proceed')
                                                <div style="display:flex;align-items:center;gap:6px;justify-content:flex-end;">
                                                    <a href="{{ route('reception.submissions.show', $submission->id) }}"
                                                       style="display:inline-flex;align-items:center;gap:6px;padding:6px 14px;background:#fff;color:#1a2f4e;font-size:11px;font-weight:700;letter-spacing:.06em;border:1px solid #1a2f4e;border-radius:3px;text-decoration:none;">
                                                        View
                                                    </a>
                                                    <form method="POST"
                                                          action="{{ route('reception.submissions.send-to-testing', $submission->id) }}"
                                                          onsubmit="return confirm('Send consented sample(s) to the testing queue?')">
                                                        @csrf
                                                        <button type="submit"
                                                                style="display:inline-flex;align-items:center;gap:6px;padding:6px 14px;background:#0d9488;color:#fff;font-size:11px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;border-radius:3px;border:none;cursor:pointer;">
                                                            Send to Testing
                                                        </button>
                                                    </form>
                                                </div>
                                            @elseif($submission->status === 'rejected')
                                                <a href="{{ route('reception.submissions.consent', $submission->id) }}"
                                                   style="display:inline-flex;align-items:center;gap:6px;padding:6px 14px;background:#dc2626;color:#fff;font-size:11px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;border-radius:3px;text-decoration:none;">
                                                    Get Consent
                                                </a>
                                            @else
                                                <a href="{{ route('reception.submissions.show', $submission->id) }}"
                                                   style="display:inline-flex;align-items:center;gap:6px;padding:6px 14px;background:#fff;color:#1a2f4e;font-size:11px;font-weight:700;letter-spacing:.06em;border:1px solid #1a2f4e;border-radius:3px;text-decoration:none;">
                                                    View
                                                </a>
                                            @endif
                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            {{-- ── Recently Processed ──────────────────────────────────── --}}
            <div style="background:#fff;border:1px solid #e2e8f0;border-radius:4px;overflow:hidden;">
                <div class="gov-card-hdr">
                    <div>
                        <h3>Recently Processed</h3>
                        <p style="font-size:11px;color:#94a3b8;margin:4px 0 0;">Submissions sent to testing or completed &mdash; last 20 records</p>
                    </div>
                    <span style="font-size:11px;color:#64748b;background:#f1f5f9;padding:4px 10px;border-radius:20px;border:1px solid #e2e8f0;">
                        {{ $processed->count() }} records
                    </span>
                </div>

                @if($processed->isEmpty())
                    <div style="padding:40px 24px;text-align:center;">
                        <svg style="width:32px;height:32px;color:#cbd5e1;margin:0 auto 10px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <p style="font-size:13px;color:#94a3b8;margin:0;">No processed submissions yet</p>
                    </div>
                @else
                    <div style="overflow-x:auto;">
                        <table style="width:100%;border-collapse:collapse;">
                            <thead>
                                <tr style="background:#1a2f4e;">
                                    <th style="padding:10px 16px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">Reference</th>
                                    <th style="padding:10px 16px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">Client</th>
                                    <th style="padding:10px 16px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">Sample</th>
                                    <th style="padding:10px 16px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">Priority</th>
                                    <th style="padding:10px 16px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">Status</th>
                                    <th style="padding:10px 16px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">Processed</th>
                                    <th style="padding:10px 16px;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($processed as $submission)
                                    <tr style="border-bottom:1px solid #f1f5f9;background:{{ $loop->even ? '#f8fafc' : '#fff' }};">
                                        <td style="padding:11px 16px;font-family:monospace;font-size:12px;color:#374151;font-weight:600;">
                                            {{ $submission->reference_number }}
                                        </td>
                                        <td style="padding:11px 16px;">
                                            <p style="font-size:12px;font-weight:600;color:#1e293b;margin:0 0 1px;">{{ $submission->client->company_name ?? '—' }}</p>
                                            <p style="font-size:11px;color:#94a3b8;margin:0;">{{ $submission->client->responsible_officer_name ?? '' }}</p>
                                        </td>
                                        <td style="padding:11px 16px;font-size:12px;color:#374151;">
                                            @if($submission->sample_items && count($submission->sample_items))
                                                @php $items = $submission->sample_items; @endphp
                                                <span style="font-weight:600;color:#1e293b;">{{ $items[0]['name'] ?? '—' }}</span>
                                                @if(count($items) > 1)
                                                    <span style="color:#0d9488;margin-left:4px;">+{{ count($items) - 1 }} more</span>
                                                @endif
                                            @else
                                                {{ $submission->sample_name ?? '—' }}
                                                @if($submission->sample_type)
                                                    <span style="color:#94a3b8;text-transform:capitalize;margin-left:4px;">&middot; {{ $submission->sample_type }}</span>
                                                @endif
                                            @endif
                                        </td>
                                        <td style="padding:11px 16px;">
                                            <x-kstl.priority-badge :priority="$submission->priority" />
                                        </td>
                                        <td style="padding:11px 16px;">
                                            <x-kstl.status-badge :status="$submission->status" />
                                        </td>
                                        <td style="padding:11px 16px;font-size:11px;color:#94a3b8;">
                                            {{ $submission->updated_at->diffForHumans() }}
                                            <p style="font-size:10px;color:#cbd5e1;margin:1px 0 0;">{{ $submission->updated_at->format('d M Y H:i') }}</p>
                                        </td>
                                        <td style="padding:11px 16px;text-align:right;">
                                            <a href="{{ route('reception.submissions.show', $submission->id) }}"
                                               style="display:inline-flex;align-items:center;gap:6px;padding:5px 12px;background:#fff;color:#1a2f4e;font-size:11px;font-weight:700;letter-spacing:.06em;border:1px solid #1a2f4e;border-radius:3px;text-decoration:none;">
                                                View
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

        </div>{{-- end flex column --}}
    </div>
</x-app-layout>
