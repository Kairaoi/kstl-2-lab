<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title>Internal Report — {{ $submission->reference_number }}</title>
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
        font-family: 'DejaVu Sans', Arial, sans-serif;
        font-size: 10.5px;
        color: #1f2937;
        background: #fff;
    }

    /* ── Letterhead ── */
    .lh {
        border-bottom: 3px solid #b8922a;
        padding: 22px 30px 16px;
    }
    .lh-org {
        font-size: 7.5px;
        font-weight: 700;
        letter-spacing: .15em;
        text-transform: uppercase;
        color: #9ca3af;
        margin-bottom: 4px;
    }
    .lh-name {
        font-family: 'DejaVu Serif', Georgia, serif;
        font-size: 17px;
        font-weight: 700;
        color: #1a2f4e;
        line-height: 1.2;
    }

    /* ── Gold rule ── */
    .rule-gold {
        height: 3px;
        background: #b8922a;
        margin: 10px 30px;
    }
    .rule-thin {
        height: 1px;
        background: #b8922a;
        margin: 3px 30px 12px;
    }

    /* ── Title block ── */
    .title-wrap { text-align: center; padding: 8px 30px 10px; }
    .coa-title {
        font-family: 'DejaVu Serif', Georgia, serif;
        font-size: 24px;
        font-weight: 400;
        color: #1a2f4e;
        letter-spacing: .01em;
    }
    .coa-subtitle {
        font-size: 7.5px;
        font-weight: 700;
        letter-spacing: .2em;
        text-transform: uppercase;
        color: #b8922a;
        margin-top: 5px;
    }

    /* ── Main content area ── */
    .content { padding: 0 30px; }

    /* ── Prepared-for row ── */
    .for-row {
        width: 100%;
        margin-bottom: 12px;
        border-collapse: collapse;
    }
    .for-row td { vertical-align: top; }
    .for-label {
        font-size: 7px;
        font-weight: 700;
        letter-spacing: .14em;
        text-transform: uppercase;
        color: #9ca3af;
        margin-bottom: 2px;
    }
    .for-value {
        font-size: 12px;
        font-weight: 700;
        color: #111827;
        margin-bottom: 2px;
    }
    .for-email { font-size: 10px; color: #6b7280; }
    .ref-code {
        font-family: 'DejaVu Sans Mono', 'Courier New', monospace;
        font-size: 13px;
        font-weight: 700;
        color: #1d4ed8;
    }

    /* ── Meta table ── */
    .meta-tbl {
        width: 100%;
        border-collapse: collapse;
        border: 1px solid #e5e7eb;
        margin-bottom: 10px;
        overflow: hidden;
    }
    .meta-tbl td {
        padding: 9px 14px;
        border: 1px solid #e5e7eb;
        vertical-align: top;
        width: 33.33%;
    }
    .meta-tbl td:nth-child(2) { background: #f8fafc; }
    .meta-key {
        font-size: 7px;
        font-weight: 700;
        letter-spacing: .12em;
        text-transform: uppercase;
        color: #9ca3af;
        margin-bottom: 3px;
    }
    .meta-val {
        font-size: 13px;
        font-weight: 700;
        color: #1a2f4e;
        font-family: 'DejaVu Serif', Georgia, serif;
    }

    /* ── Details band ── */
    .details-band {
        background: #f8fafc;
        border: 1px solid #e5e7eb;
        padding: 7px 14px;
        margin-bottom: 18px;
        font-size: 9.5px;
        color: #374151;
        line-height: 1.9;
    }
    .details-band strong { color: #9ca3af; font-weight: 600; }

    /* ── Section heading ── */
    .section-h {
        margin-bottom: 6px;
        border-bottom: 2px solid #0d9488;
        padding-bottom: 4px;
    }
    .section-h-title {
        font-family: 'DejaVu Serif', Georgia, serif;
        font-size: 15px;
        font-weight: 600;
        color: #1a2f4e;
    }
    .disclaimer {
        font-size: 10px;
        color: #9ca3af;
        font-style: italic;
        margin-bottom: 14px;
    }

    /* ── Samples list ── */
    .samples-tbl {
        width: 100%;
        border-collapse: collapse;
        border: 1px solid #e5e7eb;
        margin-bottom: 16px;
        font-size: 10px;
    }
    .samples-tbl thead tr { background: #f8fafc; }
    .samples-tbl thead th {
        padding: 7px 10px;
        text-align: left;
        font-size: 7.5px;
        font-weight: 700;
        letter-spacing: .1em;
        text-transform: uppercase;
        color: #6b7280;
        border-bottom: 1px solid #e5e7eb;
    }
    .samples-tbl tbody td {
        padding: 7px 10px;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }
    .samples-tbl tbody tr:last-child td { border-bottom: none; }
    .samples-tbl tbody tr.even td { background: #f8fafc; }

    /* ── Sample block ── */
    .sample-block {
        border: 1px solid #d1d5db;
        margin-bottom: 14px;
        overflow: hidden;
    }
    .sample-hdr {
        background: #1a2f4e;
        padding: 7px 12px;
        width: 100%;
        border-collapse: collapse;
    }
    .sample-hdr td { color: #fff; vertical-align: middle; }
    .sample-name {
        font-size: 10.5px;
        font-weight: 700;
        color: #ffffff;
    }
    .sample-code {
        font-family: 'DejaVu Sans Mono', 'Courier New', monospace;
        font-size: 9.5px;
        color: #93c5fd;
        text-align: right;
    }
    .sample-desc {
        background: #f8fafc;
        border-bottom: 1px solid #e5e7eb;
        padding: 6px 12px;
        font-size: 9.5px;
        color: #374151;
    }
    .sample-desc strong { color: #6b7280; font-weight: 600; }

    /* ── Tests table ── */
    .tests-tbl {
        width: 100%;
        border-collapse: collapse;
        font-size: 10px;
    }
    .tests-tbl thead tr { background: #243d5e; }
    .tests-tbl thead th {
        padding: 7px 10px;
        text-align: left;
        font-size: 7.5px;
        font-weight: 700;
        letter-spacing: .1em;
        text-transform: uppercase;
        color: #e2e8f0;
    }
    .tests-tbl tbody td {
        padding: 7px 10px;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }
    .tests-tbl tbody tr:last-child td { border-bottom: none; }
    .tests-tbl tbody tr.even td { background: #fafafa; }
    .result-pass { color: #16a34a; font-weight: 700; }
    .result-fail { color: #dc2626; font-weight: 700; }
    .result-unit { color: #6b7280; font-size: 9.5px; }
    .sop-code {
        font-family: 'DejaVu Sans Mono', 'Courier New', monospace;
        font-size: 9.5px;
        color: #1d4ed8;
        font-weight: 600;
    }
    .analyst-cell { color: #475569; font-size: 9.5px; }

    /* ── Director remarks ── */
    .remarks-box {
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        padding: 10px 14px;
        margin-bottom: 16px;
    }
    .remarks-label {
        font-size: 7.5px;
        font-weight: 700;
        letter-spacing: .12em;
        text-transform: uppercase;
        color: #16a34a;
        margin-bottom: 5px;
    }
    .remarks-text { font-size: 10.5px; color: #166534; line-height: 1.7; }

    /* ── Confidential notice ── */
    .confidential-box {
        background: #fff5f5;
        border: 1px solid #fecaca;
        padding: 10px 14px;
        margin-bottom: 16px;
    }
    .confidential-label {
        font-size: 7.5px;
        font-weight: 700;
        letter-spacing: .12em;
        text-transform: uppercase;
        color: #dc2626;
        margin-bottom: 4px;
    }
    .confidential-text { font-size: 9.5px; color: #6b7280; line-height: 1.6; }

    /* ── Signature footer ── */
    .sig-footer {
        border-top: 1px solid #e5e7eb;
        margin-top: 20px;
        padding-top: 14px;
        width: 100%;
        border-collapse: collapse;
    }
    .sig-footer td { vertical-align: bottom; }
    .auth-box {
        border: 1.5px solid #0d9488;
        padding: 5px 12px;
        display: inline-block;
    }
    .auth-label { font-size: 10px; font-weight: 700; color: #0d9488; }
    .sig-line { border-top: 1px solid #9ca3af; margin-bottom: 5px; width: 200px; }
    .sig-name {
        font-family: 'DejaVu Serif', Georgia, serif;
        font-size: 16px;
        font-weight: 700;
        color: #1a2f4e;
        text-align: right;
    }
    .sig-role { font-size: 8.5px; color: #9ca3af; text-align: right; }

    /* ── Doc strip footer ── */
    .doc-strip {
        background: #1a2f4e;
        padding: 8px 30px;
        margin-top: 18px;
        width: 100%;
        border-collapse: collapse;
    }
    .doc-strip td {
        font-size: 8px;
        color: #93c5fd;
        letter-spacing: .03em;
        vertical-align: middle;
    }
    .doc-strip .ref {
        font-family: 'DejaVu Sans Mono', 'Courier New', monospace;
        font-size: 8px;
        color: #e2e8f0;
    }
    .doc-strip .right { text-align: right; }
</style>
</head>
<body>

    {{-- ── Letterhead ── --}}
    <div class="lh">
        <table style="width:100%; border-collapse:collapse;">
            <tr>
                <td style="width:78px; vertical-align:top; padding-right:12px;">
                    <img src="{{ public_path('images/mfor-logo.png') }}"
                         alt="MFOR" style="width:64px; height:64px;">
                </td>
                <td style="vertical-align:top;">
                    <div class="lh-org">Government of Kiribati &nbsp;&bull;&nbsp; Ministry of Fisheries &amp; Ocean Resources</div>
                    <div class="lh-name">Kiribati Seafood Toxicology Laboratory</div>
                </td>
                <td style="width:200px; vertical-align:top; text-align:right; font-size:9px; color:#6b7280; line-height:1.75;">
                    <strong style="color:#374151;">Seafood Toxicology Laboratory</strong><br>
                    Ministry of Fisheries and Ocean Resources,<br>Tarawa, Kiribati<br>
                    t. +686 75021099<br>
                    e. stld@mfor.gov.ki<br>
                    w. stld.mfor.gov.ki
                </td>
            </tr>
        </table>
    </div>

    {{-- ── Gold rule + title ── --}}
    <div class="rule-gold"></div>
    <div class="title-wrap">
        <div class="coa-title">Certificate of Analysis</div>
        <div class="coa-subtitle">Internal Report &nbsp;&bull;&nbsp; Director Use Only</div>
    </div>
    <div class="rule-thin"></div>

    {{-- ── Content ── --}}
    <div class="content">

        {{-- ── Prepared-for / Reference ── --}}
        <table class="for-row">
            <tr>
                <td style="width:55%;">
                    <div class="for-label">Prepared for</div>
                    <div class="for-value">{{ $submission->client->company_name }}</div>
                    <div class="for-email">{{ $submission->client->user->email }}</div>
                </td>
                <td style="width:45%; text-align:right;">
                    @if($submission->client_reference)
                        <div class="for-label">Client Reference</div>
                        <div style="font-family:'DejaVu Sans Mono',monospace; font-size:12px; font-weight:700; color:#111827; margin-bottom:6px;">{{ $submission->client_reference }}</div>
                    @endif
                    <div class="for-label">KSTL Reference</div>
                    <div class="ref-code">{{ $submission->reference_number }}</div>
                </td>
            </tr>
        </table>

        {{-- ── Meta table ── --}}
        <table class="meta-tbl">
            <tr>
                <td>
                    <div class="meta-key">Report Issued</div>
                    <div class="meta-val">{{ $result?->authorised_at?->format('d M Y') ?? now()->format('d M Y') }}</div>
                </td>
                <td>
                    <div class="meta-key">KSTL Reference</div>
                    <div style="font-family:'DejaVu Sans Mono',monospace; font-size:12px; font-weight:700; color:#111827;">{{ $submission->reference_number }}</div>
                </td>
                <td>
                    <div class="meta-key">Priority</div>
                    <div class="meta-val" style="font-size:13px; {{ ($submission->priority === 'urgent') ? 'color:#dc2626;' : '' }}">
                        {{ ucfirst($submission->priority ?? 'Routine') }}
                    </div>
                </td>
            </tr>
        </table>

        {{-- ── Details band ── --}}
        @php
            $dirSamples = $submission->samples;
            $testStart  = $dirSamples->map(fn($s) => $s->sampleTests->min('completed_at'))->filter()->min();
            $testEnd    = $dirSamples->map(fn($s) => $s->sampleTests->max('completed_at'))->filter()->max();
        @endphp
        <div class="details-band">
            <strong>Collected:</strong> {{ $submission->collected_at?->format('d M Y') ?? '—' }}
            @if($submission->delivered_at)
                &nbsp;&nbsp; <strong>Delivered:</strong> {{ $submission->delivered_at->format('d M Y') }}
            @endif
            &nbsp;&nbsp; <strong>Submitted:</strong> {{ $submission->submitted_at?->format('d M Y') ?? '—' }}
            @if($testStart && $testEnd)
                &nbsp;&nbsp; <strong>Testing Period:</strong>
                {{ \Carbon\Carbon::parse($testStart)->format('d M Y') }} – {{ \Carbon\Carbon::parse($testEnd)->format('d M Y') }}
            @endif
            &nbsp;&nbsp; <strong>Samples Submitted:</strong> {{ $submission->samples->count() }}
        </div>

        {{-- ── Samples list ── --}}
        <table class="samples-tbl">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Common Name</th>
                    <th>Scientific Name</th>
                    <th>Sample Code</th>
                    <th>Qty</th>
                </tr>
            </thead>
            <tbody>
                @foreach($submission->samples as $i => $s)
                    <tr class="{{ $i % 2 === 1 ? 'even' : '' }}">
                        <td style="color:#94a3b8; font-family:'DejaVu Sans Mono',monospace; font-size:9.5px;">{{ $i + 1 }}</td>
                        <td style="font-weight:600; color:#1e293b;">{{ $s->common_name ?? '—' }}</td>
                        <td style="font-style:italic; color:#64748b;">{{ $s->scientific_name ?? '—' }}</td>
                        <td style="font-family:'DejaVu Sans Mono',monospace; font-size:9.5px; color:#64748b;">{{ $s->sample_code }}</td>
                        <td style="color:#64748b;">{{ $s->quantity ?? '—' }} {{ $s->quantity_unit ?? '' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- ── Authorised Results ── --}}
        @php
            $allTests = $submission->samples->flatMap(fn($s) => $s->sampleTests->map(fn($t) => ['sample' => $s, 'test' => $t]));
            $returnedTests   = $allTests->filter(fn($row) =>
                $row['test']->status === 'flagged' &&
                str_contains($row['test']->result_notes ?? '', '[Director query]')
            );
            $returnedTestIds = $returnedTests->pluck('test.id')->all();
            $authorisedTests = $allTests->filter(fn($row) => ! in_array($row['test']->id, $returnedTestIds));

            $sopDocuments = \App\Models\Kstl\Document::where('category', 'sop')
                ->whereIn('reference_code', array_values(\App\Models\Kstl\SampleTest::TEST_SOPS))
                ->get()
                ->keyBy('reference_code');
        @endphp

        <div class="section-h">
            <span class="section-h-title">Authorised Results</span>
        </div>
        <div class="disclaimer">The tests were performed on the samples as received.</div>

        @foreach($submission->samples as $sample)
            @php
                $sampleTests = $authorisedTests->filter(fn($r) => $r['sample']->id === $sample->id);
            @endphp
            <div class="sample-block">

                <table class="sample-hdr" style="width:100%; border-collapse:collapse;">
                    <tr>
                        <td class="sample-name">
                            Customer Sample Name: {{ $sample->common_name ?? $submission->sample_name ?? '—' }}
                        </td>
                        <td class="sample-code" style="text-align:right;">
                            Sample Code: {{ $sample->sample_code }}
                        </td>
                    </tr>
                </table>

                <div class="sample-desc">
                    @if($sample->scientific_name)
                        <strong>Scientific Name:</strong> <em>{{ $sample->scientific_name }}</em>
                    @endif
                    @if($sample->quantity)
                        &nbsp;&nbsp; <strong>Quantity:</strong> {{ $sample->quantity }}&nbsp;{{ $sample->quantity_unit ?? 'kg' }}
                    @endif
                </div>

                @if($sampleTests->isEmpty())
                    <div style="font-size:10px; color:#9ca3af; padding:10px 12px; font-style:italic;">
                        No test results recorded.
                    </div>
                @else
                    <table class="tests-tbl">
                        <thead>
                            <tr>
                                <th style="width:27%;">Test</th>
                                <th style="width:18%;">Result</th>
                                <th style="width:10%;">Unit</th>
                                <th style="width:22%;">Method Reference</th>
                                <th style="width:23%;">Analyst</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sampleTests as $i => $row)
                                @php
                                    $test = $row['test'];
                                    $unit = $test->result_unit ? ' ' . $test->result_unit : '';
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
                                    $sopCode = \App\Models\Kstl\SampleTest::TEST_SOPS[$test->test_key] ?? null;
                                    $isFail  = in_array($test->result_qualifier, ['fail','detected']);
                                    $isPass  = in_array($test->result_qualifier, ['pass','not_detected']);
                                @endphp
                                <tr class="{{ $i % 2 === 1 ? 'even' : '' }}">
                                    <td style="font-weight:600;">{{ $test->getDisplayLabel() }}</td>
                                    <td class="{{ $isFail ? 'result-fail' : ($isPass ? 'result-pass' : '') }}">
                                        {{ $resultText }}
                                    </td>
                                    <td class="result-unit">{{ $test->result_unit ?: '—' }}</td>
                                    <td>
                                        @if($sopCode)
                                            <span class="sop-code">{{ $sopCode }}</span>
                                        @else
                                            <span style="color:#9ca3af;">—</span>
                                        @endif
                                    </td>
                                    <td class="analyst-cell">{{ $test->assignedTo?->name ?? '—' }}</td>
                                </tr>
                                @if($test->result_notes && ! str_contains($test->result_notes, '[Director query]'))
                                    <tr>
                                        <td colspan="5" style="padding:5px 10px 8px; font-size:9.5px; color:#64748b; font-style:italic; background:#f8fafc;">
                                            <strong style="font-style:normal; color:#374151;">Notes:</strong> {{ $test->result_notes }}
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        @endforeach

        {{-- ── Returned for Review ── --}}
        @if($returnedTests->isNotEmpty())
            <div style="background:#fffbeb; border:1px solid #fde68a; padding:8px 12px; margin-bottom:10px;">
                <div style="font-size:7.5px; font-weight:700; letter-spacing:.1em; text-transform:uppercase; color:#92400e; margin-bottom:3px;">
                    Returned for Analyst Review
                </div>
                <div style="font-size:9.5px; color:#92400e;">
                    The following tests were queried by the Director and returned for analyst re-confirmation.
                </div>
            </div>

            @foreach($submission->samples as $sample)
                @php $sampleReturnedTests = $returnedTests->filter(fn($r) => $r['sample']->id === $sample->id); @endphp
                @if($sampleReturnedTests->isNotEmpty())
                    <div class="sample-block" style="border-color:#fde68a;">
                        <table class="sample-hdr" style="width:100%; border-collapse:collapse; background:#78350f;">
                            <tr>
                                <td class="sample-name">{{ $sample->common_name ?? $sample->sample_code }}</td>
                                <td class="sample-code" style="text-align:right;">{{ $sample->sample_code }}</td>
                            </tr>
                        </table>
                        <table class="tests-tbl">
                            <thead>
                                <tr>
                                    <th style="width:27%;">Test</th>
                                    <th style="width:20%;">Result</th>
                                    <th style="width:10%;">Unit</th>
                                    <th style="width:22%;">Method</th>
                                    <th style="width:21%;">Analyst</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sampleReturnedTests as $i => $row)
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
                                    <tr style="background:#fffbeb;" class="{{ $i % 2 === 1 ? 'even' : '' }}">
                                        <td style="font-weight:600;">{{ $test->getDisplayLabel() }}</td>
                                        <td>{{ $resultText }}</td>
                                        <td class="result-unit">{{ $test->result_unit ?: '—' }}</td>
                                        <td><span class="sop-code" style="color:#6b7280;">{{ $sopCode ?? '—' }}</span></td>
                                        <td class="analyst-cell">{{ $test->assignedTo?->name ?? '—' }}</td>
                                    </tr>
                                    @if($queryNote)
                                        <tr>
                                            <td colspan="5" style="padding:5px 10px 8px; font-size:9.5px; color:#92400e; background:#fefce8;">
                                                <strong>Director query:</strong> {{ $queryNote }}
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

        {{-- ── Director's Comments ── --}}
        @if($result?->director_comments)
            <div class="remarks-box">
                <div class="remarks-label">Director's Comments</div>
                <div class="remarks-text">{{ $result->director_comments }}</div>
            </div>
        @endif

        {{-- ── Confidential notice ── --}}
        <div class="confidential-box">
            <div class="confidential-label">INTERNAL DOCUMENT — CONFIDENTIAL</div>
            <div class="confidential-text">
                This document is for Director use only and must not be shared with the client or external parties.
                The client-facing Certificate of Analysis does not include determination outcomes or analyst details.
            </div>
        </div>

        {{-- ── Signature footer ── --}}
        <table class="sig-footer">
            <tr>
                <td style="vertical-align:bottom;">
                    @if($result?->authorised_at)
                        <div class="auth-box">
                            <div class="auth-label">&#10003; Authorisation Status: Authorised</div>
                        </div>
                    @else
                        <div style="font-size:10px; color:#9ca3af;">Authorisation Status: Pending</div>
                    @endif
                </td>
                <td style="text-align:right; vertical-align:bottom;">
                    @if($result?->authorised_at)
                        <div class="sig-line"></div>
                        <div class="sig-name">{{ $result->authorisedBy?->name ?? 'Laboratory Director' }}</div>
                        <div class="sig-role">
                            Laboratory Director &nbsp;&bull;&nbsp;
                            {{ $result->authorised_at->format('d M Y \a\t H:i') }}
                        </div>
                    @endif
                </td>
            </tr>
        </table>

    </div>{{-- /content --}}

    {{-- ── Doc strip footer ── --}}
    <table class="doc-strip" style="margin-top:18px;">
        <tr>
            <td class="ref">{{ $submission->reference_number }} &nbsp;&bull;&nbsp; INTERNAL REPORT</td>
            <td style="text-align:center; font-size:8px; color:#93c5fd;">INTERNAL — CONFIDENTIAL. Director use only. Not for client distribution.</td>
            <td class="right" style="font-size:8px; color:#93c5fd;">Kiribati Seafood Toxicology Laboratory &nbsp;&bull;&nbsp; stld.mfor.gov.ki</td>
        </tr>
    </table>

</body>
</html>
