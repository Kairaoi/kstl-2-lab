{{-- resources/views/kstl/reception/submissions/show.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <div style="position:relative;overflow:hidden;background:linear-gradient(135deg,#0f2240 0%,#1a2f4e 60%,#1e3a5f 100%);margin:-1px;">
            <div style="height:3px;background:linear-gradient(90deg,#1a2f4e,#b8922a 30%,#b8922a 70%,#1a2f4e);"></div>
            <div style="max-width:80rem;margin:0 auto;padding:28px 2rem 32px;">
                <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;">
                    <div style="display:flex;align-items:center;gap:20px;">
                        <img src="{{ asset('images/mfor-logo.png') }}" alt="MFOR" style="filter:brightness(0) invert(1);opacity:.92;width:56px;height:56px;flex-shrink:0;">
                        <div>
                            <p style="font-size:9px;font-weight:700;letter-spacing:.18em;text-transform:uppercase;color:#b8922a;margin:0 0 4px;">Submission Review</p>
                            <h1 style="font-family:'Georgia',serif;font-size:22px;font-weight:700;color:#fff;margin:0 0 6px;line-height:1.2;">{{ $submission->reference_number }}</h1>
                            <p style="font-size:12px;color:#94a3b8;margin:0;">Reception &middot; Sample Intake Record</p>
                        </div>
                    </div>
                    <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                        <a href="{{ route('reception.dashboard') }}"
                           style="display:inline-flex;align-items:center;gap:8px;padding:8px 20px;background:rgba(255,255,255,.12);color:#e2e8f0;font-size:12px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;border-radius:3px;text-decoration:none;border:1px solid rgba(255,255,255,.2);">
                            &larr; Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    @push('styles')
    <style>
        .page-hdr { padding: 0 !important; position: static !important; }
        .page-hdr-inner { max-width: 100% !important; padding: 0 !important; }
        .app-main { padding-left:0 !important; padding-right:0 !important; padding-top:0 !important; max-width:100% !important; }
    </style>
    @endpush

    <div style="background:#f1f5f9;min-height:100vh;padding:0 0 56px;">
        <div style="max-width:80rem;margin:0 auto;padding:0 2rem;">

            {{-- Flash --}}
            @if(session('success'))
                <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-left:4px solid #16a34a;border-radius:4px;padding:12px 16px;margin-bottom:20px;font-size:13px;color:#166534;">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div style="background:#fef2f2;border:1px solid #fecaca;border-left:4px solid #dc2626;border-radius:4px;padding:12px 16px;margin-bottom:20px;font-size:13px;color:#991b1b;">{{ session('error') }}</div>
            @endif

            {{-- ════════ INTAKE RECORD DOCUMENT ════════ --}}
            <div style="background:#fff;border:1px solid #e2e8f0;border-radius:4px;overflow:hidden;margin-bottom:24px;">

                {{-- Letterhead band --}}
                <div style="padding:24px 32px;border-bottom:3px double #1a2f4e;background:linear-gradient(180deg,#fbfaf8 0%,#ffffff 100%);">
                    <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:24px;">
                        <div style="display:flex;align-items:flex-start;gap:16px;">
                            <div style="width:46px;height:46px;border-radius:50%;border:2px solid #b8922a;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <svg style="width:23px;height:23px;stroke:#1a2f4e;fill:none;" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21"/>
                                </svg>
                            </div>
                            <div>
                                <p style="font-size:9px;font-weight:700;letter-spacing:.18em;text-transform:uppercase;color:#b8922a;margin:0 0 4px;">Government of Kiribati &middot; Ministry of Fisheries &amp; Ocean Resources</p>
                                <h2 style="font-family:'Georgia',serif;font-size:18px;font-weight:700;color:#1a2f4e;margin:0 0 3px;">Kiribati Seafood Toxicology Laboratory</h2>
                                <p style="font-size:11px;color:#64748b;margin:0;">Sample Intake Record</p>
                            </div>
                        </div>
                        <div style="text-align:right;flex-shrink:0;">
                            <p style="font-family:monospace;font-size:14px;font-weight:700;color:#1e293b;margin:0 0 3px;">{{ $submission->reference_number }}</p>
                            <p style="font-size:11px;color:#94a3b8;margin:0 0 8px;">
                                Submitted {{ $submission->submitted_at?->format('d M Y') ?? $submission->created_at->format('d M Y') }}
                            </p>
                            <x-kstl.status-badge :status="$submission->status" />
                        </div>
                    </div>
                </div>

                {{-- Particulars — Client + Submission side by side --}}
                <div style="padding:24px 32px;display:grid;grid-template-columns:1fr 1fr;gap:40px 40px;border-bottom:1px solid #e2e8f0;">

                    {{-- Client --}}
                    <div>
                        <h3 style="font-family:'Georgia',serif;font-size:13px;font-weight:700;color:#1a2f4e;margin:0 0 14px;display:flex;align-items:center;gap:8px;">
                            <span style="display:inline-block;width:3px;height:14px;background:#b8922a;border-radius:2px;"></span>
                            Client
                        </h3>
                        <dl style="display:grid;grid-template-columns:1fr 1fr;gap:8px 16px;">
                            <div>
                                <dt style="font-size:10px;font-weight:600;letter-spacing:.07em;text-transform:uppercase;color:#64748b;">Company</dt>
                                <dd style="font-size:13px;font-weight:600;color:#1e293b;margin:3px 0 0;">{{ $submission->client->company_name ?? '—' }}</dd>
                            </div>
                            <div>
                                <dt style="font-size:10px;font-weight:600;letter-spacing:.07em;text-transform:uppercase;color:#64748b;">Responsible Officer</dt>
                                <dd style="font-size:13px;color:#374151;margin:3px 0 0;">{{ $submission->client->responsible_officer_name ?? '—' }}</dd>
                            </div>
                            <div>
                                <dt style="font-size:10px;font-weight:600;letter-spacing:.07em;text-transform:uppercase;color:#64748b;">Contact</dt>
                                <dd style="font-size:13px;color:#374151;margin:3px 0 0;">{{ $submission->client->company_phone ?? '—' }}</dd>
                            </div>
                            <div>
                                <dt style="font-size:10px;font-weight:600;letter-spacing:.07em;text-transform:uppercase;color:#64748b;">Email</dt>
                                <dd style="font-size:13px;color:#374151;margin:3px 0 0;word-break:break-all;">{{ $submission->client->user->email ?? '—' }}</dd>
                            </div>
                        </dl>
                    </div>

                    {{-- Submission --}}
                    <div>
                        <h3 style="font-family:'Georgia',serif;font-size:13px;font-weight:700;color:#1a2f4e;margin:0 0 14px;display:flex;align-items:center;gap:8px;">
                            <span style="display:inline-block;width:3px;height:14px;background:#b8922a;border-radius:2px;"></span>
                            Submission Details
                        </h3>
                        <dl style="display:grid;grid-template-columns:1fr 1fr;gap:8px 16px;">
                            <div>
                                <dt style="font-size:10px;font-weight:600;letter-spacing:.07em;text-transform:uppercase;color:#64748b;">Priority</dt>
                                <dd style="margin:3px 0 0;"><x-kstl.priority-badge :priority="$submission->priority" /></dd>
                            </div>
                            <div>
                                <dt style="font-size:10px;font-weight:600;letter-spacing:.07em;text-transform:uppercase;color:#64748b;">Transport</dt>
                                <dd style="font-size:13px;color:#374151;margin:3px 0 0;text-transform:capitalize;">
                                    {{ $submission->transport_method }}
                                    @if($submission->transport_detail)
                                        <span style="color:#94a3b8;">&mdash; {{ str_replace('_', ' ', $submission->transport_detail) }}</span>
                                    @endif
                                </dd>
                            </div>
                            <div>
                                <dt style="font-size:10px;font-weight:600;letter-spacing:.07em;text-transform:uppercase;color:#64748b;">Collection Date</dt>
                                <dd style="font-size:13px;color:#374151;margin:3px 0 0;">{{ $submission->collected_at?->format('d M Y') ?? '—' }}</dd>
                            </div>
                            @if($submission->collection_location)
                            <div>
                                <dt style="font-size:10px;font-weight:600;letter-spacing:.07em;text-transform:uppercase;color:#64748b;">Collection Location</dt>
                                <dd style="font-size:13px;color:#374151;margin:3px 0 0;">{{ $submission->collection_location }}</dd>
                            </div>
                            @endif
                        </dl>

                        {{-- Per-sample summary table --}}
                        @if($submission->sample_items && count($submission->sample_items))
                            <div style="margin-top:16px;">
                                <dt style="font-size:10px;font-weight:600;letter-spacing:.07em;text-transform:uppercase;color:#64748b;display:block;margin-bottom:8px;">Samples ({{ count($submission->sample_items) }})</dt>
                                <div style="border:1px solid #e2e8f0;border-radius:4px;overflow:hidden;">
                                    @foreach($submission->sample_items as $si => $item)
                                        <div style="padding:8px 12px;display:flex;align-items:center;justify-content:space-between;gap:12px;font-size:12px;{{ !$loop->last ? 'border-bottom:1px solid #f1f5f9;' : '' }}background:{{ $loop->even ? '#f8fafc' : '#fff' }};">
                                            <div style="display:flex;align-items:center;gap:8px;min-width:0;">
                                                <span style="display:inline-flex;align-items:center;justify-content:center;width:20px;height:20px;border-radius:50%;background:#dbeafe;color:#1e40af;font-size:10px;font-weight:700;flex-shrink:0;">{{ $si + 1 }}</span>
                                                <div style="min-width:0;">
                                                    <p style="font-weight:600;color:#1e293b;margin:0;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $item['name'] ?? '—' }}</p>
                                                    @if(!empty($item['scientific_name']))
                                                        <p style="color:#94a3b8;font-style:italic;margin:0;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $item['scientific_name'] }}</p>
                                                    @endif
                                                    @if(!empty($item['client_sample_ref']))
                                                        <p style="color:#64748b;font-size:11px;margin:2px 0 0;">Client ref: <span style="font-family:monospace;font-weight:600;">{{ $item['client_sample_ref'] }}</span></p>
                                                    @endif
                                                </div>
                                            </div>
                                            <div style="text-align:right;flex-shrink:0;color:#64748b;">
                                                @if(!empty($item['ref']))
                                                    <span style="font-family:monospace;background:#f1f5f9;padding:2px 6px;border-radius:3px;">{{ $item['ref'] }}</span>
                                                @endif
                                                @if(isset($item['qty']) && $item['qty'] !== '')
                                                    <span style="margin-left:4px;">{{ $item['qty'] }} {{ $item['unit'] ?? '' }}</span>
                                                @endif
                                                @php $tCount = count($item['tests'] ?? []); @endphp
                                                @if($tCount)
                                                    <span style="margin-left:4px;color:#0d9488;">{{ $tCount }} test{{ $tCount > 1 ? 's' : '' }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @elseif($submission->sample_name)
                            <div style="margin-top:12px;">
                                <dt style="font-size:10px;font-weight:600;letter-spacing:.07em;text-transform:uppercase;color:#64748b;display:block;margin-bottom:4px;">Sample</dt>
                                <dd style="font-size:13px;font-weight:600;color:#1e293b;">{{ $submission->sample_name }}</dd>
                                @if($submission->scientific_name)
                                    <dd style="font-size:11px;color:#64748b;font-style:italic;">{{ $submission->scientific_name }}</dd>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Tests Requested — full width band --}}
                <div style="padding:24px 32px;{{ ($submission->special_instructions || $submission->client_notes) ? 'border-bottom:1px solid #e2e8f0;' : '' }}">
                    <h3 style="font-family:'Georgia',serif;font-size:13px;font-weight:700;color:#1a2f4e;margin:0 0 14px;display:flex;align-items:center;gap:8px;">
                        <span style="display:inline-block;width:3px;height:14px;background:#b8922a;border-radius:2px;"></span>
                        Tests Requested
                    </h3>
                    @php
                        $tests = is_array($submission->tests_requested)
                            ? $submission->tests_requested
                            : json_decode($submission->tests_requested ?? '[]', true) ?? [];

                        $allTestLabels = [
                            'total_coliforms'        => 'Total Coliforms',
                            'e_coli'                 => 'E. coli',
                            'enterococci'            => 'Enterococci',
                            'faecal_coliforms'       => 'Faecal Coliforms',
                            'yeast_mold'             => 'Yeast & Mould',
                            'apc'                    => 'APC (Aerobic Plate Count)',
                            'e_coli_coliform'        => 'E. coli & Coliform',
                            'staph_aureus'           => 'Staphylococcus aureus',
                            'salmonella_spp'         => 'Salmonella species',
                            'listeria_mono'          => 'Listeria monocytogenes',
                            'listeria_spp'           => 'Listeria species',
                            'e_coli_colilert'        => 'E. coli (Colilert)',
                            'enterococci_enterolert' => 'Enterococci (Enterolert)',
                            'moisture'               => 'Moisture Content',
                            'histamine'              => 'ELISA Histamine Rapid Kit',
                            'ph'                     => 'pH',
                            'conductivity'           => 'Conductivity',
                            'water_activity'         => 'Water Activity',
                        ];
                        $microKeys = ['total_coliforms','e_coli','enterococci','faecal_coliforms','yeast_mold','apc','e_coli_coliform','staph_aureus','salmonella_spp','listeria_mono','listeria_spp','e_coli_colilert','enterococci_enterolert'];
                        $chemKeys  = ['moisture','histamine','ph','conductivity','water_activity'];
                        $micro = array_filter($tests, fn($t) => in_array($t, $microKeys));
                        $chem  = array_filter($tests, fn($t) => in_array($t, $chemKeys));
                    @endphp

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;">
                        <div>
                            <p style="font-size:10px;font-weight:600;letter-spacing:.07em;text-transform:uppercase;color:#64748b;margin:0 0 8px;">Microbiology</p>
                            @if(count($micro))
                                <div style="display:flex;flex-wrap:wrap;gap:6px;">
                                    @foreach($micro as $t)
                                        <span style="display:inline-flex;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;background:#f5f3ff;color:#6d28d9;">{{ $allTestLabels[$t] ?? ucwords(str_replace('_', ' ', $t)) }}</span>
                                    @endforeach
                                </div>
                            @else
                                <p style="font-size:12px;color:#94a3b8;">None requested.</p>
                            @endif
                        </div>
                        <div>
                            <p style="font-size:10px;font-weight:600;letter-spacing:.07em;text-transform:uppercase;color:#64748b;margin:0 0 8px;">Chemical</p>
                            @if(count($chem))
                                <div style="display:flex;flex-wrap:wrap;gap:6px;">
                                    @foreach($chem as $t)
                                        <span style="display:inline-flex;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;background:#dbeafe;color:#1e40af;">{{ $allTestLabels[$t] ?? ucwords(str_replace('_', ' ', $t)) }}</span>
                                    @endforeach
                                </div>
                            @else
                                <p style="font-size:12px;color:#94a3b8;">None requested.</p>
                            @endif
                        </div>
                    </div>

                    @if($submission->tests_other)
                        <p style="font-size:12px;color:#64748b;margin-top:12px;background:#f8fafc;border-radius:3px;padding:8px 12px;">
                            <span style="font-weight:600;">Other:</span> {{ $submission->tests_other }}
                        </p>
                    @endif
                </div>

                {{-- Instructions — full width band (only if present) --}}
                @if($submission->special_instructions || $submission->client_notes)
                    <div style="padding:24px 32px;">
                        <h3 style="font-family:'Georgia',serif;font-size:13px;font-weight:700;color:#1a2f4e;margin:0 0 14px;display:flex;align-items:center;gap:8px;">
                            <span style="display:inline-block;width:3px;height:14px;background:#b8922a;border-radius:2px;"></span>
                            Instructions
                        </h3>
                        @if($submission->special_instructions)
                            <p style="font-size:13px;color:#374151;margin:0 0 6px;">{{ $submission->special_instructions }}</p>
                        @endif
                        @if($submission->client_notes)
                            <p style="font-size:12px;color:#64748b;margin:0;">{{ $submission->client_notes }}</p>
                        @endif
                    </div>
                @endif

            </div>

            {{-- ════════ ACTION AREA (full width, below the record) ════════ --}}

            @if($submission->status === 'submitted')
                {{-- ── Receive Form ──────────────────────────────────── --}}
                <form method="POST"
                      action="{{ route('reception.submissions.receive', $submission->id) }}"
                      x-data="sampleReceiveForm()"
                      @submit.prevent="submitForm($el)">
                    @csrf

                    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:4px;overflow:hidden;margin-bottom:20px;">
                        <div style="padding:16px 24px;border-bottom:1px solid #e2e8f0;">
                            <h3 style="font-family:'Georgia',serif;font-size:14px;font-weight:700;color:#1a2f4e;margin:0 0 4px;">Log Physical Sample Arrival</h3>
                            <p style="font-size:11px;color:#94a3b8;margin:0;">
                                Enter details for each physical sample received. Add a row per specimen.
                            </p>
                        </div>

                        {{-- Sample Rows --}}
                        <div>
                            <template x-for="(row, index) in rows" :key="index">
                                <div style="padding:20px 24px;border-bottom:1px solid #f1f5f9;">
                                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
                                        <p style="font-size:13px;font-weight:600;color:#374151;margin:0;">
                                            Sample <span x-text="index + 1"></span>
                                        </p>
                                        <button type="button"
                                                @click="removeRow(index)"
                                                x-show="rows.length > 1"
                                                style="font-size:11px;color:#dc2626;background:none;border:none;cursor:pointer;font-weight:600;">
                                            Remove
                                        </button>
                                    </div>

                                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">

                                        {{-- Common Name --}}
                                        <div>
                                            <label style="display:block;font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#475569;margin-bottom:6px;">Common Name *</label>
                                            <input type="text"
                                                   :name="`samples[${index}][common_name]`"
                                                   x-model="row.common_name"
                                                   placeholder="e.g. Yellowfin Tuna"
                                                   required
                                                   style="width:100%;padding:8px 12px;border:1px solid #cbd5e1;border-radius:3px;font-size:13px;color:#1e293b;background:#fff;box-sizing:border-box;"/>
                                        </div>

                                        {{-- Scientific Name --}}
                                        <div>
                                            <label style="display:block;font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#475569;margin-bottom:6px;">Scientific Name</label>
                                            <input type="text"
                                                   :name="`samples[${index}][scientific_name]`"
                                                   x-model="row.scientific_name"
                                                   placeholder="e.g. Thunnus albacares"
                                                   style="width:100%;padding:8px 12px;border:1px solid #cbd5e1;border-radius:3px;font-size:13px;color:#1e293b;background:#fff;box-sizing:border-box;"/>
                                        </div>

                                        {{-- Client Sample Reference (read-only) --}}
                                        <div x-show="row.client_sample_ref" style="grid-column:1/-1;">
                                            <label style="display:block;font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#475569;margin-bottom:6px;">Client's Sample Reference</label>
                                            <div style="padding:8px 12px;border:1px solid #e2e8f0;border-radius:3px;background:#f8fafc;font-size:13px;color:#475569;font-family:monospace;" x-text="row.client_sample_ref"></div>
                                        </div>

                                        {{-- Sampling Date --}}
                                        <div>
                                            <label style="display:block;font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#475569;margin-bottom:6px;">Sampling Date *</label>
                                            <input type="date"
                                                   :name="`samples[${index}][sampling_date]`"
                                                   x-model="row.sampling_date"
                                                   :max="today"
                                                   required
                                                   style="width:100%;padding:8px 12px;border:1px solid #cbd5e1;border-radius:3px;font-size:13px;color:#1e293b;background:#fff;box-sizing:border-box;"/>
                                        </div>

                                        {{-- Quantity --}}
                                        <div>
                                            <label style="display:block;font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#475569;margin-bottom:6px;">Quantity *</label>
                                            <div style="display:flex;">
                                                <input type="number"
                                                       :name="`samples[${index}][quantity]`"
                                                       x-model="row.quantity"
                                                       placeholder="0"
                                                       min="0"
                                                       step="0.01"
                                                       required
                                                       style="flex:1;min-width:0;padding:8px 12px;border:1px solid #cbd5e1;border-right:none;border-radius:3px 0 0 3px;font-size:13px;color:#1e293b;background:#fff;"/>
                                                <select :name="`samples[${index}][quantity_unit]`"
                                                        x-model="row.quantity_unit"
                                                        style="border:1px solid #cbd5e1;border-radius:0 3px 3px 0;font-size:13px;padding:8px 10px;color:#1e293b;background:#fff;">
                                                    <option value="g">g</option>
                                                    <option value="kg">kg</option>
                                                    <option value="ml">ml</option>
                                                    <option value="L">L</option>
                                                    <option value="pcs">pcs</option>
                                                </select>
                                            </div>
                                        </div>

                                        {{-- Notes --}}
                                        <div style="grid-column:span 2;">
                                            <label style="display:block;font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#475569;margin-bottom:6px;">Notes</label>
                                            <input type="text"
                                                   :name="`samples[${index}][notes]`"
                                                   x-model="row.notes"
                                                   placeholder="Condition, packaging observations, client instructions..."
                                                   style="width:100%;padding:8px 12px;border:1px solid #cbd5e1;border-radius:3px;font-size:13px;color:#1e293b;background:#fff;box-sizing:border-box;"/>
                                        </div>

                                    </div>
                                </div>
                            </template>
                        </div>

                        {{-- Validation errors --}}
                        <x-validation-errors class="mx-6 mb-4 bg-red-50 border border-red-200 rounded-xl p-4"/>
                    </div>

                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;">
                        <a href="{{ route('reception.dashboard') }}"
                           style="display:inline-flex;align-items:center;gap:8px;padding:8px 20px;background:#fff;color:#1a2f4e;font-size:12px;font-weight:700;letter-spacing:.06em;border:1px solid #1a2f4e;border-radius:3px;text-decoration:none;">
                            Cancel
                        </a>
                        <button type="submit"
                                style="display:inline-flex;align-items:center;gap:8px;padding:8px 20px;background:#1a2f4e;color:#fff;font-size:12px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;border-radius:3px;border:none;cursor:pointer;">
                            <svg style="width:16px;height:16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Confirm Receipt &amp; Continue to Assessment
                        </button>
                    </div>

                </form>

            @elseif($submission->status === 'received')
                {{-- Already received — prompt to assess --}}
                <div style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:4px;padding:24px;text-align:center;margin-bottom:20px;">
                    <svg style="width:40px;height:40px;color:#60a5fa;margin:0 auto 12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <p style="font-size:13px;font-weight:700;color:#1e40af;margin:0 0 4px;">Samples received. Ready for assessment.</p>
                    <p style="font-size:12px;color:#3b82f6;margin:0 0 16px;">
                        {{ $samples->count() }} sample{{ $samples->count() !== 1 ? 's' : '' }} logged.
                    </p>
                    <a href="{{ route('reception.submissions.assess', $submission->id) }}"
                       style="display:inline-flex;align-items:center;gap:8px;padding:8px 20px;background:#1a2f4e;color:#fff;font-size:12px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;border-radius:3px;text-decoration:none;">
                        Start Assessment &rarr;
                    </a>
                </div>

                @if($samples->isNotEmpty())
                    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:4px;overflow:hidden;margin-bottom:20px;">
                        <div style="padding:14px 20px;border-bottom:1px solid #e2e8f0;">
                            <h3 style="font-family:'Georgia',serif;font-size:14px;font-weight:700;color:#1a2f4e;margin:0;">Logged Samples</h3>
                        </div>
                        <div style="overflow-x:auto;">
                            <table style="width:100%;border-collapse:collapse;">
                                <thead>
                                    <tr style="background:#1a2f4e;">
                                        <th style="padding:10px 16px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">Code</th>
                                        <th style="padding:10px 16px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">Name</th>
                                        <th style="padding:10px 16px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">Qty</th>
                                        <th style="padding:10px 16px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($samples as $sample)
                                        <tr style="border-bottom:1px solid #f1f5f9;background:{{ $loop->even ? '#f8fafc' : '#fff' }};">
                                            <td style="padding:11px 16px;font-family:monospace;font-size:12px;color:#374151;">{{ $sample->sample_code ?? '—' }}</td>
                                            <td style="padding:11px 16px;">
                                                <p style="font-size:13px;font-weight:600;color:#1e293b;margin:0 0 2px;">{{ $sample->common_name }}</p>
                                                @if($sample->scientific_name)
                                                    <p style="font-size:11px;color:#94a3b8;font-style:italic;margin:0;">{{ $sample->scientific_name }}</p>
                                                @endif
                                            </td>
                                            <td style="padding:11px 16px;font-size:13px;color:#374151;">{{ $sample->quantity }} {{ $sample->quantity_unit }}</td>
                                            <td style="padding:11px 16px;"><x-kstl.status-badge :status="$sample->status" /></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

            @elseif($submission->status === 'rejected')
                {{-- ── Consent pending — awaiting client decision ── --}}
                <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:4px;padding:24px;margin-bottom:20px;">
                    <div style="display:flex;align-items:flex-start;gap:12px;margin-bottom:20px;">
                        <svg style="width:22px;height:22px;color:#dc2626;flex-shrink:0;margin-top:2px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                        </svg>
                        <div>
                            <p style="font-size:13px;font-weight:700;color:#991b1b;margin:0 0 4px;">Sample(s) Rejected — Awaiting Client Decision</p>
                            <p style="font-size:13px;color:#dc2626;margin:0;">
                                Send the client a consent request so they can choose to proceed with testing or cancel the submission.
                            </p>
                        </div>
                    </div>

                    @foreach($samples->filter(fn($s) => $s->assessment?->outcome === 'rejected') as $sample)
                        @php $a = $sample->assessment; @endphp
                        <div style="background:#fff;border:1px solid #fecaca;border-radius:4px;padding:16px;margin-bottom:12px;">
                            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;">
                                <div>
                                    <p style="font-size:13px;font-weight:700;color:#1e293b;margin:0 0 2px;">{{ $sample->common_name }}</p>
                                    <p style="font-size:11px;font-family:monospace;color:#94a3b8;margin:0;">{{ $sample->sample_code }}</p>
                                </div>
                                @if($a->client_decision)
                                    <span style="display:inline-flex;align-items:center;padding:3px 10px;border-radius:20px;font-size:10px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;{{ $a->client_decision === 'consent_to_proceed' ? 'background:#dcfce7;color:#166534;' : 'background:#f1f5f9;color:#475569;' }}">
                                        {{ $a->client_decision === 'consent_to_proceed' ? 'Client: Proceed' : 'Client: Cancelled' }}
                                    </span>
                                @elseif($a->consent_token)
                                    <span style="display:inline-flex;align-items:center;padding:3px 10px;border-radius:20px;font-size:10px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;background:#fef3c7;color:#92400e;">
                                        Awaiting Response
                                    </span>
                                @else
                                    <span style="display:inline-flex;align-items:center;padding:3px 10px;border-radius:20px;font-size:10px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;background:#f1f5f9;color:#64748b;">
                                        Not Notified Yet
                                    </span>
                                @endif
                            </div>

                            @if($a->rejection_reason)
                                <p style="font-size:12px;color:#dc2626;margin:0 0 12px;font-style:italic;">Reason: {{ $a->rejection_reason }}</p>
                            @endif

                            @if(! $a->client_decision)
                                @if($a->consent_token)
                                    {{-- Consent link already generated — show copyable URL + resend --}}
                                    <div style="margin-bottom:12px;">
                                        <label style="display:block;font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#475569;margin-bottom:6px;">Consent Link (share with client)</label>
                                        <div style="display:flex;gap:8px;">
                                            <input type="text"
                                                   id="consent-link-{{ $a->id }}"
                                                   readonly
                                                   value="{{ route('client.consent.show', $a->consent_token) }}"
                                                   style="flex:1;font-size:11px;font-family:monospace;border:1px solid #e2e8f0;border-radius:3px;padding:8px 12px;background:#f8fafc;color:#374151;outline:none;"/>
                                            <button type="button"
                                                    onclick="navigator.clipboard.writeText('{{ route('client.consent.show', $a->consent_token) }}'); this.textContent='Copied!'; setTimeout(()=>this.textContent='Copy',2000)"
                                                    style="flex-shrink:0;padding:8px 16px;font-size:11px;font-weight:700;background:#f1f5f9;border:1px solid #e2e8f0;border-radius:3px;cursor:pointer;">
                                                Copy
                                            </button>
                                        </div>
                                        @if($a->consent_token_expires_at)
                                            <p style="font-size:11px;color:#94a3b8;margin:4px 0 0;">Link expires {{ \Carbon\Carbon::parse($a->consent_token_expires_at)->format('d M Y') }}</p>
                                        @endif
                                    </div>
                                    <form method="POST" action="{{ route('reception.assessments.notify', $a->id) }}" style="display:inline;">
                                        @csrf
                                        <button type="submit"
                                                style="display:inline-flex;align-items:center;gap:6px;padding:6px 16px;background:#b8922a;color:#fff;font-size:11px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;border-radius:3px;border:none;cursor:pointer;">
                                            <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                            </svg>
                                            Resend Email
                                        </button>
                                    </form>
                                @else
                                    {{-- No token yet — send the first notification --}}
                                    <form method="POST" action="{{ route('reception.assessments.notify', $a->id) }}">
                                        @csrf
                                        <button type="submit"
                                                style="display:inline-flex;align-items:center;gap:8px;padding:8px 20px;background:#dc2626;color:#fff;font-size:12px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;border-radius:3px;border:none;cursor:pointer;">
                                            <svg style="width:16px;height:16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                            </svg>
                                            Send Consent Request to Client
                                        </button>
                                    </form>
                                @endif
                            @endif
                        </div>
                    @endforeach
                </div>

            @elseif($submission->status === 'accepted')
                {{-- All samples passed — send to testing --}}
                <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:4px;padding:24px;margin-bottom:20px;">
                    <div style="display:flex;align-items:flex-start;gap:12px;margin-bottom:20px;">
                        <svg style="width:22px;height:22px;color:#16a34a;flex-shrink:0;margin-top:2px;" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <p style="font-size:13px;font-weight:700;color:#166534;margin:0 0 4px;">All Samples Accepted</p>
                            <p style="font-size:13px;color:#16a34a;margin:0;">
                                All {{ $samples->count() }} sample{{ $samples->count() !== 1 ? 's' : '' }}
                                passed the assessment. Ready to send to the testing queue.
                            </p>
                        </div>
                    </div>

                    <form method="POST"
                          action="{{ route('reception.submissions.send-to-testing', $submission->id) }}"
                          onsubmit="return confirm('Send all accepted samples to the testing queue?')">
                        @csrf
                        <button type="submit"
                                style="display:inline-flex;align-items:center;gap:8px;padding:8px 20px;background:#0d9488;color:#fff;font-size:12px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;border-radius:3px;border:none;cursor:pointer;">
                            <svg style="width:16px;height:16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                            </svg>
                            Send to Testing Queue
                        </button>
                    </form>
                </div>

                @if($samples->isNotEmpty())
                    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:4px;overflow:hidden;margin-bottom:20px;">
                        <div style="padding:14px 20px;border-bottom:1px solid #e2e8f0;">
                            <h3 style="font-family:'Georgia',serif;font-size:14px;font-weight:700;color:#1a2f4e;margin:0;">Accepted Samples ({{ $samples->count() }})</h3>
                        </div>
                        <div style="overflow-x:auto;">
                            <table style="width:100%;border-collapse:collapse;">
                                <thead>
                                    <tr style="background:#1a2f4e;">
                                        <th style="padding:10px 16px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">Code</th>
                                        <th style="padding:10px 16px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">Name</th>
                                        <th style="padding:10px 16px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">Qty</th>
                                        <th style="padding:10px 16px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($samples as $sample)
                                        <tr style="border-bottom:1px solid #f1f5f9;background:{{ $loop->even ? '#f8fafc' : '#fff' }};">
                                            <td style="padding:11px 16px;font-family:monospace;font-size:12px;color:#374151;">{{ $sample->sample_code ?? '—' }}</td>
                                            <td style="padding:11px 16px;">
                                                <p style="font-size:13px;font-weight:600;color:#1e293b;margin:0 0 2px;">{{ $sample->common_name }}</p>
                                                @if($sample->scientific_name)
                                                    <p style="font-size:11px;color:#94a3b8;font-style:italic;margin:0;">{{ $sample->scientific_name }}</p>
                                                @endif
                                            </td>
                                            <td style="padding:11px 16px;font-size:13px;color:#374151;">{{ $sample->quantity }} {{ $sample->quantity_unit }}</td>
                                            <td style="padding:11px 16px;"><x-kstl.status-badge :status="$sample->status" /></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

            @elseif($submission->status === 'consent_to_proceed')
                {{-- Client consented — send to testing --}}
                <div style="background:#fff7ed;border:1px solid #fed7aa;border-radius:4px;padding:24px;margin-bottom:20px;">
                    <div style="display:flex;align-items:flex-start;gap:12px;margin-bottom:20px;">
                        <svg style="width:22px;height:22px;color:#ea580c;flex-shrink:0;margin-top:2px;" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <p style="font-size:13px;font-weight:700;color:#9a3412;margin:0 0 4px;">Client Consented to Proceed</p>
                            <p style="font-size:13px;color:#ea580c;margin:0;">
                                The client has acknowledged the assessment findings and consented to proceed with testing.
                                Click below to send the sample(s) to the testing queue.
                            </p>
                        </div>
                    </div>

                    <form method="POST"
                          action="{{ route('reception.submissions.send-to-testing', $submission->id) }}"
                          onsubmit="return confirm('Send consented samples to the testing queue?')">
                        @csrf
                        <button type="submit"
                                style="display:inline-flex;align-items:center;gap:8px;padding:8px 20px;background:#0d9488;color:#fff;font-size:12px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;border-radius:3px;border:none;cursor:pointer;">
                            <svg style="width:16px;height:16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                            </svg>
                            Send to Testing Queue
                        </button>
                    </form>
                </div>

                @if($samples->isNotEmpty())
                    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:4px;overflow:hidden;margin-bottom:20px;">
                        <div style="padding:14px 20px;border-bottom:1px solid #e2e8f0;">
                            <h3 style="font-family:'Georgia',serif;font-size:14px;font-weight:700;color:#1a2f4e;margin:0;">Samples</h3>
                        </div>
                        <div style="overflow-x:auto;">
                            <table style="width:100%;border-collapse:collapse;">
                                <thead>
                                    <tr style="background:#1a2f4e;">
                                        <th style="padding:10px 16px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">Code</th>
                                        <th style="padding:10px 16px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">Name</th>
                                        <th style="padding:10px 16px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($samples as $sample)
                                        <tr style="border-bottom:1px solid #f1f5f9;background:{{ $loop->even ? '#f8fafc' : '#fff' }};">
                                            <td style="padding:11px 16px;font-family:monospace;font-size:12px;color:#374151;">{{ $sample->sample_code }}</td>
                                            <td style="padding:11px 16px;font-size:13px;font-weight:600;color:#1e293b;">{{ $sample->common_name }}</td>
                                            <td style="padding:11px 16px;"><x-kstl.status-badge :status="$sample->status" /></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

            @else
                {{-- Any other status — read only --}}
                <div style="background:#fff;border:1px solid #e2e8f0;border-radius:4px;padding:24px;margin-bottom:20px;">
                    <p style="font-size:13px;color:#64748b;margin:0 0 6px;">
                        This submission is currently
                        <span style="font-weight:700;color:#1e293b;text-transform:capitalize;">{{ str_replace('_', ' ', $submission->status) }}</span>
                        and is no longer pending reception action.
                    </p>
                    @if($submission->received_at)
                        <p style="font-size:12px;color:#94a3b8;margin:0;">
                            Received: {{ $submission->received_at->format('d M Y \a\t H:i') }}
                        </p>
                    @endif
                </div>

                @if($samples->isNotEmpty())
                    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:4px;overflow:hidden;margin-bottom:20px;">
                        <div style="padding:14px 20px;border-bottom:1px solid #e2e8f0;">
                            <h3 style="font-family:'Georgia',serif;font-size:14px;font-weight:700;color:#1a2f4e;margin:0;">Samples ({{ $samples->count() }})</h3>
                        </div>
                        <div style="overflow-x:auto;">
                            <table style="width:100%;border-collapse:collapse;">
                                <thead>
                                    <tr style="background:#1a2f4e;">
                                        <th style="padding:10px 16px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">Code</th>
                                        <th style="padding:10px 16px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">Name</th>
                                        <th style="padding:10px 16px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">Qty</th>
                                        <th style="padding:10px 16px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($samples as $sample)
                                        <tr style="border-bottom:1px solid #f1f5f9;background:{{ $loop->even ? '#f8fafc' : '#fff' }};">
                                            <td style="padding:11px 16px;font-family:monospace;font-size:12px;color:#374151;">{{ $sample->sample_code ?? '—' }}</td>
                                            <td style="padding:11px 16px;">
                                                <p style="font-size:13px;font-weight:600;color:#1e293b;margin:0 0 2px;">{{ $sample->common_name }}</p>
                                                @if($sample->scientific_name)
                                                    <p style="font-size:11px;color:#94a3b8;font-style:italic;margin:0;">{{ $sample->scientific_name }}</p>
                                                @endif
                                            </td>
                                            <td style="padding:11px 16px;font-size:13px;color:#374151;">{{ $sample->quantity }} {{ $sample->quantity_unit }}</td>
                                            <td style="padding:11px 16px;"><x-kstl.status-badge :status="$sample->status" /></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            {{-- ════════ ASSESSMENT RECORD (shown once assessments exist) ════════ --}}
            @php
                $assessedSamples = $samples->filter(fn($s) => $s->assessment !== null);
            @endphp
            @if($assessedSamples->isNotEmpty())
                <div style="background:#fff;border:1px solid #e2e8f0;border-radius:4px;overflow:hidden;margin-bottom:20px;">
                    <div style="padding:16px 24px;border-bottom:1px solid #e2e8f0;display:flex;align-items:center;justify-content:space-between;">
                        <div>
                            <h3 style="font-family:'Georgia',serif;font-size:14px;font-weight:700;color:#1a2f4e;margin:0 0 2px;">Assessment Record</h3>
                            <p style="font-size:11px;color:#94a3b8;margin:0;">Physical sample inspection results logged by reception.</p>
                        </div>
                        @php
                            $allPassed = $assessedSamples->every(fn($s) => $s->assessment->outcome === 'accepted');
                            $anyFailed = $assessedSamples->some(fn($s)  => $s->assessment->outcome === 'rejected');
                        @endphp
                        @if($allPassed)
                            <span style="display:inline-flex;align-items:center;gap:6px;padding:4px 12px;border-radius:20px;font-size:10px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;background:#dcfce7;color:#166534;">
                                All Accepted
                            </span>
                        @elseif($anyFailed)
                            <span style="display:inline-flex;align-items:center;gap:6px;padding:4px 12px;border-radius:20px;font-size:10px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;background:#fef2f2;color:#991b1b;">
                                Rejected
                            </span>
                        @endif
                    </div>

                    @foreach($assessedSamples as $sample)
                        @php $a = $sample->assessment; @endphp
                        <div style="padding:20px 24px;{{ !$loop->last ? 'border-bottom:1px solid #e2e8f0;' : '' }}">

                            {{-- Sample header --}}
                            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
                                <div>
                                    <p style="font-size:13px;font-weight:700;color:#1e293b;margin:0 0 2px;">{{ $sample->common_name }}</p>
                                    <p style="font-size:11px;color:#94a3b8;font-family:monospace;margin:0;">{{ $sample->sample_code }}</p>
                                </div>
                                <div style="display:flex;align-items:center;gap:12px;text-align:right;">
                                    @if($a->outcome === 'accepted')
                                        <span style="display:inline-flex;align-items:center;padding:3px 10px;border-radius:20px;font-size:10px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;background:#dcfce7;color:#166534;">Accepted</span>
                                    @else
                                        <span style="display:inline-flex;align-items:center;padding:3px 10px;border-radius:20px;font-size:10px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;background:#fef2f2;color:#991b1b;">Rejected</span>
                                    @endif
                                    @if($a->assessedBy)
                                        <div style="text-align:right;">
                                            <p style="font-size:12px;color:#64748b;margin:0 0 1px;">{{ $a->assessedBy->name }}</p>
                                            <p style="font-size:11px;color:#94a3b8;margin:0;">{{ $a->assessed_at?->format('d M Y H:i') ?? $a->created_at->format('d M Y H:i') }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- Criteria grid --}}
                            @php
                                $criteria = [
                                    'Temperature'          => [$a->temperature_ok, $a->temperature_notes],
                                    'Storage Condition'    => [$a->storage_ok,     $a->storage_notes],
                                    'Transport Condition'  => [$a->transport_ok,   $a->transport_notes],
                                    'Packaging Integrity'  => [$a->packaging_ok,   $a->packaging_notes],
                                    'Colour / Appearance'  => [$a->colour_ok,      $a->colour_notes],
                                    'Weight / Quantity'    => [$a->weight_ok,      $a->weight_notes],
                                ];
                            @endphp
                            <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:10px;">
                                @foreach($criteria as $label => [$pass, $notes])
                                    <div style="border-radius:3px;border:1px solid {{ $pass ? '#bbf7d0' : '#fecaca' }};background:{{ $pass ? '#f0fdf4' : '#fef2f2' }};padding:10px 12px;">
                                        <div style="display:flex;align-items:center;justify-content:space-between;gap:8px;">
                                            <span style="font-size:12px;font-weight:600;color:#374151;">{{ $label }}</span>
                                            @if($pass)
                                                <span style="font-size:11px;font-weight:700;color:#16a34a;white-space:nowrap;">&#10003; Pass</span>
                                            @else
                                                <span style="font-size:11px;font-weight:700;color:#dc2626;white-space:nowrap;">&#10007; Fail</span>
                                            @endif
                                        </div>
                                        @if($notes)
                                            <p style="font-size:11px;color:#64748b;margin:4px 0 0;line-height:1.4;">{{ $notes }}</p>
                                        @endif
                                    </div>
                                @endforeach
                            </div>

                            {{-- Additional observations --}}
                            @if($a->additional_observations)
                                <div style="margin-top:12px;background:#f8fafc;border-radius:3px;padding:10px 12px;font-size:12px;color:#374151;">
                                    <span style="font-weight:700;color:#1e293b;">Additional Observations:</span>
                                    {{ $a->additional_observations }}
                                </div>
                            @endif

                            {{-- Rejection reason --}}
                            @if($a->rejection_reason)
                                <div style="margin-top:10px;background:#fef2f2;border:1px solid #fecaca;border-radius:3px;padding:10px 12px;font-size:12px;color:#991b1b;">
                                    <span style="font-weight:700;">Rejection Reason:</span>
                                    {{ $a->rejection_reason }}
                                </div>
                            @endif

                        </div>
                    @endforeach
                </div>
            @endif

            @endif

        </div>
    </div>

    @push('scripts')
    @php
        // Pre-populate rows from sample_items. Always fall back to the legacy
        // top-level fields so seeded or older submissions still pre-fill.
        $collectedDate     = $submission->collected_at?->format('Y-m-d') ?? '';
        $legacyName        = $submission->sample_name         ?? '';
        $legacySci         = $submission->scientific_name     ?? '';
        $legacyQty         = $submission->sample_quantity     ?? '';
        $legacyUnit        = $submission->sample_quantity_unit ?? 'g';
        $clientNote        = trim(implode(' | ', array_filter([
            $submission->special_instructions,
            $submission->client_notes,
        ])));

        if ($submission->sample_items && count($submission->sample_items)) {
            $items   = array_values($submission->sample_items);
            $isMulti = count($items) > 1;
            $initialRows = array_map(function($item, $idx) use (
                $legacyName, $legacySci, $legacyQty, $legacyUnit, $collectedDate, $clientNote, $isMulti
            ) {
                // For single-sample submissions fall back to legacy top-level fields when item fields are blank
                $name = $item['name'] ?? '';
                $sci  = $item['scientific_name'] ?? '';
                $qty  = isset($item['qty']) && $item['qty'] !== '' ? $item['qty'] : '';
                $unit = $item['unit'] ?? 'kg';
                if (!$isMulti && $idx === 0) {
                    $name = $name ?: $legacyName;
                    $sci  = $sci  ?: $legacySci;
                    $qty  = $qty  !== '' ? $qty : $legacyQty;
                    $unit = $unit ?: $legacyUnit;
                }
                return [
                    'common_name'       => $name,
                    'scientific_name'   => $sci,
                    'client_sample_ref' => $item['client_sample_ref'] ?? '',
                    'sampling_date'     => $collectedDate,
                    'quantity'          => $qty,
                    'quantity_unit'     => $unit,
                    'notes'             => $idx === 0 ? $clientNote : '',
                ];
            }, $items, array_keys($items));
        } else {
            $initialRows = [[
                'common_name'     => $legacyName,
                'scientific_name' => $legacySci,
                'sampling_date'   => $collectedDate,
                'quantity'        => $legacyQty,
                'quantity_unit'   => $legacyUnit,
                'notes'           => $clientNote,
            ]];
        }
    @endphp
    <script>
        function sampleReceiveForm() {
            return {
                today: new Date().toISOString().split('T')[0],
                rows: @json($initialRows),

                addRow() {
                    this.rows.push({
                        common_name:     '',
                        scientific_name: '',
                        sampling_date:   '',
                        quantity:        '',
                        quantity_unit:   'g',
                        notes:           '',
                    });
                },

                removeRow(index) {
                    if (this.rows.length > 1) {
                        this.rows.splice(index, 1);
                    }
                },

                submitForm(form) {
                    form.submit();
                }
            }
        }
    </script>
    @endpush

</x-app-layout>
