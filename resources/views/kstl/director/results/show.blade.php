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
                                <p class="text-xs text-gray-500 mt-0.5">National Fisheries Division &middot; INTERNAL REPORT</p>
                            </div>
                        </div>
                        <div class="text-right shrink-0">
                            <p class="ir-eyebrow">Internal Result Report</p>
                            <p class="font-mono text-sm font-semibold text-gray-800 mt-1">{{ $submission->reference_number }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">
                                Issued {{ $result?->authorised_at?->format('d M Y') ?? now()->format('d M Y') }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- ── Overall Outcome (Director only) ───────────────────────── --}}
                @php
                    $outcome = $result?->overall_outcome ?? 'pending';
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
                        default        => 'Pending Authorisation',
                    };
                @endphp
                <div class="px-8 py-6 flex items-center justify-between gap-6 border-b border-gray-100 bg-gray-50/40">
                    <div>
                        <p class="ir-meta-label">Overall Determination</p>
                        <div class="ir-seal mt-2" style="color: {{ $sealColor }};">
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
                            <p class="ir-meta-label">Authorised By</p>
                            <p class="text-sm font-medium text-gray-800 mt-1">{{ $result->authorisedBy?->name ?? 'Laboratory Director' }}</p>
                            <p class="text-xs text-gray-500">Laboratory Director</p>
                            <p class="text-xs text-gray-400 mt-1">{{ $result->authorised_at->format('d M Y \a\t H:i') }}</p>
                        @else
                            <p class="text-xs text-gray-400 italic">Awaiting Director authorisation</p>
                        @endif
                    </div>
                </div>

                {{-- ── Submission particulars ─────────────────────────────── --}}
                <div class="px-8 py-6 border-b border-gray-100">
                    <p class="ir-section-title mb-4">Submission Particulars</p>
                    <dl class="grid grid-cols-2 md:grid-cols-3 gap-x-6 gap-y-4 text-sm">
                        <div>
                            <dt class="ir-meta-label">Client</dt>
                            <dd class="text-gray-800 mt-1 font-medium">{{ $submission->client->company_name }}</dd>
                        </div>
                        <div>
                            <dt class="ir-meta-label">Reference</dt>
                            <dd class="font-mono text-gray-800 mt-1">{{ $submission->reference_number }}</dd>
                        </div>
                        <div>
                            <dt class="ir-meta-label">Sample</dt>
                            <dd class="text-gray-800 mt-1">{{ $submission->sample_name }}</dd>
                        </div>
                        <div>
                            <dt class="ir-meta-label">Type</dt>
                            <dd class="text-gray-700 mt-1 capitalize">{{ $submission->sample_type }}</dd>
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
                </div>

                {{-- ── Analytical Results (with determination + analyst) ──────── --}}
                <div class="px-8 py-6 border-b border-gray-100">
                    <p class="ir-section-title mb-4">Analytical Results</p>

                    @foreach($submission->samples as $sample)
                        <div class="mb-6 last:mb-0">
                            <div class="flex items-baseline justify-between mb-2">
                                <h4 class="text-sm font-semibold text-gray-800">
                                    {{ $sample->common_name ?? $sample->sample_code }}
                                </h4>
                                <span class="font-mono text-xs text-gray-400">{{ $sample->sample_code }}</span>
                            </div>

                            @if($sample->sampleTests->isEmpty())
                                <p class="text-sm text-gray-400 italic py-3">No test results available.</p>
                            @else
                                <table class="ir-table w-full text-sm">
                                    <thead>
                                        <tr>
                                            <th class="text-left px-3 py-2.5">Determinand</th>
                                            <th class="text-left px-3 py-2.5">Category</th>
                                            <th class="text-left px-3 py-2.5">Result</th>
                                            <th class="text-left px-3 py-2.5">Unit</th>
                                            <th class="text-left px-3 py-2.5">Determination</th>
                                            <th class="text-left px-3 py-2.5">Analyst</th>
                                            <th class="text-left px-3 py-2.5">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($sample->sampleTests as $test)
                                            @php
                                                $qualColors = [
                                                    'pass'         => 'bg-green-50 text-green-700',
                                                    'fail'         => 'bg-red-50 text-red-700',
                                                    'detected'     => 'bg-orange-50 text-orange-700',
                                                    'not_detected' => 'bg-green-50 text-green-700',
                                                    'less_than'    => 'bg-blue-50 text-blue-700',
                                                    'greater_than' => 'bg-blue-50 text-blue-700',
                                                    'equal_to'     => 'bg-blue-50 text-blue-700',
                                                    'pending'      => 'bg-gray-100 text-gray-400',
                                                ];
                                                $qColor = $qualColors[$test->result_qualifier] ?? 'bg-gray-100 text-gray-500';
                                            @endphp
                                            <tr class="{{ $test->status === 'flagged' ? 'bg-red-50/30' : '' }}">
                                                <td class="px-3 py-2.5 text-gray-800 font-medium">
                                                    {{ $test->getDisplayLabel() }}
                                                </td>
                                                <td class="px-3 py-2.5 text-xs text-gray-500 capitalize">
                                                    {{ $test->getDisplayCategory() }}
                                                </td>
                                                <td class="px-3 py-2.5 font-mono text-gray-700">
                                                    {{ $test->result_value ?? '—' }}
                                                </td>
                                                <td class="px-3 py-2.5 text-xs text-gray-400">
                                                    {{ $test->result_unit ?? '—' }}
                                                </td>
                                                <td class="px-3 py-2.5">
                                                    <span class="inline-flex px-2 py-0.5 text-xs font-medium rounded-full capitalize {{ $qColor }}">
                                                        {{ str_replace('_', ' ', $test->result_qualifier ?? 'pending') }}
                                                    </span>
                                                </td>
                                                <td class="px-3 py-2.5 text-xs text-gray-500">
                                                    {{ $test->assignedTo?->name ?? '—' }}
                                                </td>
                                                <td class="px-3 py-2.5">
                                                    @if($test->status === 'flagged')
                                                        <span class="inline-flex px-1.5 py-0.5 text-xs bg-red-50 text-red-700 rounded font-medium">Flagged</span>
                                                    @elseif($test->status === 'completed')
                                                        <span class="inline-flex px-1.5 py-0.5 text-xs bg-green-50 text-green-700 rounded">Completed</span>
                                                    @else
                                                        <span class="inline-flex px-1.5 py-0.5 text-xs bg-gray-100 text-gray-500 rounded capitalize">{{ str_replace('_',' ',$test->status) }}</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            @if($test->result_notes)
                                                <tr class="bg-gray-50/60">
                                                    <td colspan="7" class="px-3 pb-2 pt-0 text-xs text-gray-500 italic">
                                                        Notes: {{ $test->result_notes }}
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
