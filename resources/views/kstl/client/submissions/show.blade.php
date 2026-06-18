{{-- resources/views/kstl/client/submissions/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between flex-wrap gap-3">
            <div class="flex items-center gap-3">
                <a href="{{ route('client.submissions.index') }}"
                   class="flex items-center justify-center w-8 h-8 rounded-lg border border-gray-200 text-gray-400 hover:text-gray-600 hover:bg-gray-50 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <div>
                    <h2 class="font-bold text-xl text-gray-900 leading-tight tracking-tight">
                        {{ $submission->reference_number }}
                    </h2>
                    <p class="text-xs text-gray-500 mt-0.5">
                        Submitted {{ $submission->submitted_at?->format('d M Y \a\t H:i') ?? $submission->created_at->format('d M Y \a\t H:i') }}
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                @php
                    $statusConfig = [
                        'submitted'              => ['bg-yellow-50 text-yellow-700 ring-yellow-600/20',  'Submitted'],
                        'received'               => ['bg-blue-50 text-blue-700 ring-blue-600/20',        'Received'],
                        'assessing'              => ['bg-purple-50 text-purple-700 ring-purple-600/20',  'Under Assessment'],
                        'accepted'               => ['bg-green-50 text-green-700 ring-green-600/20',     'Accepted'],
                        'rejected'               => ['bg-red-50 text-red-700 ring-red-600/20',           'Rejected'],
                        'consent_to_proceed'     => ['bg-orange-50 text-orange-700 ring-orange-600/20', 'Consent Required'],
                        'testing'                => ['bg-indigo-50 text-indigo-700 ring-indigo-600/20',  'In Testing'],
                        'awaiting_authorisation' => ['bg-amber-50 text-amber-700 ring-amber-600/20',    'Awaiting Sign-off'],
                        'authorised'             => ['bg-teal-50 text-teal-700 ring-teal-600/20',        'Authorised'],
                        'completed'              => ['bg-green-50 text-green-700 ring-green-600/20',     'Completed'],
                        'cancelled'              => ['bg-gray-100 text-gray-500 ring-gray-500/20',       'Cancelled'],
                    ];
                    $sc  = $statusConfig[$submission->status][0] ?? 'bg-gray-100 text-gray-500 ring-gray-500/20';
                    $slabel = $statusConfig[$submission->status][1] ?? ucfirst($submission->status);
                @endphp
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold ring-1 ring-inset {{ $sc }}">
                    {{ $slabel }}
                </span>
                @if($submission->isEditable())
                    <a href="{{ route('client.submissions.edit', $submission->id) }}"
                       class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-gray-600 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit
                    </a>
                @endif
                @if($submission->isCancellable())
                    <form method="POST" action="{{ route('client.submissions.destroy', $submission->id) }}"
                          onsubmit="return confirm('Cancel this submission?')">
                        @csrf @method('DELETE')
                        <button type="submit"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-red-600 border border-red-200 rounded-lg hover:bg-red-50 transition">
                            Cancel Submission
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </x-slot>

    @php
        // ── Shared lookups ──────────────────────────────────────────────────────
        $testLabels = [
            'total_coliforms' => 'Total Coliforms',     'e_coli'          => 'E. coli',
            'enterococci'     => 'Enterococci & Faecal Coliforms',
            'yeast_mold'      => 'Yeast & Mould',       'apc'             => 'APC',
            'e_coli_coliform' => 'E. coli & Coliform',  'staph_aureus'    => 'S. aureus',
            'salmonella_spp'  => 'Salmonella spp.',      'listeria_mono'   => 'L. monocytogenes',
            'listeria_spp'    => 'Listeria spp.',
            'moisture'        => 'Moisture Content',    'histamine'       => 'Histamine',
            'ph'              => 'pH',                  'conductivity'    => 'Conductivity',
            'water_activity'  => 'Water Activity',
        ];
        $microKeys = ['total_coliforms','e_coli','enterococci','yeast_mold','apc','e_coli_coliform','staph_aureus','salmonella_spp','listeria_mono','listeria_spp'];
        $chemKeys  = ['moisture','histamine','ph','conductivity','water_activity'];

        $allTests    = is_array($submission->tests_requested) ? $submission->tests_requested : [];
        $sampleCount = $submission->sample_items ? count($submission->sample_items) : 1;
        $totalTests  = count($allTests);
        $microTests  = array_filter($allTests, fn($t) => in_array($t, $microKeys));
        $chemTests   = array_filter($allTests, fn($t) => in_array($t, $chemKeys));

        $transportLabels = [
            'frozen'  => ['❄️', 'Frozen',  'text-blue-700 bg-blue-50 border-blue-200'],
            'chilled' => ['🧊', 'Chilled', 'text-cyan-700 bg-cyan-50 border-cyan-200'],
            'fresh'   => ['🌿', 'Fresh',   'text-green-700 bg-green-50 border-green-200'],
        ];
        $tc = $transportLabels[$submission->transport_method] ?? ['📦', ucfirst($submission->transport_method ?? '—'), 'text-gray-600 bg-gray-50 border-gray-200'];

        $transportDetailMap = [
            'air_freight_frozen'   => 'Air Freight (Frozen)',
            'sea_freight_reefer'   => 'Sea Freight (Reefer)',
            'road_frozen_truck'    => 'Road (Frozen Truck)',
            'air_freight_chilled'  => 'Air Freight (Chilled)',
            'road_chilled_van'     => 'Road (Chilled Van)',
            'cooler_box_ice_packs' => 'Cooler Box / Ice Packs',
            'other_special'        => 'Special Arrangement',
        ];

        $priorityMap = [
            'routine'   => ['bg-gray-100 text-gray-700',  'Routine'],
            'urgent'    => ['bg-amber-100 text-amber-800', 'Urgent'],
            'emergency' => ['bg-red-100 text-red-800',     'Emergency'],
        ];
        $pc = $priorityMap[$submission->priority] ?? ['bg-gray-100 text-gray-600', ucfirst($submission->priority ?? 'Routine')];

        // Status timeline
        $stages = [
            ['label' => 'Submitted',   'statuses' => ['submitted']],
            ['label' => 'Received',    'statuses' => ['received']],
            ['label' => 'Assessment',  'statuses' => ['assessing','accepted','rejected','consent_to_proceed']],
            ['label' => 'Testing',     'statuses' => ['testing','awaiting_authorisation']],
            ['label' => 'Authorised',  'statuses' => ['authorised']],
            ['label' => 'Completed',   'statuses' => ['completed']],
        ];
        $statusOrder = [
            'submitted'=>1,'received'=>2,
            'assessing'=>3,'accepted'=>3,'rejected'=>3,'consent_to_proceed'=>3,
            'testing'=>4,'awaiting_authorisation'=>4,
            'authorised'=>5,'completed'=>6,'cancelled'=>0,
        ];
        $currentStageIdx = ($statusOrder[$submission->status] ?? 1) - 1;
        $isCancelled     = in_array($submission->status, ['cancelled']);
    @endphp

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-5">

            {{-- ── Flash Messages ─────────────────────────────────────────────── --}}
            @if(session('success'))
                <div class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 rounded-xl px-5 py-3.5 text-sm">
                    <svg class="w-5 h-5 text-green-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/></svg>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="flex items-center gap-3 bg-red-50 border border-red-200 text-red-800 rounded-xl px-5 py-3.5 text-sm">
                    <svg class="w-5 h-5 text-red-400 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1-9a1 1 0 112 0v4a1 1 0 11-2 0V9zm1-5a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd"/></svg>
                    {{ session('error') }}
                </div>
            @endif

            {{-- ── Rejection Notice ────────────────────────────────────────────── --}}
            @if(isset($rejectedAssessments) && $rejectedAssessments->isNotEmpty())
                @foreach($rejectedAssessments as $assessment)
                    @php
                        $assSample      = $assessment->sample;
                        $criteria       = [
                            'Temperature' => [$assessment->temperature_ok, $assessment->temperature_notes],
                            'Storage'     => [$assessment->storage_ok,     $assessment->storage_notes],
                            'Transport'   => [$assessment->transport_ok,   $assessment->transport_notes],
                            'Packaging'   => [$assessment->packaging_ok,   $assessment->packaging_notes],
                            'Colour'      => [$assessment->colour_ok,      $assessment->colour_notes],
                            'Odour'       => [$assessment->odour_ok,       $assessment->odour_notes],
                            'Weight'      => [$assessment->weight_ok,      $assessment->weight_notes],
                        ];
                        $failedCriteria = array_filter($criteria, fn($c) => $c[0] === false);
                        $needsDecision  = $assessment->consent_token && !$assessment->client_decision;
                        $alreadyDecided = $assessment->client_decision !== null;
                    @endphp
                    <div class="rounded-xl border border-red-200 bg-red-50 overflow-hidden">
                        <div class="px-5 py-4 border-b border-red-100 flex items-start justify-between gap-4">
                            <div class="flex items-start gap-3">
                                <div class="w-9 h-9 rounded-full bg-red-100 flex items-center justify-center shrink-0">
                                    <svg class="w-4.5 h-4.5 text-red-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/></svg>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-red-800">
                                        Sample Not Accepted
                                        @if($assSample) — {{ $assSample->common_name }} <span class="font-mono text-xs font-normal">({{ $assSample->sample_code }})</span>@endif
                                    </p>
                                    <p class="text-xs text-red-600 mt-0.5">
                                        Assessed {{ ($assessment->assessed_at ?? $assessment->created_at)->format('d M Y') }}
                                        @if($assessment->assessedBy) by {{ $assessment->assessedBy->name }}@endif
                                    </p>
                                </div>
                            </div>
                            @if($alreadyDecided)
                                <span class="shrink-0 inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium ring-1 ring-inset {{ $assessment->client_decision === 'consent_to_proceed' ? 'bg-amber-50 text-amber-700 ring-amber-600/20' : 'bg-gray-100 text-gray-600 ring-gray-500/20' }}">
                                    {{ $assessment->client_decision === 'consent_to_proceed' ? 'Proceeding with Testing' : 'Submission Withdrawn' }}
                                </span>
                            @endif
                        </div>
                        @if(count($failedCriteria) > 0)
                            <div class="px-5 py-4 border-b border-red-100">
                                <p class="text-xs font-semibold text-red-700 uppercase tracking-wide mb-2">Issues Found</p>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-1.5">
                                    @foreach($failedCriteria as $label => [$ok, $notes])
                                        <div class="flex items-start gap-2 text-sm text-red-800">
                                            <svg class="w-4 h-4 text-red-500 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1-9a1 1 0 112 0v4a1 1 0 11-2 0V9zm1-5a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd"/></svg>
                                            <span><strong>{{ $label }}</strong>@if($notes) — <span class="font-normal text-red-600">{{ $notes }}</span>@endif</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        @if($assessment->rejection_reason)
                            <div class="px-5 py-3 border-b border-red-100 bg-red-100/40">
                                <p class="text-xs font-medium text-red-700 mb-0.5">Lab's reason</p>
                                <p class="text-sm text-red-800 italic">"{{ $assessment->rejection_reason }}"</p>
                            </div>
                        @endif
                        <div class="px-5 py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            @if($needsDecision)
                                <p class="text-sm text-red-700"><strong>Your consent is required.</strong> Indicate whether to proceed with testing or withdraw.</p>
                                <a href="{{ route('client.consent.show', $assessment->consent_token) }}"
                                   class="shrink-0 inline-flex items-center gap-2 px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition">
                                    Review &amp; Decide →
                                </a>
                            @elseif($alreadyDecided)
                                <p class="text-sm text-red-600">
                                    You responded on {{ $assessment->client_decision_at?->format('d M Y') }}.
                                    {{ $assessment->client_decision === 'consent_to_proceed' ? 'Testing will proceed.' : 'Submission withdrawn.' }}
                                </p>
                            @else
                                <p class="text-sm text-red-600">Please contact the lab for further details.</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            @endif

            {{-- ── Results Banner ──────────────────────────────────────────────── --}}
            @if($submission->hasResult())
                <div class="bg-green-600 rounded-xl p-5 flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-white">Test Results Ready</p>
                            <p class="text-xs text-green-100 mt-0.5">Your results have been authorised and are available to view.</p>
                        </div>
                    </div>
                    <a href="{{ route('client.results.index') }}"
                       class="shrink-0 inline-flex items-center gap-2 px-4 py-2 bg-white text-green-700 text-sm font-semibold rounded-lg hover:bg-green-50 transition">
                        View Results →
                    </a>
                </div>
            @endif

            {{-- ══════════════════════════════════════════════════════════════════
                 SECTION 1: AT A GLANCE
            ══════════════════════════════════════════════════════════════════ --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-gray-700">Submission Overview</h3>
                    <span class="text-xs text-gray-400">{{ $submission->reference_number }}</span>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-4 divide-x divide-y sm:divide-y-0 divide-gray-100">
                    {{-- Samples --}}
                    <div class="px-5 py-5 flex items-center gap-3.5">
                        <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-gray-900 leading-none">{{ $sampleCount }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $sampleCount === 1 ? 'Sample' : 'Samples' }}</p>
                        </div>
                    </div>
                    {{-- Tests --}}
                    <div class="px-5 py-5 flex items-center gap-3.5">
                        <div class="w-10 h-10 rounded-xl bg-purple-50 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-gray-900 leading-none">{{ $totalTests }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $totalTests === 1 ? 'Test' : 'Tests' }} Requested</p>
                        </div>
                    </div>
                    {{-- Transport --}}
                    <div class="px-5 py-5 flex items-center gap-3.5">
                        <div class="w-10 h-10 rounded-xl {{ $tc[2] }} border flex items-center justify-center shrink-0 text-lg">
                            {{ $tc[0] }}
                        </div>
                        <div>
                            <p class="text-sm font-bold text-gray-900 leading-none">{{ $tc[1] }}</p>
                            <p class="text-xs text-gray-500 mt-1">Transport</p>
                        </div>
                    </div>
                    {{-- Priority + Date --}}
                    <div class="px-5 py-5 flex items-center gap-3.5">
                        <div class="w-10 h-10 rounded-xl bg-gray-50 border border-gray-100 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-gray-900 leading-none">{{ $submission->collected_at?->format('d M Y') ?? '—' }}</p>
                            <p class="text-xs text-gray-500 mt-1">
                                Collected &nbsp;·&nbsp;
                                <span class="inline-flex px-1.5 py-0.5 rounded text-xs font-medium {{ $pc[0] }}">{{ $pc[1] }}</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ══════════════════════════════════════════════════════════════════
                 SECTION 2: PROGRESS TIMELINE
            ══════════════════════════════════════════════════════════════════ --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm px-6 py-5">
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-sm font-semibold text-gray-700">Submission Progress</h3>
                    <span class="text-xs text-gray-400">
                        Step {{ min($currentStageIdx + 1, count($stages)) }} of {{ count($stages) }}
                    </span>
                </div>
                <div class="relative">
                    <div class="absolute top-4 left-4 right-4 h-0.5 bg-gray-200 rounded-full"></div>
                    <div class="absolute top-4 left-4 h-0.5 rounded-full transition-all
                        {{ $isCancelled ? 'bg-red-300' : 'bg-green-400' }}"
                         style="width: calc({{ min($currentStageIdx, count($stages) - 1) }} / {{ count($stages) - 1 }} * (100% - 2rem))"></div>
                    <div class="relative flex justify-between">
                        @foreach($stages as $si => $stage)
                            @php
                                $done   = !$isCancelled && $si < $currentStageIdx;
                                $active = !$isCancelled && $si === $currentStageIdx;
                                $fail   = $isCancelled && $si === 0;
                            @endphp
                            <div class="flex flex-col items-center" style="flex: 1">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold relative z-10
                                    {{ $done   ? 'bg-green-500 text-white shadow-sm' :
                                       ($active ? 'bg-blue-600 text-white ring-4 ring-blue-100 shadow-sm' :
                                       ($fail   ? 'bg-red-400 text-white' : 'bg-gray-100 text-gray-400')) }}">
                                    @if($done)
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    @elseif($fail)
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                                    @else
                                        {{ $si + 1 }}
                                    @endif
                                </div>
                                <p class="text-xs mt-2 text-center hidden sm:block
                                    {{ $done ? 'text-green-600 font-medium' : ($active ? 'text-blue-700 font-semibold' : ($fail ? 'text-red-500 font-medium' : 'text-gray-400')) }}">
                                    {{ $stage['label'] }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                    {{-- Mobile: label of current step --}}
                    <div class="sm:hidden mt-4 text-center">
                        <span class="text-sm font-semibold {{ $isCancelled ? 'text-red-600' : 'text-blue-700' }}">
                            {{ $isCancelled ? 'Cancelled' : $stages[min($currentStageIdx, count($stages) - 1)]['label'] }}
                        </span>
                    </div>
                </div>
                @if($isCancelled)
                    <p class="mt-4 text-xs text-center text-red-500 bg-red-50 rounded-lg py-2">
                        This submission was cancelled.
                    </p>
                @elseif($submission->status === 'submitted')
                    <p class="mt-4 text-xs text-center text-gray-400 bg-gray-50 rounded-lg py-2">
                        Your submission has been received. The lab will confirm receipt shortly.
                    </p>
                @endif
            </div>

            {{-- ══════════════════════════════════════════════════════════════════
                 TWO-COLUMN LAYOUT: Client + Collection side by side
            ══════════════════════════════════════════════════════════════════ --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                {{-- ── Client & Company ─────────────────────────────────────── --}}
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-2">
                        <div class="w-7 h-7 rounded-lg bg-blue-50 flex items-center justify-center">
                            <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <h3 class="text-sm font-semibold text-gray-700">Client Details</h3>
                    </div>
                    <div class="px-5 py-4 space-y-3.5">
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <p class="text-xs text-gray-400 uppercase tracking-wide">Company</p>
                                <p class="text-sm font-medium text-gray-800 mt-0.5">{{ $client->company_name }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 uppercase tracking-wide">Address</p>
                                <p class="text-sm text-gray-700 mt-0.5">{{ $client->address ?? '—' }}</p>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-3 pt-3 border-t border-gray-50">
                            <div>
                                <p class="text-xs text-gray-400 uppercase tracking-wide">Responsible Officer</p>
                                <p class="text-sm text-gray-700 mt-0.5">{{ $client->responsible_officer_name ?? '—' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 uppercase tracking-wide">Phone</p>
                                <p class="text-sm text-gray-700 mt-0.5">{{ $client->company_phone ?? $client->responsible_officer_phone ?? '—' }}</p>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-3 pt-3 border-t border-gray-50">
                            <div>
                                <p class="text-xs text-gray-400 uppercase tracking-wide">Email</p>
                                <p class="text-sm text-gray-700 mt-0.5">{{ $client->responsible_officer_email ?? '—' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 uppercase tracking-wide">Date of Application</p>
                                <p class="text-sm text-gray-700 mt-0.5">
                                    {{ $submission->application_date?->format('d M Y') ?? $submission->submitted_at?->format('d M Y') ?? '—' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ── Collection Info ──────────────────────────────────────── --}}
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-2">
                        <div class="w-7 h-7 rounded-lg bg-green-50 flex items-center justify-center">
                            <svg class="w-3.5 h-3.5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-sm font-semibold text-gray-700">Collection Info</h3>
                    </div>
                    <div class="px-5 py-4 space-y-3.5">
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <p class="text-xs text-gray-400 uppercase tracking-wide">Date Collected</p>
                                <p class="text-sm font-medium text-gray-800 mt-0.5">{{ $submission->collected_at?->format('d M Y') ?? '—' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 uppercase tracking-wide">Location</p>
                                <p class="text-sm text-gray-700 mt-0.5">{{ $submission->collection_location ?? '—' }}</p>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-3 pt-3 border-t border-gray-50">
                            <div>
                                <p class="text-xs text-gray-400 uppercase tracking-wide">Priority</p>
                                <span class="inline-flex mt-0.5 px-2.5 py-1 text-xs font-semibold rounded-full {{ $pc[0] }}">{{ $pc[1] }}</span>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 uppercase tracking-wide">Results Required By</p>
                                <p class="text-sm text-gray-700 mt-0.5">{{ $submission->results_required_by?->format('d M Y') ?? 'No deadline' }}</p>
                            </div>
                        </div>
                        @if($submission->sample_description)
                            <div class="pt-3 border-t border-gray-50">
                                <p class="text-xs text-gray-400 uppercase tracking-wide">Notes</p>
                                <p class="text-sm text-gray-700 mt-0.5 leading-relaxed">{{ $submission->sample_description }}</p>
                            </div>
                        @endif
                        @if(!$submission->sample_description && !$submission->collected_at && !$submission->collection_location)
                            <p class="text-sm text-gray-400 italic">No collection details recorded.</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- ══════════════════════════════════════════════════════════════════
                 SECTION 3: TRANSPORT & TESTS
            ══════════════════════════════════════════════════════════════════ --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-2">
                    <div class="w-7 h-7 rounded-lg bg-cyan-50 flex items-center justify-center">
                        <svg class="w-3.5 h-3.5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10l1 1h1m8-1V8l3 3 3-3v8m-3-9h3l3 3"/>
                        </svg>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-700">Transport & Tests</h3>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 divide-y sm:divide-y-0 sm:divide-x divide-gray-100">
                    {{-- Transport --}}
                    <div class="px-5 py-5">
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3">Sample Transport</p>
                        <div class="flex items-center gap-3 mb-3">
                            <span class="inline-flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-semibold border {{ $tc[2] }}">
                                {{ $tc[0] }} {{ $tc[1] }}
                            </span>
                        </div>
                        @if($submission->transport_detail)
                            <p class="text-xs text-gray-400 uppercase tracking-wide mb-0.5">Method</p>
                            <p class="text-sm text-gray-700">{{ $transportDetailMap[$submission->transport_detail] ?? str_replace('_', ' ', $submission->transport_detail) }}</p>
                        @endif
                        @if($submission->special_instructions)
                            <div class="mt-3 bg-amber-50 rounded-lg px-3 py-2.5 border border-amber-100">
                                <p class="text-xs font-semibold text-amber-700 mb-0.5">Special Instructions</p>
                                <p class="text-sm text-amber-800">{{ $submission->special_instructions }}</p>
                            </div>
                        @endif
                        @if($submission->client_notes)
                            <div class="mt-3 bg-gray-50 rounded-lg px-3 py-2.5">
                                <p class="text-xs font-semibold text-gray-500 mb-0.5">Additional Notes</p>
                                <p class="text-sm text-gray-700">{{ $submission->client_notes }}</p>
                            </div>
                        @endif
                    </div>
                    {{-- Tests requested --}}
                    <div class="px-5 py-5">
                        <div class="flex items-center justify-between mb-3">
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Tests Requested</p>
                            @if($totalTests)
                                <span class="text-xs font-semibold bg-indigo-50 text-indigo-700 px-2 py-0.5 rounded-full">{{ $totalTests }} total</span>
                            @endif
                        </div>
                        @if(count($microTests))
                            <p class="text-xs font-medium text-purple-600 mb-1.5">Microbiological</p>
                            <div class="flex flex-wrap gap-1.5 mb-3">
                                @foreach($microTests as $t)
                                    <span class="inline-flex px-2 py-0.5 text-xs bg-purple-50 text-purple-700 rounded-full font-medium">{{ $testLabels[$t] ?? $t }}</span>
                                @endforeach
                            </div>
                        @endif
                        @if(count($chemTests))
                            <p class="text-xs font-medium text-blue-600 mb-1.5">Chemical</p>
                            <div class="flex flex-wrap gap-1.5 mb-3">
                                @foreach($chemTests as $t)
                                    <span class="inline-flex px-2 py-0.5 text-xs bg-blue-50 text-blue-700 rounded-full font-medium">{{ $testLabels[$t] ?? $t }}</span>
                                @endforeach
                            </div>
                        @endif
                        @if($submission->tests_other)
                            <p class="text-xs font-medium text-gray-500 mb-1">Other</p>
                            <p class="text-sm text-gray-600">{{ $submission->tests_other }}</p>
                        @endif
                        @if(!$totalTests && !$submission->tests_other)
                            <p class="text-sm text-gray-400 italic">See individual samples below for per-sample tests.</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- ══════════════════════════════════════════════════════════════════
                 SECTION 4: SAMPLES
            ══════════════════════════════════════════════════════════════════ --}}
            @if($submission->sample_items && count($submission->sample_items))
                @php
                    $showMicroKeys = ['total_coliforms','e_coli','enterococci','yeast_mold','apc','e_coli_coliform','staph_aureus','salmonella_spp','listeria_mono','listeria_spp'];
                    $showChemKeys  = ['moisture','histamine','ph','conductivity','water_activity'];
                @endphp
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded-lg bg-indigo-50 flex items-center justify-center">
                                <svg class="w-3.5 h-3.5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                                </svg>
                            </div>
                            <h3 class="text-sm font-semibold text-gray-700">Test Samples</h3>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700">
                            {{ count($submission->sample_items) }} {{ count($submission->sample_items) === 1 ? 'sample' : 'samples' }}
                        </span>
                    </div>
                    <div class="divide-y divide-gray-100">
                        @foreach($submission->sample_items as $i => $item)
                            @php
                                $itemTests = $item['tests'] ?? [];
                                $itemMicro = array_filter($itemTests, fn($t) => in_array($t, $showMicroKeys));
                                $itemChem  = array_filter($itemTests, fn($t) => in_array($t, $showChemKeys));
                            @endphp
                            <div class="px-5 py-4">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="flex items-start gap-3">
                                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-indigo-100 text-xs font-bold text-indigo-700 shrink-0 mt-0.5">
                                            {{ $i + 1 }}
                                        </span>
                                        <div>
                                            <p class="text-sm font-semibold text-gray-900">{{ $item['name'] ?? '—' }}</p>
                                            @if(!empty($item['scientific_name']))
                                                <p class="text-xs text-gray-400 italic mt-0.5">{{ $item['scientific_name'] }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="text-right text-xs shrink-0 space-y-1">
                                        @if(!empty($item['ref']))
                                            <span class="inline-flex font-mono bg-gray-100 text-gray-600 px-2 py-0.5 rounded text-xs">{{ $item['ref'] }}</span>
                                        @endif
                                        @if(!empty($item['type']))
                                            <span class="inline-flex bg-gray-50 text-gray-500 px-2 py-0.5 rounded text-xs capitalize ml-1">{{ $item['type'] }}</span>
                                        @endif
                                        @if(isset($item['qty']) && $item['qty'] !== '')
                                            <p class="text-gray-500">{{ number_format((float)$item['qty'], 2) }} {{ $item['unit'] ?? '' }}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="mt-3 ml-10">
                                    @if(count($itemTests) > 0 || !empty($item['tests_other']))
                                        <div class="space-y-1.5">
                                            @if(count($itemMicro) > 0)
                                                <div class="flex flex-wrap items-center gap-1">
                                                    <span class="text-xs text-purple-400 font-medium mr-0.5">Micro</span>
                                                    @foreach($itemMicro as $t)
                                                        <span class="inline-flex px-2 py-0.5 text-xs bg-purple-50 text-purple-700 rounded-full">{{ $testLabels[$t] ?? $t }}</span>
                                                    @endforeach
                                                </div>
                                            @endif
                                            @if(count($itemChem) > 0)
                                                <div class="flex flex-wrap items-center gap-1">
                                                    <span class="text-xs text-blue-400 font-medium mr-0.5">Chem</span>
                                                    @foreach($itemChem as $t)
                                                        <span class="inline-flex px-2 py-0.5 text-xs bg-blue-50 text-blue-700 rounded-full">{{ $testLabels[$t] ?? $t }}</span>
                                                    @endforeach
                                                </div>
                                            @endif
                                            @if(!empty($item['tests_other']))
                                                <p class="text-xs text-gray-500"><span class="text-gray-400">Other:</span> {{ $item['tests_other'] }}</p>
                                            @endif
                                        </div>
                                    @else
                                        <p class="text-xs text-gray-400 italic">No tests selected for this sample.</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- ══════════════════════════════════════════════════════════════════
                 SECTION 5: LAB ASSESSMENT
            ══════════════════════════════════════════════════════════════════ --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-2">
                    <div class="w-7 h-7 rounded-lg bg-amber-50 flex items-center justify-center">
                        <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-700">Lab Assessment</h3>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 divide-y sm:divide-y-0 sm:divide-x divide-gray-100">
                    <div class="px-5 py-5">
                        <p class="text-xs text-gray-400 uppercase tracking-wide mb-1.5">Current Status</p>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold ring-1 ring-inset {{ $sc }}">
                            {{ $slabel }}
                        </span>
                    </div>
                    <div class="px-5 py-5">
                        <p class="text-xs text-gray-400 uppercase tracking-wide mb-1.5">Submitted</p>
                        <p class="text-sm font-medium text-gray-800">{{ $submission->submitted_at?->format('d M Y') ?? $submission->created_at->format('d M Y') }}</p>
                        <p class="text-xs text-gray-400">{{ $submission->submitted_at?->format('H:i') ?? $submission->created_at->format('H:i') }}</p>
                    </div>
                    <div class="px-5 py-5">
                        <p class="text-xs text-gray-400 uppercase tracking-wide mb-1.5">Received By Lab</p>
                        @if($submission->received_at)
                            <p class="text-sm font-medium text-gray-800">{{ $submission->received_at->format('d M Y') }}</p>
                            <p class="text-xs text-gray-400">{{ $submission->received_at->format('H:i') }}</p>
                        @else
                            <div class="flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-400 inline-block"></span>
                                <span class="text-sm text-gray-400 italic">Awaiting receipt</span>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="border-t border-gray-100 px-5 py-5">
                    <p class="text-xs text-gray-400 uppercase tracking-wide mb-2">Lab Notes</p>
                    @if($submission->lab_notes)
                        <p class="text-sm text-gray-700 leading-relaxed">{{ $submission->lab_notes }}</p>
                    @else
                        <p class="text-sm text-gray-400 italic">No notes from the lab yet.</p>
                    @endif
                </div>
            </div>

            {{-- ══════════════════════════════════════════════════════════════════
                 SECTION 6: DECLARATION FOOTER
            ══════════════════════════════════════════════════════════════════ --}}
            <div class="bg-gray-50 rounded-xl border border-gray-200 px-5 py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 text-sm text-gray-600">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    <span>
                        Declared by
                        <strong class="text-gray-800">{{ $submission->submitter_name ?? $user->name }}</strong>
                        @if($submission->submitter_designation)
                            <span class="text-gray-400">({{ $submission->submitter_designation }})</span>
                        @endif
                        on behalf of <strong class="text-gray-800">{{ $client->company_name }}</strong>
                    </span>
                </div>
                <p class="text-xs text-gray-400 shrink-0">
                    {{ $submission->submitted_at?->format('d M Y H:i') ?? $submission->created_at->format('d M Y H:i') }}
                </p>
            </div>

            {{-- ── Actions ─────────────────────────────────────────────────────── --}}
            <div class="flex items-center justify-between pb-8">
                <a href="{{ route('client.submissions.index') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 text-sm text-gray-600 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                    ← Back to Submissions
                </a>
                @if($submission->isEditable())
                    <a href="{{ route('client.submissions.edit', $submission->id) }}"
                       class="inline-flex items-center gap-2 px-5 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit Submission
                    </a>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
