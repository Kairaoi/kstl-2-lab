{{-- resources/views/kstl/client/submissions/edit.blade.php --}}

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
                Edit Submission
                {{-- <span class="font-mono text-base text-gray-500">#{{ strtoupper(substr($submission->id, 0, 8)) }}</span> --}}
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Validation Errors --}}
            <x-validation-errors class="bg-red-50 border border-red-200 rounded-xl p-4"/>

            {{-- Cannot edit warning --}}
            {{--
                @if(!in_array($submission->status, ['pending']))
                    <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-lg">
                        <p class="text-sm text-red-800">
                            <span class="font-medium">Cannot edit.</span>
                            This submission is currently <strong>{{ $submission->status }}</strong>
                            and can no longer be modified.
                        </p>
                    </div>
                @endif
            --}}

            <form method="POST" action="{{ route('client.submissions.index') }}"
                  enctype="multipart/form-data">
                @csrf
                @method('PUT')
                {{-- Replace action with: route('client.submissions.update', $submission->id) --}}

                {{-- ── Section 1: Sample Information ──────────────────────────────── --}}
                <div class="md:grid md:grid-cols-3 md:gap-6">
                    <div class="md:col-span-1 px-4 sm:px-0">
                        <h3 class="text-lg font-medium text-gray-900">Sample Information</h3>
                        <p class="mt-1 text-sm text-gray-600">Update the sample details.</p>
                    </div>
                    <div class="mt-5 md:mt-0 md:col-span-2">
                        <div class="bg-white shadow rounded-xl overflow-hidden">
                            <div class="px-6 py-5 space-y-5">

                                <div>
                                    <x-label for="sample_name" value="Sample Name *"/>
                                    <x-input id="sample_name"
                                             type="text"
                                             name="sample_name"
                                             value="{{ old('sample_name') }}"
                                             {{-- value="{{ old('sample_name', $submission->sample_name) }}" --}}
                                             class="mt-1 block w-full"
                                             required autofocus/>
                                    <x-input-error for="sample_name" class="mt-1"/>
                                </div>

                                <div>
                                    <x-label for="sample_description" value="Sample Description"/>
                                    <textarea id="sample_description"
                                              name="sample_description"
                                              rows="3"
                                              class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm"
                                              placeholder="Describe the sample...">{{ old('sample_description') }}</textarea>
                                              {{-- >{{ old('sample_description', $submission->sample_description) }}</textarea> --}}
                                    <x-input-error for="sample_description" class="mt-1"/>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <x-label for="sample_type" value="Sample Type *"/>
                                        <select id="sample_type"
                                                name="sample_type"
                                                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm"
                                                required>
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
                                            <x-input id="sample_quantity"
                                                     type="number"
                                                     name="sample_quantity"
                                                     value="{{ old('sample_quantity') }}"
                                                     class="block w-full rounded-r-none"
                                                     placeholder="0"
                                                     min="0"
                                                     step="0.01"
                                                     required/>
                                            <select name="sample_quantity_unit"
                                                    class="border-l-0 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-r-md shadow-sm text-sm px-3">
                                                <option value="g">g</option>
                                                <option value="kg" selected>kg</option>
                                                <option value="ml">ml</option>
                                                <option value="L">L</option>
                                            </select>
                                        </div>
                                        <x-input-error for="sample_quantity" class="mt-1"/>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <x-label for="collected_at" value="Collection Date *"/>
                                        <x-input id="collected_at"
                                                 type="date"
                                                 name="collected_at"
                                                 value="{{ old('collected_at') }}"
                                                 class="mt-1 block w-full"
                                                 max="{{ date('Y-m-d') }}"
                                                 required/>
                                        <x-input-error for="collected_at" class="mt-1"/>
                                    </div>
                                    <div>
                                        <x-label for="collection_location" value="Collection Location"/>
                                        <x-input id="collection_location"
                                                 type="text"
                                                 name="collection_location"
                                                 value="{{ old('collection_location') }}"
                                                 class="mt-1 block w-full"
                                                 placeholder="e.g. South Tarawa Lagoon"/>
                                        <x-input-error for="collection_location" class="mt-1"/>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <x-section-border/>

                {{-- ── Section 2: Special Instructions ────────────────────────────── --}}
                <div class="md:grid md:grid-cols-3 md:gap-6">
                    <div class="md:col-span-1 px-4 sm:px-0">
                        <h3 class="text-lg font-medium text-gray-900">Special Instructions</h3>
                        <p class="mt-1 text-sm text-gray-600">Handling requirements and priority.</p>
                    </div>
                    <div class="mt-5 md:mt-0 md:col-span-2">
                        <div class="bg-white shadow rounded-xl overflow-hidden">
                            <div class="px-6 py-5 space-y-5">

                                <div>
                                    <x-label for="priority" value="Priority Level"/>
                                    <div class="mt-2 flex gap-3 flex-wrap">
                                        @foreach(['routine' => 'Routine', 'urgent' => 'Urgent', 'emergency' => 'Emergency'] as $value => $label)
                                            <label class="flex items-center gap-2 cursor-pointer">
                                                <input type="radio"
                                                       name="priority"
                                                       value="{{ $value }}"
                                                       {{ old('priority', 'routine') === $value ? 'checked' : '' }}
                                                       class="border-gray-300 text-indigo-600 focus:ring-indigo-500"/>
                                                <span class="text-sm text-gray-700">{{ $label }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                    <x-input-error for="priority" class="mt-1"/>
                                </div>

                                <div>
                                    <x-label for="storage_conditions" value="Storage / Handling Conditions"/>
                                    <select id="storage_conditions"
                                            name="storage_conditions"
                                            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm">
                                        <option value="">— Select conditions —</option>
                                        <option value="ambient"  {{ old('storage_conditions') === 'ambient'  ? 'selected' : '' }}>Ambient / Room temperature</option>
                                        <option value="chilled"  {{ old('storage_conditions') === 'chilled'  ? 'selected' : '' }}>Chilled (0–4°C)</option>
                                        <option value="frozen"   {{ old('storage_conditions') === 'frozen'   ? 'selected' : '' }}>Frozen (−18°C or below)</option>
                                        <option value="dry_ice"  {{ old('storage_conditions') === 'dry_ice'  ? 'selected' : '' }}>Dry Ice</option>
                                    </select>
                                    <x-input-error for="storage_conditions" class="mt-1"/>
                                </div>

                                <div>
                                    <x-label for="special_instructions" value="Additional Notes"/>
                                    <textarea id="special_instructions"
                                              name="special_instructions"
                                              rows="3"
                                              class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm"
                                              placeholder="Any other instructions for the lab team...">{{ old('special_instructions') }}</textarea>
                                    <x-input-error for="special_instructions" class="mt-1"/>
                                </div>

                                <div>
                                    <x-label for="results_required_by" value="Results Required By"/>
                                    <x-input id="results_required_by"
                                             type="date"
                                             name="results_required_by"
                                             value="{{ old('results_required_by') }}"
                                             class="mt-1 block w-full sm:w-1/2"
                                             min="{{ date('Y-m-d', strtotime('+1 day')) }}"/>
                                    <x-input-error for="results_required_by" class="mt-1"/>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                {{-- ── Form Actions ──────────────────────────────────────────────── --}}
                <div class="flex items-center justify-between pt-4 pb-8">
                    <a href="{{ route('client.submissions.index') }}">
                        <x-secondary-button type="button">Cancel</x-secondary-button>
                    </a>
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Update Submission
                    </button>
                </div>

            </form>
        </div>
    </div>
</x-app-layout>