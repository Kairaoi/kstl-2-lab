{{-- resources/views/kstl/client/results/show.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <div class="no-print" style="background:linear-gradient(135deg,#0f2240 0%,#1a2f4e 60%,#1e3a5f 100%); margin:-1px; padding:28px 2rem; position:relative; overflow:hidden;">
            <div style="position:absolute;inset:0;opacity:.04;background-image:repeating-linear-gradient(45deg,#fff 0,#fff 1px,transparent 0,transparent 50%);background-size:12px 12px;pointer-events:none;"></div>
            <div style="position:absolute;top:0;left:0;right:0;height:3px;background:linear-gradient(90deg,#1a2f4e,#b8922a 30%,#b8922a 70%,#1a2f4e);"></div>
            <div style="max-width:80rem;margin:0 auto;width:100%;display:flex;align-items:center;justify-content:space-between;gap:20px;flex-wrap:wrap;position:relative;">
                <div style="display:flex;align-items:center;gap:18px;">
                    <img src="{{ asset('images/mfor-logo.png') }}"
                         alt="Ministry of Fisheries &amp; Ocean Resources"
                         style="width:56px;height:56px;object-fit:contain;filter:brightness(0) invert(1);opacity:.92;">
                    <div>
                        <p style="font-size:8.5px;font-weight:700;letter-spacing:.22em;text-transform:uppercase;color:#b8922a;margin-bottom:5px;">
                            Client &nbsp;·&nbsp; Seafood Toxicology Laboratory
                        </p>
                        <h1 style="font-family:'Georgia',serif;font-size:22px;font-weight:700;color:#ffffff;line-height:1.2;margin:0;">
                            Certificate of Analysis
                        </h1>
                        <p style="font-family:'Courier New',monospace;font-size:11px;color:#94a3b8;margin-top:4px;">
                            {{ $submission->reference_number }}
                        </p>
                    </div>
                </div>
                <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                    <a href="{{ route('client.results.index') }}"
                       style="display:inline-flex;align-items:center;gap:5px;font-size:11px;font-weight:600;color:#94a3b8;text-decoration:none;">
                        <svg style="width:12px;height:12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        Back to Results
                    </a>
                    <a href="{{ route('client.results.pdf', $submission->id) }}"
                       style="display:inline-flex;align-items:center;gap:6px;background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.2);color:#e2e8f0;padding:7px 16px;border-radius:3px;font-size:11px;font-weight:600;text-decoration:none;">
                        <svg style="width:13px;height:13px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Download PDF
                    </a>
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
            background: #ffffff;
            max-width: 1200px;
            margin: 0 auto;
            border: 1px solid #c9d1d9;
            border-radius: 6px;
            box-shadow: 0 4px 32px rgba(0,0,0,.12);
            overflow: hidden;
        }
        .coa-inner { padding: 40px 48px; }

        /* ── Letterhead ─────────────────────────────────────────── */
        .coa-lh          { display: flex; align-items: flex-start; gap: 16px; }
        .coa-lh-body     { flex: 1; min-width: 0; }
        .coa-lh-contact  {
            flex: 0 0 auto;
            max-width: 200px;
            text-align: right;
            font-size: 10px;
            color: #6b7280;
            line-height: 1.8;
            word-break: break-word;
            overflow-wrap: break-word;
        }
        .coa-lh-contact strong { font-weight: 600; color: #374151; }
        .coa-org-line    { font-size: 8px; font-weight: 700; letter-spacing: .18em;
                           text-transform: uppercase; color: #9ca3af; margin-bottom: 5px; }
        .coa-lab-name    { font-family: 'Georgia', 'Times New Roman', serif;
                           font-size: 19px; font-weight: 700; color: #1a2f4e;
                           line-height: 1.25; }

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
                          font-size: 28px; font-weight: 400; color: #1a2f4e;
                          letter-spacing: .01em; }
        .coa-subtitle   { display: flex; align-items: center; gap: 16px;
                          justify-content: center; margin-top: 7px; }
        .coa-subtitle hr { flex: 1; max-width: 130px; border: none;
                           border-top: 1px solid #b8922a; }
        .coa-subtitle p  { font-size: 8px; font-weight: 700; letter-spacing: .22em;
                           text-transform: uppercase; color: #b8922a; white-space: nowrap; }

        /* ── Prepared-for row ───────────────────────────────────── */
        .coa-for-row    { display: flex; justify-content: space-between;
                          flex-wrap: wrap; gap: 8px; margin-bottom: 14px; }
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
        .coa-meta-val   { font-size: 14px; font-weight: 700; color: #1a2f4e;
                          font-family: 'Georgia', serif; }

        /* ── Details band ───────────────────────────────────────── */
        .coa-details    { background: #f8fafc; border: 1px solid #e5e7eb;
                          border-radius: 6px; padding: 9px 16px;
                          margin-bottom: 22px; overflow: hidden; }
        .coa-details-row { display: flex; flex-wrap: wrap; gap: 4px 24px;
                           font-size: 10.5px; color: #374151; line-height: 1.9; }
        .coa-details-row strong { color: #9ca3af; font-weight: 600; }

        /* ── Results heading ────────────────────────────────────── */
        .coa-results-h   { display: flex; align-items: center; gap: 14px;
                           margin-bottom: 8px; }
        .coa-results-h h3 { font-family: 'Georgia', serif; font-size: 20px;
                            font-weight: 600; color: #1a2f4e; white-space: nowrap; }
        .coa-results-h hr { flex: 1; border: none; border-top: 2px solid #0d9488; }
        .coa-disclaimer  { font-size: 11px; color: #9ca3af; font-style: italic;
                           margin-bottom: 18px; }

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

        /* ── Badges ─────────────────────────────────────────────── */
        .badge-pass { display: inline-flex; align-items: center; gap: 5px;
                      border: 1.5px solid #16a34a; border-radius: 999px;
                      padding: 2px 12px; font-size: 10px; font-weight: 700;
                      color: #16a34a; letter-spacing: .05em; }
        .badge-pass::before { content: ''; width: 5px; height: 5px;
                              border-radius: 50%; background: #16a34a; }
        .badge-fail { display: inline-flex; align-items: center; gap: 5px;
                      border: 1.5px solid #dc2626; border-radius: 999px;
                      padding: 2px 12px; font-size: 10px; font-weight: 700;
                      color: #dc2626; letter-spacing: .05em; }
        .badge-fail::before { content: ''; width: 5px; height: 5px;
                              border-radius: 50%; background: #dc2626; }

        /* ── Signature footer ───────────────────────────────────── */
        .coa-foot       { border-top: 1px solid #e5e7eb; margin-top: 28px;
                          padding-top: 18px; display: flex;
                          justify-content: space-between; align-items: flex-end;
                          flex-wrap: wrap; gap: 16px; }
        .coa-auth-pill  { display: inline-flex; align-items: center; gap: 7px;
                          border: 1.5px solid #0d9488; border-radius: 6px;
                          padding: 6px 14px; }
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
        .coa-doc-strip .ref { font-family: 'Courier New', monospace;
                              font-size: 9px; color: #e2e8f0; }

        /* ── Print ──────────────────────────────────────────────── */
        @media print {
            .no-print { display: none !important; }
            body, .coa-wrap { background: #fff !important; }
            .coa-paper { border: none !important; box-shadow: none !important;
                         max-width: 100% !important; }
            .coa-inner { padding: 20px 28px !important; }
            .coa-smp-hdr, .coa-tbl thead tr, .coa-doc-strip,
            .badge-pass, .badge-fail {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
        @page { size: A4; margin: 10mm; }
    </style>
    @endpush

    <div class="coa-wrap" style="padding:0 0 56px; margin-top:24px;">
        <div style="max-width:1240px;margin:0 auto;padding:0 20px;">
            <div class="coa-paper">
                <div class="coa-inner">

                    {{-- ────────── Letterhead ────────── --}}
                    <div class="coa-lh">
                        {{-- Coat of Arms --}}
                        <div style="flex-shrink:0;">
                            <img src="{{ asset('images/Kiribati_National_Emblem.png') }}"
                                 alt="Coat of Arms"
                                 style="width:72px; height:72px; object-fit:contain;">
                        </div>

                        {{-- Name + org --}}
                        <div class="coa-lh-body">
                            <p class="coa-org-line">Government of Kiribati &nbsp;·&nbsp; Ministry of Fisheries &amp; Ocean Resources</p>
                            <h1 class="coa-lab-name">Kiribati Seafood Toxicology Laboratory</h1>
                        </div>

                        {{-- Contact + MFOR logo --}}
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

                    {{-- Gold rule --}}
                    <div class="coa-rule-gold"></div>

                    {{-- ────────── Title ────────── --}}
                    <div class="coa-title-wrap">
                        <h2 class="coa-title">Certificate of Analysis</h2>
                        <div class="coa-subtitle">
                            <hr><p>Final Report &nbsp;·&nbsp; Official Document</p><hr>
                        </div>
                    </div>
                    <div class="coa-rule-thin"></div>

                    {{-- ────────── Prepared for / Ref ────────── --}}
                    <div class="coa-for-row">
                        <div class="coa-for-block">
                            <p style="font-size:8px; font-weight:700; letter-spacing:.14em; text-transform:uppercase; color:#9ca3af; margin-bottom:3px;">Prepared for</p>
                            <p style="font-size:12.5px; font-weight:700; color:#111827;">{{ $submission->client->company_name }}</p>
                            <p style="font-size:10.5px; color:#6b7280;">{{ $submission->client->user->email }}</p>
                        </div>
                        <div class="coa-for-block" style="text-align:right;">
                            @if($submission->client_reference)
                                <p style="font-size:8px; font-weight:700; letter-spacing:.14em; text-transform:uppercase; color:#9ca3af; margin-bottom:2px;">Client Reference</p>
                                <p style="font-family:'Courier New',monospace; font-size:13px; font-weight:700; color:#111827; margin-bottom:8px;">{{ $submission->client_reference }}</p>
                            @endif
                            <p style="font-size:8px; font-weight:700; letter-spacing:.14em; text-transform:uppercase; color:#9ca3af; margin-bottom:3px;">KSTL Reference</p>
                            <p style="font-family:'Courier New',monospace; font-size:14px; font-weight:700; color:#1d4ed8;">{{ $submission->reference_number }}</p>
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
                                <p class="coa-meta-val" style="font-family:'Courier New',monospace; font-size:12px; margin-bottom:8px;">
                                    {{ $submission->client_reference }}
                                </p>
                            @endif
                            <p class="coa-meta-key">KSTL Reference</p>
                            <p class="coa-meta-val" style="font-family:'Courier New',monospace; font-size:12px;">
                                {{ $submission->reference_number }}
                            </p>
                        </div>
                        <div class="coa-meta-cell">
                            <p class="coa-meta-key">Sample(s) Received</p>
                            <p class="coa-meta-val" style="font-size:13px;">
                                {{ $submission->received_at?->format('d M Y')
                                   ?? $submission->submitted_at?->format('d M Y') ?? '—' }}
                            </p>
                        </div>
                    </div>

                    {{-- ────────── Details band ────────── --}}
                    <div class="coa-details">
                        @php
                            $testStart = $samples->map(fn($s) => $testsBySample[$s->id]?->min('completed_at'))->filter()->min();
                            $testEnd   = $samples->map(fn($s) => $testsBySample[$s->id]?->max('completed_at'))->filter()->max();
                        @endphp
                        <div class="coa-details-row">
                            <span>
                                <strong>Collected:</strong>
                                {{ $submission->collected_at?->format('d M Y') ?? '—' }}
                            </span>
                            @if($submission->delivered_at)
                                <span>
                                    <strong>Delivered:</strong>
                                    {{ $submission->delivered_at->format('d M Y') }}
                                </span>
                            @endif
                            <span>
                                <strong>Submitted:</strong>
                                {{ $submission->submitted_at?->format('d M Y') ?? '—' }}
                            </span>
                            @if($testStart && $testEnd)
                                <span>
                                    <strong>Testing Period:</strong>
                                    {{ \Carbon\Carbon::parse($testStart)->format('d M Y') }}
                                    – {{ \Carbon\Carbon::parse($testEnd)->format('d M Y') }}
                                </span>
                            @endif
                            <span style="color:#9ca3af; font-style:italic; font-size:9.5px;">
                                Date of analysis available on request.
                            </span>
                        </div>
                    </div>

                    {{-- ────────── Sample summary table ────────── --}}
                    <table style="width:100%;border-collapse:collapse;border:1px solid #e5e7eb;border-radius:6px;overflow:hidden;font-size:11.5px;margin-bottom:18px;">
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
                            @foreach($samples as $i => $s)
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

                    {{-- ────────── Results section ────────── --}}
                    <div class="coa-results-h">
                        <h3>Results</h3>
                        <hr>
                    </div>
                    <p class="coa-disclaimer">The tests were performed on the samples as received.</p>

                    @php
                        $sopDocuments = \App\Models\Kstl\Document::where('category', 'sop')
                            ->whereIn('reference_code', array_values(\App\Models\Kstl\SampleTest::TEST_SOPS))
                            ->get()
                            ->keyBy('reference_code');
                    @endphp

                    @foreach($samples as $sample)
                        @php $tests = $testsBySample[$sample->id] ?? collect(); @endphp
                        <div class="coa-sample">

                            {{-- Header bar --}}
                            <div class="coa-smp-hdr">
                                <span class="nm">Customer Sample Name: {{ $sample->common_name ?? $submission->sample_name ?? '—' }}</span>
                                <span class="cd">Sample Code: {{ $sample->sample_code }}</span>
                            </div>

                            {{-- Description row --}}
                            <div class="coa-smp-desc">
                                <span>
                                    <strong>Sample Description:</strong>&nbsp;
                                    @if($sample->scientific_name)
                                        <em>{{ $sample->scientific_name }}</em>
                                        @if($sample->common_name) ({{ $sample->common_name }})@endif
                                    @else
                                        {{ $sample->common_name ?? '—' }}
                                    @endif
                                </span>
                                @if($sample->quantity)
                                    <span>
                                        <strong>Quantity:</strong>
                                        {{ $sample->quantity }}&nbsp;{{ $sample->quantity_unit ?? 'kg' }}
                                    </span>
                                @endif
                                <span>
                                    <strong>Sample Condition:</strong>
                                    {{ ucfirst($sample->condition ?? $sample->sample_condition ?? 'Acceptable') }}
                                </span>
                            </div>

                            {{-- Tests --}}
                            @if($tests->isEmpty())
                                <p style="font-size:11px; color:#9ca3af; padding:12px 14px; font-style:italic;">
                                    No test results recorded.
                                </p>
                            @else
                                <table class="coa-tbl">
                                    <thead>
                                        <tr>
                                            <th style="width:34%;">Test</th>
                                            <th style="width:20%;">Result</th>
                                            <th style="width:12%;">Unit</th>
                                            <th style="width:34%;">Method Reference</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($tests as $test)
                                            @php
                                                $resultText = match($test->result_qualifier) {
                                                    'detected'     => 'Detected',
                                                    'not_detected' => 'Not Detected',
                                                    'pass'         => 'Pass',
                                                    'fail'         => 'Fail',
                                                    'less_than'    => '< '.$test->result_value,
                                                    'greater_than' => '> '.$test->result_value,
                                                    'equal_to'     => $test->result_value,
                                                    default        => ($test->result_value ?: '—'),
                                                };
                                                $sopCode = \App\Models\Kstl\SampleTest::TEST_SOPS[$test->test_key] ?? null;
                                                $sopDoc  = $sopCode ? ($sopDocuments[$sopCode] ?? null) : null;
                                                $isFail  = in_array($test->result_qualifier, ['fail','detected']);
                                                $isPass  = in_array($test->result_qualifier, ['pass','not_detected']);
                                            @endphp
                                            <tr>
                                                <td style="font-weight:600;">{{ $test->getDisplayLabel() }}</td>
                                                <td style="font-weight:700; color:{{ $isFail ? '#dc2626' : ($isPass ? '#16a34a' : '#1f2937') }};">
                                                    {{ $resultText }}
                                                </td>
                                                <td style="color:#6b7280; font-size:10.5px;">
                                                    {{ $test->result_unit ?: '—' }}
                                                </td>
                                                <td>
                                                    @if($sopCode && $sopDoc)
                                                        <a href="{{ route('client.documents.download', $sopDoc->id) }}"
                                                           target="_blank"
                                                           style="font-family:'Courier New',monospace; font-size:11px; color:#1d4ed8; text-decoration:none; font-weight:600;">
                                                            {{ $sopCode }}
                                                        </a>
                                                    @elseif($sopCode)
                                                        <span style="font-family:'Courier New',monospace; font-size:11px; color:#6b7280;">{{ $sopCode }}</span>
                                                    @else
                                                        <span style="color:#9ca3af;">—</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>{{-- /coa-sample --}}
                    @endforeach

                    {{-- Director remarks --}}
                    @if($result?->director_comments)
                        <div style="background:#f0fdf4; border:1px solid #bbf7d0; border-radius:6px; padding:12px 16px; margin-bottom:18px;">
                            <p style="font-size:8px; font-weight:700; letter-spacing:.14em; text-transform:uppercase; color:#16a34a; margin-bottom:6px;">Director's Remarks</p>
                            <p style="font-size:11.5px; color:#166534; line-height:1.7; white-space:pre-line;">{{ $result->director_comments }}</p>
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

                </div>{{-- /coa-inner --}}

                {{-- ────────── Doc strip footer ────────── --}}
                <div class="coa-doc-strip">
                    <p class="ref">{{ $submission->reference_number }} &nbsp;·&nbsp; FINAL REPORT</p>
                    <p>Results are confidential and intended solely for the submitting client.</p>
                    <p>Kiribati Seafood Toxicology Laboratory &nbsp;·&nbsp; kstl.mfor.gov.ki</p>
                </div>

            </div>{{-- /coa-paper --}}
        </div>
    </div>
</x-app-layout>
