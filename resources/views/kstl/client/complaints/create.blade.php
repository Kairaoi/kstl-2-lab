{{-- resources/views/kstl/client/complaints/create.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('client.complaints.index') }}"
               class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Lodge a Complaint</h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">

            @if ($errors->any())
                <div class="mb-5 bg-red-50 border border-red-200 rounded-xl p-4">
                    <p class="text-sm font-medium text-red-800 mb-1">Please fix the following:</p>
                    <ul class="text-sm text-red-700 list-disc list-inside space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('client.complaints.store') }}">
                @csrf

                <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden mb-5">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h3 class="text-sm font-semibold text-gray-800">Complaint Details</h3>
                        <p class="text-xs text-gray-400 mt-0.5">We take all complaints seriously. We will respond within 5 working days.</p>
                    </div>

                    <div class="px-6 py-5 space-y-5">

                        {{-- Subject --}}
                        <div>
                            <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">
                                Subject <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="subject" name="subject"
                                   value="{{ old('subject') }}"
                                   placeholder="Brief description of your complaint"
                                   class="w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-400 focus:border-transparent @error('subject') border-red-400 bg-red-50 @enderror">
                            @error('subject')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>

                        {{-- Date of Incident --}}
                        <div>
                            <label for="incident_date" class="block text-sm font-medium text-gray-700 mb-1">
                                Date of Incident <span class="text-red-500">*</span>
                            </label>
                            <input type="date" id="incident_date" name="incident_date"
                                   value="{{ old('incident_date', now()->toDateString()) }}"
                                   max="{{ now()->toDateString() }}"
                                   class="w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-400 focus:border-transparent @error('incident_date') border-red-400 @enderror">
                            @error('incident_date')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>

                        {{-- Complaint Types --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Type of Complaint <span class="text-red-500">*</span>
                                <span class="font-normal text-gray-400">&nbsp;— select all that apply</span>
                            </label>
                            <div class="grid grid-cols-2 gap-2">
                                @foreach(\App\Models\Kstl\Complaint::COMPLAINT_TYPES as $value => $label)
                                    @php $checked = in_array($value, old('complaint_types', [])); @endphp
                                    <label class="flex items-center gap-3 px-4 py-3 rounded-lg border cursor-pointer text-sm transition select-none
                                        {{ $checked ? 'border-red-400 bg-red-50 text-red-800' : 'border-gray-200 text-gray-700 hover:border-gray-300 hover:bg-gray-50' }}">
                                        <input type="checkbox"
                                               name="complaint_types[]"
                                               value="{{ $value }}"
                                               {{ $checked ? 'checked' : '' }}
                                               class="w-4 h-4 rounded border-gray-300 text-red-600 focus:ring-red-400">
                                        <span class="font-medium">{{ $label }}</span>
                                    </label>
                                @endforeach
                            </div>
                            @error('complaint_types')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>

                        {{-- Related Submission --}}
                        @if(isset($submissions) && $submissions->isNotEmpty())
                            <div>
                                <label for="submission_id" class="block text-sm font-medium text-gray-700 mb-1">
                                    Related Submission
                                    <span class="font-normal text-gray-400">— optional</span>
                                </label>
                                <select id="submission_id" name="submission_id"
                                        class="w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-400 focus:border-transparent bg-white">
                                    <option value="">— Not related to a specific submission —</option>
                                    @foreach($submissions as $sub)
                                        <option value="{{ $sub->id }}" {{ old('submission_id') == $sub->id ? 'selected' : '' }}>
                                            {{ $sub->reference_number }} — {{ $sub->sample_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        {{-- Description --}}
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                                Description <span class="text-red-500">*</span>
                            </label>
                            <textarea id="description" name="description" rows="6"
                                      placeholder="Provide a detailed description including dates, names of staff involved, and the outcome you are seeking..."
                                      class="w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-400 focus:border-transparent resize-none @error('description') border-red-400 bg-red-50 @enderror">{{ old('description') }}</textarea>
                            <p class="mt-1 text-xs text-gray-400">Minimum 20 characters. Be as specific as possible.</p>
                            @error('description')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>

                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex items-center justify-between">
                    <a href="{{ route('client.complaints.index') }}"
                       class="px-4 py-2.5 text-sm text-gray-600 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                        Cancel
                    </a>
                    <button type="submit"
                            class="inline-flex items-center gap-2 px-6 py-2.5 bg-red-600 text-white text-sm font-semibold rounded-lg hover:bg-red-700 transition shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                        Submit Complaint
                    </button>
                </div>

                <div class="pb-10"></div>

            </form>
        </div>
    </div>
</x-app-layout>