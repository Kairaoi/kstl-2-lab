{{-- resources/views/kstl/director/results/show.blade.php --}}
{{-- Internal director-only result report. NOT shared with client. --}}

<x-app-layout>
    <x-slot name="header">
        <div style="position:relative;overflow:hidden;background:linear-gradient(135deg,#0f2240 0%,#1a2f4e 60%,#1e3a5f 100%);margin:-1px;">
            <div style="height:3px;background:linear-gradient(90deg,#1a2f4e,#b8922a 30%,#b8922a 70%,#1a2f4e);"></div>
            <div style="max-width:80rem;margin:0 auto;padding:28px 2rem 32px;">
                <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;">
                    <div style="display:flex;align-items:center;gap:20px;">
                        <img src="{{ asset('images/mfor-logo.png') }}" alt="MFOR" style="filter:brightness(0) invert(1);opacity:.92;width:56px;height:56px;flex-shrink:0;">
                        <div>
                            <p style="font-size:9px;font-weight:700;letter-spacing:.18em;text-transform:uppercase;color:#b8922a;margin:0 0 4px;">Director &middot; Internal Report</p>
                            <h1 style="font-family:'Georgia',serif;font-size:22px;font-weight:700;color:#fff;margin:0 0 6px;line-height:1.2;">{{ $submission->reference_number }}</h1>
                            <p style="font-size:12px;color:#94a3b8;margin:0;">Director use only &middot; {{ $submission->sample_name }}</p>
                        </div>
                    </div>
                    <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;" class="no-print">
                        <span style="display:inline-flex;align-items:center;padding:5px 14px;background:#fee2e2;color:#991b1b;border:1px solid #fca5a5;border-radius:3px;font-size:11px;font-weight:700;letter-spacing:.06em;">
                            INTERNAL &mdash; NOT FOR CLIENT
                        </span>
                        <a href="{{ route('director.results.pdf', $submission->id) }}"
                           style="display:inline-flex;align-items:center;gap:6px;background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.2);color:#e2e8f0;padding:8px 20px;border-radius:3px;font-size:12px;font-weight:700;letter-spacing:.06em;text-decoration:none;">
                            <svg style="width:13px;height:13px;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Download PDF
                        </a>
                        <a href="{{ route('director.submissions.show', $submission->id) }}"
                           style="display:inline-flex;align-items:center;gap:8px;padding:8px 20px;background:#fff;color:#1a2f4e;font-size:12px;font-weight:700;letter-spacing:.06em;border:1px solid #fff;border-radius:3px;text-decoration:none;">
                            &larr; Back
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
        .app-main { padding-left: 0 !important; padding-right: 0 !important;
                    padding-top: 0 !important; max-width: 100% !important; }

        /* ── Base ──────────────────────────────────────────────── */
        .coa-wrap  { background: #eef2f7; }
        .coa-paper {
            background: #ffffff; max-width: 1200px; margin: 0 auto;
            border: 1px solid #c9d1d9; border-radius: 6px;
            box-shadow: 0 4px 32px rgba(0,0,0,.12); overflow: hidden;
        }
        .coa-inner { padding: 40px 48px; }

        /* ── Letterhead ─────────────────────────────────────────── */
        .coa-lh          { display: flex; align-items: flex-start; gap: 16px; }
        .coa-lh-body     { flex: 1; min-width: 0; }
        .coa-lh-contact  {
            flex: 0 0 auto; max-width: 200px; text-align: right;
            font-size: 10px; color: #6b7280; line-height: 1.8; word-break: break-word;
        }
        .coa-lh-contact strong { font-weight: 600; color: #374151; }
        .coa-org-line    { font-size: 8px; font-weight: 700; letter-spacing: .18em;
                           text-transform: uppercase; color: #9ca3af; margin-bottom: 5px; }
        .coa-lab-name    { font-family: 'Georgia', 'Times New Roman', serif;
                           font-size: 19px; font-weight: 700; color: #1a2f4e; line-height: 1.25; }

        /* ── Rules ──────────────────────────────────────────────── */
        .coa-rule-gold {
            height: 3px;
            background: linear-gradient(90deg, #1a2f4e 0%, #b8922a 40%, #b8922a 60%, #1a2f4e 100%);
            margin: 16px 0;
        }
        .coa-rule-thin {
            height: 1px;
            background: linear-gradient(90deg, transparent 0%, #b8922a 50%, transparent 100%);
            margin: 4px 0 16px;
        }

        /* ── Certificate title ──────────────────────────────────── */
        .coa-title-wrap { text-align: center; padding: 12px 0 14px; }
        .coa-title      { font-family: 'Georgia', 'Times New Roman', serif;
                          font-size: 28px; font-weight: 400; color: #1a2f4e; letter-spacing: .01em; }
        .coa-subtitle   { display: flex; align-items: center; gap: 16px;
                          justify-content: center; margin-top: 7px; }
        .coa-subtitle hr { flex: 1; max-width: 130px; border: none; border-top: 1px solid #b8922a; }
        .coa-subtitle p  { font-size: 8px; font-weight: 700; letter-spacing: .22em;
                           text-transform: uppercase; color: #b8922a; white-space: nowrap; }

        /* ── Prepared-for row ───────────────────────────────────── */
        .coa-for-row    { display: flex; justify-content: space-between; flex-wrap: wrap; gap: 8px; margin-bottom: 14px; }
        .coa-for-block  { min-width: 0; }

        /* ── Three-cell meta ────────────────────────────────────── */
        .coa-meta       { display: grid; grid-template-columns: repeat(3,1fr);
                          border: 1px solid #e5e7eb; border-radius: 6px;
                          overflow: hidden; margin-bottom: 12px; }
        .coa-meta-cell  { padding: 12px 16px; }
        .coa-meta-cell + .coa-meta-cell { border-left: 1px solid #e5e7eb; }
        .coa-meta-cell:nth-child(2) { background: #f8fafc; }
        .coa-meta-key   { font-size: 8px; font-weight: 700; letter-spacing: .14em;
                          text-transform: uppercase; color: #9ca3af; margin-bottom: 4px; }
        .coa-meta-val   { font-size: 14px; font-weight: 700; color: #1a2f4e; font-family: 'Georgia', serif; }

        /* ── Details band ───────────────────────────────────────── */
        .coa-details    { background: #f8fafc; border: 1px solid #e5e7eb;
                          border-radius: 6px; padding: 9px 16px;
                          margin-bottom: 22px; overflow: hidden; }
        .coa-details-row { display: flex; flex-wrap: wrap; gap: 4px 24px;
                           font-size: 10.5px; color: #374151; line-height: 1.9; }
        .coa-details-row strong { color: #9ca3af; font-weight: 600; }

        /* ── Results heading ────────────────────────────────────── */
        .coa-results-h   { display: flex; align-items: center; gap: 14px; margin-bottom: 8px; }
        .coa-results-h h3 { font-family: 'Georgia', serif; font-size: 20px;
                            font-weight: 600; color: #1a2f4e; white-space: nowrap; }
        .coa-results-h hr { flex: 1; border: none; border-top: 2px solid #0d9488; }
        .coa-disclaimer  { font-size: 11px; color: #9ca3af; font-style: italic; margin-bottom: 18px; }

        /* ── Sample card ────────────────────────────────────────── */
        .coa-sample     { border: 1px solid #d1d5db; border-radius: 6px;
                          overflow: hidden; margin-bottom: 18px;
                          box-shadow: 0 1px 6px rgba(0,0,0,.07); }
        .coa-smp-hdr    { background: #1a2f4e; padding: 9px 14px;
                          display: flex; justify-content: space-between;
                          align-items: center; gap: 12px; flex-wrap: wrap; }
        .coa-smp-hdr .nm { font-size: 11.5px; font-weight: 700; color: #ffffff;
                           min-width: 0; word-break: break-word; }
        .coa-smp-hdr .cd { font-family: 'Courier New', monospace; font-size: 10.5px;
                           color: #93c5fd; flex-shrink: 0; }
        .coa-smp-desc   { background: #f8fafc; border-bottom: 1px solid #e5e7eb;
                          padding: 7px 14px; display: flex; gap: 24px;
                          flex-wrap: wrap; font-size: 10.5px; color: #374151; }
        .coa-smp-desc strong { color: #6b7280; font-weight: 600; }

        /* ── Results table ──────────────────────────────────────── */
        .coa-tbl        { width: 100%; border-collapse: collapse; font-size: 11.5px; }
        .coa-tbl thead tr { background: #243d5e; }
        .coa-tbl thead th { padding: 8px 14px; text-align: left; font-size: 9px;
                            font-weight: 700; letter-spacing: .12em;
                            text-transform: uppercase; color: #e2e8f0; }
        .coa-tbl tbody tr { border-bottom: 1px solid #f1f5f9; }
        .coa-tbl tbody tr:last-child { border-bottom: none; }
        .coa-tbl tbody td { padding: 10px 14px; color: #1f2937; vertical-align: middle; }
        .coa-tbl tbody tr:nth-child(even) td { background: #fafafa; }

        /* ── Signature footer ───────────────────────────────────── */
        .coa-foot       { border-top: 1px solid #e5e7eb; margin-top: 28px;
                          padding-top: 18px; display: flex;
                          justify-content: space-between; align-items: flex-end;
                          flex-wrap: wrap; gap: 16px; }
        .coa-auth-pill  { display: inline-flex; align-items: center; gap: 7px;
                          border: 1.5px solid #0d9488; border-radius: 6px; padding: 6px 14px; }
        .coa-auth-pill svg { width: 14px; height: 14px; stroke: #0d9488; fill: none; }
        .coa-auth-pill p { font-size: 11px; font-weight: 700; color: #0d9488; }
        .coa-sig-name   { font-family: 'Georgia', serif; font-size: 14px;
                          font-weight: 700; color: #1a2f4e; text-align: right; }
        .coa-sig-line   { border-top: none; margin-bottom: 6px; padding-top: 48px; }
        .coa-sig-role   { font-size: 9.5px; color: #9ca3af; text-align: right; }

        /* ── Document footer strip ──────────────────────────────── */
        .coa-doc-strip  { background: #1a2f4e; padding: 10px 48px;
                          display: flex; justify-content: space-between;
                          align-items: center; flex-wrap: wrap; gap: 6px; }
        .coa-doc-strip p { font-size: 9px; color: #93c5fd; letter-spacing: .03em; }
        .coa-doc-strip .ref { font-family: 'Courier New', monospace; font-size: 9px; color: #e2e8f0; }

        /* ── Print ──────────────────────────────────────────────── */
        @media print {
            .no-print { display: none !important; }
            body, .coa-wrap { background: #fff !important; }
            .coa-paper { border: none !important; box-shadow: none !important; max-width: 100% !important; }
            .coa-inner { padding: 20px 28px !important; }
            .coa-smp-hdr, .coa-tbl thead tr, .coa-doc-strip {
                -webkit-print-color-adjust: exact; print-color-adjust: exact;
            }
            .page-hdr, .gov-stripe, .gov-top, nav, .app-footer { display: none !important; }
        }
        @page { size: A4; margin: 10mm; }
    </style>
    @endpush

    {{-- Internal use banner --}}
    <div class="no-print" style="background:#fffbeb;border-bottom:1px solid #fde68a;padding:12px 2rem;">
        <div style="max-width:900px;margin:0 auto;">
            <p style="font-size:13px;color:#92400e;font-weight:600;margin:0;">
                This is the internal Director's report. It contains outcome determination and analyst details not included in the client-facing Certificate of Analysis.
            </p>
        </div>
    </div>

    <div class="coa-wrap" style="padding:24px 0 56px;">
        <div style="max-width:1240px;margin:0 auto;padding:0 20px;">
            <div class="coa-paper">
                <div class="coa-inner">

                    {{-- ────────── Letterhead ────────── --}}
                    <div class="coa-lh">
                        <div style="flex-shrink:0;">
                            <img src="{{ asset('images/Kiribati_National_Emblem.png') }}"
                                 alt="Coat of Arms"
                                 style="width:72px;height:72px;object-fit:contain;">
                        </div>
                        <div class="coa-lh-body">
                            <p class="coa-org-line">Government of Kiribati &nbsp;·&nbsp; Ministry of Fisheries &amp; Ocean Resources</p>
                            <h1 class="coa-lab-name">Kiribati Seafood Toxicology Laboratory</h1>
                        </div>
                        <div class="coa-lh-contact" style="display:flex; align-items:flex-start; gap:12px;">
                            <img src="{{ asset('images/mfor-logo.png') }}"
                                 alt="MFOR"
                                 style="flex-shrink:0; width:72px; height:72px; object-fit:contain;">
                            <div style="text-align:right;">
                                <strong>Seafood Toxicology Laboratory</strong><br>
                                Ministry of Fisheries and Ocean Resources,<br>Tarawa, Kiribati<br>
                                t. +686 75021099<br>
                                e. stld@mfor.gov.ki<br>
                                w. kstl.mfor.gov.ki
                            </div>
                        </div>
                    </div>

                    <div class="coa-rule-gold"></div>

                    {{-- ────────── Title ────────── --}}
                    <div class="coa-title-wrap">
                        <h2 class="coa-title">Certificate of Analysis</h2>
                        <div class="coa-subtitle">
                            <hr><p>Internal Report &nbsp;·&nbsp; Director Use Only</p><hr>
                        </div>
                    </div>
                    <div class="coa-rule-thin"></div>

                    {{-- ────────── Prepared for / Ref ────────── --}}
                    <div class="coa-for-row">
                        <div class="coa-for-block">
                            <p style="font-size:8px;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:#9ca3af;margin-bottom:3px;">Prepared for</p>
                            <p style="font-size:12.5px;font-weight:700;color:#111827;">{{ $submission->client->company_name }}</p>
                            <p style="font-size:10.5px;color:#6b7280;">{{ $submission->client->user->email }}</p>
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

                    {{-- ────────── Three-cell meta ────────── --}}
                    <div class="coa-meta">
                        <div class="coa-meta-cell">
                            <p class="coa-meta-key">Report Issued</p>
                            <p class="coa-meta-val">
                                {{ $result?->authorised_at?->format('d M Y') ?? now()->format('d M Y') }}
                            </p>
                        </div>
                        <div class="coa-meta-cell">
                            @if($submission->client_reference)
                                <p class="coa-meta-key">Client Reference</p>
                                <p class="coa-meta-val" style="font-family:'Courier New',monospace;font-size:12px;margin-bottom:8px;">
                                    {{ $submission->client_reference }}
                                </p>
                            @endif
                            <p class="coa-meta-key">KSTL Reference</p>
                            <p class="coa-meta-val" style="font-family:'Courier New',monospace;font-size:12px;">
                                {{ $submission->reference_number }}
                            </p>
                        </div>
                        <div class="coa-meta-cell">
                            <p class="coa-meta-key">Priority</p>
                            <p class="coa-meta-val" style="font-size:13px;text-transform:capitalize;{{ ($submission->priority === 'urgent') ? 'color:#dc2626;' : '' }}">
                                {{ $submission->priority ?? 'Routine' }}
                            </p>
                        </div>
                    </div>

                    {{-- ────────── Details band ────────── --}}
                    <div class="coa-details">
                        @php
                            $dirSamples = $submission->samples;
                            $testStart  = $dirSamples->map(fn($s) => $s->sampleTests->min('completed_at'))->filter()->min();
                            $testEnd    = $dirSamples->map(fn($s) => $s->sampleTests->max('completed_at'))->filter()->max();
                        @endphp
                        <div class="coa-details-row">
                            <span><strong>Collected:</strong> {{ $submission->collected_at?->format('d M Y') ?? '—' }}</span>
                            @if($submission->delivered_at)
                                <span><strong>Delivered:</strong> {{ $submission->delivered_at->format('d M Y') }}</span>
                            @endif
                            <span><strong>Submitted:</strong> {{ $submission->submitted_at?->format('d M Y') ?? '—' }}</span>
                            @if($testStart && $testEnd)
                                <span>
                                    <strong>Testing Period:</strong>
                                    {{ \Carbon\Carbon::parse($testStart)->format('d M Y') }}
                                    – {{ \Carbon\Carbon::parse($testEnd)->format('d M Y') }}
                                </span>
                            @endif
                            <span><strong>Samples Submitted:</strong> {{ $submission->samples->count() }}</span>
                        </div>
                    </div>

                    {{-- ── Samples list ────────────────────────────────────── --}}
                    <div style="margin-bottom:22px;">
                        <table style="width:100%;border-collapse:collapse;border:1px solid #e5e7eb;border-radius:6px;overflow:hidden;font-size:11.5px;">
                            <thead>
                                <tr style="background:#f8fafc;">
                                    <th style="padding:8px 12px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#6b7280;">#</th>
                                    <th style="padding:8px 12px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#6b7280;">Common Name</th>
                                    <th style="padding:8px 12px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#6b7280;">Scientific Name</th>
                                    <th style="padding:8px 12px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#6b7280;">Sample Code</th>
                                    <th style="padding:8px 12px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#6b7280;">Client Reference</th>
                                    <th style="padding:8px 12px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#6b7280;">Qty</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($submission->samples as $i => $s)
                                    @php $clientRef = $submission->sample_items[$i]['client_sample_ref'] ?? ''; @endphp
                                    <tr style="border-bottom:1px solid #f1f5f9;{{ $loop->even ? 'background:#f8fafc;' : '' }}">
                                        <td style="padding:8px 12px;color:#94a3b8;font-family:'Courier New',monospace;font-size:11px;">{{ $i + 1 }}</td>
                                        <td style="padding:8px 12px;font-weight:600;color:#1e293b;">{{ $s->common_name ?? '—' }}</td>
                                        <td style="padding:8px 12px;font-style:italic;color:#64748b;">{{ $s->scientific_name ?? '—' }}</td>
                                        <td style="padding:8px 12px;font-family:'Courier New',monospace;font-size:11px;color:#64748b;">{{ $s->sample_code }}</td>
                                        <td style="padding:8px 12px;font-family:'Courier New',monospace;font-size:11px;color:#64748b;">{{ $clientRef ?: '—' }}</td>
                                        <td style="padding:8px 12px;color:#64748b;">{{ $s->quantity ?? '—' }} {{ $s->quantity_unit ?? '' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @php
                        $allTests        = $submission->samples->flatMap(fn($s) => $s->sampleTests->map(fn($t) => ['sample' => $s, 'test' => $t]));
                        $returnedTests   = $allTests->filter(fn($row) =>
                            $row['test']->status === 'flagged' &&
                            str_contains($row['test']->result_notes ?? '', '[Director query]')
                        );
                        $returnedTestIds = $returnedTests->pluck('test.id')->all();
                        $authorisedTests = $allTests->filter(fn($row) => ! in_array($row['test']->id, $returnedTestIds));

                        $sopDocuments = \App\Models\Kstl\Document::where('category', 'sop')
                            ->whereIn('reference_code', array_values(\App\Models\Kstl\SampleTest::TEST_SOPS))
                            ->with(['currentVersion'])
                            ->get()
                            ->keyBy('reference_code');
                    @endphp

                    {{-- ────────── Authorised Results ────────── --}}
                    <div class="coa-results-h">
                        <h3>Authorised Results</h3>
                        <hr>
                        @if($result?->authorised_at)
                            <span style="display:inline-flex;align-items:center;gap:6px;padding:4px 12px;background:#dcfce7;color:#166534;border:1px solid #86efac;border-radius:3px;font-size:11px;font-weight:700;white-space:nowrap;">
                                Authorised
                            </span>
                        @endif
                    </div>
                    <p class="coa-disclaimer">The tests were performed on the samples as received.</p>

                    @foreach($submission->samples as $sample)
                        @php
                            $sampleAuthorisedTests = $authorisedTests->filter(fn($r) => $r['sample']->id === $sample->id);
                        @endphp
                        <div class="coa-sample">
                            <div class="coa-smp-hdr">
                                <span class="nm">Customer Sample Name: {{ $sample->common_name ?? $sample->sample_code }}</span>
                                <span class="cd">Sample Code: {{ $sample->sample_code }}</span>
                            </div>
                            <div class="coa-smp-desc">
                                @if($sample->scientific_name)
                                    <span><strong>Scientific Name:</strong> <em>{{ $sample->scientific_name }}</em></span>
                                @endif
                                @if($sample->quantity)
                                    <span><strong>Quantity:</strong> {{ $sample->quantity }}&nbsp;{{ $sample->quantity_unit ?? 'kg' }}</span>
                                @endif
                            </div>

                            @if($sampleAuthorisedTests->isEmpty())
                                <p style="font-size:11px;color:#9ca3af;padding:12px 14px;font-style:italic;">No test results available.</p>
                            @else
                                <table class="coa-tbl">
                                    <thead>
                                        <tr>
                                            <th style="width:28%;">Test</th>
                                            <th style="width:20%;">Result</th>
                                            <th style="width:10%;">Unit</th>
                                            <th style="width:22%;">Method Reference</th>
                                            <th style="width:20%;">Analyst</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($sampleAuthorisedTests as $row)
                                            @php
                                                $test    = $row['test'];
                                                $sopCode = \App\Models\Kstl\SampleTest::TEST_SOPS[$test->test_key] ?? null;
                                                $sopDoc  = $sopCode ? ($sopDocuments[$sopCode] ?? null) : null;
                                                $unit    = $test->result_unit ? ' ' . $test->result_unit : '';
                                                $resultText = match($test->result_qualifier) {
                                                    'detected'     => $test->result_value ? 'Detected · ' . $test->result_value . $unit : 'Detected',
                                                    'not_detected' => $test->result_value ? 'Not Detected · ' . $test->result_value . $unit : 'Not Detected',
                                                    'pass'         => $test->result_value ? 'Pass · ' . $test->result_value . $unit : 'Pass',
                                                    'fail'         => $test->result_value ? 'Fail · ' . $test->result_value . $unit : 'Fail',
                                                    'less_than'    => '< ' . $test->result_value . $unit,
                                                    'greater_than' => '> ' . $test->result_value . $unit,
                                                    'equal_to'     => $test->result_value . $unit,
                                                    default        => ($test->result_value ? $test->result_value . $unit : '—'),
                                                };
                                                $isFail = in_array($test->result_qualifier, ['detected', 'fail']);
                                                $isPass = in_array($test->result_qualifier, ['not_detected', 'pass']);
                                            @endphp
                                            <tr>
                                                <td style="font-weight:600;">{{ $test->getDisplayLabel() }}</td>
                                                <td style="font-weight:700;color:{{ $isFail ? '#dc2626' : ($isPass ? '#16a34a' : '#1f2937') }};">
                                                    {{ $resultText }}
                                                </td>
                                                <td style="color:#6b7280;font-size:10.5px;">{{ $test->result_unit ?: '—' }}</td>
                                                <td>
                                                    @if($sopCode && $sopDoc && $sopDoc->currentVersion)
                                                        <a href="{{ route('staff.documents.preview', $sopDoc->id) }}" target="_blank"
                                                           style="font-family:'Courier New',monospace;font-size:11px;color:#1d4ed8;text-decoration:none;font-weight:600;">{{ $sopCode }}</a>
                                                    @elseif($sopCode && $sopDoc)
                                                        <a href="{{ route('staff.documents.show', $sopDoc->id) }}" target="_blank"
                                                           style="font-family:'Courier New',monospace;font-size:11px;color:#1d4ed8;text-decoration:none;font-weight:600;">{{ $sopCode }}</a>
                                                    @elseif($sopCode)
                                                        <span style="font-family:'Courier New',monospace;font-size:11px;color:#6b7280;">{{ $sopCode }}</span>
                                                    @else
                                                        <span style="color:#9ca3af;">—</span>
                                                    @endif
                                                </td>
                                                <td style="font-size:11px;color:#64748b;">{{ $test->assignedTo?->name ?? '—' }}</td>
                                            </tr>
                                            @if($test->result_notes)
                                                <tr>
                                                    <td colspan="5" style="padding:6px 14px 10px;font-size:11px;color:#64748b;font-style:italic;background:#f8fafc;">
                                                        <span style="font-weight:700;font-style:normal;color:#374151;">Notes:</span> {{ $test->result_notes }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>{{-- /coa-sample --}}
                    @endforeach

                    {{-- ── Analyst Section — Returned for Review ────────────── --}}
                    @if($returnedTests->isNotEmpty())
                        <div style="background:#fffbeb;border:1px solid #fde68a;border-radius:6px;padding:12px 16px;margin-bottom:12px;">
                            <p style="font-size:11px;font-weight:700;color:#92400e;margin:0 0 4px;text-transform:uppercase;letter-spacing:.06em;">Returned for Analyst Review</p>
                            <p style="font-size:11px;color:#92400e;margin:0;">The following tests have been queried. The analyst has been notified and will re-confirm results before resubmitting.</p>
                        </div>
                        @foreach($submission->samples as $sample)
                            @php $sampleReturnedTests = $returnedTests->filter(fn($r) => $r['sample']->id === $sample->id); @endphp
                            @if($sampleReturnedTests->isNotEmpty())
                                <div class="coa-sample" style="border-color:#fde68a;">
                                    <div class="coa-smp-hdr" style="background:#78350f;">
                                        <span class="nm">{{ $sample->common_name ?? $sample->sample_code }}</span>
                                        <span class="cd">{{ $sample->sample_code }}</span>
                                    </div>
                                    <table class="coa-tbl">
                                        <thead>
                                            <tr>
                                                <th>Test</th><th>Result</th><th>Unit</th><th>Method</th><th>Analyst</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($sampleReturnedTests as $row)
                                                @php
                                                    $test = $row['test'];
                                                    $unit = $test->result_unit ? ' ' . $test->result_unit : '';
                                                    $resultText = match($test->result_qualifier) {
                                                        'less_than'    => '< ' . $test->result_value . $unit,
                                                        'greater_than' => '> ' . $test->result_value . $unit,
                                                        'equal_to'     => $test->result_value . $unit,
                                                        default        => $test->result_value ? $test->result_value . $unit : '—',
                                                    };
                                                    $sopCode = \App\Models\Kstl\SampleTest::TEST_SOPS[$test->test_key] ?? null;
                                                    preg_match('/\[Director query\]\s*(.+?)(?:\n\n|$)/s', $test->result_notes ?? '', $m);
                                                    $queryNote = trim($m[1] ?? '');
                                                @endphp
                                                <tr style="background:#fffbeb;">
                                                    <td style="font-weight:600;">{{ $test->getDisplayLabel() }}</td>
                                                    <td>{{ $resultText }}</td>
                                                    <td style="color:#6b7280;font-size:10.5px;">{{ $test->result_unit ?: '—' }}</td>
                                                    <td><span style="font-family:'Courier New',monospace;font-size:11px;color:#6b7280;">{{ $sopCode ?? '—' }}</span></td>
                                                    <td style="font-size:11px;color:#64748b;">{{ $test->assignedTo?->name ?? '—' }}</td>
                                                </tr>
                                                @if($queryNote)
                                                    <tr>
                                                        <td colspan="5" style="padding:6px 14px 10px;font-size:11px;color:#92400e;background:#fefce8;">
                                                            <span style="font-weight:700;">Director query:</span> {{ $queryNote }}
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        @endforeach
                    @endif

                    {{-- ── Sample Assessment Record ─────────────────────────── --}}
                    @php $assessedSamples = $submission->samples->filter(fn($s) => $s->assessment !== null); @endphp
                    @if($assessedSamples->isNotEmpty())
                        @php
                            $allAccepted = $assessedSamples->every(fn($s) => $s->assessment->outcome === 'accepted');
                            $anyRejected = $assessedSamples->some(fn($s)  => $s->assessment->outcome === 'rejected');
                        @endphp
                        <div style="margin-top:28px;padding-top:20px;border-top:1px solid #e5e7eb;">
                            <div style="display:flex;align-items:center;gap:14px;margin-bottom:16px;">
                                <h3 style="font-family:'Georgia',serif;font-size:18px;font-weight:600;color:#1a2f4e;white-space:nowrap;margin:0;">Sample Assessment Record</h3>
                                <div style="flex:1;border-top:2px solid #b8922a;"></div>
                                @if($allAccepted)
                                    <span style="display:inline-flex;padding:4px 12px;background:#dcfce7;color:#166534;border:1px solid #86efac;border-radius:3px;font-size:11px;font-weight:700;white-space:nowrap;">All Accepted</span>
                                @elseif($anyRejected)
                                    <span style="display:inline-flex;padding:4px 12px;background:#fee2e2;color:#991b1b;border:1px solid #fca5a5;border-radius:3px;font-size:11px;font-weight:700;white-space:nowrap;">Rejected</span>
                                @endif
                            </div>

                            @foreach($assessedSamples as $sample)
                                @php $a = $sample->assessment; @endphp
                                <div style="{{ !$loop->last ? 'margin-bottom:20px;padding-bottom:20px;border-bottom:1px solid #e5e7eb;' : '' }}">
                                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;">
                                        <div>
                                            <p style="font-size:13px;font-weight:700;color:#1e293b;margin:0;">{{ $sample->common_name }}</p>
                                            <p style="font-family:'Courier New',monospace;font-size:11px;color:#94a3b8;margin:2px 0 0;">{{ $sample->sample_code }}</p>
                                        </div>
                                        <div style="display:flex;align-items:center;gap:12px;">
                                            <span style="display:inline-flex;padding:3px 10px;font-size:12px;font-weight:700;border-radius:9999px;{{ $a->outcome === 'accepted' ? 'background:#dcfce7;color:#166534;' : 'background:#fee2e2;color:#991b1b;' }}">
                                                {{ ucfirst($a->outcome) }}
                                            </span>
                                            @if($a->assessedBy)
                                                <div style="text-align:right;">
                                                    <p style="font-size:12px;color:#64748b;margin:0;">{{ $a->assessedBy->name }}</p>
                                                    <p style="font-size:11px;color:#94a3b8;margin:2px 0 0;">{{ ($a->assessed_at ?? $a->created_at)->format('d M Y H:i') }}</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    @php
                                        $criteria = [
                                            'Temperature'         => [$a->temperature_ok, $a->temperature_notes],
                                            'Storage Condition'   => [$a->storage_ok,     $a->storage_notes],
                                            'Transport Condition' => [$a->transport_ok,   $a->transport_notes],
                                            'Packaging Integrity' => [$a->packaging_ok,   $a->packaging_notes],
                                            'Colour / Appearance' => [$a->colour_ok,      $a->colour_notes],
                                            'Weight / Quantity'   => [$a->weight_ok,      $a->weight_notes],
                                        ];
                                    @endphp
                                    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:8px;">
                                        @foreach($criteria as $label => [$pass, $notes])
                                            <div style="border-radius:3px;border:1px solid;padding:10px 12px;{{ $pass ? 'border-color:#bbf7d0;background:#f0fdf4;' : 'border-color:#fecaca;background:#fff5f5;' }}">
                                                <div style="display:flex;align-items:center;justify-content:space-between;gap:8px;">
                                                    <span style="font-size:12px;font-weight:600;color:#374151;">{{ $label }}</span>
                                                    <span style="font-size:12px;font-weight:700;{{ $pass ? 'color:#16a34a;' : 'color:#dc2626;' }}">{{ $pass ? 'Pass' : 'Fail' }}</span>
                                                </div>
                                                @if($notes)
                                                    <p style="font-size:11px;color:#64748b;margin:4px 0 0;line-height:1.4;">{{ $notes }}</p>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                    @if($a->additional_observations)
                                        <div style="margin-top:8px;background:#f8fafc;border-radius:3px;padding:10px 12px;font-size:12px;color:#475569;">
                                            <span style="font-weight:700;">Observations:</span> {{ $a->additional_observations }}
                                        </div>
                                    @endif
                                    @if($a->rejection_reason)
                                        <div style="margin-top:8px;background:#fff5f5;border:1px solid #fecaca;border-radius:3px;padding:10px 12px;font-size:12px;color:#991b1b;">
                                            <span style="font-weight:700;">Rejection reason:</span> {{ $a->rejection_reason }}
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif

                    {{-- ── Director's Comments ─────────────────────────────── --}}
                    @if($result?->director_comments)
                        <div style="margin-top:24px;background:#f0fdf4;border:1px solid #bbf7d0;border-radius:6px;padding:12px 16px;">
                            <p style="font-size:8px;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:#16a34a;margin-bottom:6px;">Director's Comments</p>
                            <p style="font-size:11.5px;color:#166534;line-height:1.7;white-space:pre-line;margin:0;">{{ $result->director_comments }}</p>
                        </div>
                    @endif

                    {{-- ────────── Signature footer ────────── --}}
                    <div class="coa-foot">
                        <div></div>
                        @if($result?->authorised_at)
                            <div style="min-width:220px;">
                                <div class="coa-sig-line"></div>
                                <p class="coa-sig-name">{{ $result->authorisedBy?->name ?? 'Laboratory Director' }}</p>
                                <p class="coa-sig-role">
                                    Laboratory Director &nbsp;·&nbsp;
                                    {{ $result->authorised_at->format('d M Y \a\t H:i') }}
                                </p>
                            </div>
                        @endif
                    </div>

                    {{-- ── Confidential notice ─────────────────────────────── --}}
                    <div style="margin-top:20px;padding:12px 16px;background:#fef2f2;border:1px solid #fecaca;border-radius:6px;">
                        <p style="font-size:11px;font-weight:700;color:#dc2626;margin:0 0 4px;text-transform:uppercase;letter-spacing:.06em;">INTERNAL DOCUMENT — CONFIDENTIAL</p>
                        <p style="font-size:11px;color:#64748b;line-height:1.6;margin:0;">
                            This document is for Director use only and must not be shared with the client or external parties.
                            The client-facing Certificate of Analysis does not include determination outcomes or analyst details.
                        </p>
                    </div>

                </div>{{-- /coa-inner --}}

                {{-- ────────── Doc strip footer ────────── --}}
                <div class="coa-doc-strip">
                    <p class="ref">{{ $submission->reference_number }} &nbsp;·&nbsp; INTERNAL REPORT</p>
                    <p>INTERNAL — CONFIDENTIAL. Director use only. Not for client distribution.</p>
                    <p>Kiribati Seafood Toxicology Laboratory &nbsp;·&nbsp; kstl.mfor.gov.ki</p>
                </div>

            </div>{{-- /coa-paper --}}
        </div>
    </div>
</x-app-layout>
