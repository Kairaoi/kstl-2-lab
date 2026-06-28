{{-- resources/views/kstl/director/submissions/index.blade.php --}}
{{-- Director pipeline view — all submissions from intake to completion --}}

<x-app-layout>
    <x-slot name="header">
        <div style="display:flex;align-items:center;gap:12px;">
            <a href="{{ route('director.dashboard') }}"
               style="color:#9ca3af;text-decoration:none;display:flex;align-items:center;">
                <svg style="width:18px;height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <p style="font-size:9px;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:#b8922a;margin:0 0 3px;">Director &middot; Monitoring</p>
                <h2 style="font-family:'Georgia',serif;font-size:17px;font-weight:700;color:#1a2f4e;margin:0;line-height:1.2;">All Submissions — Pipeline View</h2>
            </div>
        </div>
    </x-slot>

    <div style="background:#f1f5f9;min-height:100vh;padding:0 0 56px;">
        <div style="max-width:80rem;margin:0 auto;padding:0 2rem;">

            {{-- ── Status Summary Bar ──────────────────────────────── --}}
            @php
                $statuses = [
                    'new_submission'        => ['label' => 'New',              'accent' => '#6b7280'],
                    'reception_review'      => ['label' => 'Reception Review', 'accent' => '#3b82f6'],
                    'sample_received'       => ['label' => 'Sample Received',  'accent' => '#06b6d4'],
                    'testing'               => ['label' => 'Testing',          'accent' => '#6366f1'],
                    'awaiting_authorisation'=> ['label' => 'Awaiting Auth',    'accent' => '#b8922a'],
                    'authorised'            => ['label' => 'Authorised',       'accent' => '#16a34a'],
                    'completed'             => ['label' => 'Completed',        'accent' => '#0d9488'],
                    'rejected'              => ['label' => 'Rejected',         'accent' => '#dc2626'],
                ];
            @endphp
            <div style="display:grid;grid-template-columns:repeat(8,1fr);gap:8px;margin-bottom:20px;">
                @foreach($statuses as $key => $meta)
                    @php $isActive = request('status') === $key; @endphp
                    <a href="?status={{ $key }}&search={{ request('search') }}"
                       style="display:block;background:#fff;border:1px solid {{ $isActive ? $meta['accent'] : '#e2e8f0' }};border-top:3px solid {{ $meta['accent'] }};border-radius:4px;padding:12px 10px;text-align:center;text-decoration:none;{{ $isActive ? 'box-shadow:0 0 0 2px '.$meta['accent'].'33;' : '' }}">
                        <p style="font-size:22px;font-weight:700;color:#1a2f4e;margin:0 0 2px;line-height:1;">
                            {{ $statusCounts[$key] ?? 0 }}
                        </p>
                        <p style="font-size:9px;font-weight:700;letter-spacing:.06em;color:{{ $isActive ? $meta['accent'] : '#6b7280' }};margin:0;text-transform:uppercase;">
                            {{ $meta['label'] }}
                        </p>
                    </a>
                @endforeach
            </div>

            {{-- ── Filters ─────────────────────────────────────────── --}}
            <div style="background:#fff;border:1px solid #e2e8f0;border-radius:4px;padding:16px 20px;margin-bottom:20px;">
                <form method="GET" style="display:flex;flex-wrap:wrap;align-items:flex-end;gap:12px;">
                    <div style="flex:1;min-width:200px;">
                        <label style="display:block;font-size:9px;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:#9ca3af;margin-bottom:6px;">Search</label>
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Reference, sample, or company…"
                               style="width:100%;padding:8px 12px;border:1px solid #e2e8f0;border-radius:3px;font-size:12.5px;color:#374151;outline:none;box-sizing:border-box;">
                    </div>
                    <div>
                        <label style="display:block;font-size:9px;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:#9ca3af;margin-bottom:6px;">Status</label>
                        <select name="status" style="padding:8px 12px;border:1px solid #e2e8f0;border-radius:3px;font-size:12.5px;color:#374151;outline:none;">
                            <option value="">All statuses</option>
                            @foreach($statuses as $key => $meta)
                                <option value="{{ $key }}" {{ request('status') === $key ? 'selected' : '' }}>
                                    {{ $meta['label'] }} ({{ $statusCounts[$key] ?? 0 }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit"
                            style="background:#1a2f4e;color:#fff;padding:8px 18px;border-radius:3px;font-size:12px;font-weight:600;border:none;cursor:pointer;">
                        Filter
                    </button>
                    @if(request()->hasAny(['status', 'search']))
                        <a href="{{ route('director.submissions.index') }}"
                           style="padding:8px 14px;border:1px solid #e2e8f0;border-radius:3px;font-size:12px;color:#6b7280;text-decoration:none;">
                            Clear
                        </a>
                    @endif
                </form>
            </div>

            {{-- ── Submissions Table ───────────────────────────────── --}}
            <div style="background:#fff;border:1px solid #e2e8f0;border-radius:4px;overflow:hidden;">

                {{-- Table header bar --}}
                <div style="padding:12px 20px;border-bottom:2px solid #b8922a;display:flex;align-items:center;justify-content:space-between;">
                    <p style="font-family:'Georgia',serif;font-size:15px;font-weight:700;color:#1a2f4e;margin:0;">
                        Submissions
                        <span style="font-family:inherit;font-size:13px;font-weight:400;color:#6b7280;">
                            — {{ $submissions->total() }} record{{ $submissions->total() !== 1 ? 's' : '' }}
                            @if(request('status'))
                                &middot;
                                @php
                                    $activeLabel = $statuses[request('status')]['label'] ?? request('status');
                                    $activeAccent = $statuses[request('status')]['accent'] ?? '#1a2f4e';
                                @endphp
                                <span style="color:{{ $activeAccent }};">{{ $activeLabel }}</span>
                            @endif
                        </span>
                    </p>
                    @if($submissions->firstItem())
                        <p style="font-size:11px;color:#9ca3af;margin:0;">
                            Showing {{ $submissions->firstItem() }}“{{ $submissions->lastItem() }}
                        </p>
                    @endif
                </div>

                @if($submissions->isEmpty())
                    <div style="padding:48px 20px;text-align:center;">
                        <svg style="width:40px;height:40px;color:#d1d5db;margin:0 auto 12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <p style="font-size:13px;color:#6b7280;margin:0 0 4px;font-weight:600;">No submissions found</p>
                        <p style="font-size:12px;color:#9ca3af;margin:0;">Try adjusting your filters.</p>
                    </div>
                @else
                    <div style="overflow-x:auto;">
                        <table style="width:100%;border-collapse:collapse;">
                            <thead>
                                <tr style="background:#1a2f4e;">
                                    <th style="text-align:left;padding:9px 16px;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;white-space:nowrap;">Reference</th>
                                    <th style="text-align:left;padding:9px 16px;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;white-space:nowrap;">Client</th>
                                    <th style="text-align:left;padding:9px 16px;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;white-space:nowrap;">Sample</th>
                                    <th style="text-align:left;padding:9px 16px;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;white-space:nowrap;">Priority</th>
                                    <th style="text-align:left;padding:9px 16px;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;white-space:nowrap;">Status</th>
                                    <th style="text-align:left;padding:9px 16px;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;white-space:nowrap;">Submitted</th>
                                    <th style="text-align:left;padding:9px 16px;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;white-space:nowrap;">Last Updated</th>
                                    <th style="padding:9px 16px;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($submissions as $i => $sub)
                                    @php
                                        $rowBg = $i % 2 === 0 ? '#fff' : '#f8fafc';
                                        $sl = [
                                            'new_submission'         => ['label' => 'New',                   'bg' => '#f3f4f6', 'color' => '#374151'],
                                            'reception_review'       => ['label' => 'Reception Review',      'bg' => '#eff6ff', 'color' => '#1d4ed8'],
                                            'sample_received'        => ['label' => 'Sample Received',       'bg' => '#ecfeff', 'color' => '#0e7490'],
                                            'testing'                => ['label' => 'Testing',               'bg' => '#eef2ff', 'color' => '#4338ca'],
                                            'awaiting_authorisation' => ['label' => 'Awaiting Authorisation','bg' => '#fffbeb', 'color' => '#92400e'],
                                            'authorised'             => ['label' => 'Authorised',            'bg' => '#f0fdf4', 'color' => '#15803d'],
                                            'completed'              => ['label' => 'Completed',             'bg' => '#f0fdfa', 'color' => '#0f766e'],
                                            'rejected'               => ['label' => 'Rejected',              'bg' => '#fef2f2', 'color' => '#b91c1c'],
                                        ];
                                        $statusData = $sl[$sub->status] ?? ['label' => ucfirst(str_replace('_',' ',$sub->status)), 'bg' => '#f3f4f6', 'color' => '#374151'];
                                        $pc = [
                                            'routine'   => ['bg' => '#f3f4f6', 'color' => '#374151'],
                                            'urgent'    => ['bg' => '#fffbeb', 'color' => '#92400e'],
                                            'emergency' => ['bg' => '#fef2f2', 'color' => '#b91c1c'],
                                        ];
                                        $priorityData = $pc[$sub->priority ?? 'routine'] ?? $pc['routine'];
                                    @endphp
                                    <tr style="background:{{ $rowBg }};border-bottom:1px solid #f1f5f9;">
                                        <td style="padding:11px 16px;font-size:12.5px;">
                                            <span style="font-family:monospace;font-size:11.5px;font-weight:700;color:#1a2f4e;">{{ $sub->reference_number }}</span>
                                        </td>
                                        <td style="padding:11px 16px;font-size:12.5px;">
                                            <span style="font-weight:600;color:#374151;">{{ $sub->client->company_name ?? '—' }}</span>
                                        </td>
                                        <td style="padding:11px 16px;font-size:12.5px;color:#6b7280;max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                            {{ $sub->sample_name }}
                                        </td>
                                        <td style="padding:11px 16px;">
                                            <span style="display:inline-flex;padding:2px 8px;border-radius:20px;background:{{ $priorityData['bg'] }};color:{{ $priorityData['color'] }};font-size:10px;font-weight:700;text-transform:capitalize;">
                                                {{ ucfirst($sub->priority ?? 'routine') }}
                                            </span>
                                        </td>
                                        <td style="padding:11px 16px;">
                                            <span style="display:inline-flex;padding:2px 8px;border-radius:20px;background:{{ $statusData['bg'] }};color:{{ $statusData['color'] }};font-size:10px;font-weight:700;">
                                                {{ $statusData['label'] }}
                                            </span>
                                        </td>
                                        <td style="padding:11px 16px;font-size:12.5px;color:#6b7280;white-space:nowrap;">
                                            {{ $sub->submitted_at?->format('d M Y') ?? $sub->created_at->format('d M Y') }}
                                        </td>
                                        <td style="padding:11px 16px;font-size:12.5px;color:#9ca3af;white-space:nowrap;">
                                            {{ $sub->updated_at->diffForHumans() }}
                                        </td>
                                        <td style="padding:11px 16px;text-align:right;">
                                            <a href="{{ route('director.submissions.show', $sub->id) }}"
                                               style="display:inline-flex;align-items:center;gap:4px;padding:5px 12px;background:#1a2f4e;color:#fff;border-radius:3px;font-size:11px;font-weight:600;text-decoration:none;">
                                                View
                                                <svg style="width:10px;height:10px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                                </svg>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    @if($submissions->hasPages())
                        <div style="padding:14px 20px;border-top:1px solid #f1f5f9;">
                            {{ $submissions->links() }}
                        </div>
                    @endif
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
