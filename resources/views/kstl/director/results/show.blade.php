{{-- resources/views/kstl/director/results/show.blade.php --}}
{{-- Internal director-only result report. NOT shared with client. --}}

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <a href="{{ route('director.submissions.show', $submission->id) }}"
                   class="text-gray-400 hover:text-gray-600 transition no-print">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <div>
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        Internal Report — {{ $submission->reference_number }}
                    </h2>
                    <p class="text-xs text-gray-400 mt-0.5">Director use only &middot; {{ $submission->sample_name }}</p>
                </div>
            </div>
            <div class="flex items-center gap-2 no-print">
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700 ring-1 ring-red-600/20">
                    INTERNAL — NOT FOR CLIENT
                </span>
                <button onclick="window.print()"
                        class="inline-flex items-center gap-2 px-3 py-1.5 text-xs font-medium text-gray-600 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                    </svg>
                    Print / Save PDF
                </button>
            </div>
        </div>
    </x-slot>

    @push('styles')
    <style>
        .ir-doc { background: var(--surface); border: 1px solid var(--border); }
        .ir-letterhead { border-bottom: 3px double var(--navy); background: linear-gradient(180deg, #fbfaf8 0%, #ffffff 100%); }
        .ir-crest { width: 54px; height: 54px; border-radius: 50%; border: 2px solid var(--gold); display: flex; align-items: center; justify-content: center; }
        .ir-crest svg { width: 28px; height: 28px; stroke: var(--navy); fill: none; }
        .ir-title { font-family: 'Noto Serif', serif; color: var(--navy); letter-spacing: .01em; }
        .ir-eyebrow { letter-spacing: .18em; text-transform: uppercase; font-size: 10px; color: var(--gold); font-weight: 700; }
        .ir-meta-label { letter-spacing: .08em; text-transform: uppercase; font-size: 10px; color: var(--subtle); font-weight: 600; }
        .ir-section-title { font-family: 'Noto Serif', serif; color: var(--navy); font-size: 13px; font-weight: 700; letter-spacing: .02em; display: flex; align-items: center; gap: 8px; }
        .ir-section-title::before { content: ''; width: 3px; height: 14px; background: var(--gold); border-radius: 2px; display: inline-block; }
        .ir-seal { font-family: 'Noto Serif', serif; font-weight: 700; border: 2px solid currentColor; border-radius: 999px; padding: .35rem 1.4rem; letter-spacing: .12em; text-transform: uppercase; display: inline-flex; align-items: center; gap: .5rem; }
        .ir-table thead th { font-family: 'Noto Sans', sans-serif; letter-spacing: .06em; text-transform: uppercase; font-size: 10px; color: var(--muted); border-bottom: 2px solid var(--border); background: #faf9f7; }
        .ir-table td { border-bottom: 1px solid #efedea; }
        .ir-watermark { position: relative; }
        .ir-internal-banner { background: #fff3cd; border: 1px solid #ffc107; border-radius: 6px; }
        @media print {
            .no-print { display: none !important; }
            body { background: #fff !important; }
            .ir-doc { border: none !important; box-shadow: none !important; }
            .page-hdr, .gov-stripe, .gov-top, nav, .app-footer { display: none !important; }
            .ir-seal { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .ir-internal-banner { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            a[href]:after { content: ''; }
        }
    </style>
    @endpush

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            {{-- Internal use banner --}}
            <div class="ir-internal-banner px-5 py-3 mb-4 flex items-center gap-3 no-print">
                <svg class="w-5 h-5 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                </svg>
                <p class="text-sm text-amber-800 font-medium">
                    This is the internal Director's report. It contains outcome determination and analyst details not included in the client-facing Certificate of Analysis.
                </p>
            </div>

            <div class="ir-doc rounded-xl shadow-sm overflow-hidden">

                {{-- ── Letterhead ─────────────────────────────────────────── --}}
                <div class="ir-letterhead px-8 py-6">
                    <div class="flex items-start justify-between gap-6">
                        <div class="flex items-start gap-4">
                            <div class="ir-crest">
                                <svg viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21"/>
                                </svg>
                            </div>
                            <div>
                                <p class="ir-eyebrow">Government of Kiribati &middot; Ministry of Fisheries &amp; Ocean Resources</p>
                                <h1 class="ir-title text-2xl font-bold mt-1">Kiribati Seafood Toxicology Laboratory</h1>
                                <img src="{{ asset('images/mfor-logo.png') }}" alt="MFOR" class="h-6 mt-1 object-contain object-left">
                            </div>
                        </div>
                        <div class="text-right shrink-0">
                            <p class="ir-eyebrow">Results</p>
                            <p class="font-mono text-sm font-semibold text-gray-800 mt-1">{{ $submission->reference_number }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">
                                Issued {{ $result?->authorised_at?->format('d M Y') ?? now()->format('d M Y') }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- ── Authorisation strip ─────────────────────────────────── --}}
                <div class="px-8 py-5 flex items-center justify-between gap-6 border-b border-gray-100 bg-gray-50/40">
                    <div>
                        <p class="ir-meta-label">Authorisation Status</p>
                        @if($result?->authorised_at)
                            <p class="text-sm font-semibold text-green-700 mt-1">Authorised</p>
                        @else
                            <p class="text-sm text-gray-400 italic mt-1">Awaiting Director authorisation</p>
                        @endif
                    </div>

                    {{-- Company --}}
                    <div class="text-center">
                        <p class="ir-meta-label">Prepared For</p>
                        <p class="text-sm font-semibold text-gray-800 mt-1">{{ $submission->client->company_name }}</p>
                        <p class="text-xs text-gray-500 mt-0.5">{{ $submission->client->user->email ?? '' }}</p>
                    </div>

                    @if($result?->authorised_at)
                        <div class="text-right">
                            <p class="ir-meta-label">Authorised By</p>
                            <p class="text-sm font-medium text-gray-800 mt-1">{{ $result->authorisedBy?->name ?? 'Laboratory Director' }}</p>
                            <p class="text-xs text-gray-500">Laboratory Director</p>
                            <p class="text-xs text-gray-400 mt-1">{{ $result->authorised_at->format('d M Y \a\t H:i') }}</p>
                        </div>
                    @endif
                </div>

                {{-- ── Submission particulars ─────────────────────────────── --}}
                <div class="px-8 py-6 border-b border-gray-100">
                    <p class="ir-section-title mb-4">Submission Particulars</p>

                    {{-- Meta row --}}
                    <dl class="grid grid-cols-2 md:grid-cols-3 gap-x-6 gap-y-4 text-sm mb-5">
                        <div>
                            <dt class="ir-meta-label">Client</dt>
                            <dd class="text-gray-800 mt-1 font-medium">{{ $submission->client->company_name }}</dd>
                        </div>
                        <div>
                            <dt class="ir-meta-label">Reference</dt>
                            <dd class="font-mono text-gray-800 mt-1">{{ $submission->reference_number }}</dd>
                        </div>
                        <div>
                            <dt class="ir-meta-label">Priority</dt>
                            <dd class="text-gray-700 mt-1 capitalize">{{ $submission->priority ?? 'Routine' }}</dd>
                        </div>
                        <div>
                            <dt class="ir-meta-label">Collected</dt>
                            <dd class="text-gray-700 mt-1">{{ $submission->collected_at?->format('d M Y') ?? '—' }}</dd>
                        </div>
                        <div>
                            <dt class="ir-meta-label">Submitted</dt>
                            <dd class="text-gray-700 mt-1">{{ $submission->submitted_at?->format('d M Y') ?? '—' }}</dd>
                        </div>
                        <div>
                            <dt class="ir-meta-label">Results Required By</dt>
                            <dd class="text-gray-700 mt-1">{{ $submission->results_required_by?->format('d M Y') ?? '—' }}</dd>
                        </div>
                        <div>
                            <dt class="ir-meta-label">Date of Issue</dt>
                            <dd class="text-gray-700 mt-1">{{ $result?->authorised_at?->format('d M Y') ?? '—' }}</dd>
                        </div>
                    </dl>

                    {{-- Samples table --}}
                    <dt class="ir-meta-label mb-2">Samples Submitted ({{ $submission->samples->count() }})</dt>
                    <table class="w-full text-sm border border-gray-100 rounded-lg overflow-hidden">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="text-left px-3 py-2 ir-meta-label">#</th>
                                <th class="text-left px-3 py-2 ir-meta-label">Common Name</th>
                                <th class="text-left px-3 py-2 ir-meta-label">Scientific Name</th>
                                <th class="text-left px-3 py-2 ir-meta-label">Sample Code</th>
                                <th class="text-left px-3 py-2 ir-meta-label">Qty</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($submission->samples as $i => $sample)
                                <tr>
                                    <td class="px-3 py-2 text-gray-400 font-mono text-xs">{{ $i + 1 }}</td>
                                    <td class="px-3 py-2 font-medium text-gray-800">{{ $sample->common_name ?? '—' }}</td>
                                    <td class="px-3 py-2 italic text-gray-500">{{ $sample->scientific_name ?? '—' }}</td>
                                    <td class="px-3 py-2 font-mono text-xs text-gray-500">{{ $sample->sample_code }}</td>
                                    <td class="px-3 py-2 text-gray-600 text-xs">{{ $sample->quantity ?? '—' }} {{ $sample->quantity_unit ?? '' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @php
                    // Partition tests: only director-queried (has [Director query] note) go to the
                    // returned section. Analyst self-flagged tests appear in authorised results.
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

                {{-- ── Authorised Results ─────────────────────────────────────── --}}
                <div class="px-8 py-6 border-b border-gray-100">
                    <div class="flex items-center justify-between mb-4">
                        <p class="ir-section-title">Authorised Results</p>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold rounded-full bg-green-50 text-green-700 ring-1 ring-green-600/20">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            Authorised
                        </span>
                    </div>

                    @foreach($submission->samples as $sample)
                        @php
                            $sampleAuthorisedTests = $authorisedTests->filter(fn($r) => $r['sample']->id === $sample->id);
                        @endphp
                        <div class="mb-6 last:mb-0">
                            <div class="flex items-center justify-between mb-2">
                                <div>
                                    <h4 class="text-sm font-semibold text-gray-800">{{ $sample->common_name ?? $sample->sample_code }}</h4>
                                    @if($sample->scientific_name)
                                        <p class="text-xs text-gray-400 italic mt-0.5">{{ $sample->scientific_name }}</p>
                                    @endif
                                    <p class="text-xs text-gray-400 font-mono mt-0.5">{{ $sample->sample_code }}</p>
                                </div>
                                <x-kstl.status-badge :status="$sample->status" />
                            </div>

                            @if($sampleAuthorisedTests->isEmpty())
                                <p class="text-sm text-gray-400 italic py-3">No test results available.</p>
                            @else
                                <table class="ir-table w-full text-sm">
                                    <thead>
                                        <tr>
                                            <th class="text-left px-3 py-2.5">Test</th>
                                            <th class="text-left px-3 py-2.5">Result</th>
                                            <th class="text-left px-3 py-2.5">Methods</th>
                                            <th class="text-left px-3 py-2.5">Analyst</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($sampleAuthorisedTests as $row)
                                            @php
                                                $test    = $row['test'];
                                                $sopCode = \App\Models\Kstl\SampleTest::TEST_SOPS[$test->test_key] ?? null;
                                                $sopDoc  = $sopCode ? ($sopDocuments[$sopCode] ?? null) : null;
                                                $unit = $test->result_unit ? ' ' . $test->result_unit : '';
                                                $resultText = match($test->result_qualifier) {
                                                    'detected'     => 'Detected',
                                                    'not_detected' => 'Not Detected',
                                                    'pass'         => 'Pass',
                                                    'fail'         => 'Fail',
                                                    'less_than'    => '< ' . $test->result_value . $unit,
                                                    'greater_than' => '> ' . $test->result_value . $unit,
                                                    'equal_to'     => $test->result_value . $unit,
                                                    default        => ($test->result_value ? $test->result_value . $unit : '—'),
                                                };
                                                $isDetected    = $test->result_qualifier === 'detected' || $test->result_qualifier === 'fail';
                                                $isNotDetected = $test->result_qualifier === 'not_detected' || $test->result_qualifier === 'pass';
                                            @endphp
                                            <tr>
                                                <td class="px-3 py-2.5 text-gray-800 font-medium">{{ $test->getDisplayLabel() }}</td>
                                                <td class="px-3 py-2.5 font-medium {{ $isDetected ? 'text-red-600' : ($isNotDetected ? 'text-green-700' : 'text-gray-700') }}">
                                                    {{ $resultText }}
                                                </td>
                                                <td class="px-3 py-2.5">
                                                    @if($sopCode && $sopDoc)
                                                        @if($sopDoc->currentVersion)
                                                            <a href="{{ route('staff.documents.preview', $sopDoc->id) }}"
                                                               target="_blank"
                                                               class="inline-flex items-center gap-1 font-mono text-xs text-blue-600 hover:text-blue-800 hover:underline">
                                                                {{ $sopCode }}
                                                                <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                            </a>
                                                        @else
                                                            <a href="{{ route('staff.documents.show', $sopDoc->id) }}"
                                                               target="_blank"
                                                               class="inline-flex items-center gap-1 font-mono text-xs text-blue-600 hover:text-blue-800 hover:underline">
                                                                {{ $sopCode }}
                                                                <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                                            </a>
                                                        @endif
                                                    @elseif($sopCode)
                                                        <span class="font-mono text-xs text-gray-500">{{ $sopCode }}</span>
                                                    @else
                                                        <span class="text-gray-400">—</span>
                                                    @endif
                                                </td>
                                                <td class="px-3 py-2.5 text-xs text-gray-500">{{ $test->assignedTo?->name ?? '—' }}</td>
                                            </tr>
                                            @if($test->result_notes)
                                                <tr class="bg-gray-50/60">
                                                    <td colspan="4" class="px-3 pb-2 pt-0 text-xs text-gray-500 italic">Notes: {{ $test->result_notes }}</td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>
                    @endforeach
                </div>

                {{-- ── Analyst Section — Returned for Review ──────────────────── --}}
                @if($returnedTests->isNotEmpty())
                <div class="px-8 py-6 border-b border-gray-100">
                    <div class="flex items-center justify-between mb-4">
                        <p class="ir-section-title">Analyst Section</p>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold rounded-full bg-amber-50 text-amber-700 ring-1 ring-amber-500/30">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                            Return — Pending Analyst Review
                        </span>
                    </div>

                    <div class="mb-3 px-4 py-3 bg-amber-50 border border-amber-200 rounded-lg text-xs text-amber-800 leading-relaxed">
                        The following tests have been queried. The analyst has been notified and will re-confirm results before resubmitting for authorisation. Results shown are from the analyst's last submission.
                    </div>

                    @foreach($submission->samples as $sample)
                        @php
                            $sampleReturnedTests = $returnedTests->filter(fn($r) => $r['sample']->id === $sample->id);
                        @endphp
                        @if($sampleReturnedTests->isNotEmpty())
                            <div class="mb-6 last:mb-0">
                                <div class="flex items-center justify-between mb-2">
                                    <div>
                                        <h4 class="text-sm font-semibold text-gray-800">{{ $sample->common_name ?? $sample->sample_code }}</h4>
                                        @if($sample->scientific_name)
                                            <p class="text-xs text-gray-400 italic mt-0.5">{{ $sample->scientific_name }}</p>
                                        @endif
                                        <p class="text-xs text-gray-400 font-mono mt-0.5">{{ $sample->sample_code }}</p>
                                    </div>
                                    <x-kstl.status-badge :status="$sample->status" />
                                </div>
                                <table class="ir-table w-full text-sm">
                                    <thead>
                                        <tr>
                                            <th class="text-left px-3 py-2.5">Test</th>
                                            <th class="text-left px-3 py-2.5">Result</th>
                                            <th class="text-left px-3 py-2.5">Methods</th>
                                            <th class="text-left px-3 py-2.5">Analyst</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($sampleReturnedTests as $row)
                                            @php
                                                $test   = $row['test'];
                                                $queryNote = null;
                                                if ($test->result_notes && str_contains($test->result_notes, '[Director query]')) {
                                                    preg_match('/\[Director query\]\s*(.+?)(?:\n\n|$)/s', $test->result_notes, $m);
                                                    $queryNote = trim($m[1] ?? '');
                                                }
                                                $unit = $test->result_unit ? ' ' . $test->result_unit : '';
                                                $resultText = match($test->result_qualifier) {
                                                    'detected'     => 'Detected',
                                                    'not_detected' => 'Not Detected',
                                                    'pass'         => 'Pass',
                                                    'fail'         => 'Fail',
                                                    'less_than'    => '< ' . $test->result_value . $unit,
                                                    'greater_than' => '> ' . $test->result_value . $unit,
                                                    'equal_to'     => $test->result_value . $unit,
                                                    default        => ($test->result_value ? $test->result_value . $unit : '—'),
                                                };
                                                $isDetected    = $test->result_qualifier === 'detected' || $test->result_qualifier === 'fail';
                                                $isNotDetected = $test->result_qualifier === 'not_detected' || $test->result_qualifier === 'pass';
                                                $sopCode = \App\Models\Kstl\SampleTest::TEST_SOPS[$test->test_key] ?? null;
                                                $sopDoc  = $sopCode ? ($sopDocuments[$sopCode] ?? null) : null;
                                            @endphp
                                            <tr class="bg-amber-50/40">
                                                <td class="px-3 py-2.5 text-gray-800 font-medium">{{ $test->getDisplayLabel() }}</td>
                                                <td class="px-3 py-2.5 font-medium {{ $isDetected ? 'text-red-600' : ($isNotDetected ? 'text-green-700' : 'text-gray-700') }}">
                                                    {{ $resultText }}
                                                </td>
                                                <td class="px-3 py-2.5">
                                                    @if($sopCode && $sopDoc)
                                                        @if($sopDoc->currentVersion)
                                                            <a href="{{ route('staff.documents.preview', $sopDoc->id) }}"
                                                               target="_blank"
                                                               class="inline-flex items-center gap-1 font-mono text-xs text-blue-600 hover:text-blue-800 hover:underline">
                                                                {{ $sopCode }}
                                                                <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                            </a>
                                                        @else
                                                            <a href="{{ route('staff.documents.show', $sopDoc->id) }}"
                                                               target="_blank"
                                                               class="inline-flex items-center gap-1 font-mono text-xs text-blue-600 hover:text-blue-800 hover:underline">
                                                                {{ $sopCode }}
                                                                <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                                            </a>
                                                        @endif
                                                    @elseif($sopCode)
                                                        <span class="font-mono text-xs text-gray-500">{{ $sopCode }}</span>
                                                    @else
                                                        <span class="text-gray-400">—</span>
                                                    @endif
                                                </td>
                                                <td class="px-3 py-2.5 text-xs text-gray-500">{{ $test->assignedTo?->name ?? '—' }}</td>
                                            </tr>
                                            @if($queryNote)
                                                <tr class="bg-amber-50/60">
                                                    <td colspan="4" class="px-3 pb-2.5 pt-0 text-xs text-amber-700 italic">
                                                        <span class="font-semibold not-italic">Director query:</span> {{ $queryNote }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    @endforeach
                </div>
                @endif

                {{-- ── Assessment Record ────────────────────────────────────── --}}
                @php $assessedSamples = $submission->samples->filter(fn($s) => $s->assessment !== null); @endphp
                @if($assessedSamples->isNotEmpty())
                <div class="px-8 py-6 border-b border-gray-100">
                    <div class="flex items-center justify-between mb-4">
                        <p class="ir-section-title">Sample Assessment Record</p>
                        @php
                            $allAccepted = $assessedSamples->every(fn($s) => $s->assessment->outcome === 'accepted');
                            $anyRejected = $assessedSamples->some(fn($s)  => $s->assessment->outcome === 'rejected');
                        @endphp
                        @if($allAccepted)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold rounded-full bg-green-50 text-green-700 ring-1 ring-green-600/20">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                All Accepted
                            </span>
                        @elseif($anyRejected)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold rounded-full bg-red-50 text-red-700 ring-1 ring-red-600/20">Rejected</span>
                        @endif
                    </div>

                    @foreach($assessedSamples as $sample)
                        @php $a = $sample->assessment; @endphp
                        <div class="{{ !$loop->last ? 'mb-5 pb-5 border-b border-gray-100' : '' }}">
                            <div class="flex items-center justify-between mb-3">
                                <div>
                                    <p class="text-sm font-semibold text-gray-800">{{ $sample->common_name }}</p>
                                    <p class="text-xs font-mono text-gray-400 mt-0.5">{{ $sample->sample_code }}</p>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="inline-flex px-2 py-0.5 text-xs font-semibold rounded-full {{ $a->outcome === 'accepted' ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700' }}">
                                        {{ ucfirst($a->outcome) }}
                                    </span>
                                    @if($a->assessedBy)
                                        <div class="text-right">
                                            <p class="text-xs text-gray-500">{{ $a->assessedBy->name }}</p>
                                            <p class="text-xs text-gray-400">{{ ($a->assessed_at ?? $a->created_at)->format('d M Y H:i') }}</p>
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
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                                @foreach($criteria as $label => [$pass, $notes])
                                    <div class="rounded-lg border {{ $pass ? 'border-green-100 bg-green-50/40' : 'border-red-100 bg-red-50/40' }} px-3 py-2">
                                        <div class="flex items-center justify-between gap-2">
                                            <span class="text-xs font-medium text-gray-700">{{ $label }}</span>
                                            <span class="text-xs font-semibold {{ $pass ? 'text-green-600' : 'text-red-600' }}">
                                                {{ $pass ? 'Pass' : 'Fail' }}
                                            </span>
                                        </div>
                                        @if($notes)
                                            <p class="text-xs text-gray-500 mt-1 leading-snug">{{ $notes }}</p>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                            @if($a->additional_observations)
                                <div class="mt-2 bg-gray-50 rounded px-3 py-2 text-xs text-gray-600">
                                    <span class="font-medium">Observations:</span> {{ $a->additional_observations }}
                                </div>
                            @endif
                            @if($a->rejection_reason)
                                <div class="mt-2 bg-red-50 border border-red-100 rounded px-3 py-2 text-xs text-red-700">
                                    <span class="font-semibold">Rejection reason:</span> {{ $a->rejection_reason }}
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
                @endif

                {{-- ── Director's Comments ─────────────────────────────────── --}}
                @if($result?->director_comments)
                    <div class="px-8 py-6 border-b border-gray-100">
                        <p class="ir-section-title mb-3">Director's Comments</p>
                        <p class="text-sm text-gray-700 leading-relaxed whitespace-pre-line">{{ $result->director_comments }}</p>
                    </div>
                @endif

                {{-- ── Footer ──────────────────────────────────────────────── --}}
                <div class="px-8 py-5 bg-gray-50/60 border-t border-gray-200">
                    <div class="flex items-start justify-between gap-6 text-xs text-gray-500">
                        <div class="max-w-md">
                            <p class="font-semibold text-red-600 mb-1">INTERNAL DOCUMENT — CONFIDENTIAL</p>
                            <p class="leading-relaxed">
                                This document is for Director use only and must not be shared with the client or external parties.
                                The client-facing Certificate of Analysis does not include determination outcomes or analyst details.
                            </p>
                        </div>
                        <div class="text-right shrink-0">
                            <p class="ir-meta-label">Document Ref.</p>
                            <p class="font-mono text-gray-700 mt-1">{{ $submission->reference_number }}</p>
                            <p class="text-gray-400 mt-1">KSTL &middot; Director Portal</p>
                        </div>
                    </div>
                </div>

            </div>

            <div class="pb-8"></div>
        </div>
    </div>
</x-app-layout>
