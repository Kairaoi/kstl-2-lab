{{-- resources/views/kstl/auditor/audit/index.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <div style="position:relative;overflow:hidden;background:linear-gradient(135deg,#0f2240 0%,#1a2f4e 60%,#1e3a5f 100%);">
            <div style="height:3px;background:linear-gradient(90deg,#1a2f4e,#b8922a 30%,#b8922a 70%,#1a2f4e);"></div>
            <div style="max-width:80rem;margin:0 auto;padding:28px 2rem 32px;">
                <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;">
                    <div style="display:flex;align-items:center;gap:20px;">
                        <img src="{{ asset('images/mfor-logo.png') }}" alt="MFOR" style="filter:brightness(0) invert(1);opacity:.92;width:56px;height:56px;flex-shrink:0;">
                        <div>
                            <p style="font-size:9px;font-weight:700;letter-spacing:.18em;text-transform:uppercase;color:#b8922a;margin:0 0 4px;">Audit Portal</p>
                            <h1 style="font-family:'Georgia',serif;font-size:22px;font-weight:700;color:#fff;margin:0 0 6px;line-height:1.2;">Audit Log</h1>
                            <p style="font-size:12px;color:#94a3b8;margin:0;">ISO 17025 — Immutable append-only record of all system actions</p>
                        </div>
                    </div>
                    <div style="display:flex;align-items:center;gap:24px;flex-wrap:wrap;">
                        <div style="text-align:center;">
                            <p style="font-size:24px;font-weight:700;color:#fff;margin:0 0 2px;">{{ number_format($summary['today']) }}</p>
                            <p style="font-size:11px;color:#94a3b8;margin:0;">Today</p>
                        </div>
                        <div style="text-align:center;">
                            <p style="font-size:24px;font-weight:700;color:#fff;margin:0 0 2px;">{{ number_format($summary['this_week']) }}</p>
                            <p style="font-size:11px;color:#94a3b8;margin:0;">This week</p>
                        </div>
                        <div style="text-align:center;">
                            <p style="font-size:24px;font-weight:700;color:#fff;margin:0 0 2px;">{{ number_format($summary['total']) }}</p>
                            <p style="font-size:11px;color:#94a3b8;margin:0;">All time</p>
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

            {{-- Filters --}}
            <form method="GET" action="{{ route('auditor.audit.index') }}"
                  style="background:#fff;border:1px solid #e2e8f0;border-radius:4px;padding:20px 24px;display:flex;flex-wrap:wrap;align-items:flex-end;gap:12px;margin-bottom:20px;">

                <div>
                    <label style="display:block;font-size:11px;font-weight:600;color:#64748b;margin-bottom:4px;text-transform:uppercase;letter-spacing:.06em;">Event</label>
                    <select name="event"
                            style="border:1px solid #cbd5e1;border-radius:3px;font-size:13px;padding:7px 10px;color:#374151;background:#fff;">
                        <option value="">All Events</option>
                        @foreach([
                            'login'          => 'Login',
                            'submitted'      => 'Submitted',
                            'status_changed' => 'Status Changed',
                            'signed'         => 'Signed',
                            'countersigned'  => 'Countersigned',
                            'authorised'     => 'Authorised',
                            'queried'        => 'Queried',
                            'generated'      => 'Generated',
                            'responded'      => 'Responded',
                            'created'        => 'Created',
                            'updated'        => 'Updated',
                            'deleted'        => 'Deleted',
                        ] as $val => $label)
                            <option value="{{ $val }}" {{ request('event') == $val ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label style="display:block;font-size:11px;font-weight:600;color:#64748b;margin-bottom:4px;text-transform:uppercase;letter-spacing:.06em;">User</label>
                    <input type="text" name="user" value="{{ request('user') }}"
                           placeholder="Name..."
                           style="border:1px solid #cbd5e1;border-radius:3px;font-size:13px;padding:7px 10px;color:#374151;width:160px;"/>
                </div>

                <div>
                    <label style="display:block;font-size:11px;font-weight:600;color:#64748b;margin-bottom:4px;text-transform:uppercase;letter-spacing:.06em;">Entity Type</label>
                    <select name="entity"
                            style="border:1px solid #cbd5e1;border-radius:3px;font-size:13px;padding:7px 10px;color:#374151;background:#fff;">
                        <option value="">All</option>
                        @foreach(['Client','Submission','Result','Invoice','Complaint'] as $entity)
                            <option value="{{ $entity }}" {{ request('entity') == $entity ? 'selected' : '' }}>
                                {{ $entity }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label style="display:block;font-size:11px;font-weight:600;color:#64748b;margin-bottom:4px;text-transform:uppercase;letter-spacing:.06em;">From</label>
                    <input type="date" name="from" value="{{ request('from') }}"
                           style="border:1px solid #cbd5e1;border-radius:3px;font-size:13px;padding:7px 10px;color:#374151;"/>
                </div>

                <div>
                    <label style="display:block;font-size:11px;font-weight:600;color:#64748b;margin-bottom:4px;text-transform:uppercase;letter-spacing:.06em;">To</label>
                    <input type="date" name="to" value="{{ request('to') }}"
                           style="border:1px solid #cbd5e1;border-radius:3px;font-size:13px;padding:7px 10px;color:#374151;"/>
                </div>

                <button type="submit"
                        style="display:inline-flex;align-items:center;gap:8px;padding:8px 20px;background:#1a2f4e;color:#fff;font-size:12px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;border-radius:3px;border:none;cursor:pointer;">
                    Filter
                </button>

                @if(request()->hasAny(['event','user','entity','from','to']))
                    <a href="{{ route('auditor.audit.index') }}"
                       style="display:inline-flex;align-items:center;gap:8px;padding:8px 20px;background:#fff;color:#1a2f4e;font-size:12px;font-weight:700;letter-spacing:.06em;border:1px solid #1a2f4e;border-radius:3px;text-decoration:none;">
                        Clear
                    </a>
                @endif
            </form>

            {{-- Log Table --}}
            <div style="background:#fff;border:1px solid #e2e8f0;border-radius:4px;overflow:hidden;">
                <div style="padding:20px 24px;border-bottom:1px solid #e2e8f0;">
                    <h3 style="font-family:'Georgia',serif;font-size:14px;font-weight:700;color:#1a2f4e;margin:0;">
                        {{ number_format($logs->total()) }} entries
                        @if(request()->hasAny(['event','user','entity','from','to']))
                            <span style="font-size:12px;color:#94a3b8;font-family:sans-serif;font-weight:400;margin-left:8px;">(filtered)</span>
                        @endif
                    </h3>
                </div>

                @if($logs->isEmpty())
                    <div style="padding:64px 24px;text-align:center;">
                        <svg style="width:40px;height:40px;color:#e2e8f0;margin:0 auto 12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <p style="font-size:13px;color:#94a3b8;margin:0;">No audit log entries found.</p>
                    </div>
                @else
                    <div style="overflow-x:auto;">
                        <table style="width:100%;border-collapse:collapse;">
                            <thead>
                                <tr style="background:#1a2f4e;">
                                    <th style="padding:10px 16px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">Timestamp</th>
                                    <th style="padding:10px 16px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">User</th>
                                    <th style="padding:10px 16px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">Event</th>
                                    <th style="padding:10px 16px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">Description</th>
                                    <th style="padding:10px 16px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">Entity</th>
                                    <th style="padding:10px 16px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">IP Address</th>
                                    <th style="padding:10px 16px;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($logs as $log)
                                    @php
                                        $eventBadgeStyle = match($log->event) {
                                            'login'          => 'background:#eff6ff;color:#1d4ed8;',
                                            'submitted'      => 'background:#eef2ff;color:#4338ca;',
                                            'status_changed' => 'background:#fefce8;color:#854d0e;',
                                            'signed'         => 'background:#faf5ff;color:#7e22ce;',
                                            'countersigned'  => 'background:#f3e8ff;color:#6b21a8;',
                                            'authorised'     => 'background:#f0fdf4;color:#166534;',
                                            'queried'        => 'background:#fff7ed;color:#9a3412;',
                                            'generated'      => 'background:#f0fdfa;color:#134e4a;',
                                            'responded'      => 'background:#f0f9ff;color:#075985;',
                                            'deleted'        => 'background:#fef2f2;color:#991b1b;',
                                            default          => 'background:#f1f5f9;color:#475569;',
                                        };
                                        $entity = class_basename($log->auditable_type ?? '');
                                    @endphp
                                    <tr style="border-bottom:1px solid #f1f5f9;{{ $loop->even ? 'background:#f8fafc;' : '' }}">
                                        <td style="padding:12px 16px;font-family:monospace;font-size:11px;color:#64748b;white-space:nowrap;">
                                            {{ $log->created_at->format('d M Y') }}<br>
                                            <span style="color:#94a3b8;">{{ $log->created_at->format('H:i:s') }}</span>
                                        </td>
                                        <td style="padding:12px 16px;">
                                            <p style="font-size:13px;font-weight:600;color:#1e293b;margin:0;">{{ $log->user_name ?? 'System' }}</p>
                                        </td>
                                        <td style="padding:12px 16px;">
                                            <span style="display:inline-flex;align-items:center;padding:3px 10px;border-radius:20px;font-size:10px;font-weight:700;text-transform:capitalize;white-space:nowrap;{{ $eventBadgeStyle }}">
                                                {{ str_replace('_', ' ', $log->event) }}
                                            </span>
                                        </td>
                                        <td style="padding:12px 16px;font-size:13px;color:#374151;max-width:280px;">
                                            {{ $log->description }}
                                        </td>
                                        <td style="padding:12px 16px;font-size:12px;color:#94a3b8;white-space:nowrap;">
                                            {{ $entity ?: '—' }}
                                        </td>
                                        <td style="padding:12px 16px;font-family:monospace;font-size:12px;color:#94a3b8;white-space:nowrap;">
                                            {{ $log->ip_address ?? '—' }}
                                        </td>
                                        <td style="padding:12px 16px;text-align:right;">
                                            <a href="{{ route('auditor.audit.show', $log->id) }}"
                                               style="display:inline-flex;align-items:center;gap:8px;padding:5px 14px;background:#fff;color:#1a2f4e;font-size:11px;font-weight:700;letter-spacing:.06em;border:1px solid #1a2f4e;border-radius:3px;text-decoration:none;">
                                                Detail
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div style="padding:16px 20px;border-top:1px solid #e2e8f0;">
                        {{ $logs->withQueryString()->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
