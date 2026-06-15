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
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        Certificate of Analysis
                    </h2>
                    <p class="text-xs text-gray-400 mt-0.5">{{ $submission->reference_number }} &middot; {{ $submission->sample_name }}</p>
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
        /* Official document palette — reuses the portal's gov tokens */
        .coa-doc { background: var(--surface); border: 1px solid var(--border); }
        .coa-letterhead {
            border-bottom: 3px double var(--navy);
            background: linear-gradient(180deg, #fbfaf8 0%, #ffffff 100%);
        }
        .coa-crest {
            width: 54px; height: 54px; border-radius: 50%;
            border: 2px solid var(--gold); color: var(--navy);
            display: flex; align-items: center; justify-content: center;
        }
        .coa-crest svg { width: 28px; height: 28px; stroke: var(--navy); fill: none; }
        .coa-title { font-family: 'Noto Serif', serif; color: var(--navy); letter-spacing: .01em; }
        .coa-eyebrow { letter-spacing: .18em; text-transform: uppercase; font-size: 10px; color: var(--gold); font-weight: 700; }
        .coa-meta-label { letter-spacing: .08em; text-transform: uppercase; font-size: 10px; color: var(--subtle); font-weight: 600; }
        .coa-section-title {
            font-family: 'Noto Serif', serif; color: var(--navy);
            font-size: 13px; font-weight: 700; letter-spacing: .02em;
            display: flex; align-items: center; gap: 8px;
        }
        .coa-section-title::before {
            content: ''; width: 3px; height: 14px; background: var(--gold); border-radius: 2px; display: inline-block;
        }
        /* Outcome seal */
        .coa-seal {
            font-family: 'Noto Serif', serif; font-weight: 700;
            border: 2px solid currentColor; border-radius: 999px;
            padding: .35rem 1.4rem; letter-spacing: .12em; text-transform: uppercase;
            display: inline-flex; align-items: center; gap: .5rem;
        }
        .coa-watermark {
            position: relative;
        }
        .coa-table thead th {
            font-family: 'Noto Sans', sans-serif; letter-spacing: .06em;
            text-transform: uppercase; font-size: 10px; color: var(--muted);
            border-bottom: 2px solid var(--border); background: #faf9f7;
        }
        .coa-table td { border-bottom: 1px solid #efedea; }
        .coa-foot { border-top: 3px double var(--navy); }

        @media print {
            .no-print { display: none !important; }
            body { background: #fff !important; }
            .coa-doc { border: none !important; box-shadow: none !important; }
            .page-hdr, .gov-stripe, .gov-top, nav, .app-footer { display: none !important; }
            .coa-seal { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            a[href]:after { content: ''; }
        }
    </style>
    @endpush

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <div class="coa-doc rounded-xl shadow-sm overflow-hidden">

                {{-- ── Letterhead ─────────────────────────────────────────── --}}
                <div class="coa-letterhead px-8 py-6">
                    <div class="flex items-start justify-between gap-6">
                        <div class="flex items-start gap-4">
                            <div class="coa-crest">
                                <svg viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21"/>
                                </svg>
                            </div>
                            <div>
                                <p class="coa-eyebrow">Government of Kiribati &middot; Ministry of Fisheries &amp; Ocean Resources</p>
                                <h1 class="coa-title text-2xl font-bold mt-1">Kiribati Seafood Toxicology Laboratory</h1>
                                <p class="text-xs text-gray-500 mt-0.5">National Fisheries Division &middot; Laboratory Information Management System</p>
                            </div>
                        </div>
                        <div class="text-right shrink-0">
                            <p class="coa-eyebrow">Certificate of Analysis</p>
                            <p class="font-mono text-sm font-semibold text-gray-800 mt-1">{{ $submission->reference_number }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">
                                Issued {{ $result?->authorised_at?->format('d M Y') ?? now()->format('d M Y') }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- ── Authorisation strip ────────────────────────────────────── --}}
                <div class="px-8 py-5 flex items-center justify-between gap-6 border-b border-gray-100">
                    <div>
                        <p class="coa-meta-label">Authorisation Status</p>
                        @if($result?->authorised_at)
                            <p class="text-sm font-semibold text-green-700 mt-1">Authorised</p>
                        @else
                            <p class="text-sm text-gray-400 italic mt-1">Awaiting Director authorisation</p>
                        @endif
                    </div>
                    @if($result?->authorised_at)
                        <div class="text-right">
                            <p class="coa-meta-label">Authorised By</p>
                            <p class="text-sm font-medium text-gray-800 mt-1">{{ $result->authorisedBy?->name ?? 'Laboratory Director' }}</p>
                            <p class="text-xs text-gray-500">Laboratory Director</p>
                            <p class="text-xs text-gray-400 mt-1">{{ $result->authorised_at->format('d M Y \a\t H:i') }}</p>
                        </div>
                    @endif
                </div>

                {{-- ── Submission particulars ─────────────────────────────── --}}
                <div class="px-8 py-6 border-b border-gray-100">
                    <p class="coa-section-title mb-4">Submission Particulars</p>
                    <dl class="grid grid-cols-2 md:grid-cols-3 gap-x-6 gap-y-4 text-sm">
                        @php $scientificName = $submission->samples->first()?->scientific_name; @endphp
                        <div>
                            <dt class="coa-meta-label">Reference</dt>
                            <dd class="font-mono text-gray-800 mt-1">{{ $submission->reference_number }}</dd>
                        </div>
                        <div>
                            <dt class="coa-meta-label">Sample (Common Name)</dt>
                            <dd class="text-gray-800 mt-1">{{ $submission->sample_name }}</dd>
                        </div>
                        <div>
                            <dt class="coa-meta-label">Scientific Name</dt>
                            <dd class="text-gray-700 italic mt-1">{{ $scientificName ?? '—' }}</dd>
                        </div>
                        <div>
                            <dt class="coa-meta-label">Type</dt>
                            <dd class="text-gray-700 mt-1 capitalize">{{ $submission->sample_type }}</dd>
                        </div>
                        <div>
                            <dt class="coa-meta-label">Collected</dt>
                            <dd class="text-gray-700 mt-1">{{ $submission->collected_at?->format('d M Y') ?? '—' }}</dd>
                        </div>
                        <div>
                            <dt class="coa-meta-label">Submitted</dt>
                            <dd class="text-gray-700 mt-1">{{ $submission->submitted_at?->format('d M Y') ?? '—' }}</dd>
                        </div>
                        <div>
                            <dt class="coa-meta-label">Date of Issue</dt>
                            <dd class="text-gray-700 mt-1">{{ $result?->authorised_at?->format('d M Y') ?? '—' }}</dd>
                        </div>
                    </dl>
                </div>

                {{-- ── Analytical results ─────────────────────────────────── --}}
                <div class="px-8 py-6">
                    <p class="coa-section-title mb-4">Analytical Results</p>

                    @php
                        $sopDocuments = \App\Models\Kstl\Document::where('category', 'sop')
                            ->whereIn('reference_code', array_values(\App\Models\Kstl\SampleTest::TEST_SOPS))
                            ->get()
                            ->keyBy('reference_code');
                    @endphp

                    @foreach($submission->samples as $sample)
                        <div class="mb-6 last:mb-0">
                            <div class="flex items-center justify-between mb-2">
                                <div>
                                    <h4 class="text-sm font-semibold text-gray-800">
                                        {{ $sample->common_name ?? $sample->sample_code }}
                                    </h4>
                                    @if($sample->scientific_name)
                                        <p class="text-xs text-gray-400 italic mt-0.5">{{ $sample->scientific_name }}</p>
                                    @endif
                                    <p class="text-xs text-gray-400 font-mono mt-0.5">{{ $sample->sample_code }}</p>
                                </div>
                                <x-kstl.status-badge :status="$sample->status" />
                            </div>

                            @if($sample->sampleTests->isEmpty())
                                <p class="text-sm text-gray-400 italic py-3">No test results available.</p>
                            @else
                                <table class="coa-table w-full text-sm">
                                    <thead>
                                        <tr>
                                            <th class="text-left px-3 py-2.5">Test</th>
                                            <th class="text-left px-3 py-2.5">Result</th>
                                            <th class="text-left px-3 py-2.5">Methods</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($sample->sampleTests as $test)
                                            @php
                                                $unit = $test->result_unit ? ' ' . $test->result_unit : '';
                                                $resultText = match($test->result_qualifier) {
                                                    'detected'     => 'Detected',
                                                    'not_detected' => 'Not Detected',
                                                    'pass'         => 'Tested',
                                                    'fail'         => 'Fail',
                                                    'less_than'    => '< ' . $test->result_value . $unit,
                                                    'greater_than' => '> ' . $test->result_value . $unit,
                                                    'equal_to'     => $test->result_value . $unit,
                                                    default        => ($test->result_value ? $test->result_value . $unit : '—'),
                                                };
                                                $isDetected    = $test->result_qualifier === 'detected'  || $test->result_qualifier === 'fail';
                                                $isNotDetected = $test->result_qualifier === 'not_detected' || $test->result_qualifier === 'pass';
                                                $sopCode = \App\Models\Kstl\SampleTest::TEST_SOPS[$test->test_key] ?? null;
                                                $sopDoc  = $sopCode ? ($sopDocuments[$sopCode] ?? null) : null;
                                            @endphp
                                            <tr>
                                                <td class="px-3 py-2.5 text-gray-800 font-medium">
                                                    {{ $test->getDisplayLabel() }}
                                                </td>
                                                <td class="px-3 py-2.5 font-medium {{ $isDetected ? 'text-red-600' : ($isNotDetected ? 'text-green-700' : 'text-gray-700') }}">
                                                    {{ $resultText }}
                                                </td>
                                                <td class="px-3 py-2.5">
                                                    @if($sopCode && $sopDoc)
                                                        <a href="{{ route('client.documents.download', $sopDoc->id) }}"
                                                           target="_blank"
                                                           class="inline-flex items-center gap-1 font-mono text-xs text-blue-600 hover:text-blue-800 hover:underline">
                                                            {{ $sopCode }}
                                                            <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                                        </a>
                                                    @elseif($sopCode)
                                                        <span class="font-mono text-xs text-gray-500">{{ $sopCode }}</span>
                                                    @else
                                                        <span class="text-gray-400">—</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>
                    @endforeach
                </div>

                {{-- ── Director's remarks ─────────────────────────────────── --}}
                @if($result?->director_comments)
                    <div class="px-8 py-6 border-t border-gray-100">
                        <p class="coa-section-title mb-3">Director's Remarks</p>
                        <p class="text-sm text-gray-700 leading-relaxed whitespace-pre-line">{{ $result->director_comments }}</p>
                    </div>
                @endif

                {{-- ── Footer / authentication ────────────────────────────── --}}
                <div class="coa-foot px-8 py-5 bg-gray-50/60">
                    <div class="flex items-start justify-between gap-6 text-xs text-gray-500">
                        <div class="max-w-md">
                            <p class="font-medium text-gray-600 mb-1">Authentication</p>
                            <p class="leading-relaxed">
                                This certificate is issued electronically by the Kiribati Seafood Toxicology Laboratory and
                                relates only to the sample(s) identified above. Results pertain solely to the items tested.
                                This document is not valid unless authorised by the Laboratory Director.
                            </p>
                        </div>
                        <div class="text-right shrink-0">
                            <p class="coa-meta-label">Document Ref.</p>
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