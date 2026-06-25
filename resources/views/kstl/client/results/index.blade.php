{{-- resources/views/kstl/client/results/index.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">My Results</h2>
    </x-slot>

    <div style="background:#f1f5f9; min-height:100vh; padding:52px 0 56px;">
        <div style="max-width:80rem; margin:0 auto; padding:0 2rem;">

            {{-- Page Header --}}
            <div style="margin-bottom:24px;">
                <h1 style="font-family:'Georgia',serif; font-size:22px; font-weight:700; color:#1a2f4e; margin:0 0 4px;">
                    Test Results
                </h1>
                <p style="font-size:12.5px; color:#6b7280; margin:0;">
                    Authorised certificates of analysis for your submissions
                </p>
            </div>

            {{-- Results Card --}}
            <div style="background:#fff; border:1px solid #e2e8f0; border-radius:4px; overflow:hidden;">

                <div style="padding:16px 24px 14px;">
                    <h3 style="font-family:'Georgia',serif; font-size:15px; font-weight:700; color:#1a2f4e; border-bottom:2px solid #b8922a; padding-bottom:8px; margin-bottom:0;">
                        Authorised Results
                    </h3>
                </div>

                @if($submissions->isEmpty())
                    <div style="padding:56px 24px; text-align:center;">
                        <svg style="width:40px; height:40px; color:#e2e8f0; margin:0 auto 12px; display:block;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <p style="font-size:13px; color:#9ca3af; margin:0 0 4px;">No results yet</p>
                        <p style="font-size:11.5px; color:#d1d5db; margin:0;">Results will appear here once the Director has authorised them.</p>
                    </div>
                @else
                    <div style="overflow-x:auto;">
                        <table style="width:100%; border-collapse:collapse;">
                            <thead>
                                <tr style="background:#1a2f4e;">
                                    <th style="text-align:left; padding:9px 16px; font-size:9px; font-weight:700; letter-spacing:.12em; text-transform:uppercase; color:#e2e8f0;">Reference</th>
                                    <th style="text-align:left; padding:9px 16px; font-size:9px; font-weight:700; letter-spacing:.12em; text-transform:uppercase; color:#e2e8f0;">Sample</th>
                                    <th style="text-align:left; padding:9px 16px; font-size:9px; font-weight:700; letter-spacing:.12em; text-transform:uppercase; color:#e2e8f0;">Authorised</th>
                                    <th style="text-align:left; padding:9px 16px; font-size:9px; font-weight:700; letter-spacing:.12em; text-transform:uppercase; color:#e2e8f0;">By</th>
                                    <th style="padding:9px 16px;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($submissions as $i => $submission)
                                    @php
                                        $result   = $submission->result;
                                        $outcome  = $result?->overall_outcome ?? 'pending';
                                        $colors   = [
                                            'pass'         => 'bg-green-50 text-green-700',
                                            'fail'         => 'bg-red-50 text-red-700',
                                            'inconclusive' => 'bg-yellow-50 text-yellow-700',
                                        ];
                                        $color   = $colors[$outcome] ?? 'bg-gray-100 text-gray-500';
                                        $invoice = $submission->invoice;
                                        $locked  = ! $invoice || (! $invoice->isPaid() && ! $invoice->isWaived());
                                        $rowBg   = $i % 2 === 0 ? '#fff' : '#f8fafc';
                                    @endphp
                                    <tr style="background:{{ $rowBg }}; border-bottom:1px solid #f1f5f9;">
                                        <td style="padding:11px 16px; font-size:12.5px;">
                                            <span style="font-family:monospace; font-size:11.5px; color:#374151;">
                                                {{ $submission->reference_number }}
                                            </span>
                                        </td>
                                        <td style="padding:11px 16px; font-size:12.5px; color:#1a2f4e; font-weight:600;">
                                            {{ $submission->sample_name }}
                                            <span style="display:block; font-size:11px; color:#9ca3af; font-weight:400; text-transform:capitalize;">{{ $submission->sample_type }}</span>
                                        </td>
                                        <td style="padding:11px 16px; font-size:12.5px; color:#6b7280;">
                                            {{ $result?->authorised_at?->format('d M Y') ?? '—' }}
                                        </td>
                                        <td style="padding:11px 16px; font-size:12.5px; color:#6b7280;">
                                            {{ $result?->authorisedBy?->name ?? '—' }}
                                        </td>
                                        <td style="padding:11px 16px; text-align:right;">
                                            @if(! $locked)
                                                <a href="{{ route('client.results.show', $submission->id) }}"
                                                   style="background:#1a2f4e; color:#fff; padding:8px 18px; border-radius:3px; font-size:12px; font-weight:600; text-decoration:none; display:inline-flex; align-items:center; gap:6px;">
                                                    <svg style="width:12px; height:12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                    </svg>
                                                    View CoA
                                                </a>
                                            @elseif($invoice)
                                                <a href="{{ route('client.invoices.show', $invoice->id) }}"
                                                   style="border:1px solid #fcd34d; color:#b45309; padding:8px 16px; border-radius:3px; font-size:12px; font-weight:600; text-decoration:none; background:#fffbeb; display:inline-flex; align-items:center; gap:6px;">
                                                    <svg style="width:12px; height:12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                                    Pay Invoice
                                                </a>
                                            @else
                                                <span style="border:1px solid #e2e8f0; color:#9ca3af; padding:8px 16px; border-radius:3px; font-size:12px; background:#f9fafb; display:inline-flex; align-items:center; gap:6px; cursor:default;">
                                                    <svg style="width:12px; height:12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                                    Pending
                                                </span>
                                            @endif
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
