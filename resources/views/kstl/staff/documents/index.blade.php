<x-app-layout>
    <x-slot name="header">
        <div style="position:relative;overflow:hidden;background:linear-gradient(135deg,#0f2240 0%,#1a2f4e 60%,#1e3a5f 100%);">
            <div style="height:3px;background:linear-gradient(90deg,#1a2f4e,#b8922a 30%,#b8922a 70%,#1a2f4e);"></div>
            <div style="max-width:80rem;margin:0 auto;padding:28px 2rem 32px;">
                <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;">
                    <div style="display:flex;align-items:center;gap:20px;">
                        <img src="{{ asset('images/mfor-logo.png') }}" alt="MFOR" style="filter:brightness(0) invert(1);opacity:.92;width:56px;height:56px;flex-shrink:0;">
                        <div>
                            <p style="font-size:9px;font-weight:700;letter-spacing:.18em;text-transform:uppercase;color:#b8922a;margin:0 0 4px;">Staff Portal</p>
                            <h1 style="font-family:'Georgia',serif;font-size:22px;font-weight:700;color:#fff;margin:0 0 6px;line-height:1.2;">Documents Repository</h1>
                            <p style="font-size:12px;color:#94a3b8;margin:0;">Lab Staff Only</p>
                        </div>
                    </div>
                    <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                        <!-- action buttons injected by Alpine below if canManage -->
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
        [x-cloak] { display: none !important; }
    </style>
    @endpush

    <div style="background:#f1f5f9;min-height:100vh;padding:52px 0 56px;" x-data="{ showAdd: false }">
        <div style="max-width:80rem;margin:0 auto;padding:0 2rem;">

            @if(session('success'))
                <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-left:4px solid #16a34a;border-radius:4px;padding:12px 16px;margin-bottom:20px;font-size:13px;color:#166534;">{{ session('success') }}</div>
            @endif
            @if(session('info'))
                <div style="background:#eff6ff;border:1px solid #bfdbfe;border-left:4px solid #3b82f6;border-radius:4px;padding:12px 16px;margin-bottom:20px;font-size:13px;color:#1e40af;">{{ session('info') }}</div>
            @endif

            {{-- Staff-only banner --}}
            <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:4px;padding:16px 20px;display:flex;align-items:center;justify-content:space-between;gap:16px;margin-bottom:20px;flex-wrap:wrap;">
                <div style="display:flex;align-items:center;gap:12px;">
                    <span style="display:inline-flex;align-items:center;justify-content:center;width:36px;height:36px;border-radius:50%;background:#166534;flex-shrink:0;">
                        <svg style="width:16px;height:16px;stroke:#fff;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </span>
                    <div>
                        <p style="font-size:13px;font-weight:700;color:#14532d;margin:0 0 2px;">Controlled Document Repository</p>
                        <p style="font-size:12px;color:#166534;margin:0;">Accessible to laboratory staff only. SOPs, manuals, records and templates with version history.</p>
                    </div>
                </div>
                @if($canManage)
                    <button @click="showAdd = !showAdd"
                            style="display:inline-flex;align-items:center;gap:8px;padding:8px 20px;background:#1a2f4e;color:#fff;font-size:12px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;border-radius:3px;border:none;cursor:pointer;flex-shrink:0;">
                        <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Add Document
                    </button>
                @endif
            </div>

            {{-- Add document form (managers only) --}}
            @if($canManage)
                <div x-show="showAdd" x-cloak style="background:#fff;border:1px solid #e2e8f0;border-radius:4px;overflow:hidden;margin-bottom:20px;">
                    <div style="padding:20px 24px;border-bottom:1px solid #e2e8f0;">
                        <h3 style="font-family:'Georgia',serif;font-size:14px;font-weight:700;color:#1a2f4e;margin:0 0 4px;">Add a Document</h3>
                        <p style="font-size:12px;color:#94a3b8;margin:0;">Uploads the first version. New versions can be added later from the document page.</p>
                    </div>
                    <form method="POST" action="{{ route('staff.documents.store') }}" enctype="multipart/form-data" style="padding:20px 24px;">
                        @csrf
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;">
                            <div>
                                <label style="display:block;font-size:11px;font-weight:600;color:#374151;margin-bottom:4px;text-transform:uppercase;letter-spacing:.06em;">Title *</label>
                                <input type="text" name="title" required maxlength="255"
                                       value="{{ old('title') }}"
                                       placeholder="e.g. Aerobic Plate Count SOP"
                                       style="width:100%;font-size:13px;border:1px solid #cbd5e1;border-radius:3px;padding:8px 10px;color:#374151;box-sizing:border-box;">
                                <x-input-error for="title" class="mt-1"/>
                            </div>
                            <div>
                                <label style="display:block;font-size:11px;font-weight:600;color:#374151;margin-bottom:4px;text-transform:uppercase;letter-spacing:.06em;">Category *</label>
                                <select name="category" required
                                        style="width:100%;font-size:13px;border:1px solid #cbd5e1;border-radius:3px;padding:8px 10px;color:#374151;background:#fff;box-sizing:border-box;">
                                    @foreach($categories as $value => $label)
                                        <option value="{{ $value }}" @selected(old('category') === $value)>{{ $label }}</option>
                                    @endforeach
                                </select>
                                <x-input-error for="category" class="mt-1"/>
                            </div>
                            <div>
                                <label style="display:block;font-size:11px;font-weight:600;color:#374151;margin-bottom:4px;text-transform:uppercase;letter-spacing:.06em;">Subcategory</label>
                                <input type="text" name="subcategory" maxlength="255"
                                       value="{{ old('subcategory') }}"
                                       placeholder="e.g. Microbiology"
                                       style="width:100%;font-size:13px;border:1px solid #cbd5e1;border-radius:3px;padding:8px 10px;color:#374151;box-sizing:border-box;">
                            </div>
                            <div>
                                <label style="display:block;font-size:11px;font-weight:600;color:#374151;margin-bottom:4px;text-transform:uppercase;letter-spacing:.06em;">Reference Code</label>
                                <input type="text" name="reference_code" maxlength="60"
                                       value="{{ old('reference_code') }}"
                                       placeholder="e.g. SOP-MICRO-001"
                                       style="width:100%;font-size:13px;border:1px solid #cbd5e1;border-radius:3px;padding:8px 10px;color:#374151;box-sizing:border-box;">
                            </div>
                        </div>
                        <div style="margin-bottom:16px;">
                            <label style="display:block;font-size:11px;font-weight:600;color:#374151;margin-bottom:4px;text-transform:uppercase;letter-spacing:.06em;">Description</label>
                            <textarea name="description" rows="2" maxlength="2000"
                                      style="width:100%;font-size:13px;border:1px solid #cbd5e1;border-radius:3px;padding:8px 10px;color:#374151;box-sizing:border-box;">{{ old('description') }}</textarea>
                        </div>
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;">
                            <div>
                                <label style="display:block;font-size:11px;font-weight:600;color:#374151;margin-bottom:4px;text-transform:uppercase;letter-spacing:.06em;">File *</label>
                                <input type="file" name="file" required
                                       accept=".pdf,.doc,.docx,.xls,.xlsx,.csv,.txt"
                                       style="display:block;width:100%;font-size:13px;color:#374151;">
                                <p style="font-size:11px;color:#94a3b8;margin:4px 0 0;">PDF, Word, Excel, CSV or text. Max 20&nbsp;MB.</p>
                                <x-input-error for="file" class="mt-1"/>
                            </div>
                            <div>
                                <label style="display:block;font-size:11px;font-weight:600;color:#374151;margin-bottom:4px;text-transform:uppercase;letter-spacing:.06em;">Change note</label>
                                <input type="text" name="change_note" maxlength="500"
                                       placeholder="e.g. Initial issue"
                                       style="width:100%;font-size:13px;border:1px solid #cbd5e1;border-radius:3px;padding:8px 10px;color:#374151;box-sizing:border-box;">
                            </div>
                        </div>
                        <div style="display:flex;justify-content:flex-end;gap:8px;">
                            <button type="button" @click="showAdd = false"
                                    style="display:inline-flex;align-items:center;gap:8px;padding:8px 20px;background:#fff;color:#1a2f4e;font-size:12px;font-weight:700;letter-spacing:.06em;border:1px solid #1a2f4e;border-radius:3px;cursor:pointer;">Cancel</button>
                            <button type="submit"
                                    style="display:inline-flex;align-items:center;gap:8px;padding:8px 20px;background:#1a2f4e;color:#fff;font-size:12px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;border-radius:3px;border:none;cursor:pointer;">Save Document</button>
                        </div>
                    </form>
                </div>
            @endif

            {{-- Category filter chips --}}
            <div style="display:flex;flex-wrap:wrap;gap:8px;margin-bottom:20px;">
                <a href="{{ route('staff.documents.index') }}"
                   style="padding:6px 14px;font-size:11px;font-weight:700;border-radius:20px;text-decoration:none;letter-spacing:.06em;text-transform:uppercase;{{ ! $active ? 'background:#1a2f4e;color:#fff;border:1px solid #1a2f4e;' : 'background:#fff;color:#64748b;border:1px solid #cbd5e1;' }}">
                    All
                </a>
                @foreach($categories as $value => $label)
                    <a href="{{ route('staff.documents.index', ['category' => $value]) }}"
                       style="padding:6px 14px;font-size:11px;font-weight:700;border-radius:20px;text-decoration:none;letter-spacing:.06em;text-transform:uppercase;{{ $active === $value ? 'background:#1a2f4e;color:#fff;border:1px solid #1a2f4e;' : 'background:#fff;color:#64748b;border:1px solid #cbd5e1;' }}">
                        {{ $label }}
                        <span style="opacity:.7;margin-left:4px;">{{ $counts[$value] ?? 0 }}</span>
                    </a>
                @endforeach
            </div>

            {{-- Documents by category --}}
            @forelse($categories as $value => $label)
                @php $docs = $documents[$value] ?? collect(); @endphp
                @if(! $active || $active === $value)
                    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:4px;overflow:hidden;margin-bottom:20px;">
                        <div style="padding:16px 24px;border-bottom:1px solid #e2e8f0;display:flex;align-items:center;justify-content:space-between;">
                            <h3 style="font-family:'Georgia',serif;font-size:14px;font-weight:700;color:#1a2f4e;margin:0;">{{ $label }}</h3>
                            <span style="font-size:11px;color:#94a3b8;">{{ $docs->count() }} document{{ $docs->count() === 1 ? '' : 's' }}</span>
                        </div>

                        @if($docs->isEmpty())
                            <div style="padding:32px 24px;text-align:center;">
                                <p style="font-size:13px;color:#94a3b8;margin:0;">No documents in this category yet.</p>
                            </div>
                        @else
                            <ul style="list-style:none;margin:0;padding:0;">
                                @foreach($docs as $doc)
                                    <li style="padding:14px 24px;display:flex;align-items:center;justify-content:space-between;gap:16px;border-bottom:1px solid #f1f5f9;{{ $loop->even ? 'background:#f8fafc;' : '' }}">
                                        <div style="min-width:0;">
                                            <a href="{{ route('staff.documents.show', $doc->id) }}"
                                               style="font-size:13px;font-weight:600;color:#1a2f4e;text-decoration:none;">
                                                {{ $doc->title }}
                                            </a>
                                            <div style="display:flex;flex-wrap:wrap;align-items:center;gap:8px;margin-top:4px;font-size:11px;color:#94a3b8;">
                                                @if($doc->reference_code)
                                                    <span style="font-family:monospace;">{{ $doc->reference_code }}</span>
                                                @endif
                                                @if($doc->subcategory)
                                                    <span>{{ $doc->subcategory }}</span>
                                                @endif
                                                @if($doc->currentVersion)
                                                    <span>v{{ $doc->currentVersion->version_number }} · {{ $doc->currentVersion->human_size }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div style="flex-shrink:0;display:flex;align-items:center;gap:12px;">
                                            @if($doc->currentVersion)
                                                <a href="{{ route('staff.documents.download', $doc->currentVersion->id) }}"
                                                   style="display:inline-flex;align-items:center;gap:4px;font-size:11px;font-weight:700;color:#0d9488;text-decoration:none;">
                                                    <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                                    </svg>
                                                    Download
                                                </a>
                                            @endif
                                            <a href="{{ route('staff.documents.show', $doc->id) }}"
                                               style="font-size:11px;color:#94a3b8;text-decoration:none;">History →</a>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                @endif
            @empty
            @endforelse

        </div>
    </div>
</x-app-layout>
