{{-- resources/views/kstl/director/flagged/index.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <div style="position:relative;overflow:hidden;background:linear-gradient(135deg,#0f2240 0%,#1a2f4e 60%,#1e3a5f 100%);">
            <div style="height:3px;background:linear-gradient(90deg,#1a2f4e,#dc2626 30%,#dc2626 70%,#1a2f4e);"></div>
            <div style="max-width:80rem;margin:0 auto;padding:28px 2rem 32px;">
                <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;">
                    <div style="display:flex;align-items:center;gap:20px;">
                        <img src="{{ asset('images/mfor-logo.png') }}" alt="MFOR" style="filter:brightness(0) invert(1);opacity:.92;width:56px;height:56px;flex-shrink:0;">
                        <div>
                            <p style="font-size:9px;font-weight:700;letter-spacing:.18em;text-transform:uppercase;color:#dc2626;margin:0 0 4px;">Director &middot; Review Queue</p>
                            <h1 style="font-family:'Georgia',serif;font-size:22px;font-weight:700;color:#fff;margin:0 0 6px;line-height:1.2;">Flagged Tests</h1>
                            <p style="font-size:12px;color:#94a3b8;margin:0;">Tests flagged for Director review</p>
                        </div>
                    </div>
                    <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
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
        .page-hdr { padding: 0 !important; }
        .page-hdr-inner { max-width: 100% !important; padding: 0 !important; }
        .app-main { padding-left:0 !important; padding-right:0 !important; padding-top:0 !important; max-width:100% !important; }
    </style>
    @endpush

    <div style="background:#f1f5f9;min-height:100vh;padding:52px 0 56px;">
        <div style="max-width:72rem;margin:0 auto;padding:0 2rem;">

            {{-- Intro banner --}}
            <div style="background:#fff;border:1px solid #e2e8f0;border-left:4px solid #dc2626;border-radius:4px;padding:16px 20px;margin-bottom:24px;display:flex;align-items:center;justify-content:space-between;">
                <p style="font-size:13px;color:#374151;margin:0;">
                    Tests flagged for Director review &mdash; either raised by an analyst or returned by a previous query.
                </p>
                <span style="display:inline-flex;align-items:center;gap:8px;padding:5px 14px;background:#fee2e2;color:#991b1b;border-radius:3px;font-size:12px;font-weight:700;white-space:nowrap;">
                    {{ $flaggedTests->count() }} flagged
                </span>
            </div>

            @if($flaggedTests->isEmpty())
                <div style="background:#fff;border:1px solid #e2e8f0;border-radius:4px;padding:64px 24px;text-align:center;">
                    <p style="font-size:14px;font-weight:600;color:#374151;margin:0 0 8px;">No flagged tests</p>
                    <p style="font-size:13px;color:#94a3b8;margin:0;">Nothing currently needs the Director's attention.</p>
                </div>
            @else
                @foreach($grouped as $submissionId => $tests)
                    @php $submission = $tests->first()->sample->submission ?? null; @endphp
                    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:4px;overflow:hidden;margin-bottom:20px;">

                        {{-- Submission header --}}
                        <div style="padding:16px 24px;border-bottom:1px solid #e2e8f0;display:flex;align-items:flex-start;justify-content:space-between;gap:16px;">
                            <div>
                                <div style="display:flex;align-items:center;gap:8px;margin-bottom:6px;">
                                    <span style="font-family:monospace;font-size:13px;font-weight:700;color:#1a2f4e;">
                                        {{ $submission->reference_number ?? '—' }}
                                    </span>
                                    <span style="display:inline-flex;align-items:center;padding:2px 8px;background:#fee2e2;color:#991b1b;border-radius:9999px;font-size:11px;font-weight:700;">
                                        {{ $tests->count() }} flagged
                                    </span>
                                </div>
                                <p style="font-size:13px;color:#374151;margin:0 0 2px;">
                                    {{ $submission->client->company_name ?? '—' }}
                                </p>
                                @if($submission && $submission->client && $submission->client->user)
                                    <p style="font-size:12px;color:#94a3b8;margin:0;">
                                        {{ $submission->client->user->name ?? trim(($submission->client->user->first_name ?? '') . ' ' . ($submission->client->user->last_name ?? '')) }}
                                    </p>
                                @endif
                            </div>
                            @if($submission)
                                <a href="{{ route('director.submissions.show', $submission->id) }}"
                                   style="flex-shrink:0;display:inline-flex;align-items:center;gap:8px;padding:8px 20px;background:#1a2f4e;color:#fff;font-size:12px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;border-radius:3px;text-decoration:none;">
                                    Review &rarr;
                                </a>
                            @endif
                        </div>

                        {{-- Flagged tests in this submission --}}
                        @foreach($tests as $test)
                            <div style="padding:16px 24px;border-bottom:1px solid #f1f5f9;">
                                <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:16px;">
                                    <div style="min-width:0;">
                                        <p style="font-size:13px;font-weight:600;color:#1e293b;margin:0 0 6px;">{{ $test->getDisplayLabel() }}</p>
                                        <div style="display:flex;flex-wrap:wrap;align-items:center;gap:12px;margin-bottom:6px;">
                                            <span style="display:inline-flex;padding:2px 8px;border-radius:9999px;font-size:11px;font-weight:600;{{ $test->getDisplayCategory() === 'microbiological' ? 'background:#f3e8ff;color:#6b21a8;' : 'background:#dbeafe;color:#1e40af;' }}">
                                                {{ $test->getDisplayCategory() }}
                                            </span>
                                            @if($test->result_value)
                                                <span style="font-size:12px;color:#64748b;">
                                                    <span style="font-size:9px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:#94a3b8;">Value:</span>
                                                    <span style="font-family:monospace;color:#374151;margin-left:4px;">{{ $test->result_value }}</span>
                                                    {{ $test->result_unit }}
                                                </span>
                                            @endif
                                            @if($test->result_qualifier && $test->result_qualifier !== 'pending')
                                                <span style="font-size:12px;color:#64748b;">
                                                    <span style="font-size:9px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:#94a3b8;">Result:</span>
                                                    <span style="text-transform:capitalize;color:#374151;margin-left:4px;">{{ str_replace('_', ' ', $test->result_qualifier) }}</span>
                                                </span>
                                            @endif
                                            <span style="font-size:12px;color:#64748b;">
                                                <span style="font-size:9px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:#94a3b8;">Analyst:</span>
                                                <span style="margin-left:4px;">{{ $test->assignedTo?->name ?? trim(($test->assignedTo->first_name ?? '') . ' ' . ($test->assignedTo->last_name ?? '')) ?: '—' }}</span>
                                            </span>
                                        </div>

                                        @if($test->result_notes)
                                            <p style="font-size:12px;color:#374151;margin:0;background:#fefce8;border:1px solid #fde68a;border-radius:3px;padding:8px 12px;white-space:pre-line;">{{ $test->result_notes }}</p>
                                        @endif

                                        {{-- Supporting documents --}}
                                        @if($test->attachments->isNotEmpty())
                                            <div style="margin-top:10px;">
                                                <p style="font-size:9px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:#94a3b8;margin:0 0 6px;">Supporting documents ({{ $test->attachments->count() }})</p>
                                                <ul style="list-style:none;padding:0;margin:0;">
                                                    @foreach($test->attachments as $attachment)
                                                        <li style="margin-bottom:4px;">
                                                            <a href="{{ route('director.attachments.download', $attachment->id) }}"
                                                               style="font-size:12px;font-weight:600;color:#1a2f4e;text-decoration:none;display:inline-flex;align-items:center;gap:4px;">
                                                                &#8675; {{ $attachment->original_filename }}
                                                            </a>
                                                            <span style="font-size:11px;color:#94a3b8;">({{ $attachment->human_size }})</span>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif
                                    </div>

                                    <span style="flex-shrink:0;display:inline-flex;align-items:center;gap:6px;padding:4px 10px;background:#fee2e2;color:#991b1b;border-radius:3px;font-size:11px;font-weight:700;">
                                        &#9873; Flagged
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            @endif

        </div>
    </div>

</x-app-layout>
