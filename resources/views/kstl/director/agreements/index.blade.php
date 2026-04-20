{{-- resources/views/kstl/director/agreements/index.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Service Agreements</h2>
            <div class="flex items-center gap-3 text-sm">
                <span class="text-gray-400">Fully executed:</span>
                <span class="font-semibold text-green-700">{{ $totalSigned }}</span>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-lg text-sm text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            {{-- ── Pending Countersign ──────────────────────────── --}}
            <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="text-sm font-medium text-gray-800">
                        Awaiting Your Countersignature
                        @if($pending->isNotEmpty())
                            <span class="ml-2 inline-flex items-center px-2 py-0.5 text-xs font-medium bg-amber-100 text-amber-700 rounded-full">
                                {{ $pending->count() }} pending
                            </span>
                        @endif
                    </h3>
                    <p class="text-xs text-gray-400 mt-0.5">
                        These clients have signed the service agreement and are waiting for your countersignature.
                    </p>
                </div>

                @if($pending->isEmpty())
                    <div class="px-6 py-12 text-center">
                        <svg class="w-10 h-10 text-gray-200 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-sm text-gray-400">No agreements pending countersignature.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 border-b border-gray-100">
                                <tr>
                                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Company</th>
                                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Officer</th>
                                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Email</th>
                                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Client Signed</th>
                                    <th class="px-6 py-3"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @foreach($pending as $client)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4">
                                            <p class="font-medium text-gray-800">{{ $client->company_name }}</p>
                                        </td>
                                        <td class="px-6 py-4 text-gray-600">
                                            {{ $client->responsible_officer_name ?? '—' }}
                                        </td>
                                        <td class="px-6 py-4 text-gray-500 text-xs">
                                            {{ $client->user?->email ?? '—' }}
                                        </td>
                                        <td class="px-6 py-4 text-xs text-gray-500">
                                            {{ $client->service_agreement_signed_at?->format('d M Y \a\t H:i') ?? '—' }}
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <a href="{{ route('director.agreements.show', $client->id) }}"
                                               class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-teal-600 text-white text-xs font-medium rounded-lg hover:bg-teal-700 transition">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                                </svg>
                                                Countersign
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>


            {{-- ── Fully Executed Agreements ───────────────────────── --}}
            <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-medium text-gray-800">Fully Executed Agreements</h3>
                        <p class="text-xs text-gray-400 mt-0.5">Both client and director have signed</p>
                    </div>
                    <span class="text-xs font-medium text-teal-700 bg-teal-50 px-3 py-1 rounded-full border border-teal-100">
                        {{ $executed->count() }} executed
                    </span>
                </div>

                @if($executed->isEmpty())
                    <div class="px-6 py-12 text-center">
                        <svg class="w-8 h-8 text-gray-200 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <p class="text-sm text-gray-400">No fully executed agreements yet</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 border-b border-gray-100">
                                <tr>
                                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Company</th>
                                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Responsible Officer</th>
                                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Email</th>
                                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Client Signed</th>
                                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Director Signed</th>
                                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Signature Type</th>
                                    <th class="px-6 py-3"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @foreach($executed as $client)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4">
                                            <p class="font-medium text-gray-800">{{ $client->company_name }}</p>
                                            <p class="text-xs text-gray-400 mt-0.5">{{ $client->address }}</p>
                                        </td>
                                        <td class="px-6 py-4 text-gray-600 text-sm">
                                            {{ $client->responsible_officer_name ?? '—' }}
                                        </td>
                                        <td class="px-6 py-4 text-xs text-gray-500">
                                            {{ $client->user?->email ?? '—' }}
                                        </td>
                                        <td class="px-6 py-4 text-xs text-gray-500">
                                            {{ $client->service_agreement_signed_at?->format('d M Y') ?? '—' }}
                                            <p class="text-gray-300">{{ $client->service_agreement_signed_at?->format('H:i') }}</p>
                                        </td>
                                        <td class="px-6 py-4 text-xs text-gray-500">
                                            {{ $client->director_signed_at?->format('d M Y') ?? '—' }}
                                            <p class="text-gray-300">{{ $client->director_signed_at?->format('H:i') }}</p>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 text-xs rounded-full capitalize
                                                {{ $client->signature_type === 'drawn' ? 'bg-blue-50 text-blue-700' : 'bg-purple-50 text-purple-700' }}">
                                                {{ $client->signature_type === 'drawn' ? '✏ Drawn' : '↑ Uploaded' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="flex items-center justify-end gap-2">
                                                <a href="{{ route('director.agreements.show', $client->id) }}"
                                                   class="text-xs text-gray-500 px-3 py-1.5 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                                                    View
                                                </a>
                                                <a href="{{ route('director.agreements.download', $client->id) }}"
                                                   class="inline-flex items-center gap-1 text-xs text-teal-700 px-3 py-1.5 border border-teal-200 rounded-lg hover:bg-teal-50 transition">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                              d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                    </svg>
                                                    PDF
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

        <div class="pb-12"></div>
    </div>
</x-app-layout>