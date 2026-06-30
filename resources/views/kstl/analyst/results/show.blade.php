{{-- resources/views/kstl/analyst/results/show.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <div style="position:relative;overflow:hidden;background:linear-gradient(135deg,#0f2240 0%,#1a2f4e 60%,#1e3a5f 100%);margin:-1px;">
            <div style="height:3px;background:linear-gradient(90deg,#1a2f4e,#b8922a 30%,#b8922a 70%,#1a2f4e);"></div>
            <div style="max-width:80rem;margin:0 auto;padding:28px 2rem 32px;">
                <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;">
                    <div style="display:flex;align-items:center;gap:20px;">
                        <img src="{{ asset('images/mfor-logo.png') }}" alt="MFOR" style="filter:brightness(0) invert(1);opacity:.92;width:56px;height:56px;flex-shrink:0;">
                        <div>
                            <p style="font-size:9px;font-weight:700;letter-spacing:.18em;text-transform:uppercase;color:#b8922a;margin:0 0 4px;">Analyst</p>
                            <h1 style="font-family:'Georgia',serif;font-size:22px;font-weight:700;color:#fff;margin:0 0 6px;line-height:1.2;">{{ $submission->reference_number }}</h1>
                            <p style="font-size:12px;color:#94a3b8;margin:0;">{{ $submission->client->company_name ?? '' }}</p>
                        </div>
                    </div>
                    <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                        <button onclick="window.print()"
                                class="no-print"
                                style="display:inline-flex;align-items:center;gap:8px;padding:8px 20px;background:#fff;color:#1a2f4e;font-size:12px;font-weight:700;letter-spacing:.06em;border:1px solid #1a2f4e;border-radius:3px;text-decoration:none;cursor:pointer;">
                            <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                            </svg>
                            Print
                        </button>
                        <a href="{{ route('analyst.results.index') }}"
                           class="no-print"
                           style="display:inline-flex;align-items:center;gap:8px;padding:8px 20px;background:rgba(255,255,255,.12);color:#fff;font-size:12px;font-weight:700;letter-spacing:.06em;border:1px solid rgba(255,255,255,.3);border-radius:3px;text-decoration:none;">
                            ← Back to results
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

        /* ── CoA base ───────────────────────────────────────────────── */
        .coa-wrap  { background: #eef2f7; }
        .coa-paper {
            background: #ffffff; max-width: 860px; margin: 0 auto;
            border: 1px solid #c9d1d9; border-radius: 6px;
            box-shadow: 0 4px 32px rgba(0,0,0,.12); overflow: hidden;
        }
        .coa-inner { padding: 40px 48px; }

        /* ── Letterhead ─────────────────────────────────────────────── */
        .coa-lh         { display: flex; align-items: flex-start; gap: 16px; }
        .coa-lh-body    { flex: 1; min-width: 0; }
        .coa-lh-contact {
            flex: 0 0 auto; max-width: 200px; text-align: right;
            font-size: 10px; color: #6b7280; line-height: 1.8; word-break: break-word;
        }
        .coa-lh-contact strong { font-weight: 600; color: #374151; }
        .coa-org-line  { font-size: 8px; font-weight: 700; letter-spacing: .18em;
                         text-transform: uppercase; color: #9ca3af; margin-bottom: 5px; }
        .coa-lab-name  { font-family: 'Georgia','Times New Roman',serif;
                         font-size: 19px; font-weight: 700; color: #1a2f4e; line-height: 1.25; }

        /* ── Rules ──────────────────────────────────────────────────── */
        .coa-rule-gold {
            height: 3px;
            background: linear-gradient(90deg,#1a2f4e 0%,#b8922a 40%,#b8922a 60%,#1a2f4e 100%);
            margin: 16px 0;
        }
        .coa-rule-thin {
            height: 1px;
            background: linear-gradient(90deg,transparent 0%,#b8922a 50%,transparent 100%);
            margin: 4px 0 16px;
        }

        /* ── Title ──────────────────────────────────────────────────── */
        .coa-title-wrap { text-align: center; padding: 12px 0 14px; }
        .coa-title      { font-family: 'Georgia','Times New Roman',serif;
                          font-size: 28px; font-weight: 400; color: #1a2f4e; }
        .coa-subtitle   { display: flex; align-items: center; gap: 16px;
                          justify-content: center; margin-top: 7px; }
        .coa-subtitle hr { flex: 1; max-width: 130px; border: none; border-top: 1px solid #b8922a; }
        .coa-subtitle p  { font-size: 8px; font-weight: 700; letter-spacing: .22em;
                           text-transform: uppercase; color: #b8922a; white-space: nowrap; }

        /* ── Prepared-for / meta / details ─────────────────────────── */
        .coa-for-row   { display: flex; justify-content: space-between; flex-wrap: wrap; gap: 8px; margin-bottom: 14px; }
        .coa-for-block { min-width: 0; }
        .coa-meta      { display: grid; grid-template-columns: repeat(3,1fr);
                         border: 1px solid #e5e7eb; border-radius: 6px; overflow: hidden; margin-bottom: 12px; }
        .coa-meta-cell { padding: 12px 16px; }
        .coa-meta-cell + .coa-meta-cell { border-left: 1px solid #e5e7eb; }
        .coa-meta-cell:nth-child(2) { background: #f8fafc; }
        .coa-meta-key  { font-size: 8px; font-weight: 700; letter-spacing: .14em;
                         text-transform: uppercase; color: #9ca3af; margin-bottom: 4px; }
        .coa-meta-val  { font-size: 14px; font-weight: 700; color: #1a2f4e; font-family: 'Georgia',serif; }
        .coa-details   { background: #f8fafc; border: 1px solid #e5e7eb; border-radius: 6px; padding: 9px 16px; }
        .coa-details-row { display: flex; flex-wrap: wrap; gap: 4px 24px;
                           font-size: 10.5px; color: #374151; line-height: 1.9; }
        .coa-details-row strong { color: #9ca3af; font-weight: 600; }

        /* ── Dark footer strip ──────────────────────────────────────── */
        .coa-doc-strip  { background: #1a2f4e; padding: 10px 48px;
                          display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 6px; }
        .coa-doc-strip p   { font-size: 9px; color: #93c5fd; letter-spacing: .03em; }
        .coa-doc-strip .ref { font-family: 'Courier New',monospace; font-size: 9px; color: #e2e8f0; }

        @media print {
            .no-print { display: none !important; }
            body, .coa-wrap { background: #fff !important; }
            .coa-paper { border: none !important; box-shadow: none !important; max-width: 100% !important; }
            .coa-inner { padding: 20px 28px !important; }
            .coa-doc-strip { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .page-hdr, .gov-stripe, .gov-top, nav, .app-footer { display: none !important; }
        }
        @page { size: A4; margin: 10mm; }
    </style>
    @endpush

    <div class="coa-wrap" style="padding:0 0 56px;">

        {{-- Read-only notice --}}
        <div class="no-print" style="background:#eff6ff;border-bottom:1px solid #bfdbfe;padding:10px 2rem;">
            <div style="max-width:900px;margin:0 auto;display:flex;align-items:center;gap:8px;">
                <svg style="width:15px;height:15px;color:#3b82f6;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p style="font-size:12px;color:#1e40af;margin:0;">This is the authorised report as signed off by the Director. It is read-only.</p>
            </div>
        </div>

        <div style="max-width:900px;margin:0 auto;padding:24px 1.5rem 0;">
            <div class="coa-paper">

                {{-- ── CoA Letterhead ─────────────────────────────────────── --}}
                <div class="coa-inner" style="padding-bottom:24px;">
                    <div class="coa-lh">
                        <div style="flex-shrink:0;">
                            <img src="{{ asset('images/mfor-logo.png') }}" alt="MFOR" style="width:72px;height:72px;object-fit:contain;">
                        </div>
                        <div class="coa-lh-body">
                            <p class="coa-org-line">Government of Kiribati &nbsp;·&nbsp; Ministry of Fisheries &amp; Ocean Resources</p>
                            <h1 class="coa-lab-name">Kiribati Seafood Toxicology Laboratory</h1>
                        </div>
                        <div class="coa-lh-contact">
                            <strong>Seafood Toxicology Laboratory</strong><br>
                            Ministry of Fisheries and Ocean Resources,<br>Tarawa, Kiribati<br>
                            t. +686 [Your Number]<br>
                            e. stld@fisheries.gov.ki<br>
                            w. stld.fisheries.gov.ki
                        </div>
                    </div>

                    <div class="coa-rule-gold"></div>

                    <div class="coa-title-wrap">
                        <h2 class="coa-title">Certificate of Analysis</h2>
                        <div class="coa-subtitle">
                            <hr><p>Final Report &nbsp;·&nbsp; Official Document</p><hr>
                        </div>
                    </div>
                    <div class="coa-rule-thin"></div>

                    <div class="coa-for-row">
                        <div class="coa-for-block">
                            <p style="font-size:8px;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:#9ca3af;margin-bottom:3px;">Prepared for</p>
                            <p style="font-size:12.5px;font-weight:700;color:#111827;">{{ $submission->client->company_name }}</p>
                            <p style="font-size:10.5px;color:#6b7280;">{{ $submission->client->user->email ?? '' }}</p>
                        </div>
                        <div class="coa-for-block" style="text-align:right;">
                            @if($submission->client_reference)
                                <p style="font-size:8px;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:#9ca3af;margin-bottom:2px;">Client Reference</p>
                                <p style="font-family:'Courier New',monospace;font-size:13px;font-weight:700;color:#111827;margin-bottom:8px;">{{ $submission->client_reference }}</p>
                            @endif
                            <p style="font-size:8px;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:#9ca3af;margin-bottom:3px;">KSTL Reference</p>
                            <p style="font-family:'Courier New',monospace;font-size:14px;font-weight:700;color:#1d4ed8;">{{ $submission->reference_number }}</p>
                        </div>
                    </div>

                    <div class="coa-meta">
                        <div class="coa-meta-cell">
                            <p class="coa-meta-key">Report Issued</p>
                            <p class="coa-meta-val">{{ $result?->authorised_at?->format('d M Y') ?? now()->format('d M Y') }}</p>
                        </div>
                        <div class="coa-meta-cell">
                            @if($submission->client_reference)
                                <p class="coa-meta-key">Client Reference</p>
                                <p class="coa-meta-val" style="font-family:'Courier New',monospace;font-size:12px;margin-bottom:8px;">{{ $submission->client_reference }}</p>
                            @endif
                            <p class="coa-meta-key">KSTL Reference</p>
                            <p class="coa-meta-val" style="font-family:'Courier New',monospace;font-size:12px;">{{ $submission->reference_number }}</p>
                        </div>
                        <div class="coa-meta-cell">
                            <p class="coa-meta-key">Sample(s) Received</p>
                            <p class="coa-meta-val" style="font-size:13px;">
                                {{ $submission->received_at?->format('d M Y')
                                   ?? $submission->submitted_at?->format('d M Y') ?? '—' }}
                            </p>
                        </div>
                    </div>

                    @php
                        $testStart = $samples->map(fn($s) => $testsBySample[$s->id]?->min('completed_at'))->filter()->min();
                        $testEnd   = $samples->map(fn($s) => $testsBySample[$s->id]?->max('completed_at'))->filter()->max();
                    @endphp
                    <div class="coa-details">
                        <div class="coa-details-row">
                            <span><strong>Collected:</strong> {{ $submission->collected_at?->format('d M Y') ?? '—' }}</span>
                            @if($submission->delivered_at)
                                <span><strong>Delivered:</strong> {{ $submission->delivered_at->format('d M Y') }}</span>
                            @endif
                            <span><strong>Submitted:</strong> {{ $submission->submitted_at?->format('d M Y') ?? '—' }}</span>
                            @if($testStart && $testEnd)
                                <span><strong>Testing Period:</strong>
                                    {{ \Carbon\Carbon::parse($testStart)->format('d M Y') }} –
                                    {{ \Carbon\Carbon::parse($testEnd)->format('d M Y') }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>{{-- /coa-inner --}}

                {{-- Authorisation strip --}}
                <div style="padding:20px 32px;display:flex;align-items:center;justify-content:space-between;gap:24px;border-bottom:1px solid #e2e8f0;background:#f8fafc;flex-wrap:wrap;">
                    <div>
                        <p style="font-size:9px;font-weight:600;letter-spacing:.08em;text-transform:uppercase;color:#94a3b8;margin:0 0 4px;">Authorisation Status</p>
                        @if($result?->authorised_at)
                            <p style="font-size:13px;font-weight:700;color:#166534;margin:0;">Authorised</p>
                        @else
                            <p style="font-size:13px;color:#94a3b8;font-style:italic;margin:0;">Awaiting Director authorisation</p>
                        @endif
                    </div>

                    <div style="text-align:center;">
                        <p style="font-size:9px;font-weight:600;letter-spacing:.08em;text-transform:uppercase;color:#94a3b8;margin:0 0 4px;">Prepared For</p>
                        <p style="font-size:13px;font-weight:700;color:#1e293b;margin:0 0 2px;">{{ $submission->client->company_name }}</p>
                        <p style="font-size:12px;color:#64748b;margin:0;">{{ $submission->client->user->email ?? '' }}</p>
                    </div>

                    @if($result?->authorised_at)
                        <div style="text-align:right;">
                            <p style="font-size:9px;font-weight:600;letter-spacing:.08em;text-transform:uppercase;color:#94a3b8;margin:0 0 4px;">Authorised By</p>
                            <p style="font-size:13px;font-weight:600;color:#1e293b;margin:0 0 2px;">{{ $result->authorisedBy?->name ?? 'Laboratory Director' }}</p>
                            <p style="font-size:12px;color:#64748b;margin:0 0 2px;">Laboratory Director</p>
                            <p style="font-size:11px;color:#94a3b8;margin:0;">{{ $result->authorised_at->format('d M Y \a\t H:i') }}</p>
                        </div>
                    @endif
                </div>

                {{-- Submission particulars --}}
                <div style="padding:24px 32px;border-bottom:1px solid #e2e8f0;">
                    <h2 style="font-family:'Georgia',serif;font-size:16px;font-weight:700;color:#1a2f4e;margin:0 0 16px;padding-bottom:8px;border-bottom:2px solid #b8922a;">Submission Particulars</h2>
                    <dl style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px 24px;margin-bottom:20px;">
                        <div>
                            <dt style="font-size:9px;font-weight:600;letter-spacing:.08em;text-transform:uppercase;color:#94a3b8;margin:0 0 4px;">Reference</dt>
                            <dd style="font-family:monospace;font-size:13px;color:#1e293b;margin:0;">{{ $submission->reference_number }}</dd>
                        </div>
                        <div>
                            <dt style="font-size:9px;font-weight:600;letter-spacing:.08em;text-transform:uppercase;color:#94a3b8;margin:0 0 4px;">Collected</dt>
                            <dd style="font-size:13px;color:#374151;margin:0;">{{ $submission->collected_at?->format('d M Y') ?? '—' }}</dd>
                        </div>
                        @if($submission->delivered_at)
                        <div>
                            <dt style="font-size:9px;font-weight:600;letter-spacing:.08em;text-transform:uppercase;color:#94a3b8;margin:0 0 4px;">Delivered</dt>
                            <dd style="font-size:13px;color:#374151;margin:0;">{{ $submission->delivered_at->format('d M Y') }}</dd>
                        </div>
                        @endif
                        <div>
                            <dt style="font-size:9px;font-weight:600;letter-spacing:.08em;text-transform:uppercase;color:#94a3b8;margin:0 0 4px;">Submitted</dt>
                            <dd style="font-size:13px;color:#374151;margin:0;">{{ $submission->submitted_at?->format('d M Y') ?? '—' }}</dd>
                        </div>
                        <div>
                            <dt style="font-size:9px;font-weight:600;letter-spacing:.08em;text-transform:uppercase;color:#94a3b8;margin:0 0 4px;">Date of Issue</dt>
                            <dd style="font-size:13px;color:#374151;margin:0;">{{ $result?->authorised_at?->format('d M Y') ?? '—' }}</dd>
                        </div>
                    </dl>

                    <p style="font-size:9px;font-weight:600;letter-spacing:.08em;text-transform:uppercase;color:#94a3b8;margin:0 0 8px;">Samples Submitted ({{ $samples->count() }})</p>
                    <table style="width:100%;border-collapse:collapse;border:1px solid #e2e8f0;border-radius:4px;overflow:hidden;">
                        <thead>
                            <tr style="background:#1a2f4e;">
                                <th style="padding:8px 12px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">#</th>
                                <th style="padding:8px 12px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">Common Name</th>
                                <th style="padding:8px 12px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">Scientific Name</th>
                                <th style="padding:8px 12px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">Sample Code</th>
                                <th style="padding:8px 12px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">Qty</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($samples as $i => $sample)
                                <tr style="border-bottom:1px solid #f1f5f9;{{ $loop->even ? 'background:#f8fafc;' : '' }}">
                                    <td style="padding:10px 12px;font-family:monospace;font-size:11px;color:#94a3b8;">{{ $i + 1 }}</td>
                                    <td style="padding:10px 12px;font-size:13px;font-weight:600;color:#1e293b;">{{ $sample->common_name ?? '—' }}</td>
                                    <td style="padding:10px 12px;font-size:12px;font-style:italic;color:#64748b;">{{ $sample->scientific_name ?? '—' }}</td>
                                    <td style="padding:10px 12px;font-family:monospace;font-size:11px;color:#64748b;">{{ $sample->sample_code }}</td>
                                    <td style="padding:10px 12px;font-size:12px;color:#374151;">{{ $sample->quantity ?? '—' }} {{ $sample->quantity_unit ?? '' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Director's remarks --}}
                @if($result && $result->director_comments)
                    <div style="padding:24px 32px;border-bottom:1px solid #e2e8f0;">
                        <h2 style="font-family:'Georgia',serif;font-size:16px;font-weight:700;color:#1a2f4e;margin:0 0 12px;padding-bottom:8px;border-bottom:2px solid #b8922a;">Director's Remarks</h2>
                        <p style="font-size:13px;color:#374151;line-height:1.6;white-space:pre-line;margin:0;">{{ $result->director_comments }}</p>
                    </div>
                @endif

                {{-- Analytical results --}}
                <div style="padding:24px 32px;">
                    <h2 style="font-family:'Georgia',serif;font-size:16px;font-weight:700;color:#1a2f4e;margin:0 0 16px;padding-bottom:8px;border-bottom:2px solid #b8922a;">Analytical Results</h2>

                    @php
                        $sopDocuments = \App\Models\Kstl\Document::where('category', 'sop')
                            ->whereIn('reference_code', array_values(\App\Models\Kstl\SampleTest::TEST_SOPS))
                            ->with('currentVersion')
                            ->get()
                            ->keyBy('reference_code');
                    @endphp

                    @foreach($samples as $sample)
                        <div style="margin-bottom:24px;">
                            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;">
                                <div>
                                    <h4 style="font-size:13px;font-weight:700;color:#1e293b;margin:0 0 2px;">
                                        {{ $sample->common_name ?? $sample->sample_code }}
                                    </h4>
                                    @if($sample->scientific_name)
                                        <p style="font-size:12px;color:#94a3b8;font-style:italic;margin:0 0 2px;">{{ $sample->scientific_name }}</p>
                                    @endif
                                    <p style="font-family:monospace;font-size:11px;color:#94a3b8;margin:0;">{{ $sample->sample_code }}</p>
                                </div>
                            </div>

                            @php $tests = $testsBySample[$sample->id] ?? collect(); @endphp
                            @if($tests->isEmpty())
                                <p style="font-size:13px;color:#94a3b8;font-style:italic;padding:12px 0;margin:0;">No tests recorded for this sample.</p>
                            @else
                                <table style="width:100%;border-collapse:collapse;">
                                    <thead>
                                        <tr style="background:#1a2f4e;">
                                            <th style="padding:8px 12px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">Test</th>
                                            <th style="padding:8px 12px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">Result</th>
                                            <th style="padding:8px 12px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">Methods</th>
                                            <th style="padding:8px 12px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">Outcome</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($tests as $test)
                                            @php
                                                $sopCode = \App\Models\Kstl\SampleTest::TEST_SOPS[$test->test_key] ?? null;
                                                $sopDoc  = $sopCode ? ($sopDocuments[$sopCode] ?? null) : null;
                                                $unit    = $test->result_unit ? ' ' . $test->result_unit : '';
                                                $resultDisplay = match($test->result_qualifier) {
                                                    'detected'     => $test->result_value ? 'Detected · ' . $test->result_value . $unit : 'Detected',
                                                    'not_detected' => $test->result_value ? 'Not Detected · ' . $test->result_value . $unit : 'Not Detected',
                                                    'pass'         => $test->result_value ? 'Pass · ' . $test->result_value . $unit : 'Pass',
                                                    'fail'         => $test->result_value ? 'Fail · ' . $test->result_value . $unit : 'Fail',
                                                    'less_than'    => '< ' . $test->result_value . $unit,
                                                    'greater_than' => '> ' . $test->result_value . $unit,
                                                    'equal_to'     => $test->result_value . $unit,
                                                    default        => ($test->result_value ? $test->result_value . $unit : null),
                                                };
                                            @endphp
                                            <tr style="border-bottom:1px solid #f1f5f9;{{ $loop->even ? 'background:#f8fafc;' : '' }}">
                                                <td style="padding:10px 12px;font-size:13px;font-weight:600;color:#1e293b;">
                                                    {{ method_exists($test, 'getDisplayLabel') ? $test->getDisplayLabel() : $test->test_key }}
                                                </td>
                                                <td style="padding:10px 12px;font-size:13px;color:#374151;">
                                                    @if($resultDisplay)
                                                        {{ $resultDisplay }}
                                                    @else
                                                        <span style="color:#94a3b8;">—</span>
                                                    @endif
                                                </td>
                                                <td style="padding:10px 12px;">
                                                    @if($sopCode && $sopDoc)
                                                        @if($sopDoc->currentVersion)
                                                            <a href="{{ route('staff.documents.preview', $sopDoc->id) }}"
                                                               target="_blank"
                                                               style="display:inline-flex;align-items:center;gap:4px;font-family:monospace;font-size:11px;color:#0d9488;text-decoration:none;">
                                                                {{ $sopCode }}
                                                                <svg style="width:12px;height:12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                            </a>
                                                        @else
                                                            <a href="{{ route('staff.documents.show', $sopDoc->id) }}"
                                                               target="_blank"
                                                               style="display:inline-flex;align-items:center;gap:4px;font-family:monospace;font-size:11px;color:#0d9488;text-decoration:none;">
                                                                {{ $sopCode }}
                                                                <svg style="width:12px;height:12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                                            </a>
                                                        @endif
                                                    @elseif($sopCode)
                                                        <span style="font-family:monospace;font-size:11px;color:#64748b;">{{ $sopCode }}</span>
                                                    @else
                                                        <span style="color:#94a3b8;">—</span>
                                                    @endif
                                                </td>
                                                <td style="padding:10px 12px;">
                                                    @if($test->director_outcome === 'pass')
                                                        <span style="display:inline-flex;align-items:center;padding:3px 10px;border-radius:20px;font-size:10px;font-weight:700;text-transform:uppercase;background:#f0fdf4;color:#166534;">Pass</span>
                                                    @elseif($test->director_outcome === 'fail')
                                                        <span style="display:inline-flex;align-items:center;padding:3px 10px;border-radius:20px;font-size:10px;font-weight:700;text-transform:uppercase;background:#fef2f2;color:#991b1b;">Fail</span>
                                                    @else
                                                        <span style="color:#94a3b8;">—</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            @if($test->result_notes)
                                                <tr>
                                                    <td colspan="4" style="padding:8px 12px;font-size:12px;color:#64748b;background:#f8fafc;">
                                                        <span style="font-weight:600;color:#374151;">Notes:</span> {{ $test->result_notes }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>
                    @endforeach
                </div>

                {{-- Dark doc strip --}}
                <div class="coa-doc-strip">
                    <p class="ref">{{ $submission->reference_number }} &nbsp;·&nbsp; FINAL REPORT</p>
                    <p>Results are confidential and intended solely for the submitting client.</p>
                    <p>Kiribati Seafood Toxicology Laboratory &nbsp;·&nbsp; stld.fisheries.gov.ki</p>
                </div>

            </div>{{-- /coa-paper --}}
        </div>
    </div>
</x-app-layout>
