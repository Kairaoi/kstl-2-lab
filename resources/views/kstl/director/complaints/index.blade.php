{{-- resources/views/kstl/director/complaints/index.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <div style="position:relative;overflow:hidden;background:linear-gradient(135deg,#0f2240 0%,#1a2f4e 60%,#1e3a5f 100%);">
            <div style="height:3px;background:linear-gradient(90deg,#1a2f4e,#b8922a 30%,#b8922a 70%,#1a2f4e);"></div>
            <div style="max-width:80rem;margin:0 auto;padding:28px 2rem 32px;">
                <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;">
                    <div style="display:flex;align-items:center;gap:20px;">
                        <img src="{{ asset('images/mfor-logo.png') }}" alt="MFOR" style="filter:brightness(0) invert(1);opacity:.92;width:56px;height:56px;flex-shrink:0;">
                        <div>
                            <p style="font-size:9px;font-weight:700;letter-spacing:.18em;text-transform:uppercase;color:#b8922a;margin:0 0 4px;">Director Portal</p>
                            <h1 style="font-family:'Georgia',serif;font-size:22px;font-weight:700;color:#fff;margin:0 0 6px;line-height:1.2;">Complaints</h1>
                            <p style="font-size:12px;color:#94a3b8;margin:0;">Review and respond to client complaints</p>
                        </div>
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

            @if(session('success'))
                <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-left:4px solid #16a34a;border-radius:4px;padding:12px 16px;margin-bottom:20px;font-size:13px;color:#166534;">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div style="background:#fef2f2;border:1px solid #fecaca;border-left:4px solid #dc2626;border-radius:4px;padding:12px 16px;margin-bottom:20px;font-size:13px;color:#991b1b;">{{ session('error') }}</div>
            @endif

            {{-- Summary Cards --}}
            <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:24px;">
                @foreach(['open' => ['#b8922a','Open'], 'under_investigation' => ['#1a2f4e','Under Investigation'], 'resolved' => ['#0d9488','Resolved'], 'closed' => ['#64748b','Closed']] as $status => [$color, $label])
                    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:4px;border-left:4px solid {{ $color }};padding:18px 20px;">
                        <p style="font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#64748b;margin:0 0 6px;">{{ $label }}</p>
                        <p style="font-size:28px;font-weight:700;color:#1a2f4e;margin:0;line-height:1;">{{ $counts[$status] }}</p>
                    </div>
                @endforeach
            </div>

            {{-- Complaints Table --}}
            <div style="background:#fff;border:1px solid #e2e8f0;border-radius:4px;overflow:hidden;margin-bottom:24px;">
                <div style="padding:16px 24px;border-bottom:1px solid #e2e8f0;">
                    <h3 style="font-family:'Georgia',serif;font-size:14px;font-weight:700;color:#1a2f4e;margin:0;">All Complaints</h3>
                </div>

                @if($complaints->isEmpty())
                    <div style="padding:64px 24px;text-align:center;">
                        <p style="font-size:13px;color:#94a3b8;">No complaints recorded.</p>
                    </div>
                @else
                    <div style="overflow-x:auto;">
                        <table style="width:100%;border-collapse:collapse;">
                            <thead>
                                <tr style="background:#1a2f4e;">
                                    <th style="padding:10px 16px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">Subject</th>
                                    <th style="padding:10px 16px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">From</th>
                                    <th style="padding:10px 16px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">Type</th>
                                    <th style="padding:10px 16px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">Lodged</th>
                                    <th style="padding:10px 16px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">Status</th>
                                    <th style="padding:10px 16px;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($complaints as $complaint)
                                    @php
                                        $statusBadge = match($complaint->status) {
                                            'open'                 => 'background:#fef9c3;color:#854d0e;',
                                            'under_investigation'  => 'background:#dbeafe;color:#1e40af;',
                                            'resolved'             => 'background:#dcfce7;color:#166534;',
                                            'closed'               => 'background:#f1f5f9;color:#64748b;',
                                            default                => 'background:#f1f5f9;color:#64748b;',
                                        };
                                    @endphp
                                    <tr style="border-bottom:1px solid #f1f5f9;{{ $loop->even ? 'background:#f8fafc;' : '' }}">
                                        <td style="padding:12px 16px;font-size:13px;font-weight:600;color:#1e293b;">
                                            {{ $complaint->subject }}
                                        </td>
                                        <td style="padding:12px 16px;">
                                            <p style="font-size:13px;color:#374151;margin:0;">{{ $complaint->complainant_name ?? '—' }}</p>
                                            @if($complaint->complainant_organisation)
                                                <p style="font-size:11px;color:#94a3b8;margin:2px 0 0;">{{ $complaint->complainant_organisation }}</p>
                                            @endif
                                        </td>
                                        <td style="padding:12px 16px;font-size:12px;color:#64748b;">
                                            {{ implode(', ', $complaint->getComplaintTypeLabels()) }}
                                        </td>
                                        <td style="padding:12px 16px;font-size:12px;color:#64748b;">
                                            {{ $complaint->created_at->format('d M Y') }}
                                        </td>
                                        <td style="padding:12px 16px;">
                                            <span style="display:inline-flex;padding:2px 8px;font-size:11px;font-weight:600;border-radius:9999px;text-transform:capitalize;{{ $statusBadge }}">
                                                {{ str_replace('_', ' ', $complaint->status) }}
                                            </span>
                                        </td>
                                        <td style="padding:12px 16px;text-align:right;">
                                            <a href="{{ route('director.complaints.show', $complaint->id) }}"
                                               style="display:inline-flex;align-items:center;gap:8px;padding:6px 14px;background:#0d9488;color:#fff;font-size:11px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;border-radius:3px;text-decoration:none;">
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
