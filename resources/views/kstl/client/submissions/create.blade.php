{{-- resources/views/kstl/client/submissions/create.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('client.submissions.index') }}"
               class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                New Sample Submission
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            {{-- Validation Errors --}}
            <x-validation-errors class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4"/>

            {{-- Service Agreement Check --}}
            @if($client && !$client->service_agreement_signed_at)
                <div class="mb-6 bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-lg flex items-center gap-3">
                    <svg class="w-5 h-5 text-yellow-400 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-sm text-yellow-800">
                        <span class="font-medium">Action:</span>
                        You must sign the service agreement before submitting samples.
                    </p>
                </div>
            @endif

            <div x-data="submissionWizard({{ $errors->isNotEmpty() ? 'false' : 'true' }})">

            {{-- Draft restore banner --}}
            <div x-show="hasDraft"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 class="mb-6 bg-blue-50 border border-blue-200 rounded-xl px-5 py-4 flex items-center justify-between gap-4"
                 style="display:none">
                <div class="flex items-center gap-3 min-w-0">
                    <svg class="w-5 h-5 text-blue-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                    </svg>
                    <div>
                        <p class="text-sm font-semibold text-blue-900">Unsaved draft found</p>
                        <p class="text-xs text-blue-600 mt-0.5">You started filling out this form earlier. Resume where you left off?</p>
                    </div>
                </div>
                <div class="flex items-center gap-2 shrink-0">
                    <button type="button" @click="clearDraft()"
                            class="text-xs text-gray-500 hover:text-gray-700 px-3 py-1.5 rounded-lg hover:bg-gray-100 transition">
                        Discard
                    </button>
                    <button type="button" @click="resumeDraft()"
                            class="text-xs font-medium text-white bg-blue-600 hover:bg-blue-700 px-4 py-1.5 rounded-lg transition">
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

                <div class="flex gap-8 items-start">

                    {{-- ── Vertical Step Sidebar ──────────────────────────────── --}}
                    <div class="hidden md:block w-52 shrink-0">
                        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                            <div class="px-4 py-3 border-b border-gray-100">
                                <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Progress</p>
                            </div>
                            <nav class="p-2">
                                @foreach($steps as $i => $label)
                                    <div class="relative">
                                        {{-- Connector line between steps --}}
                                        @if($i < count($steps) - 1)
                                            <div class="absolute left-[19px] top-10 w-0.5 h-4 z-0"
                                                 :class="currentStep > {{ $i }} ? 'bg-green-300' : 'bg-gray-200'"></div>
                                        @endif

                                        <button type="button"
                                                @click="if(currentStep > {{ $i }}) currentStep = {{ $i }}"
                                                class="w-full flex items-start gap-3 px-3 py-2.5 rounded-lg text-left transition relative z-10 mb-1"
                                                :class="currentStep === {{ $i }} ? 'bg-blue-50' : (currentStep > {{ $i }} ? 'hover:bg-gray-50 cursor-pointer' : 'cursor-default opacity-60')">
                                            {{-- Circle --}}
                                            <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-medium shrink-0 mt-0.5 transition-colors"
                                                 :class="currentStep > {{ $i }} ? 'bg-green-500 text-white' : (currentStep === {{ $i }} ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-400')">
                                                <template x-if="currentStep > {{ $i }}">
                                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                    </svg>
                                                </template>
                                                <template x-if="currentStep <= {{ $i }}">
                                                    <span>{{ $i + 1 }}</span>
                                                </template>
                                            </div>
                                            {{-- Text --}}
                                            <div class="min-w-0">
                                                <p class="text-sm font-medium leading-tight"
                                                   :class="currentStep === {{ $i }} ? 'text-blue-700' : (currentStep > {{ $i }} ? 'text-green-700' : 'text-gray-500')">
                                                    {{ $label }}
                                                </p>
                                                <p class="text-xs text-gray-400 mt-0.5 leading-tight">{{ $stepSubs[$i] }}</p>
                                            </div>
                                        </button>
                                    </div>
                                @endforeach
                            </nav>
                        </div>
                    </div>

                    {{-- ── Form Content ──────────────────────────────────────── --}}
                    <div class="flex-1 min-w-0">

                        {{-- ── Step 1: Collection Info ─────────────────────────── --}}
                        <div x-show="currentStep === 0" x-cloak>
                            <div class="bg-white shadow rounded-xl overflow-hidden">
                                <div class="px-6 py-4 border-b border-gray-100">
                                    <h3 class="text-base font-medium text-gray-900">Collection Info</h3>
                                    <p class="text-sm text-gray-500 mt-0.5">When and where the samples were collected, plus any notes.</p>
                                </div>
                                <div class="px-6 py-5 space-y-5">

                                    {{-- Client Reference --}}
                                    <div class="bg-blue-50 border border-blue-100 rounded-lg p-4">
                                        <x-label for="client_reference" value="Your Reference Number"/>
                                        <p class="text-xs text-gray-500 mt-0.5 mb-2">
                                            Enter your organisation's internal reference for this submission (e.g. MFOR-2026-001). This will appear on your Certificate of Analysis.
                                        </p>
                                        <x-input id="client_reference" type="text" name="client_reference"
                                                 value="{{ old('client_reference') }}"
                                                 class="mt-1 block w-full font-mono"
                                                 placeholder="e.g. MFOR-2026-001"
                                                 autofocus/>
                                        <x-input-error for="client_reference" class="mt-1"/>
                                    </div>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <x-label for="collected_at" value="Collection Date *"/>
                                            <x-input id="collected_at" type="date" name="collected_at"
                                                     value="{{ old('collected_at') }}"
                                                     class="mt-1 block w-full"
                                                     max="{{ date('Y-m-d') }}"
                                                     autofocus/>
                                            <x-input-error for="collected_at" class="mt-1"/>
                                        </div>
                                        <div>
                                            <x-label for="delivered_at" value="Delivery Date"/>
                                            <x-input id="delivered_at" type="date" name="delivered_at"
                                                     value="{{ old('delivered_at') }}"
                                                     class="mt-1 block w-full"/>
                                            <x-input-error for="delivered_at" class="mt-1"/>
                                        </div>
                                    </div>

                                    <div>
                                        <x-label for="collection_location" value="Collection Location"/>
                                        <x-input id="collection_location" type="text" name="collection_location"
                                                 value="{{ old('collection_location') }}"
                                                 class="mt-1 block w-full"
                                                 placeholder="e.g. South Tarawa Lagoon"/>
                                        <x-input-error for="collection_location" class="mt-1"/>
                                    </div>

                                    <div>
                                        <x-label for="sample_description" value="Notes (optional)"/>
                                        <textarea id="sample_description" name="sample_description" rows="2"
                                                  class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm"
                                                  placeholder="Any general notes about the collection or samples...">{{ old('sample_description') }}</textarea>
                                        <x-input-error for="sample_description" class="mt-1"/>
                                    </div>

                                </div>
                            </div>
                            <div class="flex justify-end mt-5">
                                <button type="button" @click="nextStep()"
                                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">
                                    Next: Samples
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </button>
                            </div>
                        </div>

                        {{-- ── Step 2: Samples ─────────────────────────────────── --}}
                        <div x-show="currentStep === 1" x-cloak>
                            <div class="bg-white shadow rounded-xl overflow-hidden">
                                <div class="px-6 py-4 border-b border-gray-100">
                                    <h3 class="text-base font-medium text-gray-900">Samples</h3>
                                    <p class="text-sm text-gray-500 mt-0.5">Add each sample — name, type, reference number, and quantity. Up to 9 samples.</p>
                                </div>
                                <div class="px-6 py-5 space-y-4">

                                    <template x-for="(item, index) in sampleItems" :key="index">
                                        <div class="border border-gray-200 rounded-lg p-4 space-y-3">

                                            {{-- Card header --}}
                                            <div class="flex items-center justify-between">
                                                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide"
                                                      x-text="`Sample ${index + 1}`"></span>
                                                <button type="button" @click="removeSample(index)"
                                                        x-show="sampleItems.length > 1"
                                                        class="text-xs text-red-400 hover:text-red-600 flex items-center gap-1 transition">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                    </svg>
                                                    Remove
                                                </button>
                                            </div>

                                            {{-- Name row --}}
                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                                <div>
                                                    <label class="block text-xs font-medium text-gray-700 mb-1">Common Name *</label>
                                                    <input type="text"
                                                           :name="`sample_items[${index}][name]`"
                                                           x-model="item.name"
                                                           class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm"
                                                           placeholder="e.g. Yellowfin Tuna"/>
                                                </div>
                                                <div>
                                                    <label class="block text-xs font-medium text-gray-700 mb-1">Scientific Name</label>
                                                    <input type="text"
                                                           :name="`sample_items[${index}][scientific_name]`"
                                                           x-model="item.scientific_name"
                                                           class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm"
                                                           placeholder="e.g. Thunnus albacares"/>
                                                </div>
                                            </div>

                                            {{-- Type / Ref / Qty / Unit row --}}
                                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                                                <div>
                                                    <label class="block text-xs font-medium text-gray-700 mb-1">Sample Type</label>
                                                    <select :name="`sample_items[${index}][type]`"
                                                            x-model="item.type"
                                                            class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm">
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
                                                    <label class="block text-xs font-medium text-gray-700 mb-1">Reference #</label>
                                                    <input type="text"
                                                           :name="`sample_items[${index}][ref]`"
                                                           x-model="item.ref"
                                                           class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm"
                                                           placeholder="e.g. S-001"/>
                                                </div>
                                                <div>
                                                    <label class="block text-xs font-medium text-gray-700 mb-1">Quantity</label>
                                                    <input type="number"
                                                           :name="`sample_items[${index}][qty]`"
                                                           x-model="item.qty"
                                                           class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm"
                                                           placeholder="0" min="0" step="0.01"/>
                                                </div>
                                                <div>
                                                    <label class="block text-xs font-medium text-gray-700 mb-1">Unit</label>
                                                    <select :name="`sample_items[${index}][unit]`"
                                                            x-model="item.unit"
                                                            class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm">
                                                        <option value="g">g</option>
                                                        <option value="kg">kg</option>
                                                        <option value="ml">ml</option>
                                                        <option value="L">L</option>
                                                    </select>
                                                </div>
                                            </div>

                                            {{-- "Other" type description --}}
                                            <div x-show="item.type === 'other'" x-cloak class="mt-1">
                                                <label class="block text-xs font-medium text-gray-700 mb-1">Other — please describe *</label>
                                                <input type="text"
                                                       :name="`sample_items[${index}][type_notes]`"
                                                       x-model="item.type_notes"
                                                       class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm"
                                                       placeholder="Describe the sample type…"/>
                                            </div>

                                            {{-- Tests for this sample --}}
                                            <div class="border-t border-gray-100 pt-3 mt-1">
                                                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Tests Requested for This Sample</p>

                                                <p class="text-xs font-medium text-gray-500 mb-1.5">Microbiological</p>
                                                <div class="grid grid-cols-2 sm:grid-cols-3 gap-1.5 mb-3">
                                                    @foreach([
                                                        'total_coliforms' => 'Total Coliforms',
                                                        'e_coli'          => 'E. coli',
                                                        'enterococci'     => 'Enterococci',
                                                        'faecal_coliforms' => 'Faecal Coliforms',
                                                        'yeast_mold'      => 'Yeast & Mould',
                                                        'apc'             => 'APC (Aerobic Plate Count)',
                                                        'e_coli_coliform' => 'E. coli & Coliform',
                                                        'staph_aureus'    => 'Staphylococcus aureus',
                                                        'salmonella_spp'  => 'Salmonella species',
                                                        'listeria_mono'   => 'Listeria monocytogenes',
                                                        'listeria_spp'    => 'Listeria species',
                                                    ] as $tValue => $tLabel)
                                                        <label class="flex items-center gap-2 p-2 border border-gray-100 rounded-lg hover:bg-purple-50 cursor-pointer transition">
                                                            <input type="checkbox"
                                                                   value="{{ $tValue }}"
                                                                   x-model="item.tests"
                                                                   :name="`sample_items[${index}][tests][]`"
                                                                   class="rounded border-gray-300 text-purple-600 shadow-sm focus:ring-purple-500 shrink-0"/>
                                                            <span class="text-xs text-gray-700 leading-tight">{{ $tLabel }}</span>
                                                        </label>
                                                    @endforeach
                                                </div>

                                                <p class="text-xs font-medium text-gray-500 mb-1.5">Water Testing</p>
                                                <div class="grid grid-cols-2 sm:grid-cols-3 gap-1.5 mb-3">
                                                    @foreach([
                                                        'e_coli_colilert'        => 'E. coli (Colilert)',
                                                        'enterococci_enterolert' => 'Enterococci (Enterolert)',
                                                    ] as $tValue => $tLabel)
                                                        <label class="flex items-center gap-2 p-2 border border-gray-100 rounded-lg hover:bg-cyan-50 cursor-pointer transition">
                                                            <input type="checkbox"
                                                                   value="{{ $tValue }}"
                                                                   x-model="item.tests"
                                                                   :name="`sample_items[${index}][tests][]`"
                                                                   class="rounded border-gray-300 text-cyan-600 shadow-sm focus:ring-cyan-500 shrink-0"/>
                                                            <span class="text-xs text-gray-700 leading-tight">{{ $tLabel }}</span>
                                                        </label>
                                                    @endforeach
                                                </div>

                                                <p class="text-xs font-medium text-gray-500 mb-1.5">Chemical</p>
                                                <div class="grid grid-cols-2 sm:grid-cols-3 gap-1.5 mb-3">
                                                    @foreach([
                                                        'moisture'       => 'Moisture Content',
                                                        'histamine'      => 'ELISA Histamine Rapid Kit',
                                                        'ph'             => 'pH',
                                                        'conductivity'   => 'Conductivity',
                                                        'water_activity' => 'Water Activity',
                                                    ] as $tValue => $tLabel)
                                                        <label class="flex items-center gap-2 p-2 border border-gray-100 rounded-lg hover:bg-blue-50 cursor-pointer transition">
                                                            <input type="checkbox"
                                                                   value="{{ $tValue }}"
                                                                   x-model="item.tests"
                                                                   :name="`sample_items[${index}][tests][]`"
                                                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500 shrink-0"/>
                                                            <span class="text-xs text-gray-700 leading-tight">{{ $tLabel }}</span>
                                                        </label>
                                                    @endforeach
                                                </div>

                                                <div>
                                                    <label class="block text-xs font-medium text-gray-700 mb-1">Other Tests (specify)</label>
                                                    <input type="text"
                                                           :name="`sample_items[${index}][tests_other]`"
                                                           x-model="item.tests_other"
                                                           class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm"
                                                           placeholder="Any additional tests not listed above..."/>
                                                </div>
                                            </div>

                                        </div>
                                    </template>

                                    <button type="button" @click="addSample()"
                                            x-show="sampleItems.length < 9"
                                            class="inline-flex items-center gap-1.5 text-sm text-blue-600 hover:text-blue-800 font-medium transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                        </svg>
                                        Add another sample
                                        <span class="text-xs font-normal text-gray-400 ml-1" x-text="`(${sampleItems.length}/9)`"></span>
                                    </button>

                                    <p x-show="sampleItems.length >= 9" class="text-xs text-amber-600 font-medium">Maximum of 9 samples reached.</p>

                                </div>
                            </div>
                            <div class="flex items-center justify-between mt-5">
                                <button type="button" @click="prevStep()"
                                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                                    Back
                                </button>
                                <button type="button" @click="nextStep()"
                                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">
                                    Next: Transport
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </button>
                            </div>
                        </div>

                        {{-- ── Step 3: Transport & Instructions ────────────────── --}}
                        <div x-show="currentStep === 2" x-cloak>
                            <div class="bg-white shadow rounded-xl overflow-hidden">
                                <div class="px-6 py-4 border-b border-gray-100">
                                    <h3 class="text-base font-medium text-gray-900">Transport &amp; Instructions</h3>
                                    <p class="text-sm text-gray-500 mt-0.5">Specify how the sample is transported and any handling requirements.</p>
                                </div>
                                <div class="px-6 py-5 space-y-5">

                                    <div>
                                        <x-label value="Priority *"/>
                                        <div class="mt-2 flex gap-4 flex-wrap">
                                            @foreach(['routine' => 'Routine', 'urgent' => 'Urgent'] as $value => $label)
                                                <label class="flex items-center gap-2 cursor-pointer">
                                                    <input type="radio" name="priority" value="{{ $value }}"
                                                           {{ old('priority', 'routine') === $value ? 'checked' : '' }}
                                                           class="border-gray-300 text-indigo-600 focus:ring-indigo-500"/>
                                                    <span class="text-sm text-gray-700">{{ $label }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                        <x-input-error for="priority" class="mt-1"/>
                                    </div>

                                    <div x-data="{ method: '{{ old('transport_method', 'chilled') }}' }">
                                        <x-label value="Sample Transport Method *"/>
                                        <p class="text-xs text-gray-400 mt-0.5 mb-2">Select the temperature category, then specify the exact transport method.</p>
                                        <div class="flex gap-4 flex-wrap mb-3">
                                            @foreach(['frozen' => 'Frozen', 'chilled' => 'Chill'] as $value => $label)
                                                <label class="flex items-center gap-2 cursor-pointer">
                                                    <input type="radio" name="transport_method" value="{{ $value }}"
                                                           x-model="method"
                                                           {{ old('transport_method', 'chilled') === $value ? 'checked' : '' }}
                                                           class="border-gray-300 text-indigo-600 focus:ring-indigo-500"/>
                                                    <span class="text-sm font-medium text-gray-700">{{ $label }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                        <select name="transport_detail"
                                                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm">
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

                                    <div>
                                        <x-label for="special_instructions" value="Additional Notes"/>
                                        <textarea id="special_instructions" name="special_instructions" rows="3"
                                                  class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm"
                                                  placeholder="Any other instructions for the lab team...">{{ old('special_instructions') }}</textarea>
                                        <x-input-error for="special_instructions" class="mt-1"/>
                                    </div>

                                    <div>
                                        <x-label for="results_required_by" value="Results Required By"/>
                                        <x-input id="results_required_by" type="date" name="results_required_by"
                                                 value="{{ old('results_required_by') }}"
                                                 class="mt-1 block w-full sm:w-1/2"
                                                 min="{{ date('Y-m-d', strtotime('+1 day')) }}"/>
                                        <p class="mt-1 text-xs text-gray-400">Leave blank if no specific deadline. Urgent requests attract additional fees.</p>
                                        <x-input-error for="results_required_by" class="mt-1"/>
                                    </div>

                                </div>
                            </div>
                            <div class="flex items-center justify-between mt-5">
                                <button type="button" @click="prevStep()"
                                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                                    Back
                                </button>
                                <button type="button" @click="nextStep()"
                                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">
                                    Next: Declaration
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </button>
                            </div>
                        </div>

                        {{-- ── Step 4: Declaration ──────────────────────────────── --}}
                        <div x-show="currentStep === 3" x-cloak>
                            <div class="bg-white shadow rounded-xl overflow-hidden">
                                <div class="px-6 py-4 border-b border-gray-100">
                                    <h3 class="text-base font-medium text-gray-900">Declaration</h3>
                                    <p class="text-sm text-gray-500 mt-0.5">Review your submission and confirm the declaration.</p>
                                </div>
                                <div class="px-6 py-5 space-y-5">

                                    {{-- Submission summary --}}
                                    <div class="bg-gray-50 rounded-lg border border-gray-100 divide-y divide-gray-100 text-sm">
                                        <div class="px-4 py-3 flex justify-between gap-4">
                                            <span class="text-gray-500">Samples</span>
                                            <span class="font-medium text-gray-800 text-right"
                                                  x-text="sampleItems.length + (sampleItems.length === 1 ? ' sample' : ' samples') + (sampleItems[0]?.name ? ' — ' + sampleItems[0].name + (sampleItems.length > 1 ? ' + ' + (sampleItems.length - 1) + ' more' : '') : '')"></span>
                                        </div>
                                        <div class="px-4 py-3 flex justify-between gap-4">
                                            <span class="text-gray-500">Tests selected</span>
                                            <span class="font-medium text-gray-800 text-right"
                                                  x-text="testCount() > 0 ? testCount() + (testCount() === 1 ? ' test' : ' tests') : '—'"></span>
                                        </div>
                                        <div class="px-4 py-3 flex justify-between gap-4">
                                            <span class="text-gray-500">Company</span>
                                            <span class="font-medium text-gray-800 text-right">{{ $client->company_name }}</span>
                                        </div>
                                        <div class="px-4 py-3 flex justify-between gap-4">
                                            <span class="text-gray-500">Submitted by</span>
                                            <span class="font-medium text-gray-800 text-right">{{ trim(auth()->user()->first_name . ' ' . auth()->user()->last_name) }}</span>
                                        </div>
                                        <div class="px-4 py-3 flex justify-between gap-4">
                                            <span class="text-gray-500">Date</span>
                                            <span class="font-medium text-gray-800 text-right">{{ now()->format('d M Y') }}</span>
                                        </div>
                                    </div>

                                    {{-- Warning: samples missing tests --}}
                                    <div x-show="sampleItems.some(s => !s.tests || s.tests.length === 0)"
                                         class="flex items-start gap-2.5 bg-amber-50 border border-amber-200 rounded-lg px-4 py-3">
                                        <svg class="w-4 h-4 text-amber-500 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                                        </svg>
                                        <p class="text-sm text-amber-800">
                                            <span class="font-medium">Some samples have no tests selected.</span>
                                            You can still submit, but go back to the Samples step to add tests if needed.
                                        </p>
                                    </div>

                                    <input type="hidden" name="submitter_name" value="{{ trim(auth()->user()->first_name . ' ' . auth()->user()->last_name) }}"/>

                                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-100"
                                         :class="declarationAccepted ? 'border-green-200 bg-green-50' : 'border-gray-100 bg-gray-50'">
                                        <label class="flex items-start gap-3 cursor-pointer">
                                            <input type="checkbox" name="declaration_accepted" value="1"
                                                   x-model="declarationAccepted"
                                                   {{ old('declaration_accepted') ? 'checked' : '' }}
                                                   class="mt-0.5 rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"/>
                                            <span class="text-sm text-gray-700 leading-relaxed">
                                                I declare that the information provided in this submission is true and accurate
                                                to the best of my knowledge. I understand that providing false information
                                                may result in rejection of this submission and may affect future submissions.
                                            </span>
                                        </label>
                                        <x-input-error for="declaration_accepted" class="mt-2"/>
                                    </div>

                                </div>
                            </div>
                            <div class="flex items-center justify-between mt-5 pb-8">
                                <button type="button" @click="prevStep()"
                                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                                    Back
                                </button>
                                <button type="submit"
                                        :disabled="!declarationAccepted"
                                        :class="declarationAccepted
                                            ? 'bg-green-600 hover:bg-green-700 cursor-pointer'
                                            : 'bg-gray-300 cursor-not-allowed'"
                                        class="inline-flex items-center gap-2 px-6 py-2.5 text-white text-sm font-medium rounded-lg transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
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
                 class="fixed bottom-6 right-6 z-50 bg-gray-900 text-white text-xs font-medium px-4 py-2.5 rounded-xl shadow-xl flex items-center gap-2"
                 style="display:none">
                <svg class="w-3.5 h-3.5 text-green-400 shrink-0" fill="currentColor" viewBox="0 0 20 20">
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
                                // Trigger Alpine reactivity for transport method toggle
                                el.dispatchEvent(new Event('change', { bubbles: true }));
                            }
                        } else if (name !== 'transport_detail') {
                            el.value = value;
                        }
                    });

                    // transport_detail select is inside x-if so needs a tick after Alpine re-renders
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