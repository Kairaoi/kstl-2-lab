{{-- resources/views/kstl/analyst/results/show.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <p class="ar-eyebrow">Authorised Result</p>
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
                                <p class="text-xs text-gray-500 mt-0.5">National Fisheries Division &middot; Laboratory Information Management System</p>
                            </div>
                        </div>
                        <div class="text-right shrink-0">
                            <p class="ar-eyebrow">Authorised Result</p>
                            <p class="font-mono text-sm font-semibold text-gray-800 mt-1">{{ $submission->reference_number }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">
                                {{ $submission->client->company_name ?? '' }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- ── Outcome seal ───────────────────────────────────────── --}}
                @php
                    $outcome = $result?->overall_outcome;
                    $sealColor = match($outcome) {
                        'pass'         => '#1a6b45',
                        'fail'         => '#a0241c',
                        'inconclusive' => '#8a6d1a',
                        default        => '#6b6760',
                    };
                    $sealText = match($outcome) {
                        'pass'         => 'Pass',
                        'fail'         => 'Fail',
                        'inconclusive' => 'Inconclusive',
                        default        => 'Pending',
                    };
                @endphp
                <div class="px-8 py-6 flex items-center justify-between gap-6 border-b border-gray-100">
                    <div>
                        <p class="ar-meta-label">Overall Outcome</p>
                        <div class="ar-seal mt-2" style="color: {{ $sealColor }};">
                            @if($outcome === 'pass')
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            @elseif($outcome === 'fail')
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            @elseif($outcome === 'inconclusive')
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
                            @endif
                            {{ $sealText }}
                        </div>
                    </div>
                    <div class="text-right">
                        @if($result?->authorised_at)
                            <p class="ar-meta-label">Authorised By</p>
                            <p class="text-sm font-medium text-gray-800 mt-1">{{ $result->authorisedBy?->name ?? 'Laboratory Director' }}</p>
                            <p class="text-xs text-gray-500">Laboratory Director</p>
                            <p class="text-xs text-gray-400 mt-1">{{ $result->authorised_at->format('d M Y \a\t H:i') }}</p>
                        @else
                            <p class="text-xs text-gray-400 italic">Awaiting Director authorisation</p>
                        @endif
                    </div>
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

                    @foreach($samples as $sample)
                        <div class="mb-6 last:mb-0">
                            <div class="flex items-center justify-between mb-2">
                                <div>
                                    <h4 class="text-sm font-semibold text-gray-800">
                                        {{ $sample->common_name ?? $sample->sample_code }}
                                    </h4>
                                    <p class="text-xs text-gray-400 mt-0.5 font-mono">{{ $sample->sample_code }}</p>
                                </div>
                                <x-kstl.status-badge :status="$sample->status" />
                            </div>

                            @php $tests = $testsBySample[$sample->id] ?? collect(); @endphp
                            @if($tests->isEmpty())
                                <p class="text-sm text-gray-400 italic py-3">No tests recorded for this sample.</p>
                            @else
                                <table class="ar-table w-full text-sm">
                                    <thead>
                                        <tr>
                                            <th class="text-left px-3 py-2.5">Determinand</th>
                                            <th class="text-left px-3 py-2.5">Result</th>
                                            <th class="text-left px-3 py-2.5">Determination</th>
                                            <th class="text-left px-3 py-2.5">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($tests as $test)
                                            @php
                                                $qualifier = $test->result_qualifier;
                                                $qualClasses = in_array($qualifier, ['pass', 'not_detected'])
                                                    ? 'bg-green-50 text-green-700'
                                                    : (in_array($qualifier, ['fail', 'detected'])
                                                        ? 'bg-red-50 text-red-700'
                                                        : 'bg-gray-100 text-gray-600');
                                            @endphp
                                            <tr>
                                                <td class="px-3 py-2.5 font-medium text-gray-800">
                                                    {{ method_exists($test, 'getDisplayLabel') ? $test->getDisplayLabel() : $test->test_key }}
                                                </td>
                                                <td class="px-3 py-2.5 text-gray-700">
                                                    @if($test->result_value)
                                                        {{ $test->result_value }}{{ $test->result_unit ? ' ' . $test->result_unit : '' }}
                                                    @else
                                                        <span class="text-gray-400">—</span>
                                                    @endif
                                                </td>
                                                <td class="px-3 py-2.5">
                                                    @if($qualifier)
                                                        <span class="inline-flex px-2 py-0.5 text-xs font-medium rounded-full {{ $qualClasses }}">
                                                            {{ ucfirst(str_replace('_', ' ', $qualifier)) }}
                                                        </span>
                                                    @else
                                                        <span class="text-xs text-gray-400">—</span>
                                                    @endif
                                                </td>
                                                <td class="px-3 py-2.5">
                                                    <x-kstl.status-badge :status="$test->status" />
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