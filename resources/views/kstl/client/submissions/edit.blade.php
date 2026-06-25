{{-- resources/views/kstl/client/submissions/edit.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <div style="position:relative;overflow:hidden;background:linear-gradient(135deg,#0f2240 0%,#1a2f4e 60%,#1e3a5f 100%);">
            <div style="height:3px;background:linear-gradient(90deg,#1a2f4e,#b8922a 30%,#b8922a 70%,#1a2f4e);"></div>
            <div style="max-width:80rem;margin:0 auto;padding:28px 2rem 32px;">
                <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;">
                    <div style="display:flex;align-items:center;gap:20px;">
                        <img src="{{ asset('images/mfor-logo.png') }}" alt="MFOR" style="filter:brightness(0) invert(1);opacity:.92;width:56px;height:56px;flex-shrink:0;">
                        <div>
                            <p style="font-size:9px;font-weight:700;letter-spacing:.18em;text-transform:uppercase;color:#b8922a;margin:0 0 4px;">Client Portal</p>
                            <h1 style="font-family:'Georgia',serif;font-size:22px;font-weight:700;color:#fff;margin:0 0 6px;line-height:1.2;">Edit Submission</h1>
                            <p style="font-size:12px;color:#94a3b8;margin:0;">Update sample details and special instructions</p>
                        </div>
                    </div>
                    <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                        <a href="{{ route('client.submissions.index') }}" style="display:inline-flex;align-items:center;gap:8px;padding:8px 20px;background:#fff;color:#1a2f4e;font-size:12px;font-weight:700;letter-spacing:.06em;border:1px solid #1a2f4e;border-radius:3px;text-decoration:none;">
                            &#8592; Back to Submissions
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
        <div style="max-width:80rem;margin:0 auto;padding:0 2rem;">

            {{-- Validation Errors --}}
            @if($errors->any())
                <div style="background:#fef2f2;border:1px solid #fecaca;border-left:4px solid #dc2626;border-radius:4px;padding:12px 16px;margin-bottom:20px;">
                    <ul style="margin:0;padding-left:16px;font-size:13px;color:#991b1b;">
                        @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
            @endif

            {{-- Cannot edit warning --}}
            {{--
                @if(!in_array($submission->status, ['pending']))
                    <div style="background:#fef2f2;border:1px solid #fecaca;border-left:4px solid #dc2626;border-radius:4px;padding:12px 16px;margin-bottom:20px;font-size:13px;color:#991b1b;">
                        <strong>Cannot edit.</strong>
                        This submission is currently <strong>{{ $submission->status }}</strong>
                        and can no longer be modified.
                    </div>
                @endif
            --}}

            <form method="POST" action="{{ route('client.submissions.update', $submission->id) }}"
                  enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- ── Section 1: Sample Information ──────────────────────────────── --}}
                <div style="display:grid;grid-template-columns:1fr 2fr;gap:24px;margin-bottom:24px;">
                    <div style="padding:0 4px;">
                        <h3 style="font-family:'Georgia',serif;font-size:15px;font-weight:700;color:#1a2f4e;margin:0 0 6px;">Sample Information</h3>
                        <p style="font-size:13px;color:#64748b;margin:0;">Update the sample details.</p>
                    </div>
                    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:4px;overflow:hidden;">
                        <div style="padding:20px 24px;display:flex;flex-direction:column;gap:20px;">

                            <div>
                                <label for="sample_name" style="display:block;font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#475569;margin-bottom:6px;">Sample Name *</label>
                                <input id="sample_name"
                                       type="text"
                                       name="sample_name"
                                       value="{{ old('sample_name') }}"
                                       {{-- value="{{ old('sample_name', $submission->sample_name) }}" --}}
                                       style="width:100%;padding:8px 12px;border:1px solid #cbd5e1;border-radius:3px;font-size:13px;color:#1e293b;background:#fff;box-sizing:border-box;"
                                       required autofocus>
                                @error('sample_name')<p style="margin:4px 0 0;font-size:12px;color:#dc2626;">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="sample_description" style="display:block;font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#475569;margin-bottom:6px;">Sample Description</label>
                                <textarea id="sample_description"
                                          name="sample_description"
                                          rows="3"
                                          style="width:100%;padding:8px 12px;border:1px solid #cbd5e1;border-radius:3px;font-size:13px;color:#1e293b;background:#fff;resize:vertical;box-sizing:border-box;"
                                          placeholder="Describe the sample...">{{ old('sample_description') }}</textarea>
                                          {{-- >{{ old('sample_description', $submission->sample_description) }}</textarea> --}}
                                @error('sample_description')<p style="margin:4px 0 0;font-size:12px;color:#dc2626;">{{ $message }}</p>@enderror
                            </div>

                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                                <div>
                                    <label for="sample_type" style="display:block;font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#475569;margin-bottom:6px;">Sample Type *</label>
                                    <select id="sample_type"
                                            name="sample_type"
                                            style="width:100%;padding:8px 12px;border:1px solid #cbd5e1;border-radius:3px;font-size:13px;color:#1e293b;background:#fff;"
                                            required>
                                        <option value="">— Select type —</option>
                                        <option value="fish"      {{ old('sample_type') === 'fish'      ? 'selected' : '' }}>Fish</option>
                                        <option value="shellfish" {{ old('sample_type') === 'shellfish' ? 'selected' : '' }}>Shellfish</option>
                                        <option value="seaweed"   {{ old('sample_type') === 'seaweed'   ? 'selected' : '' }}>Seaweed</option>
                                        <option value="water"     {{ old('sample_type') === 'water'     ? 'selected' : '' }}>Water</option>
                                        <option value="sediment"  {{ old('sample_type') === 'sediment'  ? 'selected' : '' }}>Sediment</option>
                                        <option value="other"     {{ old('sample_type') === 'other'     ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('sample_type')<p style="margin:4px 0 0;font-size:12px;color:#dc2626;">{{ $message }}</p>@enderror
                                </div>

                                <div>
                                    <label for="sample_quantity" style="display:block;font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#475569;margin-bottom:6px;">Quantity / Weight *</label>
                                    <div style="display:flex;">
                                        <input id="sample_quantity"
                                               type="number"
                                               name="sample_quantity"
                                               value="{{ old('sample_quantity') }}"
                                               style="flex:1;padding:8px 12px;border:1px solid #cbd5e1;border-right:none;border-radius:3px 0 0 3px;font-size:13px;color:#1e293b;background:#fff;box-sizing:border-box;"
                                               placeholder="0"
                                               min="0"
                                               step="0.01"
                                               required>
                                        <select name="sample_quantity_unit"
                                                style="padding:8px 10px;border:1px solid #cbd5e1;border-radius:0 3px 3px 0;font-size:13px;color:#1e293b;background:#fff;">
                                            <option value="g">g</option>
                                            <option value="kg" selected>kg</option>
                                            <option value="ml">ml</option>
                                            <option value="L">L</option>
                                        </select>
                                    </div>
                                    @error('sample_quantity')<p style="margin:4px 0 0;font-size:12px;color:#dc2626;">{{ $message }}</p>@enderror
                                </div>
                            </div>

                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                                <div>
                                    <label for="collected_at" style="display:block;font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#475569;margin-bottom:6px;">Collection Date *</label>
                                    <input id="collected_at"
                                           type="date"
                                           name="collected_at"
                                           value="{{ old('collected_at') }}"
                                           style="width:100%;padding:8px 12px;border:1px solid #cbd5e1;border-radius:3px;font-size:13px;color:#1e293b;background:#fff;box-sizing:border-box;"
                                           max="{{ date('Y-m-d') }}"
                                           required>
                                    @error('collected_at')<p style="margin:4px 0 0;font-size:12px;color:#dc2626;">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label for="collection_location" style="display:block;font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#475569;margin-bottom:6px;">Collection Location</label>
                                    <input id="collection_location"
                                           type="text"
                                           name="collection_location"
                                           value="{{ old('collection_location') }}"
                                           style="width:100%;padding:8px 12px;border:1px solid #cbd5e1;border-radius:3px;font-size:13px;color:#1e293b;background:#fff;box-sizing:border-box;"
                                           placeholder="e.g. South Tarawa Lagoon">
                                    @error('collection_location')<p style="margin:4px 0 0;font-size:12px;color:#dc2626;">{{ $message }}</p>@enderror
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <hr style="border:none;border-top:1px solid #e2e8f0;margin:0 0 24px;">

                {{-- ── Section 2: Special Instructions ────────────────────────────── --}}
                <div style="display:grid;grid-template-columns:1fr 2fr;gap:24px;margin-bottom:32px;">
                    <div style="padding:0 4px;">
                        <h3 style="font-family:'Georgia',serif;font-size:15px;font-weight:700;color:#1a2f4e;margin:0 0 6px;">Special Instructions</h3>
                        <p style="font-size:13px;color:#64748b;margin:0;">Handling requirements and priority.</p>
                    </div>
                    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:4px;overflow:hidden;">
                        <div style="padding:20px 24px;display:flex;flex-direction:column;gap:20px;">

                            <div>
                                <label style="display:block;font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#475569;margin-bottom:8px;">Priority Level</label>
                                <div style="display:flex;gap:20px;flex-wrap:wrap;">
                                    @foreach(['routine' => 'Routine', 'urgent' => 'Urgent'] as $value => $label)
                                        <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:13px;color:#374151;">
                                            <input type="radio"
                                                   name="priority"
                                                   value="{{ $value }}"
                                                   {{ old('priority', 'routine') === $value ? 'checked' : '' }}>
                                            {{ $label }}
                                        </label>
                                    @endforeach
                                </div>
                                @error('priority')<p style="margin:4px 0 0;font-size:12px;color:#dc2626;">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="storage_conditions" style="display:block;font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#475569;margin-bottom:6px;">Storage / Handling Conditions</label>
                                <select id="storage_conditions"
                                        name="storage_conditions"
                                        style="width:100%;padding:8px 12px;border:1px solid #cbd5e1;border-radius:3px;font-size:13px;color:#1e293b;background:#fff;">
                                    <option value="">— Select conditions —</option>
                                    <option value="ambient"  {{ old('storage_conditions') === 'ambient'  ? 'selected' : '' }}>Ambient / Room temperature</option>
                                    <option value="chilled"  {{ old('storage_conditions') === 'chilled'  ? 'selected' : '' }}>Chilled (0“4Â°C)</option>
                                    <option value="frozen"   {{ old('storage_conditions') === 'frozen'   ? 'selected' : '' }}>Frozen (âˆ’18Â°C or below)</option>
                                    <option value="dry_ice"  {{ old('storage_conditions') === 'dry_ice'  ? 'selected' : '' }}>Dry Ice</option>
                                </select>
                                @error('storage_conditions')<p style="margin:4px 0 0;font-size:12px;color:#dc2626;">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="special_instructions" style="display:block;font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#475569;margin-bottom:6px;">Additional Notes</label>
                                <textarea id="special_instructions"
                                          name="special_instructions"
                                          rows="3"
                                          style="width:100%;padding:8px 12px;border:1px solid #cbd5e1;border-radius:3px;font-size:13px;color:#1e293b;background:#fff;resize:vertical;box-sizing:border-box;"
                                          placeholder="Any other instructions for the lab team...">{{ old('special_instructions') }}</textarea>
                                @error('special_instructions')<p style="margin:4px 0 0;font-size:12px;color:#dc2626;">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="results_required_by" style="display:block;font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#475569;margin-bottom:6px;">Results Required By</label>
                                <input id="results_required_by"
                                       type="date"
                                       name="results_required_by"
                                       value="{{ old('results_required_by') }}"
                                       style="width:50%;padding:8px 12px;border:1px solid #cbd5e1;border-radius:3px;font-size:13px;color:#1e293b;background:#fff;box-sizing:border-box;"
                                       min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                                @error('results_required_by')<p style="margin:4px 0 0;font-size:12px;color:#dc2626;">{{ $message }}</p>@enderror
                            </div>

                        </div>
                    </div>
                </div>

                {{-- ── Form Actions ──────────────────────────────────────────────── --}}
                <div style="display:flex;align-items:center;justify-content:space-between;padding-top:8px;padding-bottom:32px;">
                    <a href="{{ route('client.submissions.index') }}" style="display:inline-flex;align-items:center;gap:8px;padding:8px 20px;background:#fff;color:#1a2f4e;font-size:12px;font-weight:700;letter-spacing:.06em;border:1px solid #1a2f4e;border-radius:3px;text-decoration:none;">
                        Cancel
                    </a>
                    <button type="submit"
                            style="display:inline-flex;align-items:center;gap:8px;padding:8px 20px;background:#1a2f4e;color:#fff;font-size:12px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;border-radius:3px;border:none;cursor:pointer;">
                        Update Submission
                    </button>
                </div>

            </form>
        </div>
    </div>
</x-app-layout>
