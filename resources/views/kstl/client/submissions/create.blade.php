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

            <form method="POST" action="{{ route('client.submissions.store') }}"
                  x-data="submissionWizard()"
                  >
                @csrf

                @php $steps = ['Sample Info', 'Tests', 'Transport', 'Declaration']; @endphp
                @php $stepSubs = ['Names, type & quantity', 'Micro & chemical tests', 'Method & priority', 'Review & submit']; @endphp

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

                        {{-- ── Step 1: Sample Information ──────────────────────── --}}
                        <div x-show="currentStep === 0" x-cloak>
                            <div class="bg-white shadow rounded-xl overflow-hidden">
                                <div class="px-6 py-4 border-b border-gray-100">
                                    <h3 class="text-base font-medium text-gray-900">Sample Information</h3>
                                    <p class="text-sm text-gray-500 mt-0.5">Provide details about the sample being submitted.</p>
                                </div>
                                <div class="px-6 py-5 space-y-5">

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <x-label for="sample_name" value="Common Name *"/>
                                            <x-input id="sample_name" type="text" name="sample_name"
                                                     value="{{ old('sample_name') }}"
                                                     class="mt-1 block w-full"
                                                     placeholder="e.g. Yellowfin Tuna"
                                                     autofocus/>
                                            <x-input-error for="sample_name" class="mt-1"/>
                                        </div>
                                        <div>
                                            <x-label for="scientific_name" value="Scientific Name"/>
                                            <x-input id="scientific_name" type="text" name="scientific_name"
                                                     value="{{ old('scientific_name') }}"
                                                     class="mt-1 block w-full"
                                                     placeholder="e.g. Thunnus albacares"/>
                                            <x-input-error for="scientific_name" class="mt-1"/>
                                        </div>
                                    </div>

                                    <div>
                                        <x-label for="sample_description" value="Sample Description"/>
                                        <textarea id="sample_description" name="sample_description" rows="2"
                                                  class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm"
                                                  placeholder="Describe the sample — condition, preservation method...">{{ old('sample_description') }}</textarea>
                                        <x-input-error for="sample_description" class="mt-1"/>
                                    </div>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <x-label for="sample_type" value="Sample Type *"/>
                                            <select id="sample_type" name="sample_type"
                                                    class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm"
                                                   >
                                                <option value="">— Select type —</option>
                                                <option value="fish"      {{ old('sample_type') === 'fish'      ? 'selected' : '' }}>Fish</option>
                                                <option value="shellfish" {{ old('sample_type') === 'shellfish' ? 'selected' : '' }}>Shellfish</option>
                                                <option value="seaweed"   {{ old('sample_type') === 'seaweed'   ? 'selected' : '' }}>Seaweed</option>
                                                <option value="water"     {{ old('sample_type') === 'water'     ? 'selected' : '' }}>Water</option>
                                                <option value="sediment"  {{ old('sample_type') === 'sediment'  ? 'selected' : '' }}>Sediment</option>
                                                <option value="other"     {{ old('sample_type') === 'other'     ? 'selected' : '' }}>Other</option>
                                            </select>
                                            <x-input-error for="sample_type" class="mt-1"/>
                                        </div>
                                        <div>
                                            <x-label for="sample_quantity" value="Quantity / Weight *"/>
                                            <div class="mt-1 flex rounded-md shadow-sm">
                                                <x-input id="sample_quantity" type="number" name="sample_quantity"
                                                         value="{{ old('sample_quantity') }}"
                                                         class="block w-full rounded-r-none"
                                                         placeholder="0" min="0" step="0.01"/>
                                                <select name="sample_quantity_unit"
                                                        class="border-l-0 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-r-md shadow-sm text-sm px-3">
                                                    <option value="g"  {{ old('sample_quantity_unit') === 'g'  ? 'selected' : '' }}>g</option>
                                                    <option value="kg" {{ old('sample_quantity_unit', 'kg') === 'kg' ? 'selected' : '' }}>kg</option>
                                                    <option value="ml" {{ old('sample_quantity_unit') === 'ml' ? 'selected' : '' }}>ml</option>
                                                    <option value="L"  {{ old('sample_quantity_unit') === 'L'  ? 'selected' : '' }}>L</option>
                                                </select>
                                            </div>
                                            <x-input-error for="sample_quantity" class="mt-1"/>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <x-label for="collected_at" value="Collection Date *"/>
                                            <x-input id="collected_at" type="date" name="collected_at"
                                                     value="{{ old('collected_at') }}"
                                                     class="mt-1 block w-full"
                                                     max="{{ date('Y-m-d') }}"/>
                                            <x-input-error for="collected_at" class="mt-1"/>
                                        </div>
                                        <div>
                                            <x-label for="collection_location" value="Collection Location"/>
                                            <x-input id="collection_location" type="text" name="collection_location"
                                                     value="{{ old('collection_location') }}"
                                                     class="mt-1 block w-full"
                                                     placeholder="e.g. South Tarawa Lagoon"/>
                                            <x-input-error for="collection_location" class="mt-1"/>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="flex justify-end mt-5">
                                <button type="button" @click="nextStep()"
                                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">
                                    Next: Tests Requested
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </button>
                            </div>
                        </div>

                        {{-- ── Step 2: Tests Requested ─────────────────────────── --}}
                        <div x-show="currentStep === 1" x-cloak>
                            <div class="bg-white shadow rounded-xl overflow-hidden">
                                <div class="px-6 py-4 border-b border-gray-100">
                                    <h3 class="text-base font-medium text-gray-900">Tests Requested</h3>
                                    <p class="text-sm text-gray-500 mt-0.5">Select all tests you require. The lab will confirm feasibility on receipt.</p>
                                </div>
                                <div class="px-6 py-5 space-y-5">

                                    <div>
                                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Microbiological Analysis</p>
                                        <p class="text-xs text-gray-400 mb-3">Select the microbiological tests for your sample type.</p>

                                        <p class="text-xs font-medium text-gray-600 mb-2">1. Water Samples (Colilert &amp; Enterolert)</p>
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 mb-4">
                                            @foreach([
                                                'total_coliforms' => 'Total Coliforms',
                                                'e_coli'          => 'E. coli',
                                                'enterococci'     => 'Enterococci &amp; Faecal Coliforms',
                                            ] as $value => $label)
                                                <label class="flex items-center gap-3 p-3 border border-gray-100 rounded-lg hover:bg-purple-50 cursor-pointer transition">
                                                    <input type="checkbox" name="tests_requested[]" value="{{ $value }}"
                                                           {{ in_array($value, old('tests_requested', [])) ? 'checked' : '' }}
                                                           class="rounded border-gray-300 text-purple-600 shadow-sm focus:ring-purple-500"/>
                                                    <span class="text-sm text-gray-700">{!! $label !!}</span>
                                                </label>
                                            @endforeach
                                        </div>

                                        <p class="text-xs font-medium text-gray-600 mb-2">2. Fish and Fishery Samples (Petrifilm)</p>
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                            @foreach([
                                                'yeast_mold'      => 'Yeast &amp; Mold',
                                                'apc'             => 'APC (Aerobic Plate Count)',
                                                'e_coli_coliform' => '<em>E. coli</em> &amp; Coliform',
                                                'staph_aureus'    => '<em>Staph. aureus</em>',
                                            ] as $value => $label)
                                                <label class="flex items-center gap-3 p-3 border border-gray-100 rounded-lg hover:bg-purple-50 cursor-pointer transition">
                                                    <input type="checkbox" name="tests_requested[]" value="{{ $value }}"
                                                           {{ in_array($value, old('tests_requested', [])) ? 'checked' : '' }}
                                                           class="rounded border-gray-300 text-purple-600 shadow-sm focus:ring-purple-500"/>
                                                    <span class="text-sm text-gray-700">{!! $label !!}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>

                                    <hr class="border-gray-100"/>

                                    <div>
                                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Chemical Analysis</p>
                                        <p class="text-xs text-gray-400 mb-3">Select the chemical tests.</p>
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                            @foreach([
                                                'histamine'      => 'Histamine — Rapid Kit',
                                                'moisture'       => 'Moisture',
                                                'ph'             => 'pH',
                                                'conductivity'   => 'Conductivity',
                                                'water_activity' => 'Water Activity',
                                            ] as $value => $label)
                                                <label class="flex items-center gap-3 p-3 border border-gray-100 rounded-lg hover:bg-blue-50 cursor-pointer transition">
                                                    <input type="checkbox" name="tests_requested[]" value="{{ $value }}"
                                                           {{ in_array($value, old('tests_requested', [])) ? 'checked' : '' }}
                                                           class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500"/>
                                                    <span class="text-sm text-gray-700">{{ $label }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>

                                    <div>
                                        <x-label for="tests_other" value="Other Tests (specify)"/>
                                        <textarea id="tests_other" name="tests_other" rows="2"
                                                  class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm"
                                                  placeholder="List any additional tests not shown above...">{{ old('tests_other') }}</textarea>
                                    </div>

                                    <x-input-error for="tests_requested" class="mt-1"/>

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
                                            @foreach(['routine' => 'Routine', 'urgent' => 'Urgent', 'emergency' => 'Emergency'] as $value => $label)
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
                                            @foreach(['frozen' => 'Frozen', 'chilled' => 'Chill', 'fresh' => 'Fresh'] as $value => $label)
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
                                                    <option value="courier_frozen"      {{ old('transport_detail') === 'courier_frozen'      ? 'selected' : '' }}>Courier / Express (e.g. DHL, FedEx)</option>
                                                    <option value="other_special"       {{ old('transport_detail') === 'other_special'       ? 'selected' : '' }}>Other / Special Arrangement</option>
                                                </optgroup>
                                            </template>
                                            <template x-if="method === 'chilled'">
                                                <optgroup label="Chilled Methods">
                                                    <option value="air_freight_chilled" {{ old('transport_detail') === 'air_freight_chilled' ? 'selected' : '' }}>Air Freight (Chilled)</option>
                                                    <option value="road_chilled_van"    {{ old('transport_detail') === 'road_chilled_van'    ? 'selected' : '' }}>Road Transport (Chilled Van)</option>
                                                    <option value="sea_freight_dry"     {{ old('transport_detail') === 'sea_freight_dry'     ? 'selected' : '' }}>Sea Freight (Dry Container)</option>
                                                    <option value="courier_express"     {{ old('transport_detail') === 'courier_express'     ? 'selected' : '' }}>Courier / Express (e.g. DHL, FedEx)</option>
                                                    <option value="hand_carried"        {{ old('transport_detail') === 'hand_carried'        ? 'selected' : '' }}>Hand-Carried (Passenger Luggage)</option>
                                                    <option value="local_courier"       {{ old('transport_detail') === 'local_courier'       ? 'selected' : '' }}>Local Courier (Same Island)</option>
                                                    <option value="other_special"       {{ old('transport_detail') === 'other_special'       ? 'selected' : '' }}>Other / Special Arrangement</option>
                                                </optgroup>
                                            </template>
                                            <template x-if="method === 'fresh'">
                                                <optgroup label="Fresh Methods">
                                                    <option value="hand_carried"        {{ old('transport_detail') === 'hand_carried'        ? 'selected' : '' }}>Hand-Carried (Passenger Luggage)</option>
                                                    <option value="local_courier"       {{ old('transport_detail') === 'local_courier'       ? 'selected' : '' }}>Local Courier (Same Island)</option>
                                                    <option value="road_transport"      {{ old('transport_detail') === 'road_transport'      ? 'selected' : '' }}>Road Transport</option>
                                                    <option value="courier_express"     {{ old('transport_detail') === 'courier_express'     ? 'selected' : '' }}>Courier / Express (e.g. DHL, FedEx)</option>
                                                    <option value="other_special"       {{ old('transport_detail') === 'other_special'       ? 'selected' : '' }}>Other / Special Arrangement</option>
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

                                    <div class="bg-gray-50 rounded-lg border border-gray-100 divide-y divide-gray-100 text-sm">
                                        <div class="px-4 py-3 flex justify-between gap-4">
                                            <span class="text-gray-500">Sample</span>
                                            <span class="font-medium text-gray-800 text-right" x-text="document.getElementById('sample_name')?.value || '—'"></span>
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

                                    <input type="hidden" name="submitter_name" value="{{ trim(auth()->user()->first_name . ' ' . auth()->user()->last_name) }}"/>

                                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
                                        <label class="flex items-start gap-3 cursor-pointer">
                                            <input type="checkbox" name="declaration_accepted" value="1"
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
                                        class="inline-flex items-center gap-2 px-6 py-2.5 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition disabled:opacity-50">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    Submit Sample
                                </button>
                            </div>
                        </div>

                    </div> {{-- end form content --}}
                </div> {{-- end flex wrapper --}}

            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        function submissionWizard() {
            return {
                currentStep: 0,

                nextStep() {
                    if (this.currentStep < 3) {
                        this.currentStep++;
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    }
                },

                prevStep() {
                    if (this.currentStep > 0) {
                        this.currentStep--;
                        window.scrollTo({ top: 0, behavior: 'smooth' });
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