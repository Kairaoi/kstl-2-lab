{{-- resources/views/kstl/director/complaints/show.blade.php --}}

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
                            <h1 style="font-family:'Georgia',serif;font-size:22px;font-weight:700;color:#fff;margin:0 0 6px;line-height:1.2;">{{ $complaint->subject }}</h1>
                            <p style="font-size:12px;color:#94a3b8;margin:0;">From {{ $complaint->complainant_name }} &middot; {{ $complaint->created_at->format('d M Y') }}</p>
                        </div>
                    </div>
                    <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                        <a href="{{ route('director.complaints.index') }}"
                           style="display:inline-flex;align-items:center;gap:8px;padding:8px 20px;background:#fff;color:#1a2f4e;font-size:12px;font-weight:700;letter-spacing:.06em;border:1px solid #1a2f4e;border-radius:3px;text-decoration:none;">
                            &larr; All Complaints
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

            @if(session('success'))
                <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-left:4px solid #16a34a;border-radius:4px;padding:12px 16px;margin-bottom:20px;font-size:13px;color:#166534;">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div style="background:#fef2f2;border:1px solid #fecaca;border-left:4px solid #dc2626;border-radius:4px;padding:12px 16px;margin-bottom:20px;font-size:13px;color:#991b1b;">{{ session('error') }}</div>
            @endif

            <div style="display:grid;grid-template-columns:1fr 2fr;gap:20px;align-items:start;">

                {{-- Left: Complaint info --}}
                <div>
                    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:4px;overflow:hidden;margin-bottom:20px;">
                        <div style="padding:14px 20px;border-bottom:1px solid #e2e8f0;">
                            <h3 style="font-family:'Georgia',serif;font-size:14px;font-weight:700;color:#1a2f4e;margin:0;">Complainant</h3>
                        </div>
                        <dl style="padding:16px 20px;">
                            <div style="margin-bottom:12px;">
                                <dt style="font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#64748b;margin:0 0 4px;">Name</dt>
                                <dd style="font-size:13px;font-weight:600;color:#1e293b;margin:0;">{{ $complaint->complainant_name ?? '—' }}</dd>
                            </div>
                            <div style="margin-bottom:12px;">
                                <dt style="font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#64748b;margin:0 0 4px;">Organisation</dt>
                                <dd style="font-size:13px;color:#374151;margin:0;">{{ $complaint->complainant_organisation ?? '—' }}</dd>
                            </div>
                            <div style="margin-bottom:12px;">
                                <dt style="font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#64748b;margin:0 0 4px;">Email</dt>
                                <dd style="font-size:13px;color:#374151;margin:0;">{{ $complaint->complainant_email ?? '—' }}</dd>
                            </div>
                            <div>
                                <dt style="font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#64748b;margin:0 0 4px;">Incident Date</dt>
                                <dd style="font-size:13px;color:#374151;margin:0;">{{ $complaint->incident_date->format('d M Y') }}</dd>
                            </div>
                        </dl>
                    </div>

                    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:4px;overflow:hidden;">
                        <div style="padding:14px 20px;border-bottom:1px solid #e2e8f0;">
                            <h3 style="font-family:'Georgia',serif;font-size:14px;font-weight:700;color:#1a2f4e;margin:0;">Details</h3>
                        </div>
                        <dl style="padding:16px 20px;">
                            <div style="margin-bottom:12px;">
                                <dt style="font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#64748b;margin:0 0 6px;">Type(s)</dt>
                                <dd style="margin:0;display:flex;flex-wrap:wrap;gap:4px;">
                                    @foreach($complaint->getComplaintTypeLabels() as $label)
                                        <span style="display:inline-flex;padding:2px 8px;font-size:11px;font-weight:600;border-radius:9999px;background:#fee2e2;color:#991b1b;">{{ $label }}</span>
                                    @endforeach
                                </dd>
                            </div>
                            @if($complaint->submission)
                                <div style="margin-bottom:12px;">
                                    <dt style="font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#64748b;margin:0 0 4px;">Submission</dt>
                                    <dd style="font-family:monospace;font-size:12px;color:#374151;margin:0;">{{ $complaint->submission->reference_number }}</dd>
                                </div>
                            @endif
                            <div style="margin-bottom:12px;">
                                <dt style="font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#64748b;margin:0 0 6px;">Status</dt>
                                <dd style="margin:0;">
                                    @php
                                        $statusBadge = match($complaint->status) {
                                            'open'                => 'background:#fef9c3;color:#854d0e;',
                                            'under_investigation' => 'background:#dbeafe;color:#1e40af;',
                                            'resolved'            => 'background:#dcfce7;color:#166534;',
                                            'closed'              => 'background:#f1f5f9;color:#64748b;',
                                            default               => 'background:#f1f5f9;color:#64748b;',
                                        };
                                    @endphp
                                    <span style="display:inline-flex;padding:2px 8px;font-size:11px;font-weight:600;border-radius:9999px;text-transform:capitalize;{{ $statusBadge }}">
                                        {{ str_replace('_', ' ', $complaint->status) }}
                                    </span>
                                </dd>
                            </div>
                            @if($complaint->resolvedBy)
                                <div>
                                    <dt style="font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#64748b;margin:0 0 4px;">Resolved By</dt>
                                    <dd style="font-size:13px;color:#374151;margin:0;">{{ $complaint->resolvedBy->name }}</dd>
                                    <dd style="font-size:11px;color:#94a3b8;margin:2px 0 0;">{{ $complaint->resolved_at?->format('d M Y') }}</dd>
                                </div>
                            @endif
                        </dl>
                    </div>
                </div>

                {{-- Right: Description + Response --}}
                <div>

                    {{-- Description --}}
                    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:4px;overflow:hidden;margin-bottom:20px;">
                        <div style="padding:16px 24px;border-bottom:1px solid #e2e8f0;">
                            <h3 style="font-family:'Georgia',serif;font-size:14px;font-weight:700;color:#1a2f4e;margin:0;">Complaint Description</h3>
                        </div>
                        <div style="padding:20px 24px;font-size:13px;color:#374151;line-height:1.7;white-space:pre-line;">
                            {{ $complaint->description }}
                        </div>
                    </div>

                    {{-- Existing response --}}
                    @if($complaint->lab_response)
                        <div style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:4px;overflow:hidden;margin-bottom:20px;">
                            <div style="padding:14px 24px;border-bottom:1px solid #bfdbfe;">
                                <h3 style="font-family:'Georgia',serif;font-size:14px;font-weight:700;color:#1e40af;margin:0;">Previous Response</h3>
                                @if($complaint->assignedTo)
                                    <p style="font-size:12px;color:#2563eb;margin:4px 0 0;">By {{ $complaint->assignedTo->name }}</p>
                                @endif
                            </div>
                            <div style="padding:20px 24px;font-size:13px;color:#1e3a8a;line-height:1.7;white-space:pre-line;">
                                {{ $complaint->lab_response }}
                                @if($complaint->action_taken)
                                    <div style="margin-top:12px;padding-top:12px;border-top:1px solid #bfdbfe;">
                                        <p style="font-size:12px;font-weight:700;color:#1e40af;margin:0 0 4px;">Action Taken:</p>
                                        <p style="white-space:pre-line;margin:0;">{{ $complaint->action_taken }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    {{-- Respond form --}}
                    @if(! $complaint->isClosed())
                        <div style="background:#fff;border:1px solid #e2e8f0;border-radius:4px;overflow:hidden;">
                            <div style="padding:16px 24px;border-bottom:1px solid #e2e8f0;">
                                <h3 style="font-family:'Georgia',serif;font-size:14px;font-weight:700;color:#1a2f4e;margin:0;">
                                    {{ $complaint->lab_response ? 'Update Response' : 'Respond to Complaint' }}
                                </h3>
                            </div>
                            <form method="POST"
                                  action="{{ route('director.complaints.respond', $complaint->id) }}"
                                  style="padding:20px 24px;">
                                @csrf

                                <div style="margin-bottom:16px;">
                                    <label style="display:block;font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#475569;margin-bottom:6px;">Response *</label>
                                    <textarea id="lab_response" name="lab_response" rows="5"
                                              style="width:100%;padding:8px 12px;border:1px solid #cbd5e1;border-radius:3px;font-size:13px;color:#1e293b;background:#fff;resize:vertical;"
                                              placeholder="Describe your findings and response to the complainant...">{{ old('lab_response', $complaint->lab_response) }}</textarea>
                                    <x-input-error for="lab_response" class="mt-1"/>
                                </div>

                                <div style="margin-bottom:16px;">
                                    <label style="display:block;font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#475569;margin-bottom:6px;">Action Taken (optional)</label>
                                    <textarea id="action_taken" name="action_taken" rows="3"
                                              style="width:100%;padding:8px 12px;border:1px solid #cbd5e1;border-radius:3px;font-size:13px;color:#1e293b;background:#fff;resize:vertical;"
                                              placeholder="Describe any corrective actions taken...">{{ old('action_taken', $complaint->action_taken) }}</textarea>
                                </div>

                                <div style="margin-bottom:20px;">
                                    <label style="display:block;font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#475569;margin-bottom:6px;">Update Status *</label>
                                    <select id="status" name="status"
                                            style="width:100%;padding:8px 12px;border:1px solid #cbd5e1;border-radius:3px;font-size:13px;color:#1e293b;background:#fff;">
                                        <option value="open" {{ $complaint->status === 'open' ? 'selected' : '' }}>Open</option>
                                        <option value="under_investigation" {{ $complaint->status === 'under_investigation' ? 'selected' : '' }}>Under Investigation</option>
                                        <option value="resolved" {{ $complaint->status === 'resolved' ? 'selected' : '' }}>Resolved</option>
                                        <option value="closed" {{ $complaint->status === 'closed' ? 'selected' : '' }}>Closed</option>
                                    </select>
                                    <x-input-error for="status" class="mt-1"/>
                                </div>

                                <div style="display:flex;justify-content:flex-end;padding-top:8px;">
                                    <button type="submit"
                                            style="display:inline-flex;align-items:center;gap:8px;padding:8px 20px;background:#0d9488;color:#fff;font-size:12px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;border-radius:3px;border:none;cursor:pointer;">
                                        Save Response
                                    </button>
                                </div>
                            </form>
                        </div>
                    @endif

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
