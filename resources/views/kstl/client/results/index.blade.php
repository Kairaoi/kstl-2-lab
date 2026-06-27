{{-- resources/views/kstl/client/results/index.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <div style="background:linear-gradient(135deg,#0f2240 0%,#1a2f4e 60%,#1e3a5f 100%); margin:-1px; padding:28px 2rem; position:relative; overflow:hidden;">
            <div style="position:absolute;inset:0;opacity:.04;background-image:repeating-linear-gradient(45deg,#fff 0,#fff 1px,transparent 0,transparent 50%);background-size:12px 12px;pointer-events:none;"></div>
            <div style="position:absolute;top:0;left:0;right:0;height:3px;background:linear-gradient(90deg,#1a2f4e,#b8922a 30%,#b8922a 70%,#1a2f4e);"></div>
            <div style="max-width:80rem;margin:0 auto;width:100%;display:flex;align-items:center;justify-content:space-between;gap:20px;flex-wrap:wrap;position:relative;">
                <div style="display:flex;align-items:center;gap:18px;">
                    <img src="{{ asset('images/mfor-logo.png') }}"
                         alt="Ministry of Fisheries &amp; Ocean Resources"
                         style="width:56px;height:56px;object-fit:contain;filter:brightness(0) invert(1);opacity:.92;">
                    <div>
                        <p style="font-size:8.5px;font-weight:700;letter-spacing:.22em;text-transform:uppercase;color:#b8922a;margin-bottom:5px;">
                            Client Portal &nbsp;·&nbsp; Seafood Toxicology Laboratory
                        </p>
                        <h1 style="font-family:'Georgia',serif;font-size:22px;font-weight:700;color:#ffffff;line-height:1.2;margin:0;">
                            Test Results
                        </h1>
                        <p style="font-size:11px;color:#94a3b8;margin-top:4px;">
                            Authorised certificates of analysis for your submissions
                        </p>
                    </div>
                </div>
                <div style="text-align:right;">
                    <p style="font-size:9px;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:#64748b;margin-bottom:4px;">Today</p>
                    <p style="font-size:13px;font-weight:600;color:#e2e8f0;">{{ now()->format('d F Y') }}</p>
                    <a href="{{ route('client.dashboard') }}"
                       style="display:inline-flex;align-items:center;gap:5px;margin-top:8px;font-size:11px;font-weight:600;color:#94a3b8;text-decoration:none;">
                        <svg style="width:12px;height:12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        Back to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </x-slot>

    @push('styles')
    <style>
        .page-hdr { padding: 0 !important; }
        .page-hdr-inner { max-width: 100% !important; padding: 0 !important; }
        .app-main { padding-left: 0 !important; padding-right: 0 !important;
                    padding-top: 0 !important; max-width: 100% !important; }
    </style>
    @endpush

    <div style="background:#f1f5f9; min-height:100vh; padding:52px 0 56px;">
        <div style="max-width:80rem; margin:0 auto; padding:0 2rem; display:flex; flex-direction:column; gap:24px;">

            {{-- Flash messages --}}
            @foreach(['success' => ['#f0fdf4','#22c55e','#166534'], 'error' => ['#fef2f2','#ef4444','#991b1b'], 'info' => ['#eff6ff','#3b82f6','#1e40af'], 'warning' => ['#fffbeb','#f59e0b','#92400e']] as $type => [$bg,$border,$text])
                @if(session($type))
                    <div style="background:{{ $bg }};border-left:4px solid {{ $border }};padding:12px 18px;border-radius:0 4px 4px 0;">
                        <p style="font-size:13px;color:{{ $text }};margin:0;">{{ session($type) }}</p>
                    </div>
                @endif
            @endforeach

            {{-- Results Card --}}
            <div style="background:#fff; border:1px solid #e2e8f0; border-radius:4px; overflow:hidden; margin-top:24px;">

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
                                        $invoice  = $submission->invoice;
                                        $locked   = ! $invoice || (! $invoice->isPaid() && ! $invoice->isWaived());
                                        $rowBg    = $i % 2 === 0 ? '#fff' : '#f8fafc';
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
