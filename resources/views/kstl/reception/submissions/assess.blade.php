{{-- resources/views/kstl/reception/submissions/assess.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('reception.dashboard') }}"
                   class="text-gray-400 hover:text-gray-600 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <div>
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        Assess Samples — {{ $submission->reference_number }}
                    </h2>
                    <p class="text-sm text-gray-500 mt-0.5">
                        {{ $samples->count() }} sample{{ $samples->count() !== 1 ? 's' : '' }} to assess
                    </p>
                </div>
            </div>

            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-50 text-purple-700 ring-1 ring-inset ring-purple-600/20">
                Assessment in Progress
            </span>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="mb-6 bg-green-50 border-l-4 border-green-400 p-4 rounded-lg flex items-center gap-3">
                    <svg class="w-4 h-4 text-green-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-sm text-green-800">{{ session('success') }}</p>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-red-50 border-l-4 border-red-400 p-4 rounded-lg flex items-center gap-3">
                    <svg class="w-4 h-4 text-red-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1-9a1 1 0 112 0v4a1 1 0 11-2 0V9zm1-5a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-sm text-red-800">{{ session('error') }}</p>
                </div>
            @endif

            <form method="POST" 
                  action="{{ route('reception.submissions.assess.store', $submission->id) }}"
                  id="assessment-form">

                @csrf

                @foreach($samples as $i => $sample)
                    <input type="hidden" 
                           name="assessments[{{ $i }}][sample_id]" 
                           value="{{ $sample->id }}">

                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-8">
                        
                        {{-- Sample Header --}}
                        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
                            <div>
                                <h3 class="font-semibold text-gray-900">
                                    {{ $sample->sample_code }} — {{ $sample->common_name }}
                                </h3>
                                <p class="text-sm text-gray-500 mt-0.5">
                                    {{ $sample->quantity }} {{ $sample->quantity_unit }}
                                    @if($sample->scientific_name)
                                        · <span class="italic">{{ $sample->scientific_name }}</span>
                                    @endif
                                </p>
                            </div>
                            <span class="text-xs font-mono bg-white px-3 py-1 rounded-full border">
                                Sample {{ $i + 1 }} of {{ $samples->count() }}
                            </span>
                        </div>

                        <div class="px-6 py-6 space-y-6">

                            @php
                                $criteria = [
                                    'temperature' => 'Temperature',
                                    'storage'     => 'Storage Condition',
                                    'transport'   => 'Transport Condition',
                                    'packaging'   => 'Packaging Integrity',
                                    'colour'      => 'Colour / Appearance',
                                    'odour'       => 'Odour',
                                    'weight'      => 'Weight / Quantity Check',
                                ];
                            @endphp

                            @foreach($criteria as $key => $label)
                                <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-start border-b border-gray-100 pb-6 last:border-0 last:pb-0">
                                    
                                    {{-- Label --}}
                                    <div class="md:col-span-3">
                                        <p class="font-medium text-gray-800">{{ $label }}</p>
                                    </div>

                                    {{-- Pass / Fail --}}
                                    <div class="md:col-span-4">
                                        <div class="flex gap-6">
                                            <label class="flex items-center gap-2 cursor-pointer group">
                                                <input type="radio" 
                                                       name="assessments[{{ $i }}][{{ $key }}_ok]" 
                                                       value="1" 
                                                       required
                                                       class="w-4 h-4 text-green-600 focus:ring-green-500 border-gray-300">
                                                <span class="text-sm font-medium text-green-700 group-hover:text-green-800">Pass</span>
                                            </label>

                                            <label class="flex items-center gap-2 cursor-pointer group">
                                                <input type="radio" 
                                                       name="assessments[{{ $i }}][{{ $key }}_ok]" 
                                                       value="0"
                                                       class="w-4 h-4 text-red-600 focus:ring-red-500 border-gray-300">
                                                <span class="text-sm font-medium text-red-700 group-hover:text-red-800">Fail</span>
                                            </label>
                                        </div>
                                    </div>

                                    {{-- Notes --}}
                                    <div class="md:col-span-5">
                                        <input type="text"
                                               name="assessments[{{ $i }}][{{ $key }}_notes]"
                                               placeholder="Notes / observations (optional)"
                                               class="w-full text-sm border-gray-300 rounded-xl focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>
                                </div>
                            @endforeach

                            {{-- Additional Observations --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Additional Observations
                                </label>
                                <textarea name="assessments[{{ $i }}][additional_observations]" 
                                          rows="3"
                                          class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                          placeholder="Any other relevant observations..."></textarea>
                            </div>

                            {{-- Rejection Reason (shown only if any Fail is selected) --}}
                            <div x-data="{ showRejection: false }">
                                <div class="flex items-center gap-2 text-red-600 text-sm font-medium mb-2">
                                    <input type="checkbox" 
                                           id="has_rejection_{{ $i }}"
                                           @change="showRejection = $el.checked"
                                           class="w-4 h-4 text-red-600">
                                    <label for="has_rejection_{{ $i }}" class="cursor-pointer">
                                        This sample should be rejected
                                    </label>
                                </div>

                                <div x-show="showRejection" x-cloak>
                                    <label class="block text-sm font-medium text-red-700 mb-1">
                                        Rejection Reason <span class="text-red-500">*</span>
                                    </label>
                                    <textarea name="assessments[{{ $i }}][rejection_reason]" 
                                              rows="3"
                                              class="w-full rounded-xl border-red-300 focus:border-red-500 focus:ring-red-500"
                                              placeholder="Please explain why this sample is being rejected..."></textarea>
                                </div>
                            </div>

                        </div>
                    </div>
                @endforeach

                {{-- Action Buttons --}}
                <div class="flex items-center justify-between pt-6">
                    <a href="{{ route('reception.submissions.show', $submission->id) }}" 
                       class="inline-flex items-center gap-2 text-gray-500 hover:text-gray-700 transition">
                        ← Back to Submission Details
                    </a>

                    <button type="submit"
                            onclick="return confirm('Are you sure you want to submit this assessment? This action cannot be undone.')"
                            class="inline-flex items-center gap-3 px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-2xl transition shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Submit Assessment Results
                    </button>
                </div>

            </form>

        </div>
    </div>

    @push('scripts')
    <script>
        // Optional: Auto-show rejection reason when any "Fail" is selected
        document.addEventListener('change', function(e) {
            if (e.target.type === 'radio' && e.target.value === '0') {
                const sampleIndex = e.target.name.match(/\[(\d+)\]/)[1];
                const rejectionSection = document.querySelector(`#has_rejection_${sampleIndex}`);
                if (rejectionSection) rejectionSection.checked = true;
            }
        });
    </script>
    @endpush

</x-app-layout>