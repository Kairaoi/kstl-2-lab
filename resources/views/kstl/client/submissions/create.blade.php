{{-- resources/views/kstl/client/submissions/create.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <div style="position:relative;overflow:hidden;background:linear-gradient(135deg,#0f2240 0%,#1a2f4e 60%,#1e3a5f 100%);margin:-1px;">
            <div style="position:absolute;inset:0;opacity:.04;background-image:repeating-linear-gradient(45deg,#fff 0,#fff 1px,transparent 0,transparent 50%);background-size:12px 12px;pointer-events:none;"></div>
            <div style="position:absolute;top:0;left:0;right:0;height:3px;background:linear-gradient(90deg,#1a2f4e,#b8922a 30%,#b8922a 70%,#1a2f4e);"></div>
            <div style="max-width:80rem;margin:0 auto;padding:28px 2rem;">
                <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:20px;position:relative;">
                    <div style="display:flex;align-items:center;gap:18px;">
                        <img src="{{ asset('images/mfor-logo.png') }}" alt="Ministry of Fisheries &amp; Ocean Resources" style="width:56px;height:56px;object-fit:contain;filter:brightness(0) invert(1);opacity:.92;">
                        <div>
                            <p style="font-size:8.5px;font-weight:700;letter-spacing:.22em;text-transform:uppercase;color:#b8922a;margin-bottom:5px;">
                                Client &nbsp;·&nbsp; Seafood Toxicology Laboratory
                            </p>
                            <h1 style="font-family:'Georgia',serif;font-size:22px;font-weight:700;color:#ffffff;line-height:1.2;margin:0;">
                                New Sample Submission
                            </h1>
                            <p style="font-size:11px;color:#94a3b8;margin-top:4px;">Complete all steps to submit your samples for analysis</p>
                        </div>
                    </div>
                    <div>
                        <a href="{{ route('client.submissions.index') }}" style="display:inline-flex;align-items:center;gap:5px;font-size:11px;font-weight:600;color:#94a3b8;text-decoration:none;">
                            <svg style="width:12px;height:12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                            Back to Submissions
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
        .app-main { padding-left: 0 !important; padding-right: 0 !important;
                    padding-top: 0 !important; max-width: 100% !important; }
        [x-cloak] { display: none !important; }
        :root {
            --gov-navy:   #1a2f4e;
            --gov-gold:   #b8922a;
            --gov-teal:   #0d9488;
            --gov-text:   #1f2937;
            --gov-muted:  #6b7280;
            --gov-light:  #f8fafc;
            --gov-border: #e2e8f0;
        }
        /* Step card headings */
        .step-heading {
            font-family: 'Georgia', serif; font-size: 15px; font-weight: 700;
            color: var(--gov-navy); margin: 0 0 3px;
        }
        .step-sub { font-size: 12px; color: var(--gov-muted); margin: 0; }

        /* Inputs focus ring override */
        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: #0d9488 !important;
            box-shadow: 0 0 0 2px rgba(13,148,136,.15) !important;
        }

        /* Test checkbox labels */
        .test-label {
            display: flex; align-items: center; gap: 8px;
            padding: 7px 10px; border: 1px solid var(--gov-border);
            border-radius: 3px; cursor: pointer; font-size: 12px;
            color: var(--gov-text); transition: background .12s, border-color .12s;
            background: #fff;
        }
        .test-label:hover { background: #f0f9f8; border-color: #0d9488; }
        .test-label input[type=checkbox] { accent-color: #0d9488; flex-shrink: 0; }

        /* Nav buttons */
        .btn-gov {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 9px 20px; border-radius: 3px; font-size: 13px;
            font-weight: 600; text-decoration: none; border: none; cursor: pointer;
            transition: background .15s;
        }
        .btn-gov-navy { background: var(--gov-navy); color: #fff; }
        .btn-gov-navy:hover { background: #0f2240; }
        .btn-gov-back { background: #f1f5f9; color: var(--gov-text); border: 1px solid var(--gov-border); }
        .btn-gov-back:hover { background: #e2e8f0; }
        .btn-gov-green { background: #059669; color: #fff; }
        .btn-gov-green:hover { background: #047857; }
        .btn-gov-disabled { background: #e2e8f0; color: #9ca3af; cursor: not-allowed; }

        /* Section divider label */
        .section-label {
            font-size: 9px; font-weight: 700; letter-spacing: .14em;
            text-transform: uppercase; color: var(--gov-muted);
            margin: 0 0 8px;
        }

        /* Summary rows */
        .summary-row {
            display: flex; justify-content: space-between; align-items: center;
            gap: 16px; padding: 10px 16px; border-bottom: 1px solid var(--gov-border);
            font-size: 13px;
        }
        .summary-row:last-child { border-bottom: none; }
        .summary-label { color: var(--gov-muted); }
        .summary-val { font-weight: 600; color: var(--gov-text); text-align: right; }
    </style>
    @endpush

    <div style="background:#f1f5f9; min-height:100vh; padding:0 0 56px;">
        <div style="max-width:72rem; margin:0 auto; padding:0 2rem;">

            {{-- Validation Errors --}}
            <x-validation-errors class="mb-5" style="background:#fef2f2; border:1px solid #fca5a5; border-left:4px solid #ef4444; border-radius:4px; padding:14px 18px; margin-bottom:20px;"/>

            {{-- Service Agreement Warning --}}
            @if($client && !$client->service_agreement_signed_at)
                <div style="margin-bottom:20px; background:#fffbeb; border:1px solid #fde68a; border-left:4px solid #f59e0b; border-radius:4px; padding:14px 18px; display:flex; align-items:center; gap:10px;">
                    <svg style="width:16px;height:16px;color:#d97706;flex-shrink:0;" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                    </svg>
                    <p style="font-size:13px; color:#92400e; margin:0;">
                        <strong>Action required:</strong> You must sign the service agreement before submitting samples.
                    </p>
                </div>
            @endif

            <div x-data="submissionWizard({{ $errors->isNotEmpty() ? 'false' : 'true' }})">

            {{-- Draft restore banner --}}
            <div x-show="hasDraft"
                 x-cloak
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 style="margin-bottom:20px; background:#fff; border:1px solid var(--gov-border); border-left:4px solid var(--gov-gold); border-radius:4px; padding:14px 18px; display:flex; align-items:center; justify-content:space-between; gap:16px;">
                <div style="display:flex; align-items:center; gap:10px;">
                    <svg style="width:16px;height:16px;color:#b8922a;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                    </svg>
                    <div>
                        <p style="font-size:13px; font-weight:600; color:var(--gov-navy); margin:0 0 2px;">Unsaved draft found</p>
                        <p style="font-size:11px; color:var(--gov-muted); margin:0;">You started filling this form earlier. Resume where you left off?</p>
                    </div>
                </div>
                <div style="display:flex; align-items:center; gap:8px; flex-shrink:0;">
                    <button type="button" @click="clearDraft()"
                            style="font-size:12px; color:var(--gov-muted); background:none; border:none; cursor:pointer; padding:6px 12px;">
                        Discard
                    </button>
                    <button type="button" @click="resumeDraft()"
                            class="btn-gov btn-gov-navy" style="padding:6px 16px; font-size:12px;">
                        Resume Draft
                    </button>
                </div>
            </div>

            <form id="submission-form"
                  method="POST" action="{{ route('client.submissions.store') }}"
                  @submit="clearDraft()">
                @csrf

                @php $steps = ['Collection Info', 'Samples', 'Transport', 'Declaration']; @endphp
                @php $stepSubs = ['Date, location & notes', 'Name, type & tests', 'Method & priority', 'Review & submit']; @endphp

                <div style="display:flex; gap:28px; align-items:flex-start;">

                    {{-- ── Vertical Step Sidebar ──────────────────────────────── --}}
                    <div class="hidden md:block" style="width:200px; flex-shrink:0;">
                        <div style="background:#fff; border:1px solid var(--gov-border); border-radius:4px; overflow:hidden;">
                            <div style="background:var(--gov-navy); padding:10px 16px;">
                                <p style="font-size:9px; font-weight:700; letter-spacing:.16em; text-transform:uppercase; color:rgba(255,255,255,.5); margin:0;">Progress</p>
                            </div>
                            <nav style="padding:8px 0;">
                                @foreach($steps as $i => $label)
                                    <div style="position:relative;">
                                        @if($i < count($steps) - 1)
                                            <div style="position:absolute; left:27px; top:42px; width:2px; height:12px; z-index:0;"
                                                 :style="currentStep > {{ $i }} ? 'background:#b8922a' : 'background:#e2e8f0'"></div>
                                        @endif
                                        <button type="button"
                                                @click="if(currentStep > {{ $i }}) currentStep = {{ $i }}"
                                                style="width:100%; display:flex; align-items:flex-start; gap:10px; padding:10px 14px; background:none; border:none; cursor:default; text-align:left; position:relative; z-index:1;"
                                                :style="currentStep > {{ $i }} ? 'cursor:pointer' : (currentStep === {{ $i }} ? '' : 'opacity:.5')"
                                                :class="currentStep === {{ $i }} ? 'bg-[#f0f4f8]' : ''">
                                            <div style="width:26px; height:26px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:11px; font-weight:700; flex-shrink:0; margin-top:1px; transition:background .15s;"
                                                 :style="currentStep > {{ $i }} ? 'background:#b8922a; color:#fff' : (currentStep === {{ $i }} ? 'background:#1a2f4e; color:#fff' : 'background:#e2e8f0; color:#6b7280')">
                                                <template x-if="currentStep > {{ $i }}">
                                                    <svg style="width:12px;height:12px;" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                    </svg>
                                                </template>
                                                <template x-if="currentStep <= {{ $i }}">
                                                    <span>{{ $i + 1 }}</span>
                                                </template>
                                            </div>
                                            <div style="min-width:0;">
                                                <p style="font-size:12.5px; font-weight:600; line-height:1.3; margin:0;"
                                                   :style="currentStep === {{ $i }} ? 'color:#1a2f4e' : (currentStep > {{ $i }} ? 'color:#b8922a' : 'color:#6b7280')">
                                                    {{ $label }}
                                                </p>
                                                <p style="font-size:10.5px; color:#9ca3af; margin:2px 0 0; line-height:1.3;">{{ $stepSubs[$i] }}</p>
                                            </div>
                                        </button>
                                    </div>
                                @endforeach
                            </nav>
                        </div>
                    </div>

                    {{-- ── Form Content ──────────────────────────────────────── --}}
                    <div style="flex:1; min-width:0;">

                        {{-- ── Step 1: Collection Info ─────────────────────────── --}}
                        <div x-show="currentStep === 0" x-cloak>
                            <div style="background:#fff; border:1px solid var(--gov-border); border-radius:4px; overflow:hidden;">
                                <div style="padding:18px 24px 16px; border-bottom:2px solid var(--gov-gold);">
                                    <h3 class="step-heading">Collection Info</h3>
                                    <p class="step-sub">When and where the samples were collected, plus any notes.</p>
                                </div>
                                <div style="padding:24px; display:flex; flex-direction:column; gap:20px;">

                                    {{-- Client Reference --}}
                                    <div style="background:#f0f4f8; border:1px solid #cbd5e1; border-left:4px solid var(--gov-navy); border-radius:4px; padding:16px;">
                                        <x-label for="client_reference" value="Your Reference Number"/>
                                        <p style="font-size:11px; color:var(--gov-muted); margin:3px 0 10px;">
                                            Enter your organisation's internal reference (e.g. MFOR-2026-001). This will appear on your Certificate of Analysis.
                                        </p>
                                        <x-input id="client_reference" type="text" name="client_reference"
                                                 value="{{ old('client_reference') }}"
                                                 class="mt-1 block w-full font-mono"
                                                 placeholder="e.g. MFOR-2026-001"
                                                 autofocus/>
                                        <x-input-error for="client_reference" class="mt-1"/>
                                    </div>

                                    <div style="background:#f0f4f8; border:1px solid #cbd5e1; border-left:4px solid var(--gov-navy); border-radius:4px; padding:16px;">
                                        <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                                            <div>
                                                <x-label for="collected_at" value="Collection Date *"/>
                                                <p style="font-size:11px; color:var(--gov-muted); margin:3px 0 10px;">The date the samples were collected. Cannot be a future date.</p>
                                                <x-input id="collected_at" type="date" name="collected_at"
                                                         value="{{ old('collected_at') }}"
                                                         class="block w-full"
                                                         max="{{ date('Y-m-d') }}"/>
                                                <x-input-error for="collected_at" class="mt-1"/>
                                            </div>
                                            <div>
                                                <x-label for="delivered_at" value="Delivery Date"/>
                                                <p style="font-size:11px; color:var(--gov-muted); margin:3px 0 10px;">The date samples will arrive at the laboratory (optional).</p>
                                                <x-input id="delivered_at" type="date" name="delivered_at"
                                                         value="{{ old('delivered_at') }}"
                                                         class="block w-full"/>
                                                <x-input-error for="delivered_at" class="mt-1"/>
                                            </div>
                                        </div>
                                    </div>

                                    <div style="background:#f0f4f8; border:1px solid #cbd5e1; border-left:4px solid var(--gov-navy); border-radius:4px; padding:16px;">
                                        <x-label for="collection_location" value="Collection Location"/>
                                        <p style="font-size:11px; color:var(--gov-muted); margin:3px 0 10px;">Where the samples were collected (e.g. South Tarawa Lagoon, Betio Harbour).</p>
                                        <x-input id="collection_location" type="text" name="collection_location"
                                                 value="{{ old('collection_location') }}"
                                                 class="block w-full"
                                                 placeholder="e.g. South Tarawa Lagoon"/>
                                        <x-input-error for="collection_location" class="mt-1"/>
                                    </div>

                                    <div style="background:#f0f4f8; border:1px solid #cbd5e1; border-left:4px solid var(--gov-navy); border-radius:4px; padding:16px;">
                                        <x-label for="sample_description" value="Notes (optional)"/>
                                        <p style="font-size:11px; color:var(--gov-muted); margin:3px 0 10px;">Any general observations, handling conditions, or context the laboratory should know.</p>
                                        <textarea id="sample_description" name="sample_description" rows="2"
                                                  style="width:100%; border:1px solid #d1d5db; border-radius:3px; padding:8px 12px; font-size:13px; color:#1f2937; box-sizing:border-box; resize:vertical;"
                                                  placeholder="Any general notes about the collection or samples...">{{ old('sample_description') }}</textarea>
                                        <x-input-error for="sample_description" class="mt-1"/>
                                    </div>

                                </div>
                            </div>
                            <div style="display:flex; justify-content:flex-end; margin-top:16px;">
                                <button type="button" @click="nextStep()" class="btn-gov btn-gov-navy">
                                    Next: Samples
                                    <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </button>
                            </div>
                        </div>

                        {{-- ── Step 2: Samples ─────────────────────────────────── --}}
                        <div x-show="currentStep === 1" x-cloak>
                            <div style="background:#fff; border:1px solid var(--gov-border); border-radius:4px; overflow:hidden;">
                                <div style="padding:18px 24px 16px; border-bottom:2px solid var(--gov-gold);">
                                    <h3 class="step-heading">Samples</h3>
                                    <p class="step-sub">Add each sample — name, type, reference number, and quantity. Up to 9 samples.</p>
                                </div>
                                <div style="padding:24px; display:flex; flex-direction:column; gap:16px;">

                                    <template x-for="(item, index) in sampleItems" :key="index">
                                        <div style="border:1px solid var(--gov-border); border-radius:4px; padding:16px; display:flex; flex-direction:column; gap:12px;">

                                            {{-- Card header --}}
                                            <div style="display:flex; align-items:center; justify-content:space-between;">
                                                <span style="font-size:9px; font-weight:700; letter-spacing:.14em; text-transform:uppercase; color:var(--gov-navy);"
                                                      x-text="`Sample ${index + 1}`"></span>
                                                <button type="button" @click="removeSample(index)"
                                                        x-show="sampleItems.length > 1"
                                                        style="font-size:11px; color:#ef4444; background:none; border:none; cursor:pointer; display:flex; align-items:center; gap:4px;">
                                                    <svg style="width:12px;height:12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                    </svg>
                                                    Remove
                                                </button>
                                            </div>

                                            {{-- Name row --}}
                                            <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
                                                <div>
                                                    <label style="display:block; font-size:12px; font-weight:600; color:var(--gov-text); margin-bottom:4px;">Common Name *</label>
                                                    <input type="text"
                                                           :name="`sample_items[${index}][name]`"
                                                           x-model="item.name"
                                                           style="width:100%; border:1px solid #d1d5db; border-radius:3px; padding:7px 11px; font-size:13px; color:#1f2937; box-sizing:border-box;"
                                                           placeholder="e.g. Yellowfin Tuna"/>
                                                </div>
                                                <div>
                                                    <label style="display:block; font-size:12px; font-weight:600; color:var(--gov-text); margin-bottom:4px;">Scientific Name</label>
                                                    <input type="text"
                                                           :name="`sample_items[${index}][scientific_name]`"
                                                           x-model="item.scientific_name"
                                                           style="width:100%; border:1px solid #d1d5db; border-radius:3px; padding:7px 11px; font-size:13px; color:#1f2937; box-sizing:border-box;"
                                                           placeholder="e.g. Thunnus albacares"/>
                                                </div>
                                            </div>

                                            {{-- Type / Ref / Qty / Unit row --}}
                                            <div style="display:grid; grid-template-columns:1fr 1fr 1fr 1fr; gap:12px;">
                                                <div>
                                                    <label style="display:block; font-size:12px; font-weight:600; color:var(--gov-text); margin-bottom:4px;">Sample Type</label>
                                                    <select :name="`sample_items[${index}][type]`"
                                                            x-model="item.type"
                                                            style="width:100%; border:1px solid #d1d5db; border-radius:3px; padding:7px 11px; font-size:13px; color:#1f2937; background:#fff; box-sizing:border-box; height:36px;">
                                                        <option value="">— Type —</option>
                                                        <option value="fish">Fish</option>
                                                        <option value="shellfish">Shellfish</option>
                                                        <option value="seaweed">Seaweed</option>
                                                        <option value="water">Water</option>
                                                        <option value="sediment">Sediment</option>
                                                        <option value="other">Other</option>
                                                    </select>
                                                </div>
                                                <div>
                                                    <label style="display:block; font-size:12px; font-weight:600; color:var(--gov-text); margin-bottom:4px;">Reference #</label>
                                                    <input type="text"
                                                           :name="`sample_items[${index}][ref]`"
                                                           x-model="item.ref"
                                                           style="width:100%; border:1px solid #d1d5db; border-radius:3px; padding:7px 11px; font-size:13px; color:#1f2937; box-sizing:border-box;"
                                                           placeholder="e.g. S-001"/>
                                                </div>
                                                <div>
                                                    <label style="display:block; font-size:12px; font-weight:600; color:var(--gov-text); margin-bottom:4px;">Quantity</label>
                                                    <input type="number"
                                                           :name="`sample_items[${index}][qty]`"
                                                           x-model="item.qty"
                                                           style="width:100%; border:1px solid #d1d5db; border-radius:3px; padding:7px 11px; font-size:13px; color:#1f2937; box-sizing:border-box;"
                                                           placeholder="0" min="0" step="0.01"/>
                                                </div>
                                                <div>
                                                    <label style="display:block; font-size:12px; font-weight:600; color:var(--gov-text); margin-bottom:4px;">Unit</label>
                                                    <select :name="`sample_items[${index}][unit]`"
                                                            x-model="item.unit"
                                                            style="width:100%; border:1px solid #d1d5db; border-radius:3px; padding:7px 11px; font-size:13px; color:#1f2937; background:#fff; box-sizing:border-box; height:36px;">
                                                        <option value="g">g</option>
                                                        <option value="kg">kg</option>
                                                        <option value="ml">ml</option>
                                                        <option value="L">L</option>
                                                    </select>
                                                </div>
                                            </div>

                                            {{-- "Other" type description --}}
                                            <div x-show="item.type === 'other'" x-cloak>
                                                <label style="display:block; font-size:12px; font-weight:600; color:var(--gov-text); margin-bottom:4px;">Other — please describe *</label>
                                                <input type="text"
                                                       :name="`sample_items[${index}][type_notes]`"
                                                       x-model="item.type_notes"
                                                       style="width:100%; border:1px solid #d1d5db; border-radius:3px; padding:7px 11px; font-size:13px; color:#1f2937; box-sizing:border-box;"
                                                       placeholder="Describe the sample type…"/>
                                            </div>

                                            {{-- Tests for this sample --}}
                                            <div style="border-top:1px solid var(--gov-border); padding-top:12px; margin-top:4px;">
                                                <p class="section-label">Tests Requested for This Sample</p>

                                                {{-- Seafood / Microbiological --}}
                                                <p style="font-size:10px; font-weight:700; letter-spacing:.1em; text-transform:uppercase; color:var(--gov-navy); margin:0 0 6px;">Seafood — Microbiological</p>
                                                <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:6px; margin-bottom:12px;">
                                                    @foreach([
                                                        'e_coli_coliform' => 'E. coli &amp; Coliform',
                                                        'staph_aureus'    => 'Staph. aureus',
                                                        'apc'             => 'APC (Aerobic Plate Count)',
                                                        'yeast_mold'      => 'Yeast &amp; Mould',
                                                        'salmonella_spp'  => 'Salmonella species',
                                                        'listeria_spp'    => 'Listeria species',
                                                        'clostridium'     => 'Clostridium',
                                                    ] as $tValue => $tLabel)
                                                        <label class="test-label">
                                                            <input type="checkbox"
                                                                   value="{{ $tValue }}"
                                                                   x-model="item.tests"
                                                                   :name="`sample_items[${index}][tests][]`"/>
                                                            <span style="line-height:1.3;">{!! $tLabel !!}</span>
                                                        </label>
                                                    @endforeach
                                                </div>

                                                {{-- Water Testing --}}
                                                <p style="font-size:10px; font-weight:700; letter-spacing:.1em; text-transform:uppercase; color:#0369a1; margin:0 0 6px;">Water Testing</p>
                                                <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:6px; margin-bottom:12px;">
                                                    @foreach([
                                                        'e_coli_colilert'         => 'E. coli (Colilert)',
                                                        'total_coliform_colilert' => 'Total Coliform (Colilert)',
                                                        'enterococci_enterolert'  => 'Enterococci (Enterolert)',
                                                    ] as $tValue => $tLabel)
                                                        <label class="test-label" style="border-color:#bae6fd;">
                                                            <input type="checkbox"
                                                                   value="{{ $tValue }}"
                                                                   x-model="item.tests"
                                                                   :name="`sample_items[${index}][tests][]`"
                                                                   style="accent-color:#0369a1;"/>
                                                            <span style="line-height:1.3;">{{ $tLabel }}</span>
                                                        </label>
                                                    @endforeach
                                                </div>

                                                {{-- Chemical --}}
                                                <p style="font-size:10px; font-weight:700; letter-spacing:.1em; text-transform:uppercase; color:#b8922a; margin:0 0 6px;">Chemical</p>
                                                <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:6px; margin-bottom:12px;">
                                                    @foreach([
                                                        'histamine' => 'Histamine',
                                                        'moisture'  => 'Moisture',
                                                    ] as $tValue => $tLabel)
                                                        <label class="test-label" style="border-color:#fde68a;">
                                                            <input type="checkbox"
                                                                   value="{{ $tValue }}"
                                                                   x-model="item.tests"
                                                                   :name="`sample_items[${index}][tests][]`"
                                                                   style="accent-color:#b8922a;"/>
                                                            <span style="line-height:1.3;">{{ $tLabel }}</span>
                                                        </label>
                                                    @endforeach
                                                </div>

                                                {{-- Physical --}}
                                                <p style="font-size:10px; font-weight:700; letter-spacing:.1em; text-transform:uppercase; color:#7c3aed; margin:0 0 6px;">Physical</p>
                                                <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:6px; margin-bottom:12px;">
                                                    @foreach([
                                                        'temperature'    => 'Temperature',
                                                        'ph'             => 'pH',
                                                        'conductivity'   => 'Conductivity',
                                                        'water_activity' => 'Water Activity',
                                                    ] as $tValue => $tLabel)
                                                        <label class="test-label" style="border-color:#ddd6fe;">
                                                            <input type="checkbox"
                                                                   value="{{ $tValue }}"
                                                                   x-model="item.tests"
                                                                   :name="`sample_items[${index}][tests][]`"
                                                                   style="accent-color:#7c3aed;"/>
                                                            <span style="line-height:1.3;">{{ $tLabel }}</span>
                                                        </label>
                                                    @endforeach
                                                </div>

                                                <div>
                                                    <label style="display:block; font-size:12px; font-weight:600; color:var(--gov-text); margin-bottom:4px;">Other Tests (specify)</label>
                                                    <input type="text"
                                                           :name="`sample_items[${index}][tests_other]`"
                                                           x-model="item.tests_other"
                                                           style="width:100%; border:1px solid #d1d5db; border-radius:3px; padding:7px 11px; font-size:13px; color:#1f2937; box-sizing:border-box;"
                                                           placeholder="Any additional tests not listed above..."/>
                                                </div>
                                            </div>

                                        </div>
                                    </template>

                                    <button type="button" @click="addSample()"
                                            x-show="sampleItems.length < 9"
                                            style="display:inline-flex; align-items:center; gap:6px; font-size:13px; font-weight:600; color:var(--gov-teal); background:none; border:none; cursor:pointer; padding:0;">
                                        <svg style="width:15px;height:15px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                        </svg>
                                        Add another sample
                                        <span style="font-size:11px; font-weight:400; color:var(--gov-muted);" x-text="`(${sampleItems.length}/9)`"></span>
                                    </button>

                                    <p x-show="sampleItems.length >= 9" style="font-size:12px; color:#d97706; font-weight:600; margin:0;">Maximum of 9 samples reached.</p>

                                </div>
                            </div>
                            <div style="display:flex; align-items:center; justify-content:space-between; margin-top:16px;">
                                <button type="button" @click="prevStep()" class="btn-gov btn-gov-back">
                                    <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                                    Back
                                </button>
                                <button type="button" @click="nextStep()" class="btn-gov btn-gov-navy">
                                    Next: Transport
                                    <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </button>
                            </div>
                        </div>

                        {{-- ── Step 3: Transport & Instructions ────────────────── --}}
                        <div x-show="currentStep === 2" x-cloak>
                            <div style="background:#fff; border:1px solid var(--gov-border); border-radius:4px; overflow:hidden;">
                                <div style="padding:18px 24px 16px; border-bottom:2px solid var(--gov-gold);">
                                    <h3 class="step-heading">Transport &amp; Instructions</h3>
                                    <p class="step-sub">Specify how the sample is transported and any handling requirements.</p>
                                </div>
                                <div style="padding:24px; display:flex; flex-direction:column; gap:20px;">

                                    <div style="background:#f0f4f8; border:1px solid #cbd5e1; border-left:4px solid var(--gov-navy); border-radius:4px; padding:16px;">
                                        <x-label value="Priority *"/>
                                        <p style="font-size:11px; color:var(--gov-muted); margin:3px 0 10px;">Routine submissions are processed within standard timeframes. Urgent requests are prioritised and attract additional fees.</p>
                                        <div style="display:flex; gap:20px; flex-wrap:wrap;">
                                            @foreach(['routine' => 'Routine', 'urgent' => 'Urgent'] as $value => $label)
                                                <label style="display:flex; align-items:center; gap:8px; cursor:pointer; font-size:13px; color:var(--gov-text);">
                                                    <input type="radio" name="priority" value="{{ $value }}"
                                                           {{ old('priority', 'routine') === $value ? 'checked' : '' }}
                                                           style="accent-color:var(--gov-navy);"/>
                                                    {{ $label }}
                                                </label>
                                            @endforeach
                                        </div>
                                        <x-input-error for="priority" class="mt-1"/>
                                    </div>

                                    <div style="background:#f0f4f8; border:1px solid #cbd5e1; border-left:4px solid var(--gov-navy); border-radius:4px; padding:16px;"
                                         x-data="{ method: '{{ old('transport_method', 'chilled') }}' }">
                                        <x-label value="Sample Transport Method *"/>
                                        <p style="font-size:11px; color:var(--gov-muted); margin:3px 0 10px;">Select the temperature category, then specify the exact transport method.</p>
                                        <div style="display:flex; gap:20px; flex-wrap:wrap; margin-bottom:12px;">
                                            @foreach(['frozen' => 'Frozen', 'chilled' => 'Chill'] as $value => $label)
                                                <label style="display:flex; align-items:center; gap:8px; cursor:pointer; font-size:13px; font-weight:600; color:var(--gov-text);">
                                                    <input type="radio" name="transport_method" value="{{ $value }}"
                                                           x-model="method"
                                                           {{ old('transport_method', 'chilled') === $value ? 'checked' : '' }}
                                                           style="accent-color:var(--gov-navy);"/>
                                                    {{ $label }}
                                                </label>
                                            @endforeach
                                        </div>
                                        <select name="transport_detail"
                                                style="width:100%; border:1px solid #d1d5db; border-radius:3px; padding:7px 11px; font-size:13px; color:#1f2937; background:#fff; box-sizing:border-box; height:38px;">
                                            <option value="">— Please select transport detail —</option>
                                            <template x-if="method === 'frozen'">
                                                <optgroup label="Frozen Methods">
                                                    <option value="air_freight_frozen"  {{ old('transport_detail') === 'air_freight_frozen'  ? 'selected' : '' }}>Air Freight (Frozen)</option>
                                                    <option value="sea_freight_reefer"  {{ old('transport_detail') === 'sea_freight_reefer'  ? 'selected' : '' }}>Sea Freight (Reefer Container)</option>
                                                    <option value="road_frozen_truck"   {{ old('transport_detail') === 'road_frozen_truck'   ? 'selected' : '' }}>Road Transport (Frozen Truck)</option>
                                                    <option value="other_special"       {{ old('transport_detail') === 'other_special'       ? 'selected' : '' }}>Other / Special Arrangement</option>
                                                </optgroup>
                                            </template>
                                            <template x-if="method === 'chilled'">
                                                <optgroup label="Chilled Methods">
                                                    <option value="air_freight_chilled"   {{ old('transport_detail') === 'air_freight_chilled'   ? 'selected' : '' }}>Air Freight (Chilled)</option>
                                                    <option value="road_chilled_van"      {{ old('transport_detail') === 'road_chilled_van'      ? 'selected' : '' }}>Road Transport (Chilled Van)</option>
                                                    <option value="cooler_box_ice_packs"  {{ old('transport_detail') === 'cooler_box_ice_packs'  ? 'selected' : '' }}>Cooler Box with Ice Packs</option>
                                                    <option value="other_special"         {{ old('transport_detail') === 'other_special'         ? 'selected' : '' }}>Other / Special Arrangement</option>
                                                </optgroup>
                                            </template>
                                        </select>
                                        <x-input-error for="transport_method" class="mt-1"/>
                                        <x-input-error for="transport_detail" class="mt-1"/>
                                    </div>

                                    <div style="background:#f0f4f8; border:1px solid #cbd5e1; border-left:4px solid var(--gov-navy); border-radius:4px; padding:16px;">
                                        <x-label for="special_instructions" value="Additional Notes"/>
                                        <p style="font-size:11px; color:var(--gov-muted); margin:3px 0 10px;">Special handling, storage conditions, or any other instructions for the laboratory team.</p>
                                        <textarea id="special_instructions" name="special_instructions" rows="3"
                                                  style="width:100%; border:1px solid #d1d5db; border-radius:3px; padding:8px 12px; font-size:13px; color:#1f2937; box-sizing:border-box; resize:vertical;"
                                                  placeholder="Any other instructions for the lab team...">{{ old('special_instructions') }}</textarea>
                                        <x-input-error for="special_instructions" class="mt-1"/>
                                    </div>

                                    <div style="background:#f0f4f8; border:1px solid #cbd5e1; border-left:4px solid var(--gov-navy); border-radius:4px; padding:16px;">
                                        <x-label for="results_required_by" value="Results Required By"/>
                                        <p style="font-size:11px; color:var(--gov-muted); margin:3px 0 10px;">Leave blank if there is no specific deadline. Urgent requests attract additional fees.</p>
                                        <x-input id="results_required_by" type="date" name="results_required_by"
                                                 value="{{ old('results_required_by') }}"
                                                 class="block"
                                                 style="width:50%;"
                                                 min="{{ date('Y-m-d', strtotime('+1 day')) }}"/>
                                        <x-input-error for="results_required_by" class="mt-1"/>
                                    </div>

                                </div>
                            </div>
                            <div style="display:flex; align-items:center; justify-content:space-between; margin-top:16px;">
                                <button type="button" @click="prevStep()" class="btn-gov btn-gov-back">
                                    <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                                    Back
                                </button>
                                <button type="button" @click="nextStep()" class="btn-gov btn-gov-navy">
                                    Next: Declaration
                                    <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </button>
                            </div>
                        </div>

                        {{-- ── Step 4: Declaration ──────────────────────────────── --}}
                        <div x-show="currentStep === 3" x-cloak>
                            <div style="background:#fff; border:1px solid var(--gov-border); border-radius:4px; overflow:hidden;">
                                <div style="padding:18px 24px 16px; border-bottom:2px solid var(--gov-gold);">
                                    <h3 class="step-heading">Declaration</h3>
                                    <p class="step-sub">Review your submission and confirm the declaration.</p>
                                </div>
                                <div style="padding:24px; display:flex; flex-direction:column; gap:16px;">

                                    {{-- Submission summary --}}
                                    <div style="background:#f8fafc; border:1px solid var(--gov-border); border-radius:4px; overflow:hidden;">
                                        <div class="summary-row">
                                            <span class="summary-label">Samples</span>
                                            <span class="summary-val"
                                                  x-text="sampleItems.length + (sampleItems.length === 1 ? ' sample' : ' samples') + (sampleItems[0]?.name ? ' — ' + sampleItems[0].name + (sampleItems.length > 1 ? ' + ' + (sampleItems.length - 1) + ' more' : '') : '')"></span>
                                        </div>
                                        <div class="summary-row">
                                            <span class="summary-label">Tests selected</span>
                                            <span class="summary-val"
                                                  x-text="testCount() > 0 ? testCount() + (testCount() === 1 ? ' test' : ' tests') : '—'"></span>
                                        </div>
                                        <div class="summary-row">
                                            <span class="summary-label">Company</span>
                                            <span class="summary-val">{{ $client->company_name }}</span>
                                        </div>
                                        <div class="summary-row">
                                            <span class="summary-label">Submitted by</span>
                                            <span class="summary-val">{{ trim(auth()->user()->first_name . ' ' . auth()->user()->last_name) }}</span>
                                        </div>
                                        <div class="summary-row">
                                            <span class="summary-label">Date</span>
                                            <span class="summary-val">{{ now()->format('d M Y') }}</span>
                                        </div>
                                    </div>

                                    {{-- Warning: samples missing tests --}}
                                    <div x-show="sampleItems.some(s => !s.tests || s.tests.length === 0)"
                                         style="display:flex; align-items:flex-start; gap:10px; background:#fffbeb; border:1px solid #fde68a; border-left:4px solid #f59e0b; border-radius:4px; padding:12px 16px;">
                                        <svg style="width:14px;height:14px;color:#d97706;flex-shrink:0;margin-top:1px;" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                                        </svg>
                                        <p style="font-size:13px; color:#92400e; margin:0;">
                                            <strong>Some samples have no tests selected.</strong>
                                            You can still submit, but go back to the Samples step to add tests if needed.
                                        </p>
                                    </div>

                                    <input type="hidden" name="submitter_name" value="{{ trim(auth()->user()->first_name . ' ' . auth()->user()->last_name) }}"/>

                                    <div style="border-radius:4px; padding:16px; border:1px solid var(--gov-border);"
                                         :style="declarationAccepted ? 'border-color:#86efac; background:#f0fdf4;' : 'border-color:var(--gov-border); background:#f8fafc;'">
                                        <label style="display:flex; align-items:flex-start; gap:12px; cursor:pointer;">
                                            <input type="checkbox" name="declaration_accepted" value="1"
                                                   x-model="declarationAccepted"
                                                   {{ old('declaration_accepted') ? 'checked' : '' }}
                                                   style="margin-top:2px; accent-color:var(--gov-navy); flex-shrink:0;"/>
                                            <span style="font-size:13px; color:var(--gov-text); line-height:1.6;">
                                                I declare that the information provided in this submission is true and accurate
                                                to the best of my knowledge. I understand that providing false information
                                                may result in rejection of this submission and may affect future submissions.
                                            </span>
                                        </label>
                                        <x-input-error for="declaration_accepted" class="mt-2"/>
                                    </div>

                                </div>
                            </div>
                            <div style="display:flex; align-items:center; justify-content:space-between; margin-top:16px; padding-bottom:8px;">
                                <button type="button" @click="prevStep()" class="btn-gov btn-gov-back">
                                    <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                                    Back
                                </button>
                                <button type="submit"
                                        :disabled="!declarationAccepted"
                                        class="btn-gov"
                                        :class="declarationAccepted ? 'btn-gov-green' : 'btn-gov-disabled'">
                                    <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    Submit Sample
                                </button>
                            </div>
                        </div>

                    </div> {{-- end form content --}}
                </div> {{-- end flex wrapper --}}

            </form>

            {{-- Draft saved toast --}}
            <div x-show="draftSaved"
                 x-transition:enter="transition ease-out duration-150"
                 x-transition:enter-start="opacity-0 translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 style="display:none; position:fixed; bottom:24px; right:24px; z-index:50; background:#1a2f4e; color:#fff; font-size:12px; font-weight:600; padding:10px 16px; border-radius:4px; box-shadow:0 4px 16px rgba(0,0,0,.2); display:flex; align-items:center; gap:8px;">
                <svg style="width:13px;height:13px;color:#34d399;flex-shrink:0;" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/>
                </svg>
                Draft saved
            </div>

            </div>{{-- end x-data wrapper --}}
        </div>
    </div>

    @php
        $blankSample = ['name' => '', 'scientific_name' => '', 'type' => '', 'type_notes' => '', 'ref' => 'S-001', 'qty' => '', 'unit' => 'kg', 'tests' => [], 'tests_other' => ''];
        $rawItems = old('sample_items', [$blankSample]);
        $defaultSampleItems = array_map(fn($i) => array_merge($blankSample, $i), $rawItems);
    @endphp
    @push('scripts')
    <script>
        function submissionWizard(checkDraft = true) {
            return {
                currentStep: 0,
                hasDraft: false,
                draftSaved: false,
                declarationAccepted: {{ old('declaration_accepted') ? 'true' : 'false' }},
                _draftTimer: null,
                sampleItems: @json($defaultSampleItems),

                testCount() {
                    return this.sampleItems.reduce((sum, s) => sum + (Array.isArray(s.tests) ? s.tests.length : 0), 0);
                },

                init() {
                    if (checkDraft) {
                        this.hasDraft = !!localStorage.getItem('kstl_submission_draft');
                    }
                    const form = document.getElementById('submission-form');
                    form.addEventListener('input',  () => this.saveDraft());
                    form.addEventListener('change', () => this.saveDraft());
                },

                addSample() {
                    if (this.sampleItems.length < 9) {
                        const nextRef = 'S-' + String(this.sampleItems.length + 1).padStart(3, '0');
                        this.sampleItems.push({ name: '', scientific_name: '', type: '', type_notes: '', ref: nextRef, qty: '', unit: 'kg', tests: [], tests_other: '' });
                        this.$nextTick(() => this.saveDraft());
                    }
                },

                removeSample(index) {
                    if (this.sampleItems.length > 1) {
                        this.sampleItems.splice(index, 1);
                        this.$nextTick(() => this.saveDraft());
                    }
                },

                saveDraft() {
                    const form = document.getElementById('submission-form');
                    const data = { _step: this.currentStep, _sampleItems: this.sampleItems };

                    form.querySelectorAll('[name]').forEach(el => {
                        if (el.name === '_token' || el.name === 'submitter_name') return;
                        if (el.name.startsWith('sample_items[')) return;
                        if (el.type === 'checkbox') {
                            if (!Array.isArray(data[el.name])) data[el.name] = [];
                            if (el.checked) data[el.name].push(el.value);
                        } else if (el.type === 'radio') {
                            if (el.checked) data[el.name] = el.value;
                        } else {
                            data[el.name] = el.value;
                        }
                    });

                    localStorage.setItem('kstl_submission_draft', JSON.stringify(data));
                    this.draftSaved = true;
                    clearTimeout(this._draftTimer);
                    this._draftTimer = setTimeout(() => { this.draftSaved = false; }, 2000);
                },

                resumeDraft() {
                    const raw = localStorage.getItem('kstl_submission_draft');
                    if (!raw) return;
                    const data = JSON.parse(raw);
                    const form = document.getElementById('submission-form');

                    if (data._sampleItems && Array.isArray(data._sampleItems) && data._sampleItems.length) {
                        this.sampleItems = data._sampleItems;
                    }

                    form.querySelectorAll('[name]').forEach(el => {
                        const name = el.name;
                        if (name === '_token' || name === 'submitter_name') return;
                        if (name.startsWith('sample_items[')) return;
                        if (!(name in data)) return;
                        const value = data[name];

                        if (el.type === 'checkbox') {
                            const vals = Array.isArray(value) ? value : (value ? [value] : []);
                            el.checked = vals.includes(el.value);
                            if (name === 'declaration_accepted') this.declarationAccepted = el.checked;
                        } else if (el.type === 'radio') {
                            if (el.value === value) {
                                el.checked = true;
                                el.dispatchEvent(new Event('change', { bubbles: true }));
                            }
                        } else if (name !== 'transport_detail') {
                            el.value = value;
                        }
                    });

                    if (data.transport_detail) {
                        const td = data.transport_detail;
                        setTimeout(() => {
                            const sel = form.querySelector('[name="transport_detail"]');
                            if (sel) sel.value = td;
                        }, 150);
                    }

                    if (data._step !== undefined) {
                        this.currentStep = parseInt(data._step, 10);
                    }

                    this.hasDraft = false;
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                },

                clearDraft() {
                    localStorage.removeItem('kstl_submission_draft');
                    this.hasDraft = false;
                },

                nextStep() {
                    if (this.currentStep < 3) {
                        this.currentStep++;
                        this.saveDraft();
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    }
                },

                prevStep() {
                    if (this.currentStep > 0) {
                        this.currentStep--;
                        this.saveDraft();
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    }
                },
            }
        }
    </script>
    @endpush

</x-app-layout>
