{{-- resources/views/kstl/director/audit/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div style="position:relative;overflow:hidden;background:linear-gradient(135deg,#0f2240 0%,#1a2f4e 60%,#1e3a5f 100%);margin:-1px;">
            <div style="height:3px;background:linear-gradient(90deg,#1a2f4e,#b8922a 30%,#b8922a 70%,#1a2f4e);"></div>
            <div style="max-width:80rem;margin:0 auto;padding:28px 2rem 32px;">
                <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;">
                    <div style="display:flex;align-items:center;gap:20px;">
                        <img src="{{ asset('images/mfor-logo.png') }}" alt="MFOR" style="filter:brightness(0) invert(1);opacity:.92;width:56px;height:56px;flex-shrink:0;">
                        <div>
                            <p style="font-size:9px;font-weight:700;letter-spacing:.18em;text-transform:uppercase;color:#b8922a;margin:0 0 4px;">Director</p>
                            <h1 style="font-family:'Georgia',serif;font-size:22px;font-weight:700;color:#fff;margin:0 0 6px;line-height:1.2;">Audit Log</h1>
                            <p style="font-size:12px;color:#94a3b8;margin:0;">Immutable record of all significant actions &mdash; ISO 17025</p>
                        </div>
                    </div>
                    <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                        <span style="display:inline-flex;align-items:center;padding:5px 14px;background:#fee2e2;color:#991b1b;border:1px solid #fca5a5;border-radius:3px;font-size:11px;font-weight:700;letter-spacing:.06em;">
                            CONFIDENTIAL
                        </span>
                        <a href="{{ route('director.dashboard') }}"
                           style="display:inline-flex;align-items:center;gap:8px;padding:8px 20px;background:#fff;color:#1a2f4e;font-size:12px;font-weight:700;letter-spacing:.06em;border:1px solid #fff;border-radius:3px;text-decoration:none;">
                            &larr; Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    @push('styles')
    <style>
        .page-hdr { padding: 0 !important; position: static !important; }
        .page-hdr-inner { max-width: 100% !important; padding: 0 !important; }
        .app-main { padding-left:0 !important; padding-right:0 !important; padding-top:0 !important; max-width:100% !important; }
    </style>
    @endpush

    <div style="background:#f1f5f9;min-height:100vh;padding:0 0 56px;">
        <div style="max-width:80rem;margin:0 auto;padding:0 2rem;">

            {{-- ── Filters ──────────────────────────────────────────── --}}
            <form method="GET" action="{{ route('director.audit.index') }}"
                  style="background:#fff;border:1px solid #e2e8f0;border-radius:4px;padding:16px 20px;margin-bottom:20px;display:flex;flex-wrap:wrap;align-items:flex-end;gap:12px;">
                <div style="flex:1;min-width:144px;">
                    <label style="display:block;font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#475569;margin-bottom:6px;">Event type</label>
                    <select name="event"
                            style="width:100%;padding:8px 12px;border:1px solid #cbd5e1;border-radius:3px;font-size:13px;color:#1e293b;background:#fff;">
                        <option value="">All events</option>
                        @foreach([
                            'created','updated','deleted','login','logout',
                            'signed','countersigned','authorised','queried',
                            'submitted','status_changed','generated','responded'
                        ] as $evt)
                            <option value="{{ $evt }}" {{ request('event') === $evt ? 'selected' : '' }}>
                                {{ ucfirst(str_replace('_', ' ', $evt)) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div style="flex:1;min-width:144px;">
                    <label style="display:block;font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#475569;margin-bottom:6px;">User</label>
                    <input type="text" name="user" value="{{ request('user') }}"
                           placeholder="Name or ID&hellip;"
                           style="width:100%;padding:8px 12px;border:1px solid #cbd5e1;border-radius:3px;font-size:13px;color:#1e293b;background:#fff;">
                </div>
                <div style="min-width:144px;">
                    <label style="display:block;font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#475569;margin-bottom:6px;">From</label>
                    <input type="date" name="from" value="{{ request('from') }}"
                           style="width:100%;padding:8px 12px;border:1px solid #cbd5e1;border-radius:3px;font-size:13px;color:#1e293b;background:#fff;">
                </div>
                <div style="min-width:144px;">
                    <label style="display:block;font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#475569;margin-bottom:6px;">To</label>
                    <input type="date" name="to" value="{{ request('to') }}"
                           style="width:100%;padding:8px 12px;border:1px solid #cbd5e1;border-radius:3px;font-size:13px;color:#1e293b;background:#fff;">
                </div>
                <div style="display:flex;align-items:center;gap:8px;">
                    <button type="submit"
                            style="display:inline-flex;align-items:center;gap:8px;padding:8px 20px;background:#1a2f4e;color:#fff;font-size:12px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;border-radius:3px;border:none;cursor:pointer;">
                        Filter
                    </button>
                    @if(request()->hasAny(['event','user','from','to']))
                        <a href="{{ route('director.audit.index') }}"
                           style="display:inline-flex;align-items:center;gap:8px;padding:8px 20px;background:#fff;color:#1a2f4e;font-size:12px;font-weight:700;letter-spacing:.06em;border:1px solid #1a2f4e;border-radius:3px;text-decoration:none;">
                            Clear
                        </a>
                    @endif
                </div>
            </form>

            {{-- ── Summary strip ────────────────────────────────────── --}}
            <div style="background:#fff;border:1px solid #e2e8f0;border-radius:4px;padding:12px 20px;margin-bottom:20px;display:flex;align-items:center;justify-content:space-between;">
                <p style="font-size:13px;color:#64748b;margin:0;">
                    Showing <span style="font-weight:700;color:#1e293b;">{{ $logs->firstItem() }}&ndash;{{ $logs->lastItem() }}</span>
                    of <span style="font-weight:700;color:#1e293b;">{{ number_format($logs->total()) }}</span> records
                </p>
                <p style="font-size:12px;color:#94a3b8;margin:0;">Append-only &mdash; records cannot be edited or deleted</p>
            </div>

            {{-- ── Log table ────────────────────────────────────────── --}}
            <div style="background:#fff;border:1px solid #e2e8f0;border-radius:4px;overflow:hidden;margin-bottom:24px;">
                @if($logs->isEmpty())
                    <div style="padding:64px 24px;text-align:center;font-size:13px;color:#94a3b8;">No audit records found.</div>
                @else
                    <div style="overflow-x:auto;">
                        <table style="width:100%;border-collapse:collapse;">
                            <thead>
                                <tr style="background:#1a2f4e;">
                                    <th style="padding:10px 16px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">Timestamp</th>
                                    <th style="padding:10px 16px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">Event</th>
                                    <th style="padding:10px 16px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">User</th>
                                    <th style="padding:10px 16px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">Description</th>
                                    <th style="padding:10px 16px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">Entity</th>
                                    <th style="padding:10px 16px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">IP</th>
                                    <th style="padding:10px 16px;width:32px;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($logs as $log)
                                    @php
                                        $eventColours = [
                                            'created'       => 'background:#dcfce7;color:#166534;',
                                            'updated'       => 'background:#dbeafe;color:#1e40af;',
                                            'deleted'       => 'background:#fee2e2;color:#991b1b;',
                                            'login'         => 'background:#ccfbf1;color:#0f766e;',
                                            'logout'        => 'background:#f1f5f9;color:#64748b;',
                                            'signed'        => 'background:#e0e7ff;color:#3730a3;',
                                            'countersigned' => 'background:#e0e7ff;color:#3730a3;',
                                            'authorised'    => 'background:#d1fae5;color:#065f46;',
                                            'queried'       => 'background:#fef9c3;color:#854d0e;',
                                            'submitted'     => 'background:#f3e8ff;color:#6b21a8;',
                                            'status_changed'=> 'background:#e0f2fe;color:#0369a1;',
                                            'generated'     => 'background:#ffedd5;color:#9a3412;',
                                            'responded'     => 'background:#fce7f3;color:#9d174d;',
                                        ];
                                        $ec = $eventColours[$log->event] ?? 'background:#f1f5f9;color:#64748b;';
                                        $entityLabel = $log->auditable_type
                                            ? class_basename($log->auditable_type)
                                            : '—';
                                        $hasDetail = !empty($log->old_values) || !empty($log->new_values);
                                    @endphp
                                    <tr x-data="{ open: false }" style="border-bottom:1px solid #f1f5f9;{{ $loop->even ? 'background:#f8fafc;' : '' }}">
                                        <td style="padding:12px 16px;font-size:11px;color:#64748b;white-space:nowrap;font-family:monospace;">
                                            {{ $log->created_at->format('d M Y') }}<br>
                                            <span style="color:#94a3b8;">{{ $log->created_at->format('H:i:s') }}</span>
                                        </td>
                                        <td style="padding:12px 16px;">
                                            <span style="display:inline-flex;padding:2px 8px;font-size:11px;font-weight:700;border-radius:9999px;text-transform:capitalize;{{ $ec }}">
                                                {{ str_replace('_', ' ', $log->event) }}
                                            </span>
                                        </td>
                                        <td style="padding:12px 16px;">
                                            <p style="font-size:12px;font-weight:600;color:#1e293b;margin:0;">{{ $log->user_name ?? '—' }}</p>
                                            @if($log->country_name ?? $log->country_code)
                                                <p style="font-size:11px;color:#94a3b8;margin:2px 0 0;">{{ $log->country_name ?? $log->country_code }}</p>
                                            @endif
                                        </td>
                                        <td style="padding:12px 16px;font-size:12px;color:#374151;max-width:240px;">
                                            {{ $log->description ?? '—' }}
                                        </td>
                                        <td style="padding:12px 16px;">
                                            <span style="display:inline-flex;padding:2px 8px;font-size:11px;font-family:monospace;border-radius:3px;background:#f1f5f9;color:#374151;">
                                                {{ $entityLabel }}
                                            </span>
                                            @if($log->auditable_id)
                                                <p style="font-size:11px;color:#94a3b8;font-family:monospace;margin:2px 0 0;max-width:96px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" title="{{ $log->auditable_id }}">
                                                    {{ substr($log->auditable_id, 0, 8) }}&hellip;
                                                </p>
                                            @endif
                                        </td>
                                        <td style="padding:12px 16px;font-size:11px;color:#94a3b8;font-family:monospace;white-space:nowrap;">
                                            {{ $log->ip_address ?? '—' }}
                                        </td>
                                        <td style="padding:12px 16px;text-align:right;">
                                            @if($hasDetail)
                                                <button @click="open = !open"
                                                        style="background:none;border:none;cursor:pointer;color:#cbd5e1;padding:0;"
                                                        :style="open ? 'color:#1a2f4e;' : ''"
                                                        :title="open ? 'Hide detail' : 'Show detail'">
                                                    <svg style="width:16px;height:16px;transition:transform .2s;" :style="{ transform: open ? 'rotate(180deg)' : 'none' }"
                                                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                                    </svg>
                                                </button>
                                            @endif
                                        </td>
                                    </tr>

                                    {{-- Expandable diff row --}}
                                    @if($hasDetail)
                                        <tr x-show="open" x-cloak style="background:#f8fafc;">
                                            <td colspan="7" style="padding:0 16px 16px;padding-top:0;">
                                                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-top:4px;">
                                                    @if(!empty($log->old_values))
                                                        <div style="border:1px solid #fecaca;background:#fff5f5;border-radius:3px;padding:12px;">
                                                            <p style="font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#dc2626;margin:0 0 8px;">Before</p>
                                                            <pre style="font-size:11px;color:#991b1b;white-space:pre-wrap;word-break:break-all;font-family:monospace;line-height:1.5;margin:0;">{{ json_encode($log->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                                        </div>
                                                    @endif
                                                    @if(!empty($log->new_values))
                                                        <div style="border:1px solid #bbf7d0;background:#f0fdf4;border-radius:3px;padding:12px;">
                                                            <p style="font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#16a34a;margin:0 0 8px;">After</p>
                                                            <pre style="font-size:11px;color:#166534;white-space:pre-wrap;word-break:break-all;font-family:monospace;line-height:1.5;margin:0;">{{ json_encode($log->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                                        </div>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    @if($logs->hasPages())
                        <div style="padding:16px 20px;border-top:1px solid #e2e8f0;">
                            {{ $logs->withQueryString()->links() }}
                        </div>
                    @endif
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
