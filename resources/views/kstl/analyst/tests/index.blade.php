{{-- resources/views/kstl/analyst/tests/index.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <div style="display:flex; align-items:center; justify-content:space-between; gap:16px;">
            <div>
                <p style="font-size:10px; font-weight:700; letter-spacing:.16em; text-transform:uppercase; color:#b8922a; margin:0 0 4px;">Analyst</p>
                <h2 style="font-family:'Georgia',serif; font-size:20px; font-weight:700; color:#1a2f4e; margin:0; letter-spacing:.01em;">Test Queue</h2>
            </div>
            <div style="display:flex; align-items:center; gap:12px;">
                <form method="GET" action="{{ route('analyst.tests.index') }}">
                    <label style="display:flex; align-items:center; gap:8px; font-size:12.5px; color:#475569; cursor:pointer;">
                        <input type="checkbox"
                               name="mine"
                               value="1"
                               {{ request('mine') ? 'checked' : '' }}
                               onchange="this.form.submit()"
                               style="border-radius:2px; accent-color:#1a2f4e;">
                        My tests only
                    </label>
                </form>
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

    <div style="background:#f1f5f9; min-height:100vh; padding:0 0 56px;">
        <div style="max-width:80rem; margin:0 auto; padding:0 2rem;">

            @if(session('success'))
                <div style="background:#f0fdf4; border:1px solid #86efac; border-left:4px solid #16a34a; border-radius:4px; padding:12px 16px; margin-bottom:20px; display:flex; align-items:center; gap:10px;">
                    <svg style="width:16px; height:16px; flex-shrink:0;" fill="#16a34a" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/>
                    </svg>
                    <p style="font-size:13px; color:#15803d; margin:0;">{{ session('success') }}</p>
                </div>
            @endif

            @php
                $countAll          = $queue->count();
                $countNew          = $queue->where('status', 'queued')->count();
                $countPending      = $queue->where('status', 'in_progress')->count();
                $countFlagged      = $queue->where('status', 'flagged')->count();
                $countDone         = $queue->where('status', 'completed')->count();
                $countDirQuery     = $queue->filter(fn($t) =>
                    $t->status === 'flagged' && $t->result_notes &&
                    preg_match('/\[Director query\]/i', $t->result_notes)
                )->count();
            @endphp

            <div x-data="{ tab: 'all', search: '', category: '' }" style="background:#fff; border:1px solid #e2e8f0; border-radius:4px; overflow:hidden;">

                {{-- Header + Tab bar --}}
                <div style="padding:16px 20px 0; border-bottom:1px solid #e2e8f0;">
                    <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:8px; margin-bottom:14px;">
                        <div>
                            <h3 style="font-family:'Georgia',serif; font-size:15px; font-weight:700; color:#1a2f4e; margin:0;">Tests</h3>
                            <p style="font-size:11px; color:#94a3b8; margin:3px 0 0;">{{ $countAll }} total</p>
                        </div>
                    </div>

                    @php
                        $tabs = [
                            ['key' => 'all',       'label' => 'All',            'count' => $countAll,      'danger' => false],
                            ['key' => 'new',       'label' => 'New',            'count' => $countNew,      'danger' => false],
                            ['key' => 'pending',   'label' => 'In Progress',    'count' => $countPending,  'danger' => false],
                            ['key' => 'dirquery',  'label' => 'Director Query', 'count' => $countDirQuery, 'danger' => $countDirQuery > 0],
                            ['key' => 'flagged',   'label' => 'Flagged',        'count' => $countFlagged,  'danger' => false],
                            ['key' => 'done',      'label' => 'Completed',      'count' => $countDone,     'danger' => false],
                        ];
                    @endphp

                    {{-- Tab nav --}}
                    <div role="tablist" style="display:flex; flex-direction:row; flex-wrap:nowrap; align-items:flex-end; gap:0; overflow-x:auto;">
                        @foreach($tabs as $t)
                            <button type="button"
                                    role="tab"
                                    @click="tab = '{{ $t['key'] }}'"
                                    :aria-selected="tab === '{{ $t['key'] }}'"
                                    style="flex-shrink:0; display:inline-flex; align-items:center; gap:6px; padding:8px 16px 10px; font-size:12.5px; line-height:1; background:none; border:none; border-bottom:3px solid transparent; cursor:pointer; white-space:nowrap; outline:none; transition:color .15s, border-color .15s;"
                                    :style="tab === '{{ $t['key'] }}'
                                        ? 'border-bottom-color:#b8922a; color:#1a2f4e; font-weight:700;'
                                        : 'border-bottom-color:transparent; color:#94a3b8; font-weight:500;'">
                                {{ $t['label'] }}
                                <span style="display:inline-flex; align-items:center; justify-content:center; min-width:18px; height:17px; padding:0 5px; border-radius:10px; font-size:10px; font-weight:700; transition:background .15s, color .15s;"
                                      :style="tab === '{{ $t['key'] }}'
                                          ? '{{ $t['danger'] ? 'background:#dc2626;color:#fff;' : 'background:#1a2f4e;color:#fff;' }}'
                                          : '{{ $t['danger'] ? 'background:#fee2e2;color:#dc2626;' : 'background:#f1f5f9;color:#94a3b8;' }}'">
                                    {{ $t['count'] }}
                                </span>
                            </button>
                        @endforeach
                    </div>
                </div>

                {{-- Search + filter toolbar --}}
                <div style="padding:12px 20px; border-bottom:1px solid #f1f5f9; background:#fafbfc; display:flex; align-items:center; gap:10px; flex-wrap:wrap;">
                    {{-- Search --}}
                    <div style="flex:1; min-width:200px; position:relative;">
                        <svg style="position:absolute; left:10px; top:50%; transform:translateY(-50%); width:14px; height:14px; color:#94a3b8; pointer-events:none;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z"/>
                        </svg>
                        <input type="text"
                               x-model.debounce.200ms="search"
                               placeholder="Search by reference, client, sample or test…"
                               style="width:100%; padding:7px 10px 7px 32px; font-size:12.5px; border:1px solid #e2e8f0; border-radius:3px; outline:none; color:#1a2f4e; box-sizing:border-box; background:#fff;"
                               @focus="$el.style.borderColor='#1a2f4e'"
                               @blur="$el.style.borderColor='#e2e8f0'">
                    </div>
                    {{-- Category filter --}}
                    <select x-model="category"
                            style="padding:7px 28px 7px 10px; font-size:12.5px; border:1px solid #e2e8f0; border-radius:3px; outline:none; color:#1a2f4e; background:#fff; cursor:pointer; appearance:none; background-image:url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2'%3E%3Cpath d='M19 9l-7 7-7-7'/%3E%3C/svg%3E\"); background-repeat:no-repeat; background-position:right 8px center;">
                        <option value="">All categories</option>
                        <option value="microbiological">Microbiological</option>
                        <option value="chemical">Chemical</option>
                        <option value="physical">Physical</option>
                        <option value="water">Water</option>
                    </select>
                    {{-- Clear button --}}
                    <button type="button"
                            x-show="search !== '' || category !== ''"
                            x-transition
                            @click="search = ''; category = ''"
                            style="display:inline-flex; align-items:center; gap:5px; padding:7px 12px; font-size:12px; font-weight:600; color:#64748b; background:#fff; border:1px solid #e2e8f0; border-radius:3px; cursor:pointer; white-space:nowrap;">
                        <svg style="width:12px; height:12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Clear
                    </button>
                </div>

                @if($queue->isEmpty())
                    <div style="padding:64px 20px; text-align:center;">
                        <svg style="width:40px; height:40px; margin:0 auto 12px;" fill="none" stroke="#e2e8f0" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p style="font-size:13px; font-weight:600; color:#94a3b8; margin:0;">No tests assigned</p>
                        <p style="font-size:11px; color:#cbd5e1; margin:4px 0 0;">Tests you work on will appear here.</p>
                    </div>
                @else
                    @php
                        $grouped = $queue->groupBy(function($test) {
                            return $test->sample->submission->id;
                        });
                    @endphp

                    {{-- Empty state per tab --}}
                    <div x-show="
                        (tab === 'new'      && {{ $countNew      === 0 ? 'true' : 'false' }}) ||
                        (tab === 'pending'  && {{ $countPending  === 0 ? 'true' : 'false' }}) ||
                        (tab === 'dirquery' && {{ $countDirQuery === 0 ? 'true' : 'false' }}) ||
                        (tab === 'flagged'  && {{ $countFlagged  === 0 ? 'true' : 'false' }}) ||
                        (tab === 'done'     && {{ $countDone     === 0 ? 'true' : 'false' }})"
                         style="display:none; padding:48px 20px; text-align:center;">
                        <p style="font-size:13px; font-weight:600; color:#94a3b8; margin:0;">No tests in this category</p>
                    </div>

                    <div>
                        @foreach($grouped as $submissionId => $tests)
                            @php
                                $submission   = $tests->first()->sample->submission;
                                $client       = $submission->client;
                                $completedCount = $tests->whereIn('status', ['completed', 'flagged'])->count();
                                $totalCount   = $tests->count();
                                $progress     = $totalCount > 0 ? round(($completedCount / $totalCount) * 100) : 0;
                                $flaggedCount = $tests->where('status', 'flagged')->count();

                                $hasNew       = $tests->where('status', 'queued')->count() > 0;
                                $hasPending   = $tests->where('status', 'in_progress')->count() > 0;
                                $hasFlagged   = $tests->where('status', 'flagged')->count() > 0;
                                $hasCompleted = $tests->where('status', 'completed')->count() > 0;
                                $hasDirQuery  = $tests->filter(fn($t) =>
                                    $t->status === 'flagged' && $t->result_notes &&
                                    preg_match('/\[Director query\]/i', $t->result_notes)
                                )->count() > 0;

                                // Searchable text for this group (lowercased for case-insensitive match)
                                $searchIndex = strtolower(implode(' ', array_filter([
                                    $submission->reference_number,
                                    $client->company_name ?? '',
                                    $client->responsible_officer_name ?? ($client->user->name ?? ''),
                                    $tests->map(fn($t) => $t->getDisplayLabel())->join(' '),
                                    $tests->map(fn($t) => $t->sample->common_name ?? '')->join(' '),
                                    $tests->map(fn($t) => $t->assignedTo?->name ?? '')->join(' '),
                                ])));

                                // Unique categories in this group for filtering
                                $catIndex = $tests->map(fn($t) => strtolower($t->getDisplayCategory()))->unique()->join(',');
                            @endphp

                            {{-- Filter wrapper --}}
                            <div x-show="
                                    (tab === 'all'
                                     || (tab === 'new'      && {{ $hasNew       ? 'true' : 'false' }})
                                     || (tab === 'pending'  && {{ $hasPending   ? 'true' : 'false' }})
                                     || (tab === 'dirquery' && {{ $hasDirQuery  ? 'true' : 'false' }})
                                     || (tab === 'flagged'  && {{ $hasFlagged   ? 'true' : 'false' }})
                                     || (tab === 'done'     && {{ $hasCompleted ? 'true' : 'false' }}))
                                    && (search === '' || '{{ $searchIndex }}'.includes(search.toLowerCase()))
                                    && (category === '' || '{{ $catIndex }}'.split(',').includes(category))">
                            {{-- Collapsible: own x-data scope for open/close --}}
                            <div style="border-bottom:1px solid #f1f5f9; padding:16px 20px;"
                                 x-data="{ open: true }">
                                {{-- Submission header --}}
                                <div style="display:flex; align-items:center; justify-content:space-between; cursor:pointer; margin-bottom:12px;"
                                     @click="open = !open">
                                    <div style="display:flex; align-items:center; gap:12px;">
                                        <div>
                                            <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
                                                <span style="font-family:monospace; font-size:13px; font-weight:700; color:#1a2f4e;">
                                                    {{ $submission->reference_number }}
                                                </span>
                                                <span style="font-size:12.5px; color:#475569;">
                                                    {{ $client->responsible_officer_name ?? $client->user->name ?? '—' }}
                                                </span>
                                                @if($flaggedCount > 0)
                                                    <span style="display:inline-flex; align-items:center; padding:2px 8px; font-size:10px; font-weight:700; background:#fff7ed; color:#c2410c; border:1px solid #fed7aa; border-radius:20px;">
                                                        {{ $flaggedCount }} flagged
                                                    </span>
                                                @endif
                                                @if($progress === 100)
                                                    <span style="display:inline-flex; align-items:center; gap:4px; padding:2px 8px; font-size:10px; font-weight:700; background:#f0fdf4; color:#15803d; border:1px solid #bbf7d0; border-radius:20px;">
                                                        All complete
                                                    </span>
                                                @endif
                                            </div>
                                            <div style="margin-top:6px; display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
                                                <span style="display:inline-flex; align-items:center; padding:2px 8px; border-radius:3px; background:#f1f5f9; color:#475569; font-size:11px; font-weight:600;">
                                                    {{ $client->company_name ?? '—' }}
                                                </span>
                                                @if($submission->sample_items && count($submission->sample_items))
                                                    @foreach($submission->sample_items as $si)
                                                        <span style="display:inline-flex; align-items:center; padding:2px 8px; border-radius:3px; background:#f0fdfa; color:#0f766e; font-size:11px; font-weight:600;">
                                                            {{ $si['name'] ?? '—' }}
                                                            @if(!empty($si['scientific_name']))
                                                                <span style="font-style:italic; font-weight:400; color:#14b8a6; margin-left:4px;">({{ $si['scientific_name'] }})</span>
                                                            @endif
                                                        </span>
                                                    @endforeach
                                                @elseif($submission->sample_name)
                                                    <span style="display:inline-flex; align-items:center; padding:2px 8px; border-radius:3px; background:#f0fdfa; color:#0f766e; font-size:11px; font-weight:600;">
                                                        {{ $submission->sample_name }}
                                                        @if($submission->sample_type)
                                                            <span style="font-style:italic; font-weight:400; color:#14b8a6; margin-left:4px; text-transform:capitalize;">({{ $submission->sample_type }})</span>
                                                        @endif
                                                    </span>
                                                @endif
                                                <span style="display:inline-flex; align-items:center; gap:4px; padding:2px 8px; border-radius:20px; background:#eff6ff; color:#1d4ed8; font-size:11px; font-weight:600; white-space:nowrap;">
                                                    <svg style="width:11px; height:11px; flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    Received {{ ($submission->received_at ?? $submission->created_at)->format('d M Y, H:i') }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div style="display:flex; align-items:center; gap:12px;">
                                        {{-- Progress --}}
                                        <div style="display:flex; align-items:center; gap:8px;">
                                            <div style="width:80px; height:3px; background:#f1f5f9; border-radius:2px; overflow:hidden;">
                                                <div style="height:100%; background:#16a34a; border-radius:2px; width:{{ $progress }}%;"></div>
                                            </div>
                                            <span style="font-size:11px; color:#94a3b8; font-weight:600; white-space:nowrap;">
                                                {{ $completedCount }}/{{ $totalCount }}
                                            </span>
                                        </div>

                                        {{-- View authorised result (analyst's own read-only report) --}}
                                        @if($progress === 100 && $submission->result)
                                            <a href="{{ route('analyst.results.show', $submission->id) }}"
                                               style="display:inline-flex; align-items:center; gap:6px; padding:5px 12px; font-size:11px; font-weight:600; background:#eff6ff; color:#1d4ed8; border:1px solid #bfdbfe; border-radius:3px; text-decoration:none;"
                                               title="View the authorised result">
                                                <svg style="width:13px; height:13px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                                View Result
                                            </a>
                                        @endif

                                        {{-- Chevron --}}
                                        <svg style="width:16px; height:16px; color:#94a3b8; transition:transform .2s;"
                                             :style="open ? 'transform:rotate(180deg)' : ''"
                                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </div>
                                </div>

                                {{-- Tests table --}}
                                <div x-show="open" x-collapse>
                                    <div style="overflow-x:auto; border:1px solid #e2e8f0; border-radius:4px;">
                                        <table style="width:100%; border-collapse:collapse;">
                                            <thead>
                                                <tr style="background:#1a2f4e;">
                                                    <th style="text-align:left; padding:9px 16px; font-size:9px; font-weight:700; letter-spacing:.12em; text-transform:uppercase; color:#e2e8f0;">Sample</th>
                                                    <th style="text-align:left; padding:9px 16px; font-size:9px; font-weight:700; letter-spacing:.12em; text-transform:uppercase; color:#e2e8f0;">Test</th>
                                                    <th style="text-align:left; padding:9px 16px; font-size:9px; font-weight:700; letter-spacing:.12em; text-transform:uppercase; color:#e2e8f0;">Category</th>
                                                    <th style="text-align:left; padding:9px 16px; font-size:9px; font-weight:700; letter-spacing:.12em; text-transform:uppercase; color:#e2e8f0;">Queued</th>
                                                    <th style="text-align:left; padding:9px 16px; font-size:9px; font-weight:700; letter-spacing:.12em; text-transform:uppercase; color:#e2e8f0;">Assigned To</th>
                                                    <th style="text-align:left; padding:9px 16px; font-size:9px; font-weight:700; letter-spacing:.12em; text-transform:uppercase; color:#e2e8f0;">Status</th>
                                                    <th style="text-align:left; padding:9px 16px; font-size:9px; font-weight:700; letter-spacing:.12em; text-transform:uppercase; color:#e2e8f0;">Result</th>
                                                    <th style="padding:9px 16px;"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($tests as $i => $test)
                                                    @php
                                                        $rowBg = $test->status === 'flagged' ? '#fffbeb' : ($test->status === 'in_progress' ? '#f8faff' : ($test->status === 'completed' ? '#f8fffe' : ($i % 2 === 0 ? '#fff' : '#f8fafc')));
                                                        $directorQuery = null;
                                                        if ($test->status === 'flagged' && $test->result_notes) {
                                                            preg_match('/\[Director query\]\s*(.+?)(?=\n\n|$)/s', $test->result_notes, $dqMatch);
                                                            $directorQuery = isset($dqMatch[1]) ? trim($dqMatch[1]) : null;
                                                        }
                                                    @endphp
                                                    <tr style="background:{{ $rowBg }}; border-bottom:1px solid #f1f5f9;" x-data="{ showModal: false }">
                                                        <td style="padding:10px 16px;">
                                                            <p style="font-weight:700; color:#1a2f4e; font-size:11px; font-family:monospace; margin:0;">{{ $test->sample->sample_code }}</p>
                                                            <p style="font-size:12px; color:#475569; margin:2px 0 0;">{{ $test->sample->common_name }}</p>
                                                            @if($test->sample->scientific_name)
                                                                <p style="font-size:11px; color:#94a3b8; font-style:italic; margin:1px 0 0;">{{ $test->sample->scientific_name }}</p>
                                                            @endif
                                                        </td>
                                                        <td style="padding:10px 16px;">
                                                            <a href="{{ route('analyst.tests.show', $test->id) }}"
                                                               style="font-size:12.5px; font-weight:600; color:#1a2f4e; text-decoration:none;">
                                                                {{ $test->getDisplayLabel() }}
                                                            </a>
                                                        </td>
                                                        <td style="padding:10px 16px;">
                                                            @php $cat = $test->getDisplayCategory(); @endphp
                                                            <span style="display:inline-flex; padding:2px 8px; font-size:10px; font-weight:600; border-radius:20px; text-transform:capitalize; background:{{ $cat === 'microbiological' ? '#f5f3ff' : '#eff6ff' }}; color:{{ $cat === 'microbiological' ? '#7c3aed' : '#1d4ed8' }};">
                                                                {{ $cat }}
                                                            </span>
                                                        </td>
                                                        <td style="padding:10px 16px;">
                                                            <span style="display:inline-flex; align-items:center; gap:4px; padding:3px 8px; border-radius:3px; background:#f1f5f9; color:#475569; font-size:11px; font-weight:600; white-space:nowrap;">
                                                                {{ $test->created_at->format('d M Y') }}
                                                                <span style="color:#94a3b8; font-weight:400;">{{ $test->created_at->format('H:i') }}</span>
                                                            </span>
                                                        </td>
                                                        <td style="padding:10px 16px; font-size:12px; color:#475569;">
                                                            {{ $test->assignedTo?->name ?? '—' }}
                                                        </td>
                                                        <td style="padding:10px 16px;">
                                                            <x-kstl.status-badge
                                                                :status="$test->status"
                                                                :label="$test->status === 'flagged' ? ($directorQuery ? 'Director Query' : 'Returned') : null" />
                                                            @if($directorQuery)
                                                                <div style="margin-top:6px; background:#fffbeb; border-left:3px solid #d97706; border-radius:2px; padding:5px 8px; max-width:200px;">
                                                                    <p style="font-size:9px; font-weight:700; letter-spacing:.08em; text-transform:uppercase; color:#92400e; margin:0 0 3px;">Director's Query</p>
                                                                    <p style="font-size:11px; color:#1a2f4e; line-height:1.5; margin:0;">{{ Str::limit($directorQuery, 90) }}</p>
                                                                </div>
                                                            @endif
                                                        </td>
                                                        <td style="padding:10px 16px; font-size:12px;">
                                                            @if($test->status === 'completed' || $test->status === 'flagged')
                                                                @php
                                                                    $qualColors = [
                                                                        'pass'         => '#15803d',
                                                                        'fail'         => '#dc2626',
                                                                        'detected'     => '#dc2626',
                                                                        'not_detected' => '#15803d',
                                                                        'less_than'    => '#475569',
                                                                        'greater_than' => '#475569',
                                                                        'equal_to'     => '#475569',
                                                                    ];
                                                                    $qualColor = $qualColors[$test->result_qualifier] ?? '#475569';
                                                                @endphp
                                                                <div style="display:flex; flex-direction:column; gap:2px;">
                                                                    @if($test->result_qualifier && $test->result_qualifier !== 'pending')
                                                                        <span style="font-weight:700; color:{{ $qualColor }}; text-transform:capitalize;">
                                                                            {{ str_replace('_', ' ', $test->result_qualifier) }}
                                                                        </span>
                                                                    @endif
                                                                    @if($test->result_value)
                                                                        <span style="color:#475569;">
                                                                            {{ $test->result_value }}
                                                                            @if($test->result_unit)
                                                                                <span style="color:#94a3b8;">{{ $test->result_unit }}</span>
                                                                            @endif
                                                                        </span>
                                                                    @endif
                                                                    @if($test->result_notes)
                                                                        <button type="button"
                                                                                @click="showModal = true"
                                                                                style="display:inline-flex; align-items:center; gap:4px; font-size:11px; color:#1a2f4e; background:none; border:none; cursor:pointer; padding:0; text-align:left; margin-top:3px; text-decoration:underline;">
                                                                            View details
                                                                        </button>

                                                                        {{-- Modal for comprehensive result details --}}
                                                                        <div x-show="showModal"
                                                                             x-cloak
                                                                             @click.away="showModal = false"
                                                                             class="fixed inset-0 z-50 overflow-y-auto"
                                                                             style="display: none;">
                                                                            <div class="flex items-center justify-center min-h-screen px-4 py-8">
                                                                                <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity"></div>

                                                                                <div class="bg-white rounded-xl overflow-hidden shadow-2xl transform transition-all sm:max-w-4xl sm:w-full relative max-h-[90vh] overflow-y-auto">
                                                                                    {{-- Header with close button --}}
                                                                                    <div style="background:linear-gradient(135deg,#0f2240,#1a2f4e); padding:20px 24px; position:sticky; top:0; z-index:10;">
                                                                                        <div style="display:flex; align-items:center; justify-content:space-between;">
                                                                                            <div style="display:flex; align-items:center; gap:12px;">
                                                                                                <svg style="width:24px; height:24px; color:#94a3b8;" fill="none" stroke="#94a3b8" viewBox="0 0 24 24">
                                                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                                                                </svg>
                                                                                                <div>
                                                                                                    <h3 style="font-family:'Georgia',serif; font-size:18px; font-weight:700; color:#fff; margin:0;">
                                                                                                        Test Results — {{ $test->sample->submission->reference_number }}
                                                                                                    </h3>
                                                                                                    <p style="font-size:12px; color:#94a3b8; margin:3px 0 0;">
                                                                                                        {{ $test->sample->common_name }}
                                                                                                        @if($test->sample->scientific_name)
                                                                                                            <span style="font-style:italic;"> — {{ $test->sample->scientific_name }}</span>
                                                                                                        @endif
                                                                                                    </p>
                                                                                                </div>
                                                                                            </div>
                                                                                            <button @click="showModal = false" style="background:none; border:none; cursor:pointer; color:#94a3b8;">
                                                                                                <svg style="width:24px; height:24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                                                                </svg>
                                                                                            </button>
                                                                                        </div>
                                                                                    </div>

                                                                                    <div class="p-6">
                                                                                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                                                                                            {{-- Left: Submission Info --}}
                                                                                            <div class="space-y-4">
                                                                                                <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:4px; padding:16px;">
                                                                                                    <h4 style="font-size:9px; font-weight:700; letter-spacing:.12em; text-transform:uppercase; color:#64748b; margin:0 0 12px; display:flex; align-items:center; gap:8px;">
                                                                                                        Submission
                                                                                                    </h4>
                                                                                                    <dl class="space-y-3 text-sm">
                                                                                                        <div>
                                                                                                            <dt style="font-size:9px; font-weight:700; letter-spacing:.1em; text-transform:uppercase; color:#94a3b8;">Reference</dt>
                                                                                                            <dd style="font-family:monospace; color:#1a2f4e; margin-top:3px; font-weight:700;">{{ $test->sample->submission->reference_number }}</dd>
                                                                                                        </div>
                                                                                                        <div>
                                                                                                            <dt style="font-size:9px; font-weight:700; letter-spacing:.1em; text-transform:uppercase; color:#94a3b8;">Client</dt>
                                                                                                            <dd style="color:#1a2f4e; margin-top:3px; font-weight:600;">{{ $test->sample->submission->client->responsible_officer_name ?? $test->sample->submission->client->user->name }}</dd>
                                                                                                            <dd style="font-size:11px; color:#475569;">{{ $test->sample->submission->client->company_name }}</dd>
                                                                                                        </div>
                                                                                                        <div>
                                                                                                            <dt style="font-size:9px; font-weight:700; letter-spacing:.1em; text-transform:uppercase; color:#94a3b8;">Sample</dt>
                                                                                                            <dd style="color:#1a2f4e; margin-top:3px; font-family:monospace; font-size:11px;">{{ $test->sample->sample_code }}</dd>
                                                                                                            <dd style="font-size:13px; font-weight:600; color:#1a2f4e; margin-top:2px;">{{ $test->sample->common_name }}</dd>
                                                                                                            @if($test->sample->scientific_name)
                                                                                                                <dd style="font-size:11px; color:#94a3b8; font-style:italic;">{{ $test->sample->scientific_name }}</dd>
                                                                                                            @endif
                                                                                                        </div>
                                                                                                        <div>
                                                                                                            <dt style="font-size:9px; font-weight:700; letter-spacing:.1em; text-transform:uppercase; color:#94a3b8;">Type</dt>
                                                                                                            <dd style="color:#1a2f4e; margin-top:3px; text-transform:capitalize;">{{ $test->sample->submission->sample_type }}</dd>
                                                                                                        </div>
                                                                                                    </dl>
                                                                                                </div>
                                                                                            </div>

                                                                                            {{-- Right: Test Results --}}
                                                                                            <div class="lg:col-span-2 space-y-4">
                                                                                                @php
                                                                                                    $isPass = in_array($test->result_qualifier, ['pass', 'not_detected']);
                                                                                                @endphp
                                                                                                <div style="border:2px solid {{ $isPass ? '#86efac' : '#fca5a5' }}; border-radius:4px; padding:20px; background:{{ $isPass ? '#f0fdf4' : '#fff1f2' }};">
                                                                                                    <div style="display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:16px;">
                                                                                                        <div>
                                                                                                            <h4 style="font-size:15px; font-weight:700; color:#1a2f4e; margin:0;">{{ $test->getDisplayLabel() }}</h4>
                                                                                                            <p style="font-size:12px; color:#475569; margin:4px 0 0; text-transform:capitalize;">{{ $test->getDisplayCategory() }}</p>
                                                                                                        </div>
                                                                                                        <span style="display:inline-flex; padding:6px 16px; font-size:13px; font-weight:700; border-radius:20px; text-transform:capitalize; border:2px solid {{ $isPass ? '#86efac' : '#fca5a5' }}; background:{{ $isPass ? '#dcfce7' : '#fee2e2' }}; color:{{ $isPass ? '#15803d' : '#dc2626' }};">
                                                                                                            {{ str_replace('_', ' ', $test->result_qualifier) }}
                                                                                                        </span>
                                                                                                    </div>

                                                                                                    <div class="grid grid-cols-2 gap-4 mt-4">
                                                                                                        @if($test->result_value)
                                                                                                            <div style="background:#fff; border-radius:4px; padding:12px; border:1px solid #e2e8f0;">
                                                                                                                <dt style="font-size:9px; font-weight:700; letter-spacing:.1em; text-transform:uppercase; color:#64748b;">Result Value</dt>
                                                                                                                <dd style="margin-top:6px; font-size:22px; font-weight:700; color:#1a2f4e;">
                                                                                                                    {{ $test->result_value }}
                                                                                                                    @if($test->result_unit)
                                                                                                                        <span style="font-size:14px; color:#475569; font-weight:500; margin-left:4px;">{{ $test->result_unit }}</span>
                                                                                                                    @endif
                                                                                                                </dd>
                                                                                                            </div>
                                                                                                        @endif

                                                                                                        @if($test->completed_at)
                                                                                                            <div style="background:#fff; border-radius:4px; padding:12px; border:1px solid #e2e8f0;">
                                                                                                                <dt style="font-size:9px; font-weight:700; letter-spacing:.1em; text-transform:uppercase; color:#64748b;">Completed</dt>
                                                                                                                <dd style="margin-top:6px; font-size:13px; font-weight:600; color:#1a2f4e;">
                                                                                                                    {{ $test->completed_at->format('d M Y') }}
                                                                                                                </dd>
                                                                                                                <dd style="font-size:11px; color:#475569; margin-top:2px;">
                                                                                                                    {{ $test->completed_at->format('H:i') }}
                                                                                                                    @if($test->assignedTo)
                                                                                                                        &middot; {{ $test->assignedTo->name }}
                                                                                                                    @endif
                                                                                                                </dd>
                                                                                                            </div>
                                                                                                        @endif
                                                                                                    </div>
                                                                                                </div>

                                                                                                {{-- Analyst Notes --}}
                                                                                                @if($test->result_notes)
                                                                                                    <div style="background:#eff6ff; border:1px solid #bfdbfe; border-radius:4px; padding:16px;">
                                                                                                        <h4 style="font-size:9px; font-weight:700; letter-spacing:.12em; text-transform:uppercase; color:#1d4ed8; margin:0 0 8px;">Analyst Notes</h4>
                                                                                                        <p style="font-size:13px; color:#1e3a8a; line-height:1.6; white-space:pre-line; margin:0;">{{ $test->result_notes }}</p>
                                                                                                    </div>
                                                                                                @endif

                                                                                                {{-- All Tests in Submission --}}
                                                                                                @php
                                                                                                    $allTests = $test->sample->submission->samples->flatMap->sampleTests->whereIn('status', ['completed', 'flagged']);
                                                                                                @endphp
                                                                                                @if($allTests->count() > 1)
                                                                                                    <div style="background:#fff; border:1px solid #e2e8f0; border-radius:4px; overflow:hidden;">
                                                                                                        <div style="padding:10px 16px; border-bottom:1px solid #e2e8f0; background:#f8fafc;">
                                                                                                            <h4 style="font-size:9px; font-weight:700; letter-spacing:.12em; text-transform:uppercase; color:#64748b; margin:0;">All Completed Tests ({{ $allTests->count() }})</h4>
                                                                                                        </div>
                                                                                                        <table style="width:100%; border-collapse:collapse;">
                                                                                                            <thead>
                                                                                                                <tr style="background:#1a2f4e;">
                                                                                                                    <th style="text-align:left; padding:8px 16px; font-size:9px; font-weight:700; letter-spacing:.12em; text-transform:uppercase; color:#e2e8f0;">Test</th>
                                                                                                                    <th style="text-align:left; padding:8px 16px; font-size:9px; font-weight:700; letter-spacing:.12em; text-transform:uppercase; color:#e2e8f0;">Category</th>
                                                                                                                    <th style="text-align:left; padding:8px 16px; font-size:9px; font-weight:700; letter-spacing:.12em; text-transform:uppercase; color:#e2e8f0;">Result</th>
                                                                                                                    <th style="text-align:left; padding:8px 16px; font-size:9px; font-weight:700; letter-spacing:.12em; text-transform:uppercase; color:#e2e8f0;">Qualifier</th>
                                                                                                                </tr>
                                                                                                            </thead>
                                                                                                            <tbody>
                                                                                                                @foreach($allTests as $j => $t)
                                                                                                                    @php
                                                                                                                        $isCurrent = $t->id === $test->id;
                                                                                                                        $tQualBg = match($t->result_qualifier) {
                                                                                                                            'pass', 'not_detected' => 'background:#dcfce7; color:#15803d; border:1px solid #86efac;',
                                                                                                                            'fail', 'detected' => 'background:#fee2e2; color:#dc2626; border:1px solid #fca5a5;',
                                                                                                                            default => 'background:#f1f5f9; color:#475569; border:1px solid #e2e8f0;'
                                                                                                                        };
                                                                                                                    @endphp
                                                                                                                    <tr style="background:{{ $isCurrent ? '#eff6ff' : ($j % 2 === 0 ? '#fff' : '#f8fafc') }}; border-bottom:1px solid #f1f5f9; {{ $isCurrent ? 'border-left:4px solid #1a2f4e;' : '' }}">
                                                                                                                        <td style="padding:10px 16px; font-size:12.5px; font-weight:{{ $isCurrent ? '700' : '600' }}; color:{{ $isCurrent ? '#1a2f4e' : '#1a2f4e' }};">
                                                                                                                            {{ $t->getDisplayLabel() }}
                                                                                                                            @if($isCurrent)
                                                                                                                                <span style="margin-left:6px; font-size:10px; color:#64748b; font-weight:400;">(viewing)</span>
                                                                                                                            @endif
                                                                                                                        </td>
                                                                                                                        <td style="padding:10px 16px; font-size:12px; color:#475569; text-transform:capitalize;">{{ $t->getDisplayCategory() }}</td>
                                                                                                                        <td style="padding:10px 16px; font-size:12.5px; font-weight:600; color:#1a2f4e;">
                                                                                                                            {{ $t->result_value ?? '—' }}
                                                                                                                            @if($t->result_unit)
                                                                                                                                <span style="color:#94a3b8; font-size:11px; margin-left:4px;">{{ $t->result_unit }}</span>
                                                                                                                            @endif
                                                                                                                        </td>
                                                                                                                        <td style="padding:10px 16px;">
                                                                                                                            <span style="display:inline-flex; padding:3px 10px; font-size:10px; font-weight:700; border-radius:20px; text-transform:capitalize; {{ $tQualBg }}">
                                                                                                                                {{ str_replace('_', ' ', $t->result_qualifier ?? '—') }}
                                                                                                                            </span>
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

                                                                                    {{-- Footer Actions --}}
                                                                                    <div style="background:#f8fafc; padding:16px 24px; border-top:1px solid #e2e8f0; display:flex; justify-content:space-between; align-items:center; position:sticky; bottom:0;">
                                                                                        <button type="button"
                                                                                                @click="showModal = false"
                                                                                                style="padding:9px 20px; font-size:13px; font-weight:600; color:#475569; background:#fff; border:1px solid #e2e8f0; border-radius:3px; cursor:pointer;">
                                                                                            Close
                                                                                        </button>
                                                                                        <a href="{{ route('analyst.tests.show', $test->id) }}"
                                                                                           style="padding:9px 20px; font-size:13px; font-weight:600; color:#fff; background:#1a2f4e; border-radius:3px; text-decoration:none;">
                                                                                            View Full Test Details &rarr;
                                                                                        </a>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                    @if(!$test->result_qualifier || $test->result_qualifier === 'pending')
                                                                        <span style="color:#cbd5e1;">—</span>
                                                                    @endif
                                                                </div>
                                                            @else
                                                                <span style="color:#cbd5e1;">—</span>
                                                            @endif
                                                        </td>
                                                        <td style="padding:10px 16px; text-align:right;">
                                                            <a href="{{ route('analyst.tests.show', $test->id) }}"
                                                               style="display:inline-flex; padding:5px 12px; font-size:11px; font-weight:600; border-radius:3px; text-decoration:none; white-space:nowrap;
                                                               {{ $test->status === 'completed' ? 'background:#f1f5f9; color:#475569; border:1px solid #e2e8f0;' : ($test->status === 'flagged' ? 'background:#fff7ed; color:#c2410c; border:1px solid #fed7aa;' : 'background:#1a2f4e; color:#fff; border:1px solid #1a2f4e;') }}">
                                                                {{ $test->status === 'completed' ? 'View' : ($test->status === 'flagged' ? 'Review' : ($test->status === 'in_progress' ? 'Continue' : 'Start')) }}
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>{{-- end collapsible --}}
                            </div>{{-- end filter wrapper --}}
                        @endforeach
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
