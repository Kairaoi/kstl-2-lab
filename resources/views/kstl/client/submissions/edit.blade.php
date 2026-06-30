{{-- resources/views/kstl/client/submissions/edit.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <div style="position:relative;overflow:hidden;background:linear-gradient(135deg,#0f2240 0%,#1a2f4e 60%,#1e3a5f 100%);margin:-1px;">
            <div style="position:absolute;inset:0;opacity:.04;background-image:repeating-linear-gradient(45deg,#fff 0,#fff 1px,transparent 0,transparent 50%);background-size:12px 12px;pointer-events:none;"></div>
            <div style="position:absolute;top:0;left:0;right:0;height:3px;background:linear-gradient(90deg,#1a2f4e,#b8922a 30%,#b8922a 70%,#1a2f4e);"></div>
            <div style="max-width:80rem;margin:0 auto;padding:28px 2rem;">
                <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;position:relative;">
                    <div style="display:flex;align-items:center;gap:18px;">
                        <img src="{{ asset('images/mfor-logo.png') }}" alt="Ministry of Fisheries &amp; Ocean Resources" style="width:56px;height:56px;object-fit:contain;filter:brightness(0) invert(1);opacity:.92;">
                        <div>
                            <p style="font-size:8.5px;font-weight:700;letter-spacing:.22em;text-transform:uppercase;color:#b8922a;margin-bottom:5px;">Client &nbsp;·&nbsp; Seafood Toxicology Laboratory</p>
                            <h1 style="font-family:'Georgia',serif;font-size:22px;font-weight:700;color:#fff;margin:0;line-height:1.2;">Edit Submission</h1>
                            <p style="font-size:11px;color:#94a3b8;margin-top:4px;">{{ $submission->reference_number }} &nbsp;·&nbsp; Update your submission details</p>
                        </div>
                    </div>
                    <a href="{{ route('client.submissions.show', $submission->id) }}" style="display:inline-flex;align-items:center;gap:5px;font-size:11px;font-weight:600;color:#94a3b8;text-decoration:none;">
                        <svg style="width:12px;height:12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        Back to Submission
                    </a>
                </div>
            </div>
        </div>
    </x-slot>

    @push('styles')
    <style>
        .page-hdr { padding: 0 !important; position: static !important; }
        .page-hdr-inner { max-width: 100% !important; padding: 0 !important; }
        .app-main { padding-left:0 !important; padding-right:0 !important; padding-top:0 !important; max-width:100% !important; }
        :root {
            --gov-navy:   #1a2f4e;
            --gov-gold:   #b8922a;
            --gov-teal:   #0d9488;
            --gov-muted:  #6b7280;
            --gov-text:   #1f2937;
            --gov-border: #e2e8f0;
        }
        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: #0d9488 !important;
            box-shadow: 0 0 0 2px rgba(13,148,136,.15) !important;
        }
        .ef-card {
            background: #f0f4f8;
            border: 1px solid #cbd5e1;
            border-left: 4px solid var(--gov-navy);
            border-radius: 4px;
            padding: 16px;
        }
        .ef-label { display:block; font-size:13px; font-weight:600; color:var(--gov-text); margin-bottom:3px; }
        .ef-hint  { font-size:11px; color:var(--gov-muted); margin:0 0 10px; }
        .ef-input { width:100%; padding:8px 12px; border:1px solid #cbd5e1; border-radius:3px; font-size:13px; color:#1e293b; background:#fff; box-sizing:border-box; }
        .ef-err   { margin:4px 0 0; font-size:12px; color:#dc2626; }
        .section-card { background:#fff; border:1px solid var(--gov-border); border-radius:4px; overflow:hidden; margin-bottom:24px; }
        .section-hdr  { padding:14px 20px; border-bottom:2px solid var(--gov-gold); }
        .section-body { padding:20px 24px; display:flex; flex-direction:column; gap:16px; }
        .test-label {
            display:flex; align-items:center; gap:8px;
            padding:7px 10px; border:1px solid var(--gov-border);
            border-radius:3px; cursor:pointer; font-size:12px; color:var(--gov-text);
            background:#fff; transition:background .12s, border-color .12s;
        }
        .test-label:hover { background:#f0f9f8; border-color:#0d9488; }
        .test-label input[type=checkbox] { accent-color:#0d9488; flex-shrink:0; }
    </style>
    @endpush

    @php
        // sample_items is a JSON array of per-sample entries stored at create time.
        // Multi-sample create flow doesn't populate the flat quantity/type columns,
        // so fall back to sample_items[0] before trying the Sample relationship.
        $sampleItems = $submission->sample_items ?? [];
        $firstItem   = $sampleItems[0] ?? [];

        $testsRequested  = $submission->tests_requested ?? [];
        $transportMethod = old('transport_method', $submission->transport_method ?? 'chilled');
        $transportDetail = old('transport_detail', $submission->transport_detail ?? '');

        $sampleName    = old('sample_name',         $submission->sample_name        ?? ($firstItem['name']            ?? ($firstSample?->common_name      ?? '')));
        $sciName       = old('scientific_name',      $submission->scientific_name     ?? ($firstItem['scientific_name'] ?? ($firstSample?->scientific_name   ?? '')));
        $sampleType    = old('sample_type',          $submission->sample_type         ?? ($firstItem['type']            ?? ''));
        $sampleQty     = old('sample_quantity',      $submission->sample_quantity     ?? ($firstItem['qty']             ?? ($firstSample?->quantity          ?? '')));
        $sampleQtyUnit = old('sample_quantity_unit', $submission->sample_quantity_unit ?? ($firstItem['unit']           ?? ($firstSample?->quantity_unit     ?? 'kg')));
        $collectedAt   = old('collected_at',         $submission->collected_at?->format('Y-m-d') ?? ($firstSample?->sampling_date?->format('Y-m-d') ?? ''));
        $deliveredAt   = old('delivered_at',         $submission->delivered_at?->format('Y-m-d') ?? '');
    @endphp

    <div style="background:#f1f5f9;min-height:100vh;padding:0 0 56px;">
        <div style="max-width:72rem;margin:0 auto;padding:0 2rem;">

            {{-- Validation Errors --}}
            @if($errors->any())
                <div style="background:#fef2f2;border:1px solid #fecaca;border-left:4px solid #dc2626;border-radius:4px;padding:12px 16px;margin-bottom:20px;">
                    <ul style="margin:0;padding-left:16px;font-size:13px;color:#991b1b;">
                        @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('client.submissions.update', $submission->id) }}">
                @csrf
                @method('PUT')
                <input type="hidden" name="submitter_name" value="{{ trim(auth()->user()->first_name . ' ' . auth()->user()->last_name) }}">

                {{-- ── Section 1: Collection Info ──────────────────────── --}}
                <div class="section-card">
                    <div class="section-hdr">
                        <h3 style="font-family:'Georgia',serif;font-size:15px;font-weight:700;color:var(--gov-navy);margin:0 0 2px;">Collection Info</h3>
                        <p style="font-size:12px;color:var(--gov-muted);margin:0;">When and where the samples were collected.</p>
                    </div>
                    <div class="section-body">

                        <div class="ef-card">
                            <label for="client_reference" class="ef-label">Your Reference Number</label>
                            <p class="ef-hint">Your organisation's internal reference. This will appear on your Certificate of Analysis.</p>
                            <input id="client_reference" type="text" name="client_reference"
                                   value="{{ old('client_reference', $submission->client_reference) }}"
                                   class="ef-input" style="font-family:monospace;"
                                   placeholder="e.g. MFOR-2026-001">
                            @error('client_reference')<p class="ef-err">{{ $message }}</p>@enderror
                        </div>

                        <div class="ef-card">
                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                                <div>
                                    <label for="collected_at" class="ef-label">Collection Date *</label>
                                    <p class="ef-hint">The date samples were collected. Cannot be a future date.</p>
                                    <input id="collected_at" type="date" name="collected_at"
                                           value="{{ $collectedAt }}"
                                           class="ef-input" max="{{ date('Y-m-d') }}" required>
                                    @error('collected_at')<p class="ef-err">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label for="delivered_at" class="ef-label">Delivery Date</label>
                                    <p class="ef-hint">The date samples will arrive at the laboratory (optional).</p>
                                    <input id="delivered_at" type="date" name="delivered_at"
                                           value="{{ $deliveredAt }}"
                                           class="ef-input">
                                    @error('delivered_at')<p class="ef-err">{{ $message }}</p>@enderror
                                </div>
                            </div>
                        </div>

                        <div class="ef-card">
                            <label for="collection_location" class="ef-label">Collection Location</label>
                            <p class="ef-hint">Where the samples were collected (e.g. South Tarawa Lagoon, Betio Harbour).</p>
                            <input id="collection_location" type="text" name="collection_location"
                                   value="{{ old('collection_location', $submission->collection_location) }}"
                                   class="ef-input" placeholder="e.g. South Tarawa Lagoon">
                            @error('collection_location')<p class="ef-err">{{ $message }}</p>@enderror
                        </div>

                        <div class="ef-card">
                            <label for="sample_description" class="ef-label">Notes (optional)</label>
                            <p class="ef-hint">Any general observations, handling conditions, or context the laboratory should know.</p>
                            <textarea id="sample_description" name="sample_description" rows="2"
                                      class="ef-input" style="resize:vertical;"
                                      placeholder="Any general notes about the collection or samples...">{{ old('sample_description', $submission->sample_description) }}</textarea>
                            @error('sample_description')<p class="ef-err">{{ $message }}</p>@enderror
                        </div>

                    </div>
                </div>

                {{-- ── Section 2: Sample ───────────────────────────────── --}}
                <div class="section-card">
                    <div class="section-hdr">
                        <h3 style="font-family:'Georgia',serif;font-size:15px;font-weight:700;color:var(--gov-navy);margin:0 0 2px;">Sample</h3>
                        <p style="font-size:12px;color:var(--gov-muted);margin:0;">Name, type, quantity, and tests requested.</p>
                    </div>
                    <div class="section-body">

                        <div class="ef-card">
                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                                <div>
                                    <label for="sample_name" class="ef-label">Common Name *</label>
                                    <p class="ef-hint">The common name of the sample (e.g. Yellowfin Tuna).</p>
                                    <input id="sample_name" type="text" name="sample_name"
                                           value="{{ $sampleName }}"
                                           class="ef-input" required autofocus placeholder="e.g. Yellowfin Tuna">
                                    @error('sample_name')<p class="ef-err">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label for="scientific_name" class="ef-label">Scientific Name</label>
                                    <p class="ef-hint">Latin species name, if known (optional).</p>
                                    <input id="scientific_name" type="text" name="scientific_name"
                                           value="{{ $sciName }}"
                                           class="ef-input" placeholder="e.g. Thunnus albacares">
                                    @error('scientific_name')<p class="ef-err">{{ $message }}</p>@enderror
                                </div>
                            </div>
                        </div>

                        <div class="ef-card">
                            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px;">
                                <div>
                                    <label for="sample_type" class="ef-label">Sample Type</label>
                                    <p class="ef-hint">Category that best describes the sample.</p>
                                    <select id="sample_type" name="sample_type" class="ef-input">
                                        <option value="">— Select type —</option>
                                        @foreach(['fish'=>'Fish','shellfish'=>'Shellfish','seaweed'=>'Seaweed','water'=>'Water','sediment'=>'Sediment','other'=>'Other'] as $v => $l)
                                            <option value="{{ $v }}" {{ $sampleType === $v ? 'selected' : '' }}>{{ $l }}</option>
                                        @endforeach
                                    </select>
                                    @error('sample_type')<p class="ef-err">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label for="sample_quantity" class="ef-label">Quantity</label>
                                    <p class="ef-hint">Total weight or volume submitted.</p>
                                    <input id="sample_quantity" type="number" name="sample_quantity"
                                           value="{{ $sampleQty }}"
                                           class="ef-input" placeholder="0" min="0" step="0.01">
                                    @error('sample_quantity')<p class="ef-err">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label for="sample_quantity_unit" class="ef-label">Unit</label>
                                    <p class="ef-hint">Measurement unit for quantity.</p>
                                    <select id="sample_quantity_unit" name="sample_quantity_unit" class="ef-input">
                                        @foreach(['g'=>'g','kg'=>'kg','ml'=>'ml','L'=>'L'] as $v => $l)
                                            <option value="{{ $v }}" {{ $sampleQtyUnit === $v ? 'selected' : '' }}>{{ $l }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- Tests --}}
                        <div class="ef-card">
                            <label class="ef-label">Tests Requested</label>
                            <p class="ef-hint">Select all tests required for this sample.</p>

                            {{-- Seafood / Microbiological --}}
                            <p style="font-size:10px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--gov-navy);margin:0 0 6px;">Seafood — Microbiological</p>
                            <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:6px;margin-bottom:14px;">
                                @foreach([
                                    'e_coli_coliform' => 'E. coli &amp; Coliform',
                                    'staph_aureus'    => 'Staph. aureus',
                                    'apc'             => 'APC (Aerobic Plate Count)',
                                    'yeast_mold'      => 'Yeast &amp; Mould',
                                    'salmonella_spp'  => 'Salmonella species',
                                    'listeria_spp'    => 'Listeria species',
                                    'clostridium'     => 'Clostridium',
                                ] as $tv => $tl)
                                    <label class="test-label">
                                        <input type="checkbox" name="tests_requested[]" value="{{ $tv }}"
                                               {{ in_array($tv, old('tests_requested', $testsRequested) ?? []) ? 'checked' : '' }}>
                                        <span style="line-height:1.3;">{!! $tl !!}</span>
                                    </label>
                                @endforeach
                            </div>

                            {{-- Water Testing --}}
                            <p style="font-size:10px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:#0369a1;margin:0 0 6px;">Water Testing</p>
                            <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:6px;margin-bottom:14px;">
                                @foreach([
                                    'e_coli_colilert'         => 'E. coli (Colilert)',
                                    'total_coliform_colilert' => 'Total Coliform (Colilert)',
                                    'enterococci_enterolert'  => 'Enterococci (Enterolert)',
                                ] as $tv => $tl)
                                    <label class="test-label" style="border-color:#bae6fd;">
                                        <input type="checkbox" name="tests_requested[]" value="{{ $tv }}"
                                               style="accent-color:#0369a1;"
                                               {{ in_array($tv, old('tests_requested', $testsRequested) ?? []) ? 'checked' : '' }}>
                                        <span style="line-height:1.3;">{{ $tl }}</span>
                                    </label>
                                @endforeach
                            </div>

                            {{-- Chemical --}}
                            <p style="font-size:10px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:#b8922a;margin:0 0 6px;">Chemical</p>
                            <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:6px;margin-bottom:14px;">
                                @foreach([
                                    'histamine' => 'Histamine',
                                    'moisture'  => 'Moisture',
                                ] as $tv => $tl)
                                    <label class="test-label" style="border-color:#fde68a;">
                                        <input type="checkbox" name="tests_requested[]" value="{{ $tv }}"
                                               style="accent-color:#b8922a;"
                                               {{ in_array($tv, old('tests_requested', $testsRequested) ?? []) ? 'checked' : '' }}>
                                        <span style="line-height:1.3;">{{ $tl }}</span>
                                    </label>
                                @endforeach
                            </div>

                            {{-- Physical --}}
                            <p style="font-size:10px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:#7c3aed;margin:0 0 6px;">Physical</p>
                            <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:6px;margin-bottom:14px;">
                                @foreach([
                                    'temperature'    => 'Temperature',
                                    'ph'             => 'pH',
                                    'conductivity'   => 'Conductivity',
                                    'water_activity' => 'Water Activity',
                                ] as $tv => $tl)
                                    <label class="test-label" style="border-color:#ddd6fe;">
                                        <input type="checkbox" name="tests_requested[]" value="{{ $tv }}"
                                               style="accent-color:#7c3aed;"
                                               {{ in_array($tv, old('tests_requested', $testsRequested) ?? []) ? 'checked' : '' }}>
                                        <span style="line-height:1.3;">{{ $tl }}</span>
                                    </label>
                                @endforeach
                            </div>

                            <label for="tests_other" class="ef-label" style="margin-top:4px;">Other Tests (specify)</label>
                            <input id="tests_other" type="text" name="tests_other"
                                   value="{{ old('tests_other', $submission->tests_other) }}"
                                   class="ef-input" placeholder="Any additional tests not listed above...">
                            @error('tests_requested')<p class="ef-err">{{ $message }}</p>@enderror
                        </div>

                    </div>
                </div>

                {{-- ── Section 3: Transport & Instructions ─────────────── --}}
                <div class="section-card">
                    <div class="section-hdr">
                        <h3 style="font-family:'Georgia',serif;font-size:15px;font-weight:700;color:var(--gov-navy);margin:0 0 2px;">Transport &amp; Instructions</h3>
                        <p style="font-size:12px;color:var(--gov-muted);margin:0;">How the sample is transported and any handling requirements.</p>
                    </div>
                    <div class="section-body">

                        <div class="ef-card" x-data="{ method: '{{ $transportMethod }}', detail: '{{ $transportDetail }}' }">
                            <label class="ef-label">Sample Transport Method *</label>
                            <p class="ef-hint">Select the temperature category, then specify the exact transport method.</p>
                            <div style="display:flex;gap:20px;flex-wrap:wrap;margin-bottom:12px;">
                                @foreach(['frozen' => 'Frozen', 'chilled' => 'Chill'] as $v => $l)
                                    <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:13px;font-weight:600;color:var(--gov-text);">
                                        <input type="radio" name="transport_method" value="{{ $v }}"
                                               x-model="method"
                                               @change="detail = ''"
                                               {{ $transportMethod === $v ? 'checked' : '' }}
                                               style="accent-color:var(--gov-navy);">
                                        {{ $l }}
                                    </label>
                                @endforeach
                            </div>
                            {{-- x-model sets the value reactively so it survives x-show hiding/showing optgroups --}}
                            <select name="transport_detail" x-model="detail" class="ef-input">
                                <option value="">— Please select transport detail —</option>
                                <optgroup label="Frozen Methods" x-show="method === 'frozen'">
                                    <option value="air_freight_frozen">Air Freight (Frozen)</option>
                                    <option value="sea_freight_reefer">Sea Freight (Reefer Container)</option>
                                    <option value="road_frozen_truck">Road Transport (Frozen Truck)</option>
                                    <option value="other_special_frozen">Other / Special Arrangement</option>
                                </optgroup>
                                <optgroup label="Chilled Methods" x-show="method === 'chilled'">
                                    <option value="air_freight_chilled">Air Freight (Chilled)</option>
                                    <option value="road_chilled_van">Road Transport (Chilled Van)</option>
                                    <option value="cooler_box_ice_packs">Cooler Box with Ice Packs</option>
                                    <option value="other_special">Other / Special Arrangement</option>
                                </optgroup>
                            </select>
                            @error('transport_method')<p class="ef-err">{{ $message }}</p>@enderror
                            @error('transport_detail')<p class="ef-err">{{ $message }}</p>@enderror
                        </div>

                        <div class="ef-card">
                            <label class="ef-label">Priority *</label>
                            <p class="ef-hint">Routine submissions are processed within standard timeframes. Urgent requests are prioritised and attract additional fees.</p>
                            <div style="display:flex;gap:20px;flex-wrap:wrap;">
                                @foreach(['routine' => 'Routine', 'urgent' => 'Urgent'] as $v => $l)
                                    <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:13px;color:var(--gov-text);">
                                        <input type="radio" name="priority" value="{{ $v }}"
                                               {{ old('priority', $submission->priority ?? 'routine') === $v ? 'checked' : '' }}
                                               style="accent-color:var(--gov-navy);">
                                        {{ $l }}
                                    </label>
                                @endforeach
                            </div>
                            @error('priority')<p class="ef-err">{{ $message }}</p>@enderror
                        </div>

                        <div class="ef-card">
                            <label for="special_instructions" class="ef-label">Additional Notes</label>
                            <p class="ef-hint">Special handling, storage conditions, or any other instructions for the laboratory team.</p>
                            <textarea id="special_instructions" name="special_instructions" rows="3"
                                      class="ef-input" style="resize:vertical;"
                                      placeholder="Any other instructions for the lab team...">{{ old('special_instructions', $submission->special_instructions) }}</textarea>
                            @error('special_instructions')<p class="ef-err">{{ $message }}</p>@enderror
                        </div>

                        <div class="ef-card">
                            <label for="results_required_by" class="ef-label">Results Required By</label>
                            <p class="ef-hint">Leave blank if there is no specific deadline. Urgent requests attract additional fees.</p>
                            <input id="results_required_by" type="date" name="results_required_by"
                                   value="{{ old('results_required_by', $submission->results_required_by?->format('Y-m-d')) }}"
                                   style="width:50%;padding:8px 12px;border:1px solid #cbd5e1;border-radius:3px;font-size:13px;color:#1e293b;background:#fff;box-sizing:border-box;"
                                   min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                            @error('results_required_by')<p class="ef-err">{{ $message }}</p>@enderror
                        </div>

                    </div>
                </div>

                {{-- ── Actions ────────────────────────────────────────── --}}
                <div style="display:flex;align-items:center;justify-content:space-between;padding-bottom:8px;">
                    <a href="{{ route('client.submissions.show', $submission->id) }}"
                       style="display:inline-flex;align-items:center;gap:8px;padding:9px 20px;background:#fff;color:var(--gov-navy);font-size:13px;font-weight:600;border:1px solid var(--gov-navy);border-radius:3px;text-decoration:none;">
                        Cancel
                    </a>
                    <button type="submit"
                            style="display:inline-flex;align-items:center;gap:8px;padding:9px 24px;background:var(--gov-navy);color:#fff;font-size:13px;font-weight:600;border-radius:3px;border:none;cursor:pointer;">
                        <svg style="width:15px;height:15px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Update Submission
                    </button>
                </div>

            </form>
        </div>
    </div>
</x-app-layout>
