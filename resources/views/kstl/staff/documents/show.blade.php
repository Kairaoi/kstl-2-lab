<x-app-layout>
    <x-slot name="header">
        <div style="position:relative;overflow:hidden;background:linear-gradient(135deg,#0f2240 0%,#1a2f4e 60%,#1e3a5f 100%);margin:-1px;">
            <div style="height:3px;background:linear-gradient(90deg,#1a2f4e,#b8922a 30%,#b8922a 70%,#1a2f4e);"></div>
            <div style="max-width:80rem;margin:0 auto;padding:28px 2rem 32px;">
                <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;">
                    <div style="display:flex;align-items:center;gap:20px;">
                        <img src="{{ asset('images/mfor-logo.png') }}" alt="MFOR" style="filter:brightness(0) invert(1);opacity:.92;width:56px;height:56px;flex-shrink:0;">
                        <div>
                            <p style="font-size:9px;font-weight:700;letter-spacing:.18em;text-transform:uppercase;color:#b8922a;margin:0 0 4px;">Staff · {{ $document->category_label }}</p>
                            <h1 style="font-family:'Georgia',serif;font-size:22px;font-weight:700;color:#fff;margin:0 0 6px;line-height:1.2;">{{ $document->title }}</h1>
                            <p style="font-size:12px;color:#94a3b8;margin:0;">Lab Staff Only</p>
                        </div>
                    </div>
                    <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                        <a href="{{ route('staff.documents.index') }}"
                           style="display:inline-flex;align-items:center;gap:8px;padding:8px 20px;background:rgba(255,255,255,.12);color:#fff;font-size:12px;font-weight:700;letter-spacing:.06em;border:1px solid rgba(255,255,255,.3);border-radius:3px;text-decoration:none;">
                            ← Back to documents
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
        [x-cloak] { display: none !important; }
    </style>
    @endpush

    <div style="background:#f1f5f9;min-height:100vh;padding:0 0 56px;" x-data="{ showUpload: false }">
        <div style="max-width:80rem;margin:0 auto;padding:0 2rem;">

            @if(session('success'))
                <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-left:4px solid #16a34a;border-radius:4px;padding:12px 16px;margin-bottom:20px;font-size:13px;color:#166534;">{{ session('success') }}</div>
            @endif
            @if(session('info'))
                <div style="background:#eff6ff;border:1px solid #bfdbfe;border-left:4px solid #3b82f6;border-radius:4px;padding:12px 16px;margin-bottom:20px;font-size:13px;color:#1e40af;">{{ session('info') }}</div>
            @endif

            {{-- Details --}}
            <div style="background:#fff;border:1px solid #e2e8f0;border-radius:4px;overflow:hidden;margin-bottom:20px;">
                <div style="padding:20px 24px;border-bottom:1px solid #e2e8f0;display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap;">
                    <h3 style="font-family:'Georgia',serif;font-size:14px;font-weight:700;color:#1a2f4e;margin:0;">Document Details</h3>
                    @if($canManage)
                        <div style="display:flex;align-items:center;gap:8px;">
                            <button @click="showUpload = !showUpload"
                                    style="display:inline-flex;align-items:center;gap:6px;padding:7px 16px;background:#fff;color:#0d9488;font-size:11px;font-weight:700;letter-spacing:.06em;border:1px solid #0d9488;border-radius:3px;cursor:pointer;">
                                <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                </svg>
                                Upload New Version
                            </button>
                            <form method="POST" action="{{ route('staff.documents.destroy', $document->id) }}"
                                  onsubmit="return confirm('Delete this document and all its versions? This cannot be undone.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        style="display:inline-flex;align-items:center;gap:6px;padding:7px 16px;background:#fff;color:#dc2626;font-size:11px;font-weight:700;letter-spacing:.06em;border:1px solid #dc2626;border-radius:3px;cursor:pointer;">
                                    <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Delete
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
                <dl style="padding:20px 24px;display:grid;grid-template-columns:1fr 1fr;gap:16px 24px;font-size:13px;">
                    <div>
                        <dt style="font-size:9px;font-weight:600;letter-spacing:.08em;text-transform:uppercase;color:#94a3b8;margin:0 0 4px;">Category</dt>
                        <dd style="color:#1e293b;margin:0;">{{ $document->category_label }}{{ $document->subcategory ? ' · ' . $document->subcategory : '' }}</dd>
                    </div>
                    <div>
                        <dt style="font-size:9px;font-weight:600;letter-spacing:.08em;text-transform:uppercase;color:#94a3b8;margin:0 0 4px;">Reference Code</dt>
                        <dd style="font-family:monospace;color:#1e293b;margin:0;">{{ $document->reference_code ?: '—' }}</dd>
                    </div>
                    <div>
                        <dt style="font-size:9px;font-weight:600;letter-spacing:.08em;text-transform:uppercase;color:#94a3b8;margin:0 0 4px;">Current Version</dt>
                        <dd style="color:#1e293b;margin:0;">
                            @if($document->currentVersion)
                                v{{ $document->currentVersion->version_number }}
                                <a href="{{ route('staff.documents.download', $document->currentVersion->id) }}"
                                   style="margin-left:8px;color:#0d9488;text-decoration:none;font-weight:600;">Download current</a>
                            @else — @endif
                        </dd>
                    </div>
                    <div>
                        <dt style="font-size:9px;font-weight:600;letter-spacing:.08em;text-transform:uppercase;color:#94a3b8;margin:0 0 4px;">Added By</dt>
                        <dd style="color:#1e293b;margin:0;">{{ $document->createdBy?->name ?? trim(($document->createdBy->first_name ?? '') . ' ' . ($document->createdBy->last_name ?? '')) ?: '—' }}</dd>
                    </div>
                    @if($document->description)
                        <div style="grid-column:span 2;">
                            <dt style="font-size:9px;font-weight:600;letter-spacing:.08em;text-transform:uppercase;color:#94a3b8;margin:0 0 4px;">Description</dt>
                            <dd style="color:#374151;white-space:pre-line;margin:0;">{{ $document->description }}</dd>
                        </div>
                    @endif
                </dl>

                {{-- Upload new version (managers) --}}
                @if($canManage)
                    <div x-show="showUpload" x-cloak style="padding:20px 24px;border-top:1px solid #e2e8f0;background:#f8fafc;">
                        <form method="POST" action="{{ route('staff.documents.versions.store', $document->id) }}" enctype="multipart/form-data">
                            @csrf
                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:12px;">
                                <div>
                                    <label style="display:block;font-size:11px;font-weight:600;color:#374151;margin-bottom:4px;text-transform:uppercase;letter-spacing:.06em;">New file *</label>
                                    <input type="file" name="file" required
                                           accept=".pdf,.doc,.docx,.xls,.xlsx,.csv,.txt"
                                           style="display:block;width:100%;font-size:13px;color:#374151;">
                                    <x-input-error for="file" class="mt-1"/>
                                </div>
                                <div>
                                    <label style="display:block;font-size:11px;font-weight:600;color:#374151;margin-bottom:4px;text-transform:uppercase;letter-spacing:.06em;">What changed?</label>
                                    <input type="text" name="change_note" maxlength="500"
                                           placeholder="e.g. Updated acceptance limits in Â§4"
                                           style="width:100%;font-size:13px;border:1px solid #cbd5e1;border-radius:3px;padding:8px 10px;color:#374151;box-sizing:border-box;">
                                </div>
                            </div>
                            <div style="display:flex;justify-content:flex-end;">
                                <button type="submit"
                                        style="display:inline-flex;align-items:center;gap:8px;padding:8px 20px;background:#1a2f4e;color:#fff;font-size:12px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;border-radius:3px;border:none;cursor:pointer;">
                                    Upload as v{{ $document->next_version_number }}
                                </button>
                            </div>
                        </form>
                    </div>
                @endif
            </div>

            {{-- Version history --}}
            <div style="background:#fff;border:1px solid #e2e8f0;border-radius:4px;overflow:hidden;margin-bottom:20px;">
                <div style="padding:20px 24px;border-bottom:1px solid #e2e8f0;">
                    <h3 style="font-family:'Georgia',serif;font-size:14px;font-weight:700;color:#1a2f4e;margin:0 0 4px;">Version History</h3>
                    <p style="font-size:12px;color:#94a3b8;margin:0;">All prior versions are retained for audit. The current version is highlighted.</p>
                </div>
                <div style="overflow-x:auto;">
                    <table style="width:100%;border-collapse:collapse;">
                        <thead>
                            <tr style="background:#1a2f4e;">
                                <th style="padding:10px 16px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">Version</th>
                                <th style="padding:10px 16px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">File</th>
                                <th style="padding:10px 16px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">Change Note</th>
                                <th style="padding:10px 16px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">Uploaded</th>
                                <th style="padding:10px 16px;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($document->versions as $version)
                                <tr style="border-bottom:1px solid #f1f5f9;{{ $document->current_version_id === $version->id ? 'background:#f0fdf4;' : ($loop->even ? 'background:#f8fafc;' : '') }}">
                                    <td style="padding:12px 16px;">
                                        <span style="font-size:13px;font-weight:700;color:#1e293b;">v{{ $version->version_number }}</span>
                                        @if($document->current_version_id === $version->id)
                                            <span style="margin-left:6px;display:inline-flex;padding:2px 8px;font-size:10px;font-weight:700;text-transform:uppercase;background:#dcfce7;color:#166534;border-radius:20px;">current</span>
                                        @endif
                                    </td>
                                    <td style="padding:12px 16px;">
                                        <p style="font-size:13px;color:#374151;margin:0 0 2px;">{{ $version->original_filename }}</p>
                                        <p style="font-size:11px;color:#94a3b8;margin:0;">{{ $version->human_size }}</p>
                                    </td>
                                    <td style="padding:12px 16px;font-size:13px;color:#374151;">{{ $version->change_note ?: '—' }}</td>
                                    <td style="padding:12px 16px;">
                                        <p style="font-size:13px;color:#374151;margin:0 0 2px;">{{ $version->created_at->format('d M Y H:i') }}</p>
                                        @if($version->uploadedBy)
                                            <p style="font-size:11px;color:#94a3b8;margin:0;">{{ $version->uploadedBy->name ?? trim(($version->uploadedBy->first_name ?? '') . ' ' . ($version->uploadedBy->last_name ?? '')) }}</p>
                                        @endif
                                    </td>
                                    <td style="padding:12px 16px;text-align:right;">
                                        <a href="{{ route('staff.documents.download', $version->id) }}"
                                           style="display:inline-flex;align-items:center;gap:4px;font-size:11px;font-weight:700;color:#0d9488;text-decoration:none;">
                                            <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                            </svg>
                                            Download
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
