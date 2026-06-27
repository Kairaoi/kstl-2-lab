{{-- resources/views/kstl/client/submissions/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        @php
            $statusConfig = [
                'submitted'              => ['background:#fefce8;color:#854d0e;',  'Submitted'],
                'received'               => ['background:#eff6ff;color:#1e40af;',  'Received'],
                'assessing'              => ['background:#faf5ff;color:#6b21a8;',  'Under Assessment'],
                'accepted'               => ['background:#f0fdf4;color:#166534;',  'Accepted'],
                'rejected'               => ['background:#fef2f2;color:#991b1b;',  'Rejected'],
                'consent_to_proceed'     => ['background:#fff7ed;color:#9a3412;',  'Consent Required'],
                'testing'                => ['background:#eef2ff;color:#3730a3;',  'In Testing'],
                'awaiting_authorisation' => ['background:#fffbeb;color:#92400e;',  'Awaiting Sign-off'],
                'authorised'             => ['background:#f0fdfa;color:#065f46;',  'Authorised'],
                'completed'              => ['background:#f0fdf4;color:#166534;',  'Completed'],
                'cancelled'              => ['background:#f8fafc;color:#6b7280;',  'Cancelled'],
            ];
            $sc     = $statusConfig[$submission->status][0] ?? 'background:#f8fafc;color:#6b7280;';
            $slabel = $statusConfig[$submission->status][1] ?? ucfirst($submission->status);
        @endphp
        <div style="position:relative;overflow:hidden;background:linear-gradient(135deg,#0f2240 0%,#1a2f4e 60%,#1e3a5f 100%);margin:-1px;">
            <div style="position:absolute;inset:0;opacity:.04;background-image:repeating-linear-gradient(45deg,#fff 0,#fff 1px,transparent 0,transparent 50%);background-size:12px 12px;pointer-events:none;"></div>
            <div style="position:absolute;top:0;left:0;right:0;height:3px;background:linear-gradient(90deg,#1a2f4e,#b8922a 30%,#b8922a 70%,#1a2f4e);"></div>
            <div style="max-width:80rem;margin:0 auto;padding:28px 2rem;">
                <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:20px;position:relative;">
                    <div style="display:flex;align-items:center;gap:18px;">
                        <img src="{{ asset('images/mfor-logo.png') }}" alt="Ministry of Fisheries &amp; Ocean Resources" style="width:56px;height:56px;object-fit:contain;filter:brightness(0) invert(1);opacity:.92;">
                        <div>
                            <p style="font-size:8.5px;font-weight:700;letter-spacing:.22em;text-transform:uppercase;color:#b8922a;margin-bottom:5px;">
                                Client Portal &nbsp;·&nbsp; My Submissions
                            </p>
                            <h1 style="font-family:'Georgia',serif;font-size:22px;font-weight:700;color:#ffffff;line-height:1.2;margin:0;">
                                {{ $submission->reference_number }}
                            </h1>
                            <p style="font-size:11px;color:#94a3b8;margin-top:4px;">
                                Submitted {{ $submission->submitted_at?->format('d M Y \a\t H:i') ?? $submission->created_at->format('d M Y \a\t H:i') }}
                            </p>
                        </div>
                    </div>
                    <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
                        <span style="display:inline-flex;align-items:center;border-radius:999px;padding:3px 14px;font-size:10px;font-weight:700;{{ $sc }}">
                            {{ $slabel }}
                        </span>
                        @if($submission->isEditable())
                            <a href="{{ route('client.submissions.edit', $submission->id) }}"
                               style="display:inline-flex;align-items:center;gap:6px;padding:7px 14px;border-radius:3px;font-size:12px;font-weight:600;text-decoration:none;background:rgba(255,255,255,.12);color:#fff;border:1px solid rgba(255,255,255,.25);">
                                <svg style="width:12px;height:12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Edit
                            </a>
                        @endif
                        @if($submission->isCancellable())
                            <form method="POST" action="{{ route('client.submissions.destroy', $submission->id) }}"
                                  onsubmit="return confirm('Cancel this submission?')" style="margin:0;">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        style="display:inline-flex;align-items:center;padding:7px 14px;border-radius:3px;font-size:12px;font-weight:600;cursor:pointer;background:rgba(220,38,38,.2);color:#fca5a5;border:1px solid rgba(252,165,165,.3);">
                                    Cancel
                                </button>
                            </form>
                        @endif
                        <a href="{{ route('client.submissions.index') }}" style="display:inline-flex;align-items:center;gap:5px;font-size:11px;font-weight:600;color:#94a3b8;text-decoration:none;">
                            <svg style="width:12px;height:12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                            Back
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    @push('styles')
    <style>
        .page-hdr { padding: 0 !important; }
        .page-hdr-inner { max-width: 100% !important; padding: 0 !important; }
        .app-main { padding-left: 0 !important; padding-right: 0 !important;
                    padding-top: 0 !important; max-width: 100% !important; }
    </style>
    @endpush

    @php
        // ── Shared lookups ──────────────────────────────────────────────────────
        $testLabels = [
            'total_coliforms'        => 'Total Coliforms',     'e_coli'                 => 'E. coli',
            'enterococci'            => 'Enterococci',          'faecal_coliforms'       => 'Faecal Coliforms',
            'yeast_mold'             => 'Yeast & Mould',        'apc'                    => 'APC',
            'e_coli_coliform'        => 'E. coli & Coliform',   'staph_aureus'           => 'S. aureus',
            'salmonella_spp'         => 'Salmonella spp.',      'listeria_mono'          => 'L. monocytogenes',
            'listeria_spp'           => 'Listeria spp.',        'e_coli_colilert'        => 'E. coli (Colilert)',
            'enterococci_enterolert' => 'Enterococci (Enterolert)',
            'moisture'               => 'Moisture Content',     'histamine'              => 'Histamine',
            'ph'                     => 'pH',                   'conductivity'           => 'Conductivity',
            'water_activity'         => 'Water Activity',
        ];
        $microKeys = ['total_coliforms','e_coli','enterococci','faecal_coliforms','yeast_mold','apc','e_coli_coliform','staph_aureus','salmonella_spp','listeria_mono','listeria_spp','e_coli_colilert','enterococci_enterolert'];
        $chemKeys  = ['moisture','histamine','ph','conductivity','water_activity'];

        $allTests    = is_array($submission->tests_requested) ? $submission->tests_requested : [];
        $sampleCount = $submission->sample_items ? count($submission->sample_items) : 1;
        $totalTests  = count($allTests);
        $microTests  = array_filter($allTests, fn($t) => in_array($t, $microKeys));
        $chemTests   = array_filter($allTests, fn($t) => in_array($t, $chemKeys));

        $transportLabels = [
            'frozen'  => ['❄️', 'Frozen',  'color:#1d4ed8;background:#eff6ff;border-color:#bfdbfe;'],
            'chilled' => ['🧊', 'Chilled', 'color:#0e7490;background:#ecfeff;border-color:#a5f3fc;'],
            'fresh'   => ['🌿', 'Fresh',   'color:#166534;background:#f0fdf4;border-color:#bbf7d0;'],
        ];
        $tc = $transportLabels[$submission->transport_method] ?? ['📦', ucfirst($submission->transport_method ?? '—'), 'color:#374151;background:#f8fafc;border-color:#e2e8f0;'];

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
            'routine'   => ['background:#f1f5f9;color:#374151;',  'Routine'],
            'urgent'    => ['background:#fef3c7;color:#92400e;',  'Urgent'],
            'emergency' => ['background:#fef2f2;color:#991b1b;',  'Emergency'],
        ];
        $pc = $priorityMap[$submission->priority] ?? ['background:#f1f5f9;color:#374151;', ucfirst($submission->priority ?? 'Routine')];

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

    <div style="background:#f1f5f9;min-height:100vh;padding:52px 0 56px;">
        <div style="max-width:80rem;margin:0 auto;padding:0 2rem;display:flex;flex-direction:column;gap:24px;">

            {{-- ── Flash Messages ─────────────────────────────────────────────── --}}
            @if(session('success'))
                <div style="border-left:4px solid #16a34a;padding:12px 18px;border-radius:0 4px 4px 0;background:#f0fdf4;margin-bottom:16px;font-size:13px;color:#166534;">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div style="border-left:4px solid #dc2626;padding:12px 18px;border-radius:0 4px 4px 0;background:#fef2f2;margin-bottom:16px;font-size:13px;color:#991b1b;">
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
                    <div style="background:#fff;border:1px solid #fca5a5;border-radius:4px;overflow:hidden;margin-bottom:16px;">
                        <div style="padding:14px 18px;border-bottom:1px solid #fecaca;display:flex;align-items:flex-start;justify-content:space-between;gap:12px;background:#fef2f2;">
                            <div style="display:flex;align-items:flex-start;gap:10px;">
                                <svg style="width:18px;height:18px;flex-shrink:0;margin-top:1px;" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" style="color:#dc2626;"/></svg>
                                <div>
                                    <p style="font-size:13px;font-weight:700;color:#991b1b;margin:0;">
                                        Sample Not Accepted
                                        @if($assSample) — {{ $assSample->common_name }} <span style="font-family:monospace;font-size:11px;font-weight:400;">({{ $assSample->sample_code }})</span>@endif
                                    </p>
                                    <p style="font-size:11px;color:#b91c1c;margin:3px 0 0;">
                                        Assessed {{ ($assessment->assessed_at ?? $assessment->created_at)->format('d M Y') }}
                                        @if($assessment->assessedBy) by {{ $assessment->assessedBy->name }}@endif
                                    </p>
                                </div>
                            </div>
                            @if($alreadyDecided)
                                <span style="flex-shrink:0;display:inline-flex;align-items:center;border-radius:999px;padding:2px 12px;font-size:10px;font-weight:700;{{ $assessment->client_decision === 'consent_to_proceed' ? 'background:#fefce8;color:#854d0e;' : 'background:#f8fafc;color:#6b7280;' }}">
                                    {{ $assessment->client_decision === 'consent_to_proceed' ? 'Proceeding with Testing' : 'Submission Withdrawn' }}
                                </span>
                            @endif
                        </div>
                        @if(count($failedCriteria) > 0)
                            <div style="padding:14px 18px;border-bottom:1px solid #fecaca;">
                                <p style="font-size:9px;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:#b91c1c;margin:0 0 10px;">Issues Found</p>
                                <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:8px;">
                                    @foreach($failedCriteria as $label => [$ok, $notes])
                                        <div style="display:flex;align-items:flex-start;gap:6px;font-size:12.5px;color:#991b1b;">
                                            <svg style="width:14px;height:14px;flex-shrink:0;margin-top:1px;" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1-9a1 1 0 112 0v4a1 1 0 11-2 0V9zm1-5a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd"/></svg>
                                            <span><strong>{{ $label }}</strong>@if($notes) — <span style="font-weight:400;color:#b91c1c;">{{ $notes }}</span>@endif</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        @if($assessment->rejection_reason)
                            <div style="padding:12px 18px;border-bottom:1px solid #fecaca;background:#fef9f9;">
                                <p style="font-size:9px;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:#9ca3af;margin:0 0 4px;">Lab's Reason</p>
                                <p style="font-size:12.5px;color:#991b1b;font-style:italic;margin:0;">"{{ $assessment->rejection_reason }}"</p>
                            </div>
                        @endif
                        <div style="padding:14px 18px;display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;">
                            @if($needsDecision)
                                <p style="font-size:12.5px;color:#b91c1c;margin:0;"><strong>Your consent is required.</strong> Indicate whether to proceed with testing or withdraw.</p>
                                <a href="{{ route('client.consent.show', $assessment->consent_token) }}"
                                   style="background:#dc2626;color:#fff;padding:7px 16px;border-radius:3px;font-size:12px;font-weight:600;text-decoration:none;flex-shrink:0;display:inline-flex;align-items:center;gap:6px;">
                                    Review &amp; Decide
                                    <svg style="width:12px;height:12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                    </svg>
                                </a>
                            @elseif($alreadyDecided)
                                <p style="font-size:12.5px;color:#b91c1c;margin:0;">
                                    You responded on {{ $assessment->client_decision_at?->format('d M Y') }}.
                                    {{ $assessment->client_decision === 'consent_to_proceed' ? 'Testing will proceed.' : 'Submission withdrawn.' }}
                                </p>
                            @else
                                <p style="font-size:12.5px;color:#b91c1c;margin:0;">Please contact the lab for further details.</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            @endif

            {{-- ── Results Banner ──────────────────────────────────────────────── --}}
            @if($submission->hasResult())
                <div style="background:#0d9488;border-radius:4px;padding:18px 22px;display:flex;align-items:center;justify-content:space-between;gap:16px;margin-bottom:16px;flex-wrap:wrap;">
                    <div>
                        <p style="font-size:13px;font-weight:700;color:#fff;margin:0;">Test Results Ready</p>
                        <p style="font-size:11px;color:#ccfbf1;margin:4px 0 0;">Your results have been authorised and are available to view.</p>
                    </div>
                    <a href="{{ route('client.results.index') }}"
                       style="background:#fff;color:#0d9488;padding:8px 18px;border-radius:3px;font-size:12px;font-weight:700;text-decoration:none;flex-shrink:0;">
                        View Results →
                    </a>
                </div>
            @endif

            {{-- ══════════════════════════════════════════════════════════════════
                 SECTION 1: AT A GLANCE
            ══════════════════════════════════════════════════════════════════ --}}
            <div style="background:#fff;border:1px solid #e2e8f0;border-radius:4px;overflow:hidden;margin-bottom:16px;">
                <div style="padding:14px 18px;border-bottom:2px solid #b8922a;display:flex;align-items:center;justify-content:space-between;">
                    <h3 style="font-family:'Georgia',serif;font-size:15px;font-weight:700;color:#1a2f4e;margin:0;padding-bottom:0;">Submission Overview</h3>
                    <span style="font-size:9px;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:#9ca3af;">{{ $submission->reference_number }}</span>
                </div>
                <div style="display:grid;grid-template-columns:repeat(4,1fr);">
                    {{-- Samples --}}
                    <div style="padding:18px 20px;border-right:1px solid #f1f5f9;">
                        <p style="font-size:9px;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:#9ca3af;margin:0 0 6px;">Samples</p>
                        <p style="font-size:24px;font-weight:700;color:#1a2f4e;margin:0;line-height:1;">{{ $sampleCount }}</p>
                        <p style="font-size:11px;color:#6b7280;margin:4px 0 0;">{{ $sampleCount === 1 ? 'Sample' : 'Samples' }}</p>
                    </div>
                    {{-- Tests --}}
                    <div style="padding:18px 20px;border-right:1px solid #f1f5f9;">
                        <p style="font-size:9px;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:#9ca3af;margin:0 0 6px;">Tests Requested</p>
                        <p style="font-size:24px;font-weight:700;color:#1a2f4e;margin:0;line-height:1;">{{ $totalTests }}</p>
                        <p style="font-size:11px;color:#6b7280;margin:4px 0 0;">{{ $totalTests === 1 ? 'Test' : 'Tests' }}</p>
                    </div>
                    {{-- Transport --}}
                    <div style="padding:18px 20px;border-right:1px solid #f1f5f9;">
                        <p style="font-size:9px;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:#9ca3af;margin:0 0 6px;">Transport</p>
                        <p style="font-size:18px;margin:0;line-height:1;">{{ $tc[0] }}</p>
                        <p style="font-size:12.5px;font-weight:600;color:#1a2f4e;margin:4px 0 0;">{{ $tc[1] }}</p>
                    </div>
                    {{-- Collection & Priority --}}
                    <div style="padding:18px 20px;">
                        <p style="font-size:9px;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:#9ca3af;margin:0 0 6px;">Collected</p>
                        <p style="font-size:13px;font-weight:600;color:#1a2f4e;margin:0;">{{ $submission->collected_at?->format('d M Y') ?? '—' }}</p>
                        <span style="display:inline-flex;margin-top:5px;padding:2px 10px;border-radius:999px;font-size:10px;font-weight:700;{{ $pc[0] }}">{{ $pc[1] }}</span>
                    </div>
                </div>
            </div>

            {{-- ══════════════════════════════════════════════════════════════════
                 SECTION 2: PROGRESS TIMELINE
            ══════════════════════════════════════════════════════════════════ --}}
            <div style="background:#fff;border:1px solid #e2e8f0;border-radius:4px;padding:20px 22px;margin-bottom:16px;">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
                    <h3 style="font-family:'Georgia',serif;font-size:15px;font-weight:700;color:#1a2f4e;margin:0;border-bottom:2px solid #b8922a;padding-bottom:8px;">Submission Progress</h3>
                    <span style="font-size:9px;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:#9ca3af;">
                        Step {{ min($currentStageIdx + 1, count($stages)) }} of {{ count($stages) }}
                    </span>
                </div>
                <div style="position:relative;">
                    <div style="position:absolute;top:16px;left:16px;right:16px;height:2px;background:#e2e8f0;border-radius:999px;"></div>
                    <div style="position:absolute;top:16px;left:16px;height:2px;border-radius:999px;background:{{ $isCancelled ? '#fca5a5' : '#b8922a' }};width:calc({{ min($currentStageIdx, count($stages) - 1) }} / {{ count($stages) - 1 }} * (100% - 2rem));"></div>
                    <div style="position:relative;display:flex;justify-content:space-between;">
                        @foreach($stages as $si => $stage)
                            @php
                                $done   = !$isCancelled && $si < $currentStageIdx;
                                $active = !$isCancelled && $si === $currentStageIdx;
                                $fail   = $isCancelled && $si === 0;
                            @endphp
                            <div style="display:flex;flex-direction:column;align-items:center;flex:1;">
                                <div style="width:32px;height:32px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;position:relative;z-index:1;
                                    {{ $done   ? 'background:#b8922a;color:#fff;' :
                                       ($active ? 'background:#1a2f4e;color:#fff;box-shadow:0 0 0 4px #e2e8f0;' :
                                       ($fail   ? 'background:#fca5a5;color:#991b1b;' : 'background:#f1f5f9;color:#9ca3af;')) }}">
                                    @if($done)
                                        <svg style="width:14px;height:14px;" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    @elseif($fail)
                                        <svg style="width:14px;height:14px;" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                                    @else
                                        {{ $si + 1 }}
                                    @endif
                                </div>
                                <p style="font-size:10px;margin:6px 0 0;text-align:center;
                                    {{ $done ? 'color:#b8922a;font-weight:600;' : ($active ? 'color:#1a2f4e;font-weight:700;' : ($fail ? 'color:#dc2626;font-weight:600;' : 'color:#9ca3af;')) }}">
                                    {{ $stage['label'] }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                </div>
                @if($isCancelled)
                    <p style="margin-top:16px;font-size:11px;text-align:center;color:#dc2626;background:#fef2f2;border-radius:3px;padding:8px;">
                        This submission was cancelled.
                    </p>
                @elseif($submission->status === 'submitted')
                    <p style="margin-top:16px;font-size:11px;text-align:center;color:#9ca3af;background:#f8fafc;border-radius:3px;padding:8px;">
                        Your submission has been received. The lab will confirm receipt shortly.
                    </p>
                @endif
            </div>

            {{-- ══════════════════════════════════════════════════════════════════
                 TWO-COLUMN LAYOUT: Client + Collection side by side
            ══════════════════════════════════════════════════════════════════ --}}
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;">

                {{-- ── Client & Company ─────────────────────────────────────── --}}
                <div style="background:#fff;border:1px solid #e2e8f0;border-radius:4px;overflow:hidden;">
                    <div style="padding:14px 18px;border-bottom:2px solid #b8922a;">
                        <h3 style="font-family:'Georgia',serif;font-size:15px;font-weight:700;color:#1a2f4e;margin:0;padding-bottom:0;">Client Details</h3>
                    </div>
                    <div style="padding:16px 18px;">
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:14px;">
                            <div>
                                <p style="font-size:9px;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:#9ca3af;margin:0 0 4px;">Company</p>
                                <p style="font-size:12.5px;font-weight:600;color:#1a2f4e;margin:0;">{{ $client->company_name }}</p>
                            </div>
                            <div>
                                <p style="font-size:9px;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:#9ca3af;margin:0 0 4px;">Address</p>
                                <p style="font-size:12.5px;color:#374151;margin:0;">{{ $client->address ?? '—' }}</p>
                            </div>
                        </div>
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:14px;padding-top:12px;border-top:1px solid #f1f5f9;">
                            <div>
                                <p style="font-size:9px;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:#9ca3af;margin:0 0 4px;">Responsible Officer</p>
                                <p style="font-size:12.5px;color:#374151;margin:0;">{{ $client->responsible_officer_name ?? '—' }}</p>
                            </div>
                            <div>
                                <p style="font-size:9px;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:#9ca3af;margin:0 0 4px;">Phone</p>
                                <p style="font-size:12.5px;color:#374151;margin:0;">{{ $client->company_phone ?? $client->responsible_officer_phone ?? '—' }}</p>
                            </div>
                        </div>
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;padding-top:12px;border-top:1px solid #f1f5f9;">
                            <div>
                                <p style="font-size:9px;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:#9ca3af;margin:0 0 4px;">Email</p>
                                <p style="font-size:12.5px;color:#374151;margin:0;">{{ $client->responsible_officer_email ?? '—' }}</p>
                            </div>
                            <div>
                                <p style="font-size:9px;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:#9ca3af;margin:0 0 4px;">Date of Application</p>
                                <p style="font-size:12.5px;color:#374151;margin:0;">
                                    {{ $submission->application_date?->format('d M Y') ?? $submission->submitted_at?->format('d M Y') ?? '—' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ── Collection Info ──────────────────────────────────────── --}}
                <div style="background:#fff;border:1px solid #e2e8f0;border-radius:4px;overflow:hidden;">
                    <div style="padding:14px 18px;border-bottom:2px solid #b8922a;">
                        <h3 style="font-family:'Georgia',serif;font-size:15px;font-weight:700;color:#1a2f4e;margin:0;padding-bottom:0;">Collection Info</h3>
                    </div>
                    <div style="padding:16px 18px;">
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:14px;">
                            <div>
                                <p style="font-size:9px;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:#9ca3af;margin:0 0 4px;">Date Collected</p>
                                <p style="font-size:12.5px;font-weight:600;color:#1a2f4e;margin:0;">{{ $submission->collected_at?->format('d M Y') ?? '—' }}</p>
                            </div>
                            <div>
                                <p style="font-size:9px;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:#9ca3af;margin:0 0 4px;">Location</p>
                                <p style="font-size:12.5px;color:#374151;margin:0;">{{ $submission->collection_location ?? '—' }}</p>
                            </div>
                        </div>
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:14px;padding-top:12px;border-top:1px solid #f1f5f9;">
                            <div>
                                <p style="font-size:9px;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:#9ca3af;margin:0 0 4px;">Priority</p>
                                <span style="display:inline-flex;margin-top:2px;padding:2px 10px;border-radius:999px;font-size:10px;font-weight:700;{{ $pc[0] }}">{{ $pc[1] }}</span>
                            </div>
                            <div>
                                <p style="font-size:9px;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:#9ca3af;margin:0 0 4px;">Results Required By</p>
                                <p style="font-size:12.5px;color:#374151;margin:0;">{{ $submission->results_required_by?->format('d M Y') ?? 'No deadline' }}</p>
                            </div>
                        </div>
                        @if($submission->sample_description)
                            <div style="padding-top:12px;border-top:1px solid #f1f5f9;">
                                <p style="font-size:9px;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:#9ca3af;margin:0 0 4px;">Notes</p>
                                <p style="font-size:12.5px;color:#374151;margin:0;line-height:1.6;">{{ $submission->sample_description }}</p>
                            </div>
                        @endif
                        @if(!$submission->sample_description && !$submission->collected_at && !$submission->collection_location)
                            <p style="font-size:12.5px;color:#9ca3af;font-style:italic;margin:0;">No collection details recorded.</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- ══════════════════════════════════════════════════════════════════
                 SECTION 3: TRANSPORT & TESTS
            ══════════════════════════════════════════════════════════════════ --}}
            <div style="background:#fff;border:1px solid #e2e8f0;border-radius:4px;overflow:hidden;margin-bottom:16px;">
                <div style="padding:14px 18px;border-bottom:2px solid #b8922a;">
                    <h3 style="font-family:'Georgia',serif;font-size:15px;font-weight:700;color:#1a2f4e;margin:0;padding-bottom:0;">Transport &amp; Tests</h3>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;">
                    {{-- Transport --}}
                    <div style="padding:18px 20px;border-right:1px solid #f1f5f9;">
                        <p style="font-size:9px;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:#9ca3af;margin:0 0 12px;">Sample Transport</p>
                        <span style="display:inline-flex;align-items:center;gap:8px;padding:7px 14px;border-radius:3px;font-size:12.5px;font-weight:600;border:1px solid;margin-bottom:12px;{{ $tc[2] }}">
                            {{ $tc[0] }} {{ $tc[1] }}
                        </span>
                        @if($submission->transport_detail)
                            <div style="margin-bottom:10px;">
                                <p style="font-size:9px;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:#9ca3af;margin:0 0 4px;">Method</p>
                                <p style="font-size:12.5px;color:#374151;margin:0;">{{ $transportDetailMap[$submission->transport_detail] ?? str_replace('_', ' ', $submission->transport_detail) }}</p>
                            </div>
                        @endif
                        @if($submission->special_instructions)
                            <div style="background:#fffbeb;border:1px solid #fde68a;border-radius:3px;padding:10px 14px;margin-bottom:10px;">
                                <p style="font-size:9px;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:#92400e;margin:0 0 4px;">Special Instructions</p>
                                <p style="font-size:12.5px;color:#78350f;margin:0;">{{ $submission->special_instructions }}</p>
                            </div>
                        @endif
                        @if($submission->client_notes)
                            <div style="background:#f8fafc;border-radius:3px;padding:10px 14px;">
                                <p style="font-size:9px;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:#9ca3af;margin:0 0 4px;">Additional Notes</p>
                                <p style="font-size:12.5px;color:#374151;margin:0;">{{ $submission->client_notes }}</p>
                            </div>
                        @endif
                    </div>
                    {{-- Tests requested --}}
                    <div style="padding:18px 20px;">
                        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;">
                            <p style="font-size:9px;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:#9ca3af;margin:0;">Tests Requested</p>
                            @if($totalTests)
                                <span style="font-size:10px;font-weight:700;background:#eef2ff;color:#3730a3;border-radius:999px;padding:2px 10px;">{{ $totalTests }} total</span>
                            @endif
                        </div>
                        @if(count($microTests))
                            <p style="font-size:10px;font-weight:700;color:#7c3aed;margin:0 0 6px;text-transform:uppercase;letter-spacing:.06em;">Microbiological</p>
                            <div style="display:flex;flex-wrap:wrap;gap:5px;margin-bottom:12px;">
                                @foreach($microTests as $t)
                                    <span style="display:inline-flex;padding:2px 10px;font-size:10px;background:#faf5ff;color:#6b21a8;border-radius:999px;font-weight:600;">{{ $testLabels[$t] ?? $t }}</span>
                                @endforeach
                            </div>
                        @endif
                        @if(count($chemTests))
                            <p style="font-size:10px;font-weight:700;color:#1d4ed8;margin:0 0 6px;text-transform:uppercase;letter-spacing:.06em;">Chemical</p>
                            <div style="display:flex;flex-wrap:wrap;gap:5px;margin-bottom:12px;">
                                @foreach($chemTests as $t)
                                    <span style="display:inline-flex;padding:2px 10px;font-size:10px;background:#eff6ff;color:#1d4ed8;border-radius:999px;font-weight:600;">{{ $testLabels[$t] ?? $t }}</span>
                                @endforeach
                            </div>
                        @endif
                        @if($submission->tests_other)
                            <p style="font-size:10px;font-weight:700;color:#6b7280;margin:0 0 4px;text-transform:uppercase;letter-spacing:.06em;">Other</p>
                            <p style="font-size:12.5px;color:#374151;margin:0;">{{ $submission->tests_other }}</p>
                        @endif
                        @if(!$totalTests && !$submission->tests_other)
                            <p style="font-size:12.5px;color:#9ca3af;font-style:italic;margin:0;">See individual samples below for per-sample tests.</p>
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
                <div style="background:#fff;border:1px solid #e2e8f0;border-radius:4px;overflow:hidden;margin-bottom:16px;">
                    <div style="padding:14px 18px;border-bottom:2px solid #b8922a;display:flex;align-items:center;justify-content:space-between;">
                        <h3 style="font-family:'Georgia',serif;font-size:15px;font-weight:700;color:#1a2f4e;margin:0;padding-bottom:0;">Test Samples</h3>
                        <span style="display:inline-flex;align-items:center;border-radius:999px;padding:2px 12px;font-size:10px;font-weight:700;background:#eef2ff;color:#3730a3;">
                            {{ count($submission->sample_items) }} {{ count($submission->sample_items) === 1 ? 'sample' : 'samples' }}
                        </span>
                    </div>
                    @foreach($submission->sample_items as $i => $item)
                        @php
                            $itemTests = $item['tests'] ?? [];
                            $itemMicro = array_filter($itemTests, fn($t) => in_array($t, $showMicroKeys));
                            $itemChem  = array_filter($itemTests, fn($t) => in_array($t, $showChemKeys));
                        @endphp
                        <div style="padding:16px 18px;border-bottom:1px solid #f1f5f9;background:{{ $i % 2 === 0 ? '#fff' : '#f8fafc' }};">
                            <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:12px;">
                                <div style="display:flex;align-items:flex-start;gap:10px;">
                                    <span style="display:inline-flex;align-items:center;justify-content:center;width:26px;height:26px;border-radius:50%;background:#1a2f4e;font-size:11px;font-weight:700;color:#fff;flex-shrink:0;margin-top:1px;">
                                        {{ $i + 1 }}
                                    </span>
                                    <div>
                                        <p style="font-size:13px;font-weight:700;color:#1a2f4e;margin:0;">{{ $item['name'] ?? '—' }}</p>
                                        @if(!empty($item['scientific_name']))
                                            <p style="font-size:11px;color:#9ca3af;font-style:italic;margin:2px 0 0;">{{ $item['scientific_name'] }}</p>
                                        @endif
                                    </div>
                                </div>
                                <div style="text-align:right;flex-shrink:0;display:flex;flex-direction:column;align-items:flex-end;gap:4px;">
                                    @if(!empty($item['ref']))
                                        <span style="font-family:monospace;background:#f1f5f9;color:#374151;padding:2px 8px;border-radius:3px;font-size:11px;">{{ $item['ref'] }}</span>
                                    @endif
                                    @if(!empty($item['type']))
                                        <span style="background:#f8fafc;color:#6b7280;padding:2px 8px;border-radius:3px;font-size:11px;text-transform:capitalize;">{{ $item['type'] }}</span>
                                    @endif
                                    @if(isset($item['qty']) && $item['qty'] !== '')
                                        <p style="font-size:11px;color:#6b7280;margin:0;">{{ number_format((float)$item['qty'], 2) }} {{ $item['unit'] ?? '' }}</p>
                                    @endif
                                </div>
                            </div>
                            <div style="margin-top:10px;padding-left:36px;">
                                @if(count($itemTests) > 0 || !empty($item['tests_other']))
                                    <div style="display:flex;flex-direction:column;gap:6px;">
                                        @if(count($itemMicro) > 0)
                                            <div style="display:flex;flex-wrap:wrap;align-items:center;gap:5px;">
                                                <span style="font-size:9px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:#7c3aed;margin-right:2px;">Micro</span>
                                                @foreach($itemMicro as $t)
                                                    <span style="display:inline-flex;padding:2px 8px;font-size:10px;background:#faf5ff;color:#6b21a8;border-radius:999px;">{{ $testLabels[$t] ?? $t }}</span>
                                                @endforeach
                                            </div>
                                        @endif
                                        @if(count($itemChem) > 0)
                                            <div style="display:flex;flex-wrap:wrap;align-items:center;gap:5px;">
                                                <span style="font-size:9px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:#1d4ed8;margin-right:2px;">Chem</span>
                                                @foreach($itemChem as $t)
                                                    <span style="display:inline-flex;padding:2px 8px;font-size:10px;background:#eff6ff;color:#1d4ed8;border-radius:999px;">{{ $testLabels[$t] ?? $t }}</span>
                                                @endforeach
                                            </div>
                                        @endif
                                        @if(!empty($item['tests_other']))
                                            <p style="font-size:11px;color:#6b7280;margin:0;"><span style="color:#9ca3af;">Other:</span> {{ $item['tests_other'] }}</p>
                                        @endif
                                    </div>
                                @else
                                    <p style="font-size:11px;color:#9ca3af;font-style:italic;margin:0;">No tests selected for this sample.</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- ══════════════════════════════════════════════════════════════════
                 SECTION 5: LAB ASSESSMENT
            ══════════════════════════════════════════════════════════════════ --}}
            <div style="background:#fff;border:1px solid #e2e8f0;border-radius:4px;overflow:hidden;margin-bottom:16px;">
                <div style="padding:14px 18px;border-bottom:2px solid #b8922a;">
                    <h3 style="font-family:'Georgia',serif;font-size:15px;font-weight:700;color:#1a2f4e;margin:0;padding-bottom:0;">Lab Assessment</h3>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr 1fr;">
                    <div style="padding:16px 20px;border-right:1px solid #f1f5f9;">
                        <p style="font-size:9px;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:#9ca3af;margin:0 0 8px;">Current Status</p>
                        <span style="display:inline-flex;align-items:center;border-radius:999px;padding:3px 14px;font-size:10px;font-weight:700;{{ $sc }}">
                            {{ $slabel }}
                        </span>
                    </div>
                    <div style="padding:16px 20px;border-right:1px solid #f1f5f9;">
                        <p style="font-size:9px;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:#9ca3af;margin:0 0 6px;">Submitted</p>
                        <p style="font-size:13px;font-weight:600;color:#1a2f4e;margin:0;">{{ $submission->submitted_at?->format('d M Y') ?? $submission->created_at->format('d M Y') }}</p>
                        <p style="font-size:11px;color:#9ca3af;margin:2px 0 0;">{{ $submission->submitted_at?->format('H:i') ?? $submission->created_at->format('H:i') }}</p>
                    </div>
                    <div style="padding:16px 20px;">
                        <p style="font-size:9px;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:#9ca3af;margin:0 0 6px;">Received By Lab</p>
                        @if($submission->received_at)
                            <p style="font-size:13px;font-weight:600;color:#1a2f4e;margin:0;">{{ $submission->received_at->format('d M Y') }}</p>
                            <p style="font-size:11px;color:#9ca3af;margin:2px 0 0;">{{ $submission->received_at->format('H:i') }}</p>
                        @else
                            <div style="display:flex;align-items:center;gap:6px;">
                                <span style="width:8px;height:8px;border-radius:50%;background:#d97706;display:inline-block;"></span>
                                <span style="font-size:12.5px;color:#9ca3af;font-style:italic;">Awaiting receipt</span>
                            </div>
                        @endif
                    </div>
                </div>
                <div style="border-top:1px solid #f1f5f9;padding:16px 20px;">
                    <p style="font-size:9px;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:#9ca3af;margin:0 0 6px;">Lab Notes</p>
                    @if($submission->lab_notes)
                        <p style="font-size:12.5px;color:#374151;margin:0;line-height:1.6;">{{ $submission->lab_notes }}</p>
                    @else
                        <p style="font-size:12.5px;color:#9ca3af;font-style:italic;margin:0;">No notes from the lab yet.</p>
                    @endif
                </div>
            </div>

            {{-- ══════════════════════════════════════════════════════════════════
                 SECTION 6: DECLARATION FOOTER
            ══════════════════════════════════════════════════════════════════ --}}
            <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:4px;padding:14px 18px;display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;margin-bottom:16px;">
                <div style="display:flex;align-items:center;gap:8px;">
                    <svg style="width:14px;height:14px;color:#9ca3af;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    <span style="font-size:12.5px;color:#6b7280;">
                        Declared by
                        <strong style="color:#1a2f4e;">{{ $submission->submitter_name ?? $user->name }}</strong>
                        @if($submission->submitter_designation)
                            <span style="color:#9ca3af;">({{ $submission->submitter_designation }})</span>
                        @endif
                        on behalf of <strong style="color:#1a2f4e;">{{ $client->company_name }}</strong>
                    </span>
                </div>
                <p style="font-size:11px;color:#9ca3af;flex-shrink:0;margin:0;">
                    {{ $submission->submitted_at?->format('d M Y H:i') ?? $submission->created_at->format('d M Y H:i') }}
                </p>
            </div>

            {{-- ── Actions ─────────────────────────────────────────────────────── --}}
            <div style="display:flex;align-items:center;justify-content:space-between;padding-bottom:32px;">
                <a href="{{ route('client.submissions.index') }}"
                   style="border:1px solid #e2e8f0;color:#374151;padding:8px 16px;border-radius:3px;font-size:12px;background:#fff;text-decoration:none;font-weight:600;">
                    ← Back to Submissions
                </a>
                @if($submission->isEditable())
                    <a href="{{ route('client.submissions.edit', $submission->id) }}"
                       style="background:#1a2f4e;color:#fff;padding:8px 18px;border-radius:3px;font-size:12px;font-weight:600;text-decoration:none;display:inline-flex;align-items:center;gap:6px;">
                        <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit Submission
                    </a>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
