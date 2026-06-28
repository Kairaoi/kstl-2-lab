{{-- resources/views/kstl/director/dashboard.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        {{-- ── Navy hero banner ─────────────────────────────────── --}}
        <div style="position:relative;overflow:hidden;background:linear-gradient(135deg,#0f2240 0%,#1a2f4e 60%,#1e3a5f 100%);margin:-1px;">
            <div style="position:absolute;inset:0;opacity:.04;background-image:repeating-linear-gradient(45deg,#fff 0,#fff 1px,transparent 0,transparent 50%);background-size:12px 12px;pointer-events:none;"></div>
            <div style="position:absolute;top:0;left:0;right:0;height:3px;background:linear-gradient(90deg,#1a2f4e,#b8922a 30%,#b8922a 70%,#1a2f4e);"></div>
            <div style="max-width:80rem;margin:0 auto;padding:28px 2rem;position:relative;">
                <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;">
                    <div style="display:flex;align-items:center;gap:20px;">
                        <img src="{{ asset('images/mfor-logo.png') }}"
                             alt="MFOR"
                             style="filter:brightness(0) invert(1);opacity:.92;width:56px;height:56px;flex-shrink:0;">
                        <div>
                            <p style="font-size:9px;font-weight:700;letter-spacing:.18em;text-transform:uppercase;color:#b8922a;margin:0 0 4px;">
                                Director
                            </p>
                            <h1 style="font-family:'Georgia',serif;font-size:22px;font-weight:700;color:#fff;margin:0 0 6px;line-height:1.2;">
                                Welcome, {{ Auth::user()->first_name }}
                            </h1>
                            <p style="font-size:12px;color:#94a3b8;margin:0;">
                                Authorise results and monitor laboratory operations
                            </p>
                        </div>
                    </div>
                    <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                        <span style="display:inline-flex;align-items:center;padding:4px 12px;border-radius:20px;background:rgba(184,146,42,.18);border:1px solid rgba(184,146,42,.4);font-size:10px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:#b8922a;">
                            Director
                        </span>
                        <span style="font-size:11px;color:#64748b;">
                            {{ now()->format('l, j F Y') }}
                        </span>
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
    :root {
        --gov-navy:#1a2f4e; --gov-gold:#b8922a; --gov-teal:#0d9488;
        --gov-text:#1f2937; --gov-muted:#6b7280; --gov-border:#e2e8f0;
    }
    .gov-stat { background:#fff; border:1px solid var(--gov-border); border-left:4px solid var(--gov-gold); border-radius:4px; padding:18px 20px; text-decoration:none; display:block; transition:border-left-color .15s,box-shadow .15s; }
    .gov-stat:hover { border-left-color:var(--gov-navy); box-shadow:0 2px 12px rgba(0,0,0,.08); }
    .gov-stat-label { font-size:9px; font-weight:700; letter-spacing:.16em; text-transform:uppercase; color:var(--gov-muted); margin-bottom:10px; }
    .gov-stat-num { font-family:'Georgia',serif; font-size:36px; font-weight:700; color:var(--gov-navy); line-height:1; }
    .gov-stat-sub { font-size:11px; color:var(--gov-muted); margin-top:5px; }
    .gov-section-label { font-size:9px; font-weight:700; letter-spacing:.18em; text-transform:uppercase; color:#9ca3af; margin-bottom:12px; }
    .gov-card-hdr { padding:16px 24px; border-bottom:2px solid var(--gov-gold); }
    .gov-card-hdr h3 { font-family:'Georgia',serif; font-size:15px; font-weight:700; color:var(--gov-navy); margin:0; }
    </style>
    @endpush

    <div style="background:#f1f5f9;min-height:100vh;padding:0 0 56px;">
        <div style="max-width:80rem;margin:0 auto;padding:0 2rem;display:flex;flex-direction:column;gap:24px;">

            {{-- ── Audit Search Bar ──────────────────────────────────── --}}
            <div style="background:#fff;border:1px solid #e2e8f0;border-radius:4px;overflow:hidden;"
                 x-data="{ reference: '' }">
                <div style="background:#1a2f4e;padding:14px 20px;">
                    <p style="font-family:'Georgia',serif;font-size:15px;font-weight:700;color:#fff;margin:0;">
                        Audit Search
                    </p>
                    <p style="font-size:11px;color:#94a3b8;margin:4px 0 0;">
                        Search submissions by reference number for audit sessions
                    </p>
                </div>
                <div style="padding:18px 20px;">
                    <div style="display:flex;gap:10px;margin-bottom:14px;">
                        <input type="text"
                               x-model="reference"
                               placeholder="Enter reference number (e.g., KSTL-2026-00001)"
                               style="flex:1;padding:9px 14px;border:1px solid #e2e8f0;border-radius:3px;font-size:12.5px;font-family:monospace;color:#1e293b;outline:none;"
                               @keydown.enter="searchByReference()"
                               pattern="KSTL-\d{4}-\d{5}"
                               title="Format: KSTL-YYYY-NNNNN">
                        <button type="button"
                                @click="searchByReference()"
                                style="background:#1a2f4e;color:#fff;padding:8px 18px;border-radius:3px;font-size:12px;font-weight:600;border:none;cursor:pointer;white-space:nowrap;">
                            Search
                        </button>
                    </div>
                    <div style="display:flex;flex-wrap:wrap;gap:8px;align-items:center;">
                        <span style="font-size:9px;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:#9ca3af;">Quick access:</span>
                        <a href="{{ route('director.dashboard') }}"
                           style="padding:4px 12px;background:#f1f5f9;border:1px solid #e2e8f0;border-radius:20px;font-size:11px;color:#1a2f4e;text-decoration:none;font-weight:600;">
                            Dashboard
                        </a>
                        <a href="{{ route('director.agreements.index') }}"
                           style="padding:4px 12px;background:#f1f5f9;border:1px solid #e2e8f0;border-radius:20px;font-size:11px;color:#1a2f4e;text-decoration:none;font-weight:600;">
                            Agreements
                        </a>
                        <a href="{{ route('director.invoices.index') }}"
                           style="padding:4px 12px;background:#f1f5f9;border:1px solid #e2e8f0;border-radius:20px;font-size:11px;color:#1a2f4e;text-decoration:none;font-weight:600;">
                            Invoices
                        </a>
                        <a href="{{ route('director.audit.index') }}"
                           style="padding:4px 12px;background:#f1f5f9;border:1px solid #e2e8f0;border-radius:20px;font-size:11px;color:#1a2f4e;text-decoration:none;font-weight:600;">
                            Audit Log
                        </a>
                        <a href="{{ route('director.complaints.index') }}"
                           style="padding:4px 12px;background:#f1f5f9;border:1px solid #e2e8f0;border-radius:20px;font-size:11px;color:#1a2f4e;text-decoration:none;font-weight:600;">
                            Complaints
                        </a>
                        <a href="{{ route('director.submissions.index') }}"
                           style="padding:4px 12px;background:#f1f5f9;border:1px solid #e2e8f0;border-radius:20px;font-size:11px;color:#1a2f4e;text-decoration:none;font-weight:600;">
                            Pipeline
                        </a>
                        @if($flagged > 0)
                        <a href="{{ route('director.flagged.index') }}"
                           style="padding:4px 12px;background:#fee2e2;border:1px solid #fca5a5;border-radius:20px;font-size:11px;color:#dc2626;text-decoration:none;font-weight:600;">
                            Flagged ({{ $flagged }})
                        </a>
                        @endif
                    </div>
                </div>
            </div>

            {{-- ── Stat cards ─────────────────────────────────────────── --}}
            <div style="margin-top:24px;">
                <p class="gov-section-label">Laboratory Overview</p>
                <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:12px;">

                    <a href="{{ route('director.submissions.index', ['status' => 'awaiting_authorisation']) }}"
                       class="gov-stat" style="border-left-color:#b8922a;">
                        <div class="gov-stat-label">Awaiting Authorisation</div>
                        <p class="gov-stat-num">{{ $pending->count() }}</p>
                        <p class="gov-stat-sub">
                            @if($pending->isNotEmpty())
                                Oldest: {{ $pending->sortBy('created_at')->first()->created_at->diffForHumans() }}
                            @else
                                All caught up
                            @endif
                        </p>
                    </a>

                    <a href="{{ route('director.flagged.index') }}"
                       class="gov-stat" style="border-left-color:#dc2626;">
                        <div class="gov-stat-label">Flagged Tests</div>
                        <p class="gov-stat-num">{{ $flagged }}</p>
                        <p class="gov-stat-sub" style="{{ $flagged > 0 ? 'color:#dc2626;' : 'color:#0d9488;' }}">
                            {{ $flagged > 0 ? 'Needs immediate review' : 'No issues flagged' }}
                        </p>
                    </a>

                    <a href="{{ route('director.invoices.index') }}"
                       class="gov-stat" style="border-left-color:#7c3aed;">
                        <div class="gov-stat-label">Pending Payments</div>
                        <p class="gov-stat-num">{{ $unpaid_invoices }}</p>
                        <p class="gov-stat-sub" style="{{ $unpaid_invoices > 0 ? '' : 'color:#0d9488;' }}">
                            {{ $unpaid_invoices > 0 ? 'Awaiting payment confirmation' : 'All invoices settled' }}
                        </p>
                    </a>

                    <div class="gov-stat" style="border-left-color:#0d9488;">
                        <div class="gov-stat-label">Authorised Today</div>
                        <p class="gov-stat-num">{{ $authorised_today }}</p>
                        <p class="gov-stat-sub">{{ now()->format('l, F j') }}</p>
                    </div>

                    @php
                        $thisWeekCount = $history->where('created_at', '>=', now()->startOfWeek())->count();
                        $dailyAverage  = $thisWeekCount > 0 ? round($thisWeekCount / max(1, now()->dayOfWeek ?: 1), 1) : 0;
                    @endphp
                    <div class="gov-stat" style="border-left-color:#1a2f4e;">
                        <div class="gov-stat-label">This Week</div>
                        <p class="gov-stat-num">{{ $thisWeekCount }}</p>
                        <p class="gov-stat-sub">Avg {{ $dailyAverage }}/day</p>
                    </div>

                </div>
            </div>

            {{-- ── Quick Actions ──────────────────────────────────────── --}}
            <div style="background:#fff;border:1px solid #e2e8f0;border-radius:4px;overflow:hidden;">
                <div class="gov-card-hdr">
                    <h3>Quick Actions</h3>
                </div>
                <div style="padding:16px 20px;display:flex;flex-wrap:wrap;gap:10px;align-items:center;">
                    @if($pending->isNotEmpty())
                        <a href="{{ route('director.submissions.show', $pending->first()->id) }}"
                           style="background:#0d9488;color:#fff;padding:8px 18px;border-radius:3px;font-size:12px;font-weight:600;text-decoration:none;display:inline-flex;align-items:center;gap:6px;">
                            <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                            Authorise Next
                        </a>
                    @endif
                    <a href="{{ route('director.agreements.index') }}"
                       style="background:#fff;color:#1a2f4e;border:1px solid #e2e8f0;padding:8px 18px;border-radius:3px;font-size:12px;font-weight:600;text-decoration:none;">
                        Agreements
                    </a>
                    <a href="{{ route('director.submissions.index') }}"
                       style="background:#fff;color:#1a2f4e;border:1px solid #e2e8f0;padding:8px 18px;border-radius:3px;font-size:12px;font-weight:600;text-decoration:none;">
                        Full Pipeline
                    </a>
                    @if($flagged > 0)
                        <a href="{{ route('director.flagged.index') }}"
                           style="background:#fee2e2;color:#dc2626;border:1px solid #fca5a5;padding:8px 18px;border-radius:3px;font-size:12px;font-weight:600;text-decoration:none;">
                            View Flagged ({{ $flagged }})
                        </a>
                    @endif
                </div>
            </div>

            {{-- ── Service Agreements Alert ───────────────────────────── --}}
            @if(isset($unsigned_agreements) && $unsigned_agreements > 0)
            <div style="background:#fffbeb;border:1px solid #fbbf24;border-left:4px solid #b8922a;border-radius:4px;padding:18px 20px;display:flex;align-items:flex-start;justify-content:space-between;gap:16px;flex-wrap:wrap;">
                <div>
                    <p style="font-size:13px;font-weight:700;color:#92400e;margin:0 0 4px;">Service Agreements Awaiting Signature</p>
                    <p style="font-size:12px;color:#78350f;margin:0;">
                        You have <strong>{{ $unsigned_agreements }}</strong> service agreement(s) awaiting your countersignature.
                    </p>
                </div>
                <a href="{{ route('director.agreements.index') }}"
                   style="background:#b8922a;color:#fff;padding:8px 18px;border-radius:3px;font-size:12px;font-weight:600;text-decoration:none;white-space:nowrap;flex-shrink:0;">
                    Review &amp; Sign
                </a>
            </div>
            @endif

            {{-- ── Two-column panels ─────────────────────────────────── --}}
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">

                {{-- Awaiting Authorisation panel --}}
                <div style="background:#fff;border:1px solid #e2e8f0;border-radius:4px;overflow:hidden;">
                    <div class="gov-card-hdr">
                        <h3>Awaiting Your Authorisation</h3>
                    </div>

                    @if($pending->isEmpty())
                        <div style="padding:40px 20px;text-align:center;">
                            <p style="font-size:13px;color:#6b7280;margin:0 0 4px;font-weight:600;">No pending authorisations</p>
                            <p style="font-size:12px;color:#9ca3af;margin:0;">All submissions have been reviewed</p>
                        </div>
                    @else
                        <div style="max-height:400px;overflow-y:auto;">
                            @foreach($pending as $submission)
                                @php
                                    $flaggedCount = $submission->samples->sum(function($s) {
                                        return $s->sampleTests ? $s->sampleTests->where('status', 'flagged')->count() : 0;
                                    });
                                    $totalTests = $submission->samples->sum(function($s) {
                                        return $s->sampleTests ? $s->sampleTests->count() : 0;
                                    });
                                    $completedTests = $submission->samples->sum(function($s) {
                                        return $s->sampleTests ? $s->sampleTests->whereIn('status', ['completed', 'flagged'])->count() : 0;
                                    });
                                    $progress = $totalTests > 0 ? round(($completedTests / $totalTests) * 100) : 0;
                                @endphp
                                <div style="padding:14px 20px;border-bottom:1px solid #f1f5f9;">
                                    <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:10px;">
                                        <div>
                                            <div style="display:flex;align-items:center;gap:8px;margin-bottom:3px;">
                                                <span style="font-family:monospace;font-size:12px;font-weight:700;color:#1a2f4e;">{{ $submission->reference_number }}</span>
                                                @if($flaggedCount > 0)
                                                    <span style="padding:2px 8px;border-radius:20px;background:#fee2e2;color:#dc2626;font-size:10px;font-weight:700;">{{ $flaggedCount }} Flagged</span>
                                                @else
                                                    <span style="padding:2px 8px;border-radius:20px;background:#d1fae5;color:#065f46;font-size:10px;font-weight:700;">All Clear</span>
                                                @endif
                                            </div>
                                            <p style="font-size:12.5px;color:#374151;font-weight:600;margin:0 0 2px;">{{ $submission->client->user->name }}</p>
                                            <p style="font-size:11px;color:#6b7280;margin:0;">{{ $submission->client->company_name }}</p>
                                        </div>
                                        <div style="text-align:right;font-size:11px;color:#6b7280;">
                                            <p style="margin:0 0 4px;">{{ $submission->created_at->diffForHumans() }}</p>
                                            <div style="display:flex;align-items:center;gap:4px;">
                                                <div style="width:60px;height:3px;background:#e2e8f0;border-radius:2px;overflow:hidden;">
                                                    <div style="width:{{ $progress }}%;height:100%;background:#0d9488;border-radius:2px;"></div>
                                                </div>
                                                <span style="font-size:10px;color:#9ca3af;">{{ $completedTests }}/{{ $totalTests }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <a href="{{ route('director.submissions.show', $submission->id) }}"
                                       style="display:block;text-align:center;background:#1a2f4e;color:#fff;padding:7px 14px;border-radius:3px;font-size:12px;font-weight:600;text-decoration:none;">
                                        Review &amp; Authorise
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Authorisation History panel --}}
                <div style="background:#fff;border:1px solid #e2e8f0;border-radius:4px;overflow:hidden;" id="flagged-section">
                    <div class="gov-card-hdr">
                        <h3>Authorisation History</h3>
                    </div>

                    @if($history->isEmpty())
                        <div style="padding:40px 20px;text-align:center;">
                            <p style="font-size:13px;color:#6b7280;margin:0 0 4px;font-weight:600;">No history yet</p>
                            <p style="font-size:12px;color:#9ca3af;margin:0;">Authorised submissions will appear here</p>
                        </div>
                    @else
                        <div style="max-height:400px;overflow-y:auto;">
                            @foreach($history->take(20) as $submission)
                                <div style="padding:12px 20px;border-bottom:1px solid #f1f5f9;">
                                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:4px;">
                                        <div style="display:flex;align-items:center;gap:8px;">
                                            <span style="font-family:monospace;font-size:12px;font-weight:700;color:#1a2f4e;">{{ $submission->reference_number }}</span>
                                            @if($submission->result)
                                                @if($submission->result->overall_outcome === 'pass')
                                                    <span style="padding:2px 8px;border-radius:20px;background:#d1fae5;color:#065f46;font-size:10px;font-weight:700;">Pass</span>
                                                @else
                                                    <span style="padding:2px 8px;border-radius:20px;background:#fee2e2;color:#dc2626;font-size:10px;font-weight:700;">Fail</span>
                                                @endif
                                            @endif
                                        </div>
                                        <span style="font-size:11px;color:#9ca3af;">{{ $submission->updated_at->format('M j, Y') }}</span>
                                    </div>
                                    <p style="font-size:12.5px;color:#374151;font-weight:600;margin:0 0 2px;">{{ $submission->client->user->name }}</p>
                                    <p style="font-size:11px;color:#6b7280;margin:0 0 6px;">
                                        {{ $submission->client->company_name }} &middot;
                                        {{ $submission->samples->first()->sample_type ?? 'N/A' }}
                                    </p>
                                    <a href="{{ route('director.submissions.show', $submission->id) }}"
                                       style="font-size:11px;font-weight:600;color:#0d9488;text-decoration:none;">
                                        View Details &rsaquo;
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

            </div>

            {{-- Search Results Area --}}
            <div id="search-results" style="display:none;">
                <div style="background:#fff;border:1px solid #e2e8f0;border-radius:4px;overflow:hidden;">
                    <div class="gov-card-hdr">
                        <h3>Search Results</h3>
                    </div>
                    <div id="search-results-content" style="padding:20px;"></div>
                </div>
            </div>

        </div>
    </div>

    <script>
        function searchByReference() {
            const reference = document.querySelector('[x-model="reference"]').value.trim();

            if (!reference) {
                alert('Please enter a reference number');
                return;
            }

            const pattern = /^KSTL-\d{4}-\d{5}$/;
            if (!pattern.test(reference)) {
                alert('Invalid reference format. Use: KSTL-YYYY-NNNNN');
                return;
            }

            const pendingSubmissions = @json($pending);
            const foundPending = pendingSubmissions.find(s => s.reference === reference);

            if (foundPending) {
                window.location.href = `{{ url('director/submissions') }}/${foundPending.id}`;
                return;
            }

            const historySubmissions = @json($history);
            const foundHistory = historySubmissions.find(s => s.reference === reference);

            if (foundHistory) {
                window.location.href = `{{ url('director/submissions') }}/${foundHistory.id}`;
                return;
            }

            alert(`Submission ${reference} not found in your accessible records. The submission may not exist or may still be in earlier processing stages (reception/testing).`);
        }
    </script>
</x-app-layout>