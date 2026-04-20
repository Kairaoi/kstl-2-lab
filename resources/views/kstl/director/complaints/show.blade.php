{{-- resources/views/kstl/director/complaints/show.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('director.complaints.index') }}"
               class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ $complaint->subject }}
                </h2>
                <p class="text-sm text-gray-500 mt-0.5">
                    From {{ $complaint->complainant_name }} · {{ $complaint->created_at->format('d M Y') }}
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-5">

            @if(session('success'))
                <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-lg text-sm text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

                {{-- Left: Complaint info --}}
                <div class="space-y-4">
                    <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
                        <div class="px-5 py-3.5 border-b border-gray-100">
                            <h3 class="text-sm font-medium text-gray-800">Complainant</h3>
                        </div>
                        <dl class="px-5 py-4 space-y-3 text-sm">
                            <div>
                                <dt class="text-xs text-gray-400 uppercase">Name</dt>
                                <dd class="font-medium text-gray-800 mt-0.5">{{ $complaint->complainant_name ?? '—' }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs text-gray-400 uppercase">Organisation</dt>
                                <dd class="text-gray-700 mt-0.5">{{ $complaint->complainant_organisation ?? '—' }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs text-gray-400 uppercase">Email</dt>
                                <dd class="text-gray-700 mt-0.5">{{ $complaint->complainant_email ?? '—' }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs text-gray-400 uppercase">Incident Date</dt>
                                <dd class="text-gray-700 mt-0.5">{{ $complaint->incident_date->format('d M Y') }}</dd>
                            </div>
                        </dl>
                    </div>

                    <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
                        <div class="px-5 py-3.5 border-b border-gray-100">
                            <h3 class="text-sm font-medium text-gray-800">Details</h3>
                        </div>
                        <dl class="px-5 py-4 space-y-3 text-sm">
                            <div>
                                <dt class="text-xs text-gray-400 uppercase">Type(s)</dt>
                                <dd class="mt-1 flex flex-wrap gap-1">
                                    @foreach($complaint->getComplaintTypeLabels() as $label)
                                        <span class="inline-flex px-2 py-0.5 text-xs bg-red-50 text-red-700 rounded-full">{{ $label }}</span>
                                    @endforeach
                                </dd>
                            </div>
                            @if($complaint->submission)
                                <div>
                                    <dt class="text-xs text-gray-400 uppercase">Submission</dt>
                                    <dd class="font-mono text-xs text-gray-700 mt-0.5">{{ $complaint->submission->reference_number }}</dd>
                                </div>
                            @endif
                            <div>
                                <dt class="text-xs text-gray-400 uppercase">Status</dt>
                                <dd class="mt-1">
                                    <span class="inline-flex px-2 py-0.5 text-xs font-medium rounded-full capitalize {{ $complaint->getStatusColour() }}">
                                        {{ str_replace('_', ' ', $complaint->status) }}
                                    </span>
                                </dd>
                            </div>
                            @if($complaint->resolvedBy)
                                <div>
                                    <dt class="text-xs text-gray-400 uppercase">Resolved By</dt>
                                    <dd class="text-gray-700 mt-0.5">{{ $complaint->resolvedBy->name }}</dd>
                                    <dd class="text-xs text-gray-400">{{ $complaint->resolved_at?->format('d M Y') }}</dd>
                                </div>
                            @endif
                        </dl>
                    </div>
                </div>

                {{-- Right: Description + Response --}}
                <div class="lg:col-span-2 space-y-5">

                    {{-- Description --}}
                    <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100">
                            <h3 class="text-sm font-medium text-gray-800">Complaint Description</h3>
                        </div>
                        <div class="px-6 py-5 text-sm text-gray-700 leading-relaxed whitespace-pre-line">
                            {{ $complaint->description }}
                        </div>
                    </div>

                    {{-- Existing response --}}
                    @if($complaint->lab_response)
                        <div class="bg-blue-50 rounded-xl border border-blue-200 overflow-hidden">
                            <div class="px-6 py-4 border-b border-blue-100">
                                <h3 class="text-sm font-medium text-blue-800">Previous Response</h3>
                                @if($complaint->assignedTo)
                                    <p class="text-xs text-blue-500 mt-0.5">By {{ $complaint->assignedTo->name }}</p>
                                @endif
                            </div>
                            <div class="px-6 py-5 text-sm text-blue-900 leading-relaxed whitespace-pre-line">
                                {{ $complaint->lab_response }}
                                @if($complaint->action_taken)
                                    <div class="mt-3 pt-3 border-t border-blue-100">
                                        <p class="text-xs font-medium text-blue-700 mb-1">Action Taken:</p>
                                        <p class="whitespace-pre-line">{{ $complaint->action_taken }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    {{-- Respond form --}}
                    @if(! $complaint->isClosed())
                        <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-100">
                                <h3 class="text-sm font-medium text-gray-800">
                                    {{ $complaint->lab_response ? 'Update Response' : 'Respond to Complaint' }}
                                </h3>
                            </div>
                            <form method="POST"
                                  action="{{ route('director.complaints.respond', $complaint->id) }}"
                                  class="px-6 py-5 space-y-4">
                                @csrf

                                <div>
                                    <x-label for="lab_response" value="Response *"/>
                                    <textarea id="lab_response" name="lab_response" rows="5"
                                              class="mt-1 block w-full border-gray-300 rounded-lg text-sm focus:border-teal-500 focus:ring-teal-500"
                                              placeholder="Describe your findings and response to the complainant...">{{ old('lab_response', $complaint->lab_response) }}</textarea>
                                    <x-input-error for="lab_response" class="mt-1"/>
                                </div>

                                <div>
                                    <x-label for="action_taken" value="Action Taken (optional)"/>
                                    <textarea id="action_taken" name="action_taken" rows="3"
                                              class="mt-1 block w-full border-gray-300 rounded-lg text-sm focus:border-teal-500 focus:ring-teal-500"
                                              placeholder="Describe any corrective actions taken...">{{ old('action_taken', $complaint->action_taken) }}</textarea>
                                </div>

                                <div>
                                    <x-label for="status" value="Update Status *"/>
                                    <select id="status" name="status"
                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:border-teal-500 focus:ring-teal-500">
                                        <option value="open" {{ $complaint->status === 'open' ? 'selected' : '' }}>Open</option>
                                        <option value="under_investigation" {{ $complaint->status === 'under_investigation' ? 'selected' : '' }}>Under Investigation</option>
                                        <option value="resolved" {{ $complaint->status === 'resolved' ? 'selected' : '' }}>Resolved</option>
                                        <option value="closed" {{ $complaint->status === 'closed' ? 'selected' : '' }}>Closed</option>
                                    </select>
                                    <x-input-error for="status" class="mt-1"/>
                                </div>

                                <div class="flex justify-end pt-2">
                                    <button type="submit"
                                            class="inline-flex items-center gap-2 px-6 py-2.5 bg-teal-600 text-white text-sm font-medium rounded-lg hover:bg-teal-700 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        Save Response
                                    </button>
                                </div>
                            </form>
                        </div>
                    @endif

                </div>
            </div>

            <div class="pb-8"></div>

        </div>
    </div>
</x-app-layout>