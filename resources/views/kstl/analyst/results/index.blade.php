{{-- resources/views/kstl/analyst/results/index.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <div style="position:relative;overflow:hidden;background:linear-gradient(135deg,#0f2240 0%,#1a2f4e 60%,#1e3a5f 100%);">
            <div style="height:3px;background:linear-gradient(90deg,#1a2f4e,#b8922a 30%,#b8922a 70%,#1a2f4e);"></div>
            <div style="max-width:80rem;margin:0 auto;padding:28px 2rem 32px;">
                <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;">
                    <div style="display:flex;align-items:center;gap:20px;">
                        <img src="{{ asset('images/mfor-logo.png') }}" alt="MFOR" style="filter:brightness(0) invert(1);opacity:.92;width:56px;height:56px;flex-shrink:0;">
                        <div>
                            <p style="font-size:9px;font-weight:700;letter-spacing:.18em;text-transform:uppercase;color:#b8922a;margin:0 0 4px;">Analyst Portal</p>
                            <h1 style="font-family:'Georgia',serif;font-size:22px;font-weight:700;color:#fff;margin:0 0 6px;line-height:1.2;">Authorised Results</h1>
                            <p style="font-size:12px;color:#94a3b8;margin:0;">Read-only — final reports signed off by the Director</p>
                        </div>
                    </div>
                    <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                        <span style="display:inline-flex;align-items:center;gap:6px;padding:6px 14px;background:rgba(184,146,42,.15);border:1px solid rgba(184,146,42,.4);border-radius:20px;font-size:10px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:#b8922a;">
                            Analyst
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    @push('styles')
    <style>
        .page-hdr { padding: 0 !important; }
        .page-hdr-inner { max-width: 100% !important; padding: 0 !important; }
        .app-main { padding-left:0 !important; padding-right:0 !important; padding-top:0 !important; max-width:100% !important; }
    </style>
    @endpush

    <div style="background:#f1f5f9;min-height:100vh;padding:52px 0 56px;">
        <div style="max-width:80rem;margin:0 auto;padding:0 2rem;">

            <div style="background:#fff;border:1px solid #e2e8f0;border-radius:4px;overflow:hidden;margin-bottom:24px;">
                <div style="padding:20px 24px;border-bottom:1px solid #e2e8f0;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;">
                    <div>
                        <h3 style="font-family:'Georgia',serif;font-size:14px;font-weight:700;color:#1a2f4e;margin:0 0 4px;">Submissions with authorised results</h3>
                        <p style="font-size:11px;color:#94a3b8;margin:0;">Authorised and completed reports</p>
                    </div>
                    <span style="font-size:11px;color:#64748b;background:#f1f5f9;padding:4px 12px;border-radius:20px;font-weight:600;">
                        {{ $submissions->count() }} total
                    </span>
                </div>

                @if($submissions->isEmpty())
                    <div style="padding:64px 24px;text-align:center;">
                        <svg style="width:40px;height:40px;color:#e2e8f0;margin:0 auto 12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <p style="font-size:13px;font-weight:600;color:#94a3b8;margin:0 0 4px;">No authorised results yet</p>
                        <p style="font-size:12px;color:#cbd5e1;margin:0;">Reports appear here once the Director authorises them.</p>
                    </div>
                @else
                    <div style="overflow-x:auto;">
                        <table style="width:100%;border-collapse:collapse;">
                            <thead>
                                <tr style="background:#1a2f4e;">
                                    <th style="padding:10px 16px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">Reference</th>
                                    <th style="padding:10px 16px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">Client</th>
                                    <th style="padding:10px 16px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">Outcome</th>
                                    <th style="padding:10px 16px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">Status</th>
                                    <th style="padding:10px 16px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">Authorised</th>
                                    <th style="padding:10px 16px;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($submissions as $submission)
                                    @php
                                        $outcome = $submission->result?->overall_outcome;
                                        $outcomeStyle = match($outcome) {
                                            'pass'         => 'background:#f0fdf4;color:#166534;',
                                            'fail'         => 'background:#fef2f2;color:#991b1b;',
                                            'inconclusive' => 'background:#fffbeb;color:#92400e;',
                                            default        => 'background:#f1f5f9;color:#64748b;',
                                        };
                                    @endphp
                                    <tr style="border-bottom:1px solid #f1f5f9;{{ $loop->even ? 'background:#f8fafc;' : '' }}">
                                        <td style="padding:12px 16px;font-family:monospace;font-size:12px;font-weight:600;color:#374151;">
                                            {{ $submission->reference_number }}
                                        </td>
                                        <td style="padding:12px 16px;">
                                            <p style="font-size:13px;font-weight:600;color:#1e293b;margin:0;">{{ $submission->client->company_name ?? '—' }}</p>
                                        </td>
                                        <td style="padding:12px 16px;">
                                            @if($outcome)
                                                <span style="display:inline-flex;align-items:center;padding:3px 10px;border-radius:20px;font-size:10px;font-weight:700;text-transform:uppercase;{{ $outcomeStyle }}">
                                                    {{ $outcome }}
                                                </span>
                                            @else
                                                <span style="font-size:12px;color:#94a3b8;">—</span>
                                            @endif
                                        </td>
                                        <td style="padding:12px 16px;">
                                            <x-kstl.status-badge :status="$submission->status" />
                                        </td>
                                        <td style="padding:12px 16px;font-size:12px;color:#64748b;">
                                            @if($submission->result?->authorised_at)
                                                {{ $submission->result->authorised_at->format('d M Y') }}
                                                <p style="font-size:11px;color:#94a3b8;margin:2px 0 0;">{{ $submission->result->authorisedBy?->name }}</p>
                                            @else
                                                —
                                            @endif
                                        </td>
                                        <td style="padding:12px 16px;text-align:right;">
                                            <a href="{{ route('analyst.results.show', $submission->id) }}"
                                               style="display:inline-flex;align-items:center;gap:8px;padding:6px 16px;background:#fff;color:#1a2f4e;font-size:11px;font-weight:700;letter-spacing:.06em;border:1px solid #1a2f4e;border-radius:3px;text-decoration:none;">
                                                View report
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
