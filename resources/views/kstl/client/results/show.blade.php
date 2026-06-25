{{-- resources/views/kstl/client/results/show.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <a href="{{ route('client.results.index') }}"
                   class="text-gray-400 hover:text-gray-600 transition no-print">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <div>
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">Certificate of Analysis</h2>
                    <p class="text-xs text-gray-400 mt-0.5">{{ $submission->reference_number }}</p>
                </div>
            </div>
            <button onclick="window.print()"
                    class="no-print inline-flex items-center gap-2 px-3 py-1.5 text-xs font-medium text-gray-600 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                Print / Save PDF
            </button>
        </div>
    </x-slot>

    @push('styles')
    <style>
        .coa-doc { background: #fff; border: 1px solid #d1d5db; font-family: 'Noto Sans', sans-serif; }

        /* Letterhead */
        .coa-lh-left  { font-size: 11px; color: #374151; line-height: 1.6; }
        .coa-lh-right { font-size: 10px; color: #6b7280; text-align: right; line-height: 1.7; }
        .coa-org      { font-size: 8.5px; font-weight: 700; letter-spacing: .15em; text-transform: uppercase; color: #6b7280; }
        .coa-lab-name { font-family: 'Noto Serif', serif; font-size: 22px; font-weight: 700; color: #1e3a5f; }
        .coa-crest    { width: 52px; height: 52px; border-radius: 50%; border: 2px solid #b8922a; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .coa-crest svg { width: 26px; height: 26px; stroke: #1e3a5f; fill: none; }

        /* Title */
        .coa-title { font-family: 'Noto Serif', serif; font-size: 28px; font-weight: 400; color: #1e3a5f; letter-spacing: .01em; }

        /* Metadata blocks */
        .coa-addr-block  { font-size: 11px; color: #374151; line-height: 1.8; }
        .coa-ref-block   { font-size: 11px; color: #374151; line-height: 1.8; text-align: right; }
        .coa-meta-row    { border-top: 1px solid #e5e7eb; border-bottom: 1px solid #e5e7eb; }
        .coa-meta-cell   { padding: 10px 0; }
        .coa-meta-key    { font-size: 9px; font-weight: 700; letter-spacing: .12em; text-transform: uppercase; color: #9ca3af; }
        .coa-meta-val    { font-size: 13px; font-weight: 600; color: #111827; margin-top: 2px; }
        .coa-period-bar  { font-size: 11px; color: #374151; padding: 6px 0; border-bottom: 1px solid #e5e7eb; }

        /* Results section */
        .coa-results-heading {
            font-family: 'Noto Serif', serif; font-size: 18px; font-weight: 600; color: #1e3a5f;
            border-bottom: 2px solid #0d9488; padding-bottom: 6px; margin-bottom: 10px;
        }
        .coa-disclaimer { font-size: 11px; color: #6b7280; font-style: italic; margin-bottom: 16px; }

        /* Sample block */
        .coa-sample-header {
            background: #1e3a5f; color: #fff; padding: 8px 12px;
            display: flex; justify-content: space-between; align-items: center;
        }
        .coa-sample-header .label { font-size: 11px; font-weight: 700; letter-spacing: .04em; }
        .coa-sample-header .code  { font-family: monospace; font-size: 11px; font-weight: 600; }
        .coa-sample-desc { background: #f8fafc; border: 1px solid #e2e8f0; border-top: none; padding: 6px 12px; font-size: 10.5px; color: #374151; }

        /* Results table */
        .coa-table { width: 100%; border-collapse: collapse; font-size: 11.5px; margin-top: 0; }
        .coa-table thead tr { background: #1e3a5f; color: #fff; }
        .coa-table thead th { padding: 8px 12px; text-align: left; font-size: 10px; font-weight: 700; letter-spacing: .1em; text-transform: uppercase; }
        .coa-table tbody tr { border-bottom: 1px solid #e5e7eb; }
        .coa-table tbody tr:last-child { border-bottom: none; }
        .coa-table tbody td { padding: 9px 12px; color: #1f2937; vertical-align: middle; }
        .coa-table tbody tr:nth-child(even) { background: #f9fafb; }

        /* Outcome badge */
        .coa-outcome-pass { display: inline-flex; align-items: center; border: 1.5px solid #16a34a; border-radius: 999px; padding: 2px 14px; font-size: 11px; font-weight: 700; color: #16a34a; letter-spacing: .06em; }
        .coa-outcome-fail { display: inline-flex; align-items: center; border: 1.5px solid #dc2626; border-radius: 999px; padding: 2px 14px; font-size: 11px; font-weight: 700; color: #dc2626; letter-spacing: .06em; }

        /* Footer */
        .coa-foot { border-top: 2px solid #1e3a5f; margin-top: 24px; padding-top: 14px; }
        .coa-sig-name { font-family: 'Noto Serif', serif; font-size: 17px; font-weight: 700; color: #1e3a5f; }
        .coa-sig-line { border-top: 1px solid #374151; width: 220px; margin-bottom: 4px; }
        .coa-sig-role { font-size: 10.5px; color: #6b7280; }
        .coa-auth-status { font-size: 11px; }
        .coa-auth-status .label { color: #6b7280; }
        .coa-auth-status .value { font-weight: 700; color: #16a34a; }

        @media print {
            .no-print { display: none !important; }
            body { background: #fff !important; }
            .coa-doc { border: none !important; box-shadow: none !important; }
            .page-hdr, .gov-stripe, .gov-top, nav, .app-footer { display: none !important; }
            .coa-outcome-pass, .coa-outcome-fail { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .coa-table thead tr { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .coa-sample-header { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        }
    </style>
    @endpush

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <div class="coa-doc rounded-xl shadow-sm overflow-hidden px-10 py-8">

                {{-- ── Letterhead ──────────────────────────────────────────── --}}
                <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:16px;">
                    <div style="display:flex; align-items:flex-start; gap:14px;">
                        <div class="coa-crest">
                            <svg viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21"/>
                            </svg>
                        </div>
                        <div>
                            <p class="coa-org">Government of Kiribati &middot; Ministry of Fisheries &amp; Ocean Resources</p>
                            <h1 class="coa-lab-name" style="margin-top:3px;">Kiribati Seafood Toxicology Laboratory</h1>
                        </div>
                    </div>
                    <div class="coa-lh-right" style="padding-top:4px;">
                        <p style="font-weight:600; color:#374151;">Seafood Toxicology Laboratory</p>
                        <p>National Fisheries, Tarawa, Kiribati</p>
                        <p>t. +686 [Your Number]</p>
                        <p>e. stld@fisheries.gov.ki</p>
                        <p>w. stld.fisheries.gov.ki</p>
                    </div>
                </div>

                {{-- ── CoA Title ───────────────────────────────────────────── --}}
                <div style="text-align:center; border-top:1px solid #e5e7eb; border-bottom:1px solid #e5e7eb; padding:14px 0; margin-bottom:16px;">
                    <h2 class="coa-title">Certificate of Analysis</h2>
                </div>

                {{-- ── Address + Ref block ─────────────────────────────────── --}}
                <div style="display:flex; justify-content:space-between; gap:24px; margin-bottom:14px;">
                    <div class="coa-addr-block">
                        <strong>Kiribati Seafood Toxicology Laboratory</strong><br>
                        National Fisheries Authority<br>
                        Tanaea, Tarawa<br>
                        Republic of Kiribati
                    </div>
                    <div class="coa-ref-block">
                        <p>Submission Reference: <strong style="color:#1d4ed8;">{{ $submission->reference_number }}</strong></p>
                        <p style="font-weight:700; color:#111827;">Final Report</p>
                        <p>Document Ref: {{ $submission->reference_number }}</p>
                        <p>STLD &middot; Official Portal</p>
                    </div>
                </div>

                {{-- ── Metadata row: Report Issued / KSTL Reference / Samples Received ── --}}
                <div class="coa-meta-row" style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:0; margin-bottom:0;">
                    <div class="coa-meta-cell" style="border-right:1px solid #e5e7eb; padding-right:16px;">
                        <p class="coa-meta-key">Report Issued</p>
                        <p class="coa-meta-val">{{ $result?->authorised_at?->format('d M Y') ?? now()->format('d M Y') }}</p>
                    </div>
                    <div class="coa-meta-cell" style="padding-left:16px; border-right:1px solid #e5e7eb; padding-right:16px;">
                        <p class="coa-meta-key">KSTL Reference</p>
                        <p class="coa-meta-val" style="font-family:monospace;">{{ $submission->reference_number }}</p>
                    </div>
                    <div class="coa-meta-cell" style="padding-left:16px;">
                        <p class="coa-meta-key">Sample(s) Received</p>
                        <p class="coa-meta-val">{{ $submission->received_at?->format('d M Y H:i') ?? $submission->submitted_at?->format('d M Y H:i') ?? '—' }}</p>
                    </div>
                </div>

                {{-- ── Testing period / dates bar ──────────────────────────── --}}
                <div class="coa-period-bar" style="margin-top:0; margin-bottom:20px;">
                    @php
                        $testStart = $samples->map(fn($s) => $s->sampleTests->min('completed_at'))->filter()->min();
                        $testEnd   = $samples->map(fn($s) => $s->sampleTests->max('completed_at'))->filter()->max();
                    @endphp
                    @if($testStart && $testEnd)
                        <strong>Testing Period:</strong>
                        {{ \Carbon\Carbon::parse($testStart)->format('d M Y') }} to
                        {{ \Carbon\Carbon::parse($testEnd)->format('d M Y') }}
                        &nbsp;&middot;&nbsp; <em>Date of analysis is available on request.</em>
                    @endif
                    &nbsp;&nbsp;
                    <strong>Collected:</strong> {{ $submission->collected_at?->format('d M Y') ?? '—' }}
                    @if($submission->delivered_at)
                        &nbsp;&middot;&nbsp; <strong>Delivered:</strong> {{ $submission->delivered_at->format('d M Y') }}
                    @endif
                    &nbsp;&middot;&nbsp; <strong>Submitted:</strong> {{ $submission->submitted_at?->format('d M Y') ?? '—' }}
                    &nbsp;&middot;&nbsp; <strong>Prepared for:</strong> {{ $submission->client->company_name }} ({{ $submission->client->user->email ?? '' }})
                </div>

                {{-- ── Results section ─────────────────────────────────────── --}}
                <h3 class="coa-results-heading">Results</h3>
                <p class="coa-disclaimer">The tests were performed on the samples as received.</p>

                @php
                    $sopDocuments = \App\Models\Kstl\Document::where('category', 'sop')
                        ->whereIn('reference_code', array_values(\App\Models\Kstl\SampleTest::TEST_SOPS))
                        ->get()
                        ->keyBy('reference_code');
                @endphp

                @foreach($samples as $sample)
                    @php $tests = $testsBySample[$sample->id] ?? collect(); @endphp
                    <div style="margin-bottom:24px;">

                        {{-- Sample header bar --}}
                        <div class="coa-sample-header">
                            <span class="label">Customer Sample Name: {{ $sample->common_name ?? $submission->sample_name ?? '—' }}</span>
                            <span class="code">Sample Code: {{ $sample->sample_code }}</span>
                        </div>

                        {{-- Sample description row --}}
                        <div class="coa-sample-desc" style="display:flex; gap:32px; flex-wrap:wrap;">
                            <span>
                                <strong>Sample Description:</strong>
                                @if($sample->scientific_name)
                                    <em>{{ $sample->scientific_name }}</em>
                                    @if($sample->common_name) ({{ $sample->common_name }})@endif
                                @else
                                    {{ $sample->common_name ?? '—' }}
                                @endif
                            </span>
                            @if($sample->quantity)
                                <span><strong>Quantity:</strong> {{ $sample->quantity }} {{ $sample->quantity_unit ?? '' }}</span>
                            @endif
                            @if($sample->condition ?? $sample->sample_condition ?? null)
                                <span><strong>Sample Condition:</strong> {{ ucfirst($sample->condition ?? $sample->sample_condition) }}</span>
                            @else
                                <span><strong>Sample Condition:</strong> Acceptable</span>
                            @endif
                        </div>

                        {{-- Results table --}}
                        @if($tests->isEmpty())
                            <p style="font-size:11px; color:#9ca3af; padding:10px 12px; border:1px solid #e5e7eb; border-top:none; font-style:italic;">No test results available.</p>
                        @else
                            <table class="coa-table">
                                <thead>
                                    <tr>
                                        <th style="width:30%;">Test</th>
                                        <th style="width:15%;">Result</th>
                                        <th style="width:10%;">Unit</th>
                                        <th style="width:25%;">Method Reference</th>
                                        <th style="width:20%;">Outcome</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($tests as $test)
                                        @php
                                            $unit = $test->result_unit ?: '';
                                            $resultText = match($test->result_qualifier) {
                                                'detected'     => 'Detected',
                                                'not_detected' => 'Not Detected',
                                                'pass'         => 'Pass',
                                                'fail'         => 'Fail',
                                                'less_than'    => '< ' . $test->result_value,
                                                'greater_than' => '> ' . $test->result_value,
                                                'equal_to'     => $test->result_value,
                                                default        => ($test->result_value ?: '—'),
                                            };
                                            $sopCode = \App\Models\Kstl\SampleTest::TEST_SOPS[$test->test_key] ?? null;
                                            $sopDoc  = $sopCode ? ($sopDocuments[$sopCode] ?? null) : null;
                                            $isPass  = in_array($test->result_qualifier, ['pass', 'not_detected']);
                                            $isFail  = in_array($test->result_qualifier, ['fail', 'detected']);
                                        @endphp
                                        <tr>
                                            <td style="font-weight:600;">{{ $test->getDisplayLabel() }}</td>
                                            <td style="color: {{ $isFail ? '#dc2626' : ($isPass ? '#16a34a' : '#1f2937') }}; font-weight:600;">
                                                {{ $resultText }}
                                            </td>
                                            <td style="color:#6b7280;">{{ $unit ?: '—' }}</td>
                                            <td>
                                                @if($sopCode && $sopDoc)
                                                    <a href="{{ route('client.documents.download', $sopDoc->id) }}"
                                                       target="_blank"
                                                       style="font-family:monospace; font-size:11px; color:#1d4ed8; text-decoration:none;">
                                                        {{ $sopCode }}
                                                    </a>
                                                @elseif($sopCode)
                                                    <span style="font-family:monospace; font-size:11px; color:#6b7280;">{{ $sopCode }}</span>
                                                @else
                                                    <span style="color:#9ca3af;">—</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($test->director_outcome === 'pass')
                                                    <span class="coa-outcome-pass">Pass</span>
                                                @elseif($test->director_outcome === 'fail')
                                                    <span class="coa-outcome-fail">Fail</span>
                                                @else
                                                    <span style="color:#9ca3af; font-size:11px;">—</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                @endforeach

                {{-- ── Director's remarks ───────────────────────────────────── --}}
                @if($result?->director_comments)
                    <div style="border-top:1px solid #e5e7eb; padding-top:14px; margin-bottom:20px;">
                        <p style="font-size:10px; font-weight:700; letter-spacing:.1em; text-transform:uppercase; color:#9ca3af; margin-bottom:6px;">Director's Remarks</p>
                        <p style="font-size:11.5px; color:#374151; line-height:1.7; white-space:pre-line;">{{ $result->director_comments }}</p>
                    </div>
                @endif

                {{-- ── Footer: Authorisation status + Director signature ────── --}}
                <div class="coa-foot" style="display:flex; justify-content:space-between; align-items:flex-end; gap:16px;">
                    <div class="coa-auth-status">
                        <p class="label">Authorisation Status:
                            @if($result?->authorised_at)
                                <span class="value">Authorised</span>
                            @else
                                <span style="color:#9ca3af; font-weight:600;">Pending</span>
                            @endif
                        </p>
                    </div>

                    @if($result?->authorised_at)
                        <div style="text-align:right;">
                            <p class="coa-sig-name">{{ $result->authorisedBy?->name ?? 'Laboratory Director' }}</p>
                            <div class="coa-sig-line" style="margin-left:auto;"></div>
                            <p class="coa-sig-role">Laboratory Director &middot; {{ $result->authorised_at->format('d M Y \a\t H:i') }}</p>
                        </div>
                    @endif
                </div>

            </div>{{-- /coa-doc --}}

            <div class="pb-8"></div>

        </div>
    </div>
</x-app-layout>
