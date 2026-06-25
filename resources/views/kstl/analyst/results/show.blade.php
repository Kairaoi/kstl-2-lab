{{-- resources/views/kstl/analyst/results/show.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <p class="ar-eyebrow">Results</p>
                <h2 class="ar-title text-xl font-bold leading-tight mt-0.5">
                    {{ $submission->reference_number }}
                </h2>
                <p class="text-xs text-gray-400 mt-0.5">{{ $submission->client->company_name ?? '' }}</p>
            </div>
            <div class="flex items-center gap-2">
                <button onclick="window.print()"
                        class="no-print inline-flex items-center gap-2 px-3 py-1.5 text-xs font-medium text-gray-600 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                    </svg>
                    Print
                </button>
                <a href="{{ route('analyst.results.index') }}"
                   class="no-print text-xs text-gray-600 px-3 py-1.5 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                    ← Back to results
                </a>
            </div>
        </div>
    </x-slot>

    @push('styles')
    <style>
        .ar-eyebrow { letter-spacing: .16em; text-transform: uppercase; font-size: 10px; color: var(--gold); font-weight: 700; }
        .ar-title { font-family: 'Noto Serif', serif; color: var(--navy); letter-spacing: .01em; }
        .ar-doc { background: var(--surface); border: 1px solid var(--border); }
        .ar-letterhead {
            border-bottom: 3px double var(--navy);
            background: linear-gradient(180deg, #fbfaf8 0%, #ffffff 100%);
        }
        .ar-crest {
            width: 48px; height: 48px; border-radius: 50%;
            border: 2px solid var(--gold);
            display: flex; align-items: center; justify-content: center;
        }
        .ar-crest svg { width: 24px; height: 24px; stroke: var(--navy); fill: none; }
        .ar-lab-title { font-family: 'Noto Serif', serif; color: var(--navy); letter-spacing: .01em; }
        .ar-meta-label { letter-spacing: .08em; text-transform: uppercase; font-size: 10px; color: var(--subtle); font-weight: 600; }
        .ar-section-title {
            font-family: 'Noto Serif', serif; color: var(--navy);
            font-size: 13px; font-weight: 700; letter-spacing: .02em;
            display: flex; align-items: center; gap: 8px;
        }
        .ar-section-title::before {
            content: ''; width: 3px; height: 14px; background: var(--gold); border-radius: 2px; display: inline-block;
        }
        .ar-seal {
            font-family: 'Noto Serif', serif; font-weight: 700;
            border: 2px solid currentColor; border-radius: 999px;
            padding: .35rem 1.4rem; letter-spacing: .12em; text-transform: uppercase;
            display: inline-flex; align-items: center; gap: .5rem;
        }
        .ar-table thead th {
            letter-spacing: .06em; text-transform: uppercase; font-size: 10px;
            color: var(--muted); border-bottom: 2px solid var(--border); background: #faf9f7;
        }
        .ar-table td { border-bottom: 1px solid #efedea; }

        @media print {
            .no-print { display: none !important; }
            body { background: #fff !important; }
            .ar-doc { border: none !important; box-shadow: none !important; }
            .page-hdr, .gov-stripe, .gov-top, nav, .app-footer { display: none !important; }
            .ar-seal { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        }
    </style>
    @endpush

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            {{-- Read-only notice --}}
            <div class="no-print rounded-lg border border-blue-100 bg-blue-50 px-4 py-3 flex items-center gap-2 mb-6">
                <svg class="w-4 h-4 text-blue-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-xs text-blue-800">This is the authorised report as signed off by the Director. It is read-only.</p>
            </div>

            <div class="ar-doc rounded-xl shadow-sm overflow-hidden">

                {{-- ── Letterhead ─────────────────────────────────────────── --}}
                <div class="ar-letterhead px-8 py-6">
                    <div class="flex items-start justify-between gap-6">
                        <div class="flex items-start gap-4">
                            <div class="ar-crest">
                                <svg viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21"/>
                                </svg>
                            </div>
                            <div>
                                <p class="ar-eyebrow">Government of Kiribati &middot; Ministry of Fisheries &amp; Ocean Resources</p>
                                <h1 class="ar-lab-title text-xl font-bold mt-1">Kiribati Seafood Toxicology Laboratory</h1>
                                <img src="{{ asset('images/mfor-logo.png') }}" alt="MFOR" class="h-6 mt-1 object-contain object-left">
                            </div>
                        </div>
                        <div class="text-right shrink-0">
                            <p class="ar-eyebrow">Results</p>
                            <p class="font-mono text-sm font-semibold text-gray-800 mt-1">{{ $submission->reference_number }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">
                                {{ $submission->client->company_name ?? '' }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- ── Authorisation strip ─────────────────────────────────── --}}
                <div class="px-8 py-5 flex items-center justify-between gap-6 border-b border-gray-100 bg-gray-50/40">
                    <div>
                        <p class="ar-meta-label">Authorisation Status</p>
                        @if($result?->authorised_at)
                            <p class="text-sm font-semibold text-green-700 mt-1">Authorised</p>
                        @else
                            <p class="text-sm text-gray-400 italic mt-1">Awaiting Director authorisation</p>
                        @endif
                    </div>

                    {{-- Company --}}
                    <div class="text-center">
                        <p class="ar-meta-label">Prepared For</p>
                        <p class="text-sm font-semibold text-gray-800 mt-1">{{ $submission->client->company_name }}</p>
                        <p class="text-xs text-gray-500 mt-0.5">{{ $submission->client->user->email ?? '' }}</p>
                    </div>

                    @if($result?->authorised_at)
                        <div class="text-right">
                            <p class="ar-meta-label">Authorised By</p>
                            <p class="text-sm font-medium text-gray-800 mt-1">{{ $result->authorisedBy?->name ?? 'Laboratory Director' }}</p>
                            <p class="text-xs text-gray-500">Laboratory Director</p>
                            <p class="text-xs text-gray-400 mt-1">{{ $result->authorised_at->format('d M Y \a\t H:i') }}</p>
                        </div>
                    @endif
                </div>

                {{-- ── Submission particulars ──────────────────────────────── --}}
                <div class="px-8 py-6 border-b border-gray-100">
                    <p class="ar-section-title mb-4">Submission Particulars</p>
                    <dl class="grid grid-cols-2 md:grid-cols-3 gap-x-6 gap-y-4 text-sm mb-5">
                        <div>
                            <dt class="ar-meta-label">Reference</dt>
                            <dd class="font-mono text-gray-800 mt-1">{{ $submission->reference_number }}</dd>
                        </div>
                        <div>
                            <dt class="ar-meta-label">Collected</dt>
                            <dd class="text-gray-700 mt-1">{{ $submission->collected_at?->format('d M Y') ?? '—' }}</dd>
                        </div>
                        @if($submission->delivered_at)
                        <div>
                            <dt class="ar-meta-label">Delivered</dt>
                            <dd class="text-gray-700 mt-1">{{ $submission->delivered_at->format('d M Y') }}</dd>
                        </div>
                        @endif
                        <div>
                            <dt class="ar-meta-label">Submitted</dt>
                            <dd class="text-gray-700 mt-1">{{ $submission->submitted_at?->format('d M Y') ?? '—' }}</dd>
                        </div>
                        <div>
                            <dt class="ar-meta-label">Date of Issue</dt>
                            <dd class="text-gray-700 mt-1">{{ $result?->authorised_at?->format('d M Y') ?? '—' }}</dd>
                        </div>
                    </dl>

                    <dt class="ar-meta-label mb-2">Samples Submitted ({{ $samples->count() }})</dt>
                    <table class="w-full text-sm border border-gray-100 rounded-lg overflow-hidden">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="text-left px-3 py-2 ar-meta-label">#</th>
                                <th class="text-left px-3 py-2 ar-meta-label">Common Name</th>
                                <th class="text-left px-3 py-2 ar-meta-label">Scientific Name</th>
                                <th class="text-left px-3 py-2 ar-meta-label">Sample Code</th>
                                <th class="text-left px-3 py-2 ar-meta-label">Qty</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($samples as $i => $sample)
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

                {{-- ── Director's remarks ─────────────────────────────────── --}}
                @if($result && $result->director_comments)
                    <div class="px-8 py-6 border-b border-gray-100">
                        <p class="ar-section-title mb-3">Director's Remarks</p>
                        <p class="text-sm text-gray-700 leading-relaxed whitespace-pre-line">{{ $result->director_comments }}</p>
                    </div>
                @endif

                {{-- ── Analytical results ─────────────────────────────────── --}}
                <div class="px-8 py-6">
                    <p class="ar-section-title mb-4">Analytical Results</p>

                    @php
                        $sopDocuments = \App\Models\Kstl\Document::where('category', 'sop')
                            ->whereIn('reference_code', array_values(\App\Models\Kstl\SampleTest::TEST_SOPS))
                            ->with('currentVersion')
                            ->get()
                            ->keyBy('reference_code');
                    @endphp

                    @foreach($samples as $sample)
                        <div class="mb-6 last:mb-0">
                            <div class="flex items-center justify-between mb-2">
                                <div>
                                    <h4 class="text-sm font-semibold text-gray-800">
                                        {{ $sample->common_name ?? $sample->sample_code }}
                                    </h4>
                                    @if($sample->scientific_name)
                                        <p class="text-xs text-gray-400 italic mt-0.5">{{ $sample->scientific_name }}</p>
                                    @endif
                                    <p class="text-xs text-gray-400 mt-0.5 font-mono">{{ $sample->sample_code }}</p>
                                </div>
                            </div>

                            @php $tests = $testsBySample[$sample->id] ?? collect(); @endphp
                            @if($tests->isEmpty())
                                <p class="text-sm text-gray-400 italic py-3">No tests recorded for this sample.</p>
                            @else
                                <table class="ar-table w-full text-sm">
                                    <thead>
                                        <tr>
                                            <th class="text-left px-3 py-2.5">Test</th>
                                            <th class="text-left px-3 py-2.5">Result</th>
                                            <th class="text-left px-3 py-2.5">Methods</th>
                                            <th class="text-left px-3 py-2.5">Outcome</th>
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
                                            <tr>
                                                <td class="px-3 py-2.5 font-medium text-gray-800">
                                                    {{ method_exists($test, 'getDisplayLabel') ? $test->getDisplayLabel() : $test->test_key }}
                                                </td>
                                                <td class="px-3 py-2.5 text-gray-700">
                                                    @if($resultDisplay)
                                                        {{ $resultDisplay }}
                                                    @else
                                                        <span class="text-gray-400">—</span>
                                                    @endif
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
                                                <td class="px-3 py-2.5">
                                                    @if($test->director_outcome === 'pass')
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-green-50 text-green-700 ring-1 ring-green-600/20">Pass</span>
                                                    @elseif($test->director_outcome === 'fail')
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-red-50 text-red-700 ring-1 ring-red-600/20">Fail</span>
                                                    @else
                                                        <span class="text-gray-400">—</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            @if($test->result_notes)
                                                <tr>
                                                    <td colspan="4" class="px-3 py-2 text-xs text-gray-500 bg-gray-50/50">
                                                        <span class="font-medium text-gray-600">Notes:</span> {{ $test->result_notes }}
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

                {{-- ── Footer ─────────────────────────────────────────────── --}}
                <div class="px-8 py-5 bg-gray-50/60 border-t border-gray-100">
                    <div class="flex items-start justify-between gap-6 text-xs text-gray-500">
                        <p class="max-w-md leading-relaxed">
                            Internal laboratory record. Results pertain solely to the sample(s) identified above.
                            This view reflects the report as authorised by the Laboratory Director.
                        </p>
                        <div class="text-right shrink-0">
                            <p class="ar-meta-label">Document Ref.</p>
                            <p class="font-mono text-gray-700 mt-1">{{ $submission->reference_number }}</p>
                            <p class="text-gray-400 mt-1">STLD &middot; Official Portal</p>
                        </div>
                    </div>
                </div>

            </div>

            <div class="pb-8"></div>

        </div>
    </div>
</x-app-layout>