{{-- resources/views/kstl/client/results/show.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('client.results.index') }}"
               class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Results — {{ $submission->reference_number }}
                </h2>
                <p class="text-xs text-gray-400 mt-0.5">{{ $submission->sample_name }}</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-5">

            {{-- Outcome Banner --}}
            @php
                $outcome = $result?->overall_outcome ?? 'pending';
                $bannerClass = match($outcome) {
                    'pass'         => 'bg-green-50 border-green-200',
                    'fail'         => 'bg-red-50 border-red-200',
                    'inconclusive' => 'bg-yellow-50 border-yellow-200',
                    default        => 'bg-gray-50 border-gray-200',
                };
                $iconClass = match($outcome) {
                    'pass'  => 'text-green-500',
                    'fail'  => 'text-red-500',
                    default => 'text-yellow-500',
                };
                $label = match($outcome) {
                    'pass'         => '✓ Overall Result: PASS',
                    'fail'         => '✗ Overall Result: FAIL',
                    'inconclusive' => '⚠ Overall Result: INCONCLUSIVE',
                    default        => 'Pending',
                };
            @endphp

            <div class="rounded-xl border {{ $bannerClass }} p-5 flex items-center gap-4">
                <div class="text-3xl font-bold {{ $iconClass }}">{{ strtoupper($outcome) }}</div>
                <div>
                    <p class="font-semibold text-gray-800">{{ $label }}</p>
                    @if($result?->authorised_at)
                        <p class="text-xs text-gray-500 mt-0.5">
                            Authorised by {{ $result->authorisedBy?->name ?? 'Director' }}
                            on {{ $result->authorised_at->format('d M Y \a\t H:i') }}
                        </p>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

                {{-- Left: Submission details --}}
                <div class="space-y-4">
                    <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
                        <div class="px-5 py-3.5 border-b border-gray-100">
                            <h3 class="text-sm font-medium text-gray-800">Submission</h3>
                        </div>
                        <dl class="px-5 py-4 space-y-3 text-sm">
                            <div>
                                <dt class="text-xs text-gray-400 uppercase">Reference</dt>
                                <dd class="font-mono text-gray-800 mt-0.5">{{ $submission->reference_number }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs text-gray-400 uppercase">Sample</dt>
                                <dd class="text-gray-800 mt-0.5">{{ $submission->sample_name }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs text-gray-400 uppercase">Type</dt>
                                <dd class="text-gray-700 mt-0.5 capitalize">{{ $submission->sample_type }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs text-gray-400 uppercase">Collected</dt>
                                <dd class="text-gray-700 mt-0.5">{{ $submission->collected_at?->format('d M Y') ?? '—' }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs text-gray-400 uppercase">Submitted</dt>
                                <dd class="text-gray-700 mt-0.5">{{ $submission->submitted_at?->format('d M Y') ?? '—' }}</dd>
                            </div>
                        </dl>
                    </div>

                    @if($result?->director_comments)
                        <div class="bg-blue-50 rounded-xl border border-blue-200 overflow-hidden">
                            <div class="px-5 py-3.5 border-b border-blue-100">
                                <h3 class="text-sm font-medium text-blue-800">Director Comments</h3>
                            </div>
                            <p class="px-5 py-4 text-sm text-blue-900 leading-relaxed">
                                {{ $result->director_comments }}
                            </p>
                        </div>
                    @endif
                </div>

                {{-- Right: Test results --}}
                <div class="lg:col-span-2 space-y-4">

                    @foreach($submission->samples as $sample)
                        <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                                <div>
                                    <h3 class="text-sm font-medium text-gray-800">
                                        {{ $sample->common_name ?? $sample->sample_code }}
                                    </h3>
                                    <p class="text-xs text-gray-400 mt-0.5">{{ $sample->sample_code }}</p>
                                </div>
                            </div>

                            @if($sample->sampleTests->isEmpty())
                                <p class="px-6 py-4 text-sm text-gray-400">No test results available.</p>
                            @else
                                <div class="overflow-x-auto">
                                    <table class="w-full text-sm">
                                        <thead class="bg-gray-50 border-b border-gray-100">
                                            <tr>
                                                <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase">Test</th>
                                                <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase">Category</th>
                                                <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase">Result</th>
                                                <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase">Qualifier</th>
                                                <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase">Unit</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-50">
                                            @foreach($sample->sampleTests as $test)
                                                @php
                                                    $qualColors = [
                                                        'pass'         => 'bg-green-50 text-green-700',
                                                        'fail'         => 'bg-red-50 text-red-700',
                                                        'detected'     => 'bg-red-50 text-red-700',
                                                        'not_detected' => 'bg-green-50 text-green-700',
                                                        'pending'      => 'bg-gray-100 text-gray-400',
                                                    ];
                                                    $qColor = $qualColors[$test->result_qualifier] ?? 'bg-gray-100 text-gray-500';
                                                @endphp
                                                <tr>
                                                    <td class="px-5 py-3 text-gray-800 font-medium">
                                                        {{ $test->getDisplayLabel() }}
                                                    </td>
                                                    <td class="px-5 py-3 text-xs text-gray-500 capitalize">
                                                        {{ $test->getDisplayCategory() }}
                                                    </td>
                                                    <td class="px-5 py-3 text-gray-700">
                                                        {{ $test->result_value ?? '—' }}
                                                    </td>
                                                    <td class="px-5 py-3">
                                                        <span class="inline-flex px-2 py-0.5 text-xs font-medium rounded-full capitalize {{ $qColor }}">
                                                            {{ str_replace('_', ' ', $test->result_qualifier ?? 'pending') }}
                                                        </span>
                                                    </td>
                                                    <td class="px-5 py-3 text-xs text-gray-400">
                                                        {{ $test->result_unit ?? '—' }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    @endforeach

                </div>
            </div>

            <div class="pb-8"></div>

        </div>
    </div>
</x-app-layout>