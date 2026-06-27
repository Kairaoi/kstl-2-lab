{{-- resources/views/kstl/client/complaints/show.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <div style="position:relative;overflow:hidden;background:linear-gradient(135deg,#0f2240 0%,#1a2f4e 60%,#1e3a5f 100%);margin:-1px;">
            <div style="position:absolute;inset:0;opacity:.04;background-image:repeating-linear-gradient(45deg,#fff 0,#fff 1px,transparent 0,transparent 50%);background-size:12px 12px;pointer-events:none;"></div>
            <div style="position:absolute;top:0;left:0;right:0;height:3px;background:linear-gradient(90deg,#1a2f4e,#b8922a 30%,#b8922a 70%,#1a2f4e);"></div>
            <div style="max-width:80rem;margin:0 auto;padding:28px 2rem;">
                <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;position:relative;">
                    <div style="display:flex;align-items:center;gap:20px;">
                        <img src="{{ asset('images/mfor-logo.png') }}" alt="MFOR" style="filter:brightness(0) invert(1);opacity:.92;width:56px;height:56px;flex-shrink:0;">
                        <div>
                            <p style="font-size:9px;font-weight:700;letter-spacing:.18em;text-transform:uppercase;color:#b8922a;margin:0 0 4px;">Client Portal</p>
                            <h1 style="font-family:'Georgia',serif;font-size:22px;font-weight:700;color:#fff;margin:0 0 6px;line-height:1.2;">{{ $complaint->subject }}</h1>
                            <p style="font-size:12px;color:#94a3b8;margin:0;">Lodged {{ $complaint->created_at->format('d M Y \a\t H:i') }}</p>
                        </div>
                    </div>
                    <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                        <a href="{{ route('client.complaints.index') }}" style="display:inline-flex;align-items:center;gap:8px;padding:8px 20px;background:#fff;color:#1a2f4e;font-size:12px;font-weight:700;letter-spacing:.06em;border:1px solid #1a2f4e;border-radius:3px;text-decoration:none;">
                            &#8592; Back to Complaints
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
        <div style="max-width:56rem;margin:0 auto;padding:0 2rem;">

            @if(session('success'))
                <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-left:4px solid #16a34a;border-radius:4px;padding:12px 16px;margin-bottom:20px;font-size:13px;color:#166534;">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div style="background:#fef2f2;border:1px solid #fecaca;border-left:4px solid #dc2626;border-radius:4px;padding:12px 16px;margin-bottom:20px;font-size:13px;color:#991b1b;">{{ session('error') }}</div>
            @endif

            {{-- Status Banner --}}
            @php
                $statusStyles = match($complaint->status) {
                    'open'                => 'background:#fef2f2;color:#dc2626;',
                    'under_investigation' => 'background:#fffbeb;color:#b8922a;',
                    'resolved'            => 'background:#f0fdf4;color:#16a34a;',
                    'closed'              => 'background:#f1f5f9;color:#64748b;',
                    default               => 'background:#f1f5f9;color:#64748b;',
                };
            @endphp
            <div style="background:#fff;border:1px solid #e2e8f0;border-radius:4px;padding:20px 24px;display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
                <div>
                    <p style="font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#64748b;margin:0 0 8px;">Status</p>
                    <span style="display:inline-flex;align-items:center;padding:3px 12px;border-radius:20px;font-size:11px;font-weight:700;text-transform:uppercase;{{ $statusStyles }}">
                        {{ str_replace('_', ' ', $complaint->status) }}
                    </span>
                </div>
                @if($complaint->resolved_at)
                    <div style="text-align:right;">
                        <p style="font-size:12px;color:#94a3b8;margin:0;">Resolved {{ $complaint->resolved_at->format('d M Y') }}</p>
                    </div>
                @endif
            </div>

            {{-- Complaint Details --}}
            <div style="background:#fff;border:1px solid #e2e8f0;border-radius:4px;overflow:hidden;margin-bottom:16px;">
                <div style="padding:16px 24px;border-bottom:1px solid #e2e8f0;">
                    <h3 style="font-family:'Georgia',serif;font-size:14px;font-weight:700;color:#1a2f4e;margin:0;">Your Complaint</h3>
                </div>
                <div style="padding:20px 24px;">
                    <dl style="display:flex;flex-direction:column;gap:16px;">
                        <div>
                            <dt style="font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#94a3b8;margin-bottom:6px;">Type(s)</dt>
                            <dd style="margin:0;display:flex;flex-wrap:wrap;gap:6px;">
                                @foreach($complaint->getComplaintTypeLabels() as $label)
                                    <span style="display:inline-flex;padding:3px 10px;border-radius:20px;font-size:10px;font-weight:700;text-transform:uppercase;background:#fef2f2;color:#dc2626;">{{ $label }}</span>
                                @endforeach
                            </dd>
                        </div>
                        <div>
                            <dt style="font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#94a3b8;margin-bottom:6px;">Incident Date</dt>
                            <dd style="margin:0;font-size:13px;color:#374151;">{{ $complaint->incident_date->format('d M Y') }}</dd>
                        </div>
                        @if($complaint->submission)
                            <div>
                                <dt style="font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#94a3b8;margin-bottom:6px;">Related Submission</dt>
                                <dd style="margin:0;font-size:13px;color:#374151;font-family:monospace;">{{ $complaint->submission->reference_number }}</dd>
                            </div>
                        @endif
                        <div>
                            <dt style="font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#94a3b8;margin-bottom:6px;">Description</dt>
                            <dd style="margin:0;font-size:13px;color:#374151;line-height:1.6;white-space:pre-line;">{{ $complaint->description }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            {{-- Lab Response --}}
            @if($complaint->lab_response)
                <div style="background:#eff6ff;border:1px solid #bfdbfe;border-left:4px solid #1a2f4e;border-radius:4px;overflow:hidden;margin-bottom:16px;">
                    <div style="padding:16px 24px;border-bottom:1px solid #bfdbfe;">
                        <h3 style="font-family:'Georgia',serif;font-size:14px;font-weight:700;color:#1a2f4e;margin:0;">Laboratory Response</h3>
                        @if($complaint->assignedTo)
                            <p style="font-size:12px;color:#1e40af;margin:4px 0 0;">From {{ $complaint->assignedTo->name }}</p>
                        @endif
                    </div>
                    <div style="padding:20px 24px;">
                        <p style="font-size:13px;color:#1e40af;line-height:1.6;white-space:pre-line;margin:0 0 12px;">{{ $complaint->lab_response }}</p>
                        @if($complaint->action_taken)
                            <div style="padding-top:12px;border-top:1px solid #bfdbfe;">
                                <p style="font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#1e40af;margin:0 0 6px;">Action Taken</p>
                                <p style="font-size:13px;color:#1e40af;line-height:1.6;white-space:pre-line;margin:0;">{{ $complaint->action_taken }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @else
                <div style="background:#fffbeb;border:1px solid #fde68a;border-left:4px solid #b8922a;border-radius:4px;padding:16px 20px;margin-bottom:16px;font-size:13px;color:#92400e;">
                    <p style="margin:0;">Your complaint has been received and is being reviewed. We will respond within 5 working days.</p>
                </div>
            @endif

            <div style="padding-bottom:32px;"></div>

        </div>
    </div>
</x-app-layout>
