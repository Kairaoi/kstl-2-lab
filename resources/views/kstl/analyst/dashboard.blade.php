{{-- resources/views/kstl/analyst/dashboard.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        @php
            $activeCount  = $activeSubmissions->count();
            $flaggedTotal = $counts['flagged'] ?? 0;
        @endphp
        {{-- Navy hero banner --}}
        <div style="background:linear-gradient(135deg,#0f2240 0%,#1a2f4e 60%,#1e3a5f 100%); position:relative; overflow:hidden; margin:-1px;">
            {{-- dot pattern overlay --}}
            <div style="position:absolute; inset:0; background-image:radial-gradient(circle,rgba(255,255,255,.06) 1px,transparent 1px); background-size:20px 20px; pointer-events:none;"></div>
            {{-- gold accent stripe --}}
            <div style="position:absolute; top:0; left:0; right:0; height:3px; background:linear-gradient(90deg,#b8922a,#d4a843,#b8922a);"></div>
            <div style="max-width:80rem; margin:0 auto; padding:28px 2rem;">
                <div style="display:flex; align-items:center; justify-content:space-between; gap:24px; flex-wrap:wrap;">
                    <div style="display:flex; align-items:center; gap:20px;">
                        <img src="{{ asset('images/mfor-logo.png') }}" alt="MFOR"
                             style="filter:brightness(0) invert(1); opacity:.92; width:56px; height:56px; flex-shrink:0;">
                        <div>
                            <p style="font-size:10px; font-weight:700; letter-spacing:.18em; text-transform:uppercase; color:#b8922a; margin:0 0 4px;">
                                Analyst Portal
                            </p>
                            <h1 style="font-family:'Georgia',serif; font-size:24px; font-weight:700; color:#ffffff; margin:0; letter-spacing:.01em;">
                                {{ $user->first_name ?? auth()->user()->first_name ?? 'Analyst' }}
                            </h1>
                            <p style="font-size:12px; color:#94a3b8; margin:4px 0 0;">
                                {{ now()->format('l, d F Y') }}
                            </p>
                        </div>
                    </div>
                    <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
                        @if($flaggedTotal > 0)
                            <span style="display:inline-flex; align-items:center; gap:6px; font-size:11px; font-weight:700; color:#fbbf24; background:rgba(251,191,36,.12); border:1px solid rgba(251,191,36,.3); padding:5px 12px; border-radius:3px;">
                                {{ $flaggedTotal }} flagged
                            </span>
                        @endif
                        <span style="display:inline-flex; align-items:center; gap:6px; font-size:11px; font-weight:600; color:#cbd5e1; background:rgba(255,255,255,.08); border:1px solid rgba(255,255,255,.12); padding:5px 12px; border-radius:3px;">
                            {{ $activeCount }} active
                        </span>
                        <span style="display:inline-flex; align-items:center; gap:6px; font-size:11px; font-weight:700; color:#b8922a; background:rgba(184,146,42,.12); border:1px solid rgba(184,146,42,.3); padding:5px 12px; border-radius:3px;">
                            <span style="width:6px; height:6px; background:#b8922a; border-radius:50%; display:inline-block;"></span>
                            Analyst
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
        --gov-navy:   #1a2f4e;
        --gov-gold:   #b8922a;
        --gov-teal:   #0d9488;
        --gov-slate:  #f1f5f9;
        --gov-border: #e2e8f0;
    }
    .gov-section-label {
        font-size:9px; font-weight:700; letter-spacing:.12em;
        text-transform:uppercase; color:#64748b; margin:0 0 10px;
    }
    .gov-stat {
        background:#fff; border:1px solid var(--gov-border);
        border-radius:4px; padding:18px 20px; display:block; text-decoration:none;
    }
    .gov-stat-label {
        font-size:9px; font-weight:700; letter-spacing:.12em;
        text-transform:uppercase; color:#64748b; margin:0 0 8px;
    }
    .gov-stat-num {
        font-size:36px; font-weight:700; color:var(--gov-navy);
        margin:0; font-family:'Georgia',serif; line-height:1;
    }
    .gov-stat-sub { font-size:11px; color:#94a3b8; margin:4px 0 0; }
    .gov-card-hdr {
        padding:16px 20px; border-bottom:1px solid var(--gov-border);
        display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px;
    }
    .gov-card-hdr h3 {
        font-family:'Georgia',serif; font-size:15px; font-weight:700;
        color:var(--gov-navy); border-bottom:2px solid var(--gov-gold);
        padding-bottom:8px; margin:0 0 4px; display:inline-block;
    }
    .gov-card-hdr p { font-size:11px; color:#94a3b8; margin:0; }
    </style>
    @endpush

    <div style="background:#f1f5f9; min-height:100vh; padding:52px 0 56px;">
        <div style="max-width:80rem; margin:0 auto; padding:0 2rem; display:flex; flex-direction:column; gap:24px;">

            <x-kstl.flash />

            {{-- ── Summary Cards ─────────────────────────────────── --}}
            <div style="margin-top:24px;">
                <p class="gov-section-label">Test Overview</p>
                <div style="display:grid; grid-template-columns:repeat(4,1fr); gap:16px;">
                    <a href="{{ route('analyst.tests.index') }}" class="gov-stat" style="border-left:4px solid #d97706;">
                        <p class="gov-stat-label">Queued</p>
                        <p class="gov-stat-num">{{ $counts['queued'] ?? 0 }}</p>
                        <p class="gov-stat-sub">Awaiting analyst</p>
                    </a>

                    <a href="{{ route('analyst.tests.index') }}" class="gov-stat" style="border-left:4px solid #2563eb;">
                        <p class="gov-stat-label">In Progress</p>
                        <p class="gov-stat-num">{{ $counts['in_progress'] ?? 0 }}</p>
                        <p class="gov-stat-sub">Currently running</p>
                    </a>

                    <a href="{{ route('analyst.results.index') }}" class="gov-stat" style="border-left:4px solid #16a34a;">
                        <p class="gov-stat-label">Completed</p>
                        <p class="gov-stat-num">{{ $counts['completed'] ?? 0 }}</p>
                        <p class="gov-stat-sub">Results entered</p>
                    </a>

                    <a href="{{ route('analyst.tests.index') }}" class="gov-stat" style="border-left:4px solid #dc2626;">
                        <p class="gov-stat-label">Flagged</p>
                        <p class="gov-stat-num">{{ $counts['flagged'] ?? 0 }}</p>
                        <p class="gov-stat-sub">Needs review</p>
                    </a>
                </div>
            </div>

            {{-- ── Result Summary Cards ─────────────────────────── --}}
            @php
                $allTests = $queue ?? collect();
                $completedTests = $allTests->whereIn('status', ['completed', 'flagged']);
                $passCount = $completedTests->whereIn('result_qualifier', ['pass', 'not_detected'])->count();
                $failCount = $completedTests->whereIn('result_qualifier', ['fail', 'detected'])->count();
                $otherCount = $completedTests->whereNotIn('result_qualifier', ['pass', 'not_detected', 'fail', 'detected', 'pending'])->count();
                $totalCompleted = $completedTests->count();
            @endphp

            @if($totalCompleted > 0)
                <div>
                    <p class="gov-section-label">Result Breakdown</p>
                    <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:16px;">
                        <div class="gov-stat" style="border-left:4px solid #16a34a;">
                            <p class="gov-stat-label">Pass / Not Detected</p>
                            <p class="gov-stat-num" style="color:#15803d;">{{ $passCount }}</p>
                            <p class="gov-stat-sub">{{ $totalCompleted > 0 ? round(($passCount / $totalCompleted) * 100) : 0 }}% of completed</p>
                        </div>
                        <div class="gov-stat" style="border-left:4px solid #dc2626;">
                            <p class="gov-stat-label">Fail / Detected</p>
                            <p class="gov-stat-num" style="color:#dc2626;">{{ $failCount }}</p>
                            <p class="gov-stat-sub">{{ $totalCompleted > 0 ? round(($failCount / $totalCompleted) * 100) : 0 }}% of completed</p>
                        </div>
                        <div class="gov-stat" style="border-left:4px solid #64748b;">
                            <p class="gov-stat-label">Other Results</p>
                            <p class="gov-stat-num">{{ $otherCount }}</p>
                            <p class="gov-stat-sub">Less than, greater than, etc.</p>
                        </div>
                    </div>
                </div>
            @endif

            {{-- ── Flagged for clarification (surfaced near the top) ───── --}}
            @php
                $flaggedGroups = $activeSubmissions->filter(fn($g) => ($g['flagged'] ?? 0) > 0);
            @endphp
            @if($flaggedGroups->isNotEmpty())
                <div style="background:#fffbeb; border:1px solid #fcd34d; border-left:4px solid #d97706; border-radius:4px; padding:16px 20px;">
                    <div style="display:flex; align-items:flex-start; gap:12px;">
                        <svg style="width:18px; height:18px; flex-shrink:0; margin-top:2px;" fill="none" stroke="#d97706" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2z"/>
                        </svg>
                        <div style="flex:1;">
                            <p style="font-size:13px; font-weight:700; color:#92400e; margin:0 0 8px;">
                                {{ $flaggedGroups->sum('flagged') }} test{{ $flaggedGroups->sum('flagged') > 1 ? 's' : '' }}
                                flagged for clarification — these need your attention
                            </p>
                            <div style="display:flex; flex-direction:column; gap:6px;">
                                @foreach($flaggedGroups as $g)
                                    @php $firstFlagged = $g['tests']->firstWhere('status', 'flagged'); @endphp
                                    <div style="display:flex; align-items:center; justify-content:space-between;">
                                        <span style="font-size:12.5px; color:#92400e;">
                                            <span style="font-family:monospace; font-weight:600;">{{ $g['submission']->reference_number }}</span>
                                            — {{ $g['submission']->client->company_name ?? '?' }}
                                            <span style="display:inline-flex; align-items:center; margin-left:8px; padding:1px 8px; background:#fef3c7; color:#92400e; border-radius:20px; font-size:11px;">{{ $g['flagged'] }} flagged</span>
                                        </span>
                                        @if($firstFlagged)
                                            <a href="{{ route('analyst.tests.show', $firstFlagged->id) }}"
                                               style="font-size:11px; font-weight:600; color:#d97706; text-decoration:none;">
                                                Review &rarr;
                                            </a>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- ── My Tests (Grouped by Submission) ──────────────────── --}}
            @php
                $priorityRank = ['emergency' => 0, 'urgent' => 1, 'routine' => 2];
                $activeSorted = $activeSubmissions->sortBy(fn($g) => [
                    ($g['flagged'] ?? 0) > 0 ? 0 : 1,
                    $priorityRank[$g['submission']->priority ?? 'routine'] ?? 2,
                    ($g['total'] ?? 0) > 0 ? ($g['done'] / $g['total']) : 1,
                ])->values();
            @endphp
            <div style="background:#fff; border:1px solid #e2e8f0; border-radius:4px; overflow:hidden;">
                <div class="gov-card-hdr">
                    <div>
                        <h3>My Tests</h3>
                        <p>Grouped by submission — all tests assigned to or started by you</p>
                    </div>
                    <a href="{{ route('analyst.tests.index') }}"
                       style="font-size:12px; font-weight:600; color:#1a2f4e; text-decoration:none; background:#f1f5f9; border:1px solid #e2e8f0; padding:6px 14px; border-radius:3px; white-space:nowrap;">
                        View all tests &rarr;
                    </a>
                </div>

                @if($activeSubmissions->isEmpty())
                    <div style="padding:48px 20px; text-align:center;">
                        <svg style="width:40px; height:40px; margin:0 auto 12px;" fill="none" stroke="#e2e8f0" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p style="font-size:13px; font-weight:600; color:#94a3b8; margin:0;">No tests assigned</p>
                        <p style="font-size:11px; color:#cbd5e1; margin:4px 0 0;">Tests you work on will appear here.</p>
                    </div>
                @else
                    <div>
                        @foreach($activeSorted as $group)
                            @php
                                $submission  = $group['submission'];
                                $tests       = $group['tests'];
                                $total       = $group['total'];
                                $done        = $group['done'];
                                $flagged     = $group['flagged'];
                                $progress    = $total > 0 ? round(($done / $total) * 100) : 0;
                            @endphp
                            <div style="border-bottom:1px solid #f1f5f9; padding:16px 20px;" x-data="{ open: true }">

                                {{-- Submission header row --}}
                                <div style="display:flex; align-items:center; justify-content:space-between; cursor:pointer;"
                                     @click="open = !open">
                                    <div style="display:flex; align-items:center; gap:12px;">
                                        <div>
                                            <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
                                                <span style="font-family:monospace; font-size:13px; font-weight:700; color:#1a2f4e;">
                                                    {{ $submission->reference_number }}
                                                </span>
                                                <span style="font-size:12.5px; color:#475569;">
                                                    {{ $submission->client->responsible_officer_name ?? $submission->client->user->name ?? '—' }}
                                                </span>
                                                @if($flagged > 0)
                                                    <span style="display:inline-flex; align-items:center; padding:2px 8px; font-size:10px; font-weight:700; background:#fff7ed; color:#c2410c; border:1px solid #fed7aa; border-radius:20px;">
                                                        {{ $flagged }} flagged
                                                    </span>
                                                @endif
                                                @if($progress === 100)
                                                    <span style="display:inline-flex; align-items:center; gap:4px; padding:2px 8px; font-size:10px; font-weight:700; background:#f0fdf4; color:#15803d; border:1px solid #bbf7d0; border-radius:20px;">
                                                        All complete
                                                    </span>
                                                @endif
                                            </div>
                                            <p style="font-size:11px; color:#94a3b8; margin:4px 0 0;">
                                                {{ $submission->client->company_name ?? '—' }}
                                                @if($submission->sample_items && count($submission->sample_items))
                                                    @foreach($submission->sample_items as $si)
                                                        &bull; <span style="color:#475569;">{{ $si['name'] ?? '—' }}</span>@if(!empty($si['scientific_name'])) <span style="font-style:italic;">({{ $si['scientific_name'] }})</span>@endif
                                                    @endforeach
                                                @elseif($submission->sample_name)
                                                    &bull; {{ $submission->sample_name }}
                                                @endif
                                                @if($submission->priority !== 'routine')
                                                    &bull; <span style="color:#d97706; font-weight:600; text-transform:capitalize;">{{ $submission->priority }}</span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>

                                    <div style="display:flex; align-items:center; gap:16px;">
                                        {{-- Progress bar --}}
                                        <div style="width:120px;">
                                            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:4px;">
                                                <span style="font-size:10px; color:#64748b;">{{ $done }}/{{ $total }} done</span>
                                                <span style="font-size:10px; font-weight:700; color:{{ $progress === 100 ? '#16a34a' : '#1a2f4e' }};">{{ $progress }}%</span>
                                            </div>
                                            <div style="width:100%; height:3px; background:#f1f5f9; border-radius:2px; overflow:hidden;">
                                                <div style="height:100%; border-radius:2px; background:{{ $progress === 100 ? '#16a34a' : '#1a2f4e' }}; width:{{ $progress }}%;"></div>
                                            </div>
                                        </div>

                                        {{-- Expand/collapse --}}
                                        <svg style="width:16px; height:16px; color:#94a3b8; transition:transform .2s;"
                                             :style="open ? 'transform:rotate(180deg)' : ''"
                                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </div>
                                </div>

                                {{-- Tests list (collapsible) --}}
                                <div x-show="open" style="margin-top:12px;">
                                    <div style="border:1px solid #e2e8f0; border-radius:4px; overflow:hidden;">
                                        <table style="width:100%; border-collapse:collapse;">
                                            <thead>
                                                <tr style="background:#1a2f4e;">
                                                    <th style="text-align:left; padding:9px 16px; font-size:9px; font-weight:700; letter-spacing:.12em; text-transform:uppercase; color:#e2e8f0;">Test</th>
                                                    <th style="text-align:left; padding:9px 16px; font-size:9px; font-weight:700; letter-spacing:.12em; text-transform:uppercase; color:#e2e8f0;">Sample</th>
                                                    <th style="text-align:left; padding:9px 16px; font-size:9px; font-weight:700; letter-spacing:.12em; text-transform:uppercase; color:#e2e8f0;">Category</th>
                                                    <th style="text-align:left; padding:9px 16px; font-size:9px; font-weight:700; letter-spacing:.12em; text-transform:uppercase; color:#e2e8f0;">Status</th>
                                                    <th style="text-align:left; padding:9px 16px; font-size:9px; font-weight:700; letter-spacing:.12em; text-transform:uppercase; color:#e2e8f0;">Result</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($tests as $i => $test)
                                                    <tr style="background:{{ $i % 2 === 0 ? '#fff' : '#f8fafc' }}; border-bottom:1px solid #f1f5f9;">
                                                        <td style="padding:10px 16px; font-size:12.5px;">
                                                            <a href="{{ route('analyst.tests.show', $test->id) }}"
                                                               style="font-weight:600; color:#1a2f4e; text-decoration:none;">
                                                                {{ $test->getDisplayLabel() }}
                                                            </a>
                                                        </td>
                                                        <td style="padding:10px 16px; font-size:12.5px; color:#475569;">
                                                            <p style="font-weight:600; margin:0;">{{ $test->sample->common_name }}</p>
                                                            @if($test->sample->scientific_name)
                                                                <p style="font-size:11px; color:#94a3b8; font-style:italic; margin:2px 0 0;">{{ $test->sample->scientific_name }}</p>
                                                            @endif
                                                        </td>
                                                        <td style="padding:10px 16px;">
                                                            @php $cat = $test->getDisplayCategory(); @endphp
                                                            <span style="display:inline-flex; padding:2px 8px; font-size:10px; font-weight:600; border-radius:20px; text-transform:capitalize; background:{{ $cat === 'microbiological' ? '#f5f3ff' : '#eff6ff' }}; color:{{ $cat === 'microbiological' ? '#7c3aed' : '#1d4ed8' }};">
                                                                {{ $cat }}
                                                            </span>
                                                        </td>
                                                        <td style="padding:10px 16px;">
                                                            <x-kstl.status-badge :status="$test->status" />
                                                        </td>
                                                        <td style="padding:10px 16px; font-size:12.5px;">
                                                            @if($test->status === 'completed' || $test->status === 'flagged')
                                                                @php
                                                                    $qualColors = [
                                                                        'pass'         => '#15803d',
                                                                        'fail'         => '#dc2626',
                                                                        'detected'     => '#dc2626',
                                                                        'not_detected' => '#15803d',
                                                                        'less_than'    => '#475569',
                                                                        'greater_than' => '#475569',
                                                                        'equal_to'     => '#475569',
                                                                    ];
                                                                    $qualColor = $qualColors[$test->result_qualifier] ?? '#475569';
                                                                @endphp
                                                                @if($test->result_qualifier && $test->result_qualifier !== 'pending')
                                                                    <span style="font-weight:700; color:{{ $qualColor }}; text-transform:capitalize; display:block;">
                                                                        {{ str_replace('_', ' ', $test->result_qualifier) }}
                                                                    </span>
                                                                @endif
                                                                @if($test->result_value)
                                                                    <span style="color:#475569;">
                                                                        {{ $test->result_value }}
                                                                        @if($test->result_unit) <span style="color:#94a3b8;">{{ $test->result_unit }}</span> @endif
                                                                    </span>
                                                                @endif
                                                                @if(!$test->result_qualifier || $test->result_qualifier === 'pending')
                                                                    <span style="color:#cbd5e1;">—</span>
                                                                @endif
                                                            @else
                                                                <span style="color:#cbd5e1;">—</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- ── Historical Record ────────────────────────────────── --}}
            <div style="background:#fff; border:1px solid #e2e8f0; border-radius:4px; overflow:hidden;">
                <div class="gov-card-hdr">
                    <div>
                        <h3>Testing History</h3>
                        <p>
                            @if(($search ?? '') !== '')
                                Search results for reference "<span style="font-family:monospace; color:#475569;">{{ $search }}</span>" — searched across all records
                            @else
                                All submissions in testing, awaiting authorisation, authorised or completed — last 20
                            @endif
                        </p>
                    </div>
                    <div style="display:flex; align-items:center; gap:8px;">
                        <form method="GET" action="{{ route('analyst.dashboard') }}" style="display:flex; align-items:center; gap:8px;">
                            <div style="position:relative;">
                                <svg style="width:14px; height:14px; position:absolute; left:9px; top:50%; transform:translateY(-50%);" fill="none" stroke="#94a3b8" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z"/>
                                </svg>
                                <input type="text" name="ref" value="{{ $search ?? '' }}"
                                       placeholder="Search reference no."
                                       style="padding:6px 10px 6px 30px; font-size:12px; border:1px solid #e2e8f0; border-radius:3px; width:180px; outline:none; color:#1a2f4e;">
                            </div>
                            <button type="submit"
                                    style="padding:6px 14px; font-size:12px; font-weight:600; color:#fff; background:#1a2f4e; border:none; border-radius:3px; cursor:pointer;">
                                Search
                            </button>
                            @if(($search ?? '') !== '')
                                <a href="{{ route('analyst.dashboard') }}"
                                   style="padding:6px 14px; font-size:12px; font-weight:600; color:#64748b; border:1px solid #e2e8f0; border-radius:3px; text-decoration:none; background:#fff;">
                                    Clear
                                </a>
                            @endif
                        </form>
                        <span style="font-size:11px; color:#94a3b8; background:#f8fafc; border:1px solid #e2e8f0; padding:5px 12px; border-radius:3px; white-space:nowrap;">
                            {{ $history->count() }} {{ ($search ?? '') !== '' ? 'found' : 'records' }}
                        </span>
                    </div>
                </div>

                @if($history->isEmpty())
                    <div style="padding:40px 20px; text-align:center;">
                        @if(($search ?? '') !== '')
                            <p style="font-size:13px; color:#94a3b8; margin:0;">No submissions found matching reference "<span style="font-family:monospace;">{{ $search }}</span>".</p>
                            <a href="{{ route('analyst.dashboard') }}" style="font-size:11px; color:#1a2f4e; text-decoration:none; display:inline-block; margin-top:6px;">Clear search</a>
                        @else
                            <p style="font-size:13px; color:#94a3b8; margin:0;">No completed submissions yet.</p>
                        @endif
                    </div>
                @else
                    <div style="overflow-x:auto;">
                        <table style="width:100%; border-collapse:collapse;">
                            <thead>
                                <tr style="background:#1a2f4e;">
                                    <th style="text-align:left; padding:9px 16px; font-size:9px; font-weight:700; letter-spacing:.12em; text-transform:uppercase; color:#e2e8f0;">Reference</th>
                                    <th style="text-align:left; padding:9px 16px; font-size:9px; font-weight:700; letter-spacing:.12em; text-transform:uppercase; color:#e2e8f0;">Client</th>
                                    <th style="text-align:left; padding:9px 16px; font-size:9px; font-weight:700; letter-spacing:.12em; text-transform:uppercase; color:#e2e8f0;">Sample</th>
                                    <th style="text-align:left; padding:9px 16px; font-size:9px; font-weight:700; letter-spacing:.12em; text-transform:uppercase; color:#e2e8f0;">Tests</th>
                                    <th style="text-align:left; padding:9px 16px; font-size:9px; font-weight:700; letter-spacing:.12em; text-transform:uppercase; color:#e2e8f0;">Status</th>
                                    <th style="text-align:left; padding:9px 16px; font-size:9px; font-weight:700; letter-spacing:.12em; text-transform:uppercase; color:#e2e8f0;">Completed</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($history as $i => $submission)
                                    @php
                                        $allTests     = $submission->samples->flatMap->sampleTests;
                                        $completedCnt = $allTests->where('status', 'completed')->count();
                                        $totalCnt     = $allTests->count();
                                    @endphp
                                    <tr style="background:{{ $i % 2 === 0 ? '#fff' : '#f8fafc' }}; border-bottom:1px solid #f1f5f9;">
                                        <td style="padding:10px 16px; font-family:monospace; font-size:12px; font-weight:700; color:#1a2f4e;">
                                            {{ $submission->reference_number }}
                                        </td>
                                        <td style="padding:10px 16px; font-size:12.5px; color:#475569;">
                                            <p style="font-weight:600; margin:0;">{{ $submission->client->company_name ?? '—' }}</p>
                                            <p style="font-size:11px; color:#94a3b8; margin:2px 0 0;">{{ $submission->client->responsible_officer_name ?? '' }}</p>
                                        </td>
                                        <td style="padding:10px 16px; font-size:12.5px; color:#475569;">
                                            @if($submission->sample_items && count($submission->sample_items))
                                                @foreach($submission->sample_items as $si)
                                                    <span style="font-weight:600;">{{ $si['name'] ?? '—' }}</span>@if(!empty($si['scientific_name'])) <span style="color:#94a3b8; font-style:italic;">({{ $si['scientific_name'] }})</span>@endif
                                                    @if(!$loop->last) <span style="color:#cbd5e1; margin:0 4px;">&middot;</span> @endif
                                                @endforeach
                                            @else
                                                {{ $submission->sample_name ?? '—' }}
                                                @if($submission->sample_type)
                                                    <span style="color:#94a3b8; text-transform:capitalize;">&middot; {{ $submission->sample_type }}</span>
                                                @endif
                                            @endif
                                        </td>
                                        <td style="padding:10px 16px; font-size:12.5px;">
                                            <span style="font-weight:700; color:#15803d;">{{ $completedCnt }}</span>
                                            <span style="color:#94a3b8;">/ {{ $totalCnt }} tests done</span>
                                        </td>
                                        <td style="padding:10px 16px;">
                                            <x-kstl.status-badge :status="$submission->status" />
                                        </td>
                                        <td style="padding:10px 16px; font-size:12.5px; color:#94a3b8;">
                                            {{ $submission->updated_at->diffForHumans() }}
                                            <p style="font-size:10px; color:#cbd5e1; margin:2px 0 0;">{{ $submission->updated_at->format('d M Y') }}</p>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
