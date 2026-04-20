{{-- resources/views/kstl/director/complaints/index.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Complaints</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-lg text-sm text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Summary Cards --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                @foreach(['open' => ['bg-yellow-100','text-yellow-700'], 'under_investigation' => ['bg-blue-100','text-blue-700'], 'resolved' => ['bg-green-100','text-green-700'], 'closed' => ['bg-gray-100','text-gray-500']] as $status => $colors)
                    <div class="bg-white rounded-xl border border-gray-100 p-4">
                        <p class="text-xs text-gray-500 uppercase tracking-wide mb-1 capitalize">{{ str_replace('_',' ',$status) }}</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $counts[$status] }}</p>
                    </div>
                @endforeach
            </div>

            {{-- Complaints Table --}}
            <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="text-sm font-medium text-gray-800">All Complaints</h3>
                </div>

                @if($complaints->isEmpty())
                    <div class="px-6 py-16 text-center">
                        <p class="text-sm text-gray-400">No complaints recorded.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 border-b border-gray-100">
                                <tr>
                                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Subject</th>
                                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">From</th>
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
                                        <td class="px-6 py-4 text-gray-600">
                                            <p>{{ $complaint->complainant_name ?? '—' }}</p>
                                            @if($complaint->complainant_organisation)
                                                <p class="text-xs text-gray-400">{{ $complaint->complainant_organisation }}</p>
                                            @endif
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
                                            <a href="{{ route('director.complaints.show', $complaint->id) }}"
                                               class="text-xs text-teal-600 px-3 py-1.5 border border-teal-200 rounded-lg hover:bg-teal-50 transition">
                                                Respond
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