{{-- resources/views/kstl/client/complaints/index.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">My Complaints</h2>
            <a href="{{ route('client.complaints.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Lodge Complaint
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-lg text-sm text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="text-sm font-medium text-gray-800">All Complaints</h3>
                </div>

                @if($complaints->isEmpty())
                    <div class="px-6 py-16 text-center">
                        <svg class="w-10 h-10 text-gray-200 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-sm text-gray-400">No complaints lodged</p>
                        <p class="text-xs text-gray-300 mt-1">Use the button above to lodge a new complaint.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 border-b border-gray-100">
                                <tr>
                                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Subject</th>
                                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Type</th>
                                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Lodged</th>
                                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-6 py-3"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @foreach($complaints as $complaint)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4 font-medium text-gray-800">
                                            {{ $complaint->subject }}
                                        </td>
                                        <td class="px-6 py-4 text-xs text-gray-500">
                                            {{ implode(', ', $complaint->getComplaintTypeLabels()) }}
                                        </td>
                                        <td class="px-6 py-4 text-xs text-gray-500">
                                            {{ $complaint->created_at->format('d M Y') }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex px-2 py-0.5 text-xs font-medium rounded-full capitalize {{ $complaint->getStatusColour() }}">
                                                {{ str_replace('_', ' ', $complaint->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <a href="{{ route('client.complaints.show', $complaint->id) }}"
                                               class="text-xs text-blue-600 px-3 py-1.5 border border-blue-200 rounded-lg hover:bg-blue-50 transition">
                                                View
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>