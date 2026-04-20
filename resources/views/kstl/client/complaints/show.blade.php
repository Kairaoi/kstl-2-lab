{{-- resources/views/kstl/client/complaints/show.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('client.complaints.index') }}"
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
                    Lodged {{ $complaint->created_at->format('d M Y \a\t H:i') }}
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-5">

            @if(session('success'))
                <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-lg text-sm text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Status Banner --}}
            <div class="bg-white rounded-xl border border-gray-100 p-5 flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Status</p>
                    <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full capitalize {{ $complaint->getStatusColour() }}">
                        {{ str_replace('_', ' ', $complaint->status) }}
                    </span>
                </div>
                @if($complaint->resolved_at)
                    <div class="text-right text-xs text-gray-400">
                        <p>Resolved {{ $complaint->resolved_at->format('d M Y') }}</p>
                    </div>
                @endif
            </div>

            {{-- Complaint Details --}}
            <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="text-sm font-medium text-gray-800">Your Complaint</h3>
                </div>
                <dl class="px-6 py-5 space-y-4 text-sm">
                    <div>
                        <dt class="text-xs text-gray-400 uppercase tracking-wide">Type(s)</dt>
                        <dd class="mt-1 flex flex-wrap gap-1.5">
                            @foreach($complaint->getComplaintTypeLabels() as $label)
                                <span class="inline-flex px-2 py-0.5 text-xs bg-red-50 text-red-700 rounded-full">{{ $label }}</span>
                            @endforeach
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-400 uppercase tracking-wide">Incident Date</dt>
                        <dd class="mt-1 text-gray-700">{{ $complaint->incident_date->format('d M Y') }}</dd>
                    </div>
                    @if($complaint->submission)
                        <div>
                            <dt class="text-xs text-gray-400 uppercase tracking-wide">Related Submission</dt>
                            <dd class="mt-1 font-mono text-gray-700">{{ $complaint->submission->reference_number }}</dd>
                        </div>
                    @endif
                    <div>
                        <dt class="text-xs text-gray-400 uppercase tracking-wide">Description</dt>
                        <dd class="mt-1 text-gray-700 leading-relaxed whitespace-pre-line">{{ $complaint->description }}</dd>
                    </div>
                </dl>
            </div>

            {{-- Lab Response --}}
            @if($complaint->lab_response)
                <div class="bg-blue-50 rounded-xl border border-blue-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-blue-100">
                        <h3 class="text-sm font-medium text-blue-800">Laboratory Response</h3>
                        @if($complaint->assignedTo)
                            <p class="text-xs text-blue-600 mt-0.5">From {{ $complaint->assignedTo->name }}</p>
                        @endif
                    </div>
                    <div class="px-6 py-5 space-y-3 text-sm text-blue-900">
                        <p class="leading-relaxed whitespace-pre-line">{{ $complaint->lab_response }}</p>
                        @if($complaint->action_taken)
                            <div class="pt-3 border-t border-blue-100">
                                <p class="text-xs font-medium text-blue-700 uppercase tracking-wide mb-1">Action Taken</p>
                                <p class="leading-relaxed whitespace-pre-line">{{ $complaint->action_taken }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @else
                <div class="bg-amber-50 rounded-xl border border-amber-200 p-5 text-sm text-amber-800">
                    <p>Your complaint has been received and is being reviewed. We will respond within 5 working days.</p>
                </div>
            @endif

            <div class="pb-8"></div>

        </div>
    </div>
</x-app-layout>