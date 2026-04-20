{{-- resources/views/kstl/client/results/index.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">My Results</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="text-sm font-medium text-gray-800">Authorised Results</h3>
                </div>

                @if($submissions->isEmpty())
                    <div class="px-6 py-16 text-center">
                        <svg class="w-10 h-10 text-gray-200 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <p class="text-sm text-gray-400">No results yet</p>
                        <p class="text-xs text-gray-300 mt-1">Results will appear here once the Director has authorised them.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 border-b border-gray-100">
                                <tr>
                                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Reference</th>
                                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Sample</th>
                                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Outcome</th>
                                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Authorised</th>
                                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">By</th>
                                    <th class="px-6 py-3"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @foreach($submissions as $submission)
                                    @php
                                        $result   = $submission->result;
                                        $outcome  = $result?->overall_outcome ?? 'pending';
                                        $colors   = [
                                            'pass'         => 'bg-green-50 text-green-700',
                                            'fail'         => 'bg-red-50 text-red-700',
                                            'inconclusive' => 'bg-yellow-50 text-yellow-700',
                                        ];
                                        $color = $colors[$outcome] ?? 'bg-gray-100 text-gray-500';
                                    @endphp
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4 font-mono text-xs text-gray-600">
                                            {{ $submission->reference_number }}
                                        </td>
                                        <td class="px-6 py-4 text-gray-800 font-medium">
                                            {{ $submission->sample_name }}
                                            <p class="text-xs text-gray-400 font-normal capitalize">{{ $submission->sample_type }}</p>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex px-2.5 py-0.5 text-xs font-semibold rounded-full capitalize {{ $color }}">
                                                {{ $outcome === 'pass' ? '✓ Pass' : ($outcome === 'fail' ? '✗ Fail' : ucfirst($outcome)) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-xs text-gray-500">
                                            {{ $result?->authorised_at?->format('d M Y') ?? '—' }}
                                        </td>
                                        <td class="px-6 py-4 text-xs text-gray-500">
                                            {{ $result?->authorisedBy?->name ?? '—' }}
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <a href="{{ route('client.results.show', $submission->id) }}"
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