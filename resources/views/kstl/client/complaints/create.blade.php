{{-- resources/views/kstl/client/complaints/create.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <div style="position:relative;overflow:hidden;background:linear-gradient(135deg,#0f2240 0%,#1a2f4e 60%,#1e3a5f 100%);margin:-1px;">
            <div style="height:3px;background:linear-gradient(90deg,#1a2f4e,#b8922a 30%,#b8922a 70%,#1a2f4e);"></div>
            <div style="max-width:80rem;margin:0 auto;padding:28px 2rem 32px;">
                <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;">
                    <div style="display:flex;align-items:center;gap:20px;">
                        <img src="{{ asset('images/mfor-logo.png') }}" alt="MFOR" style="filter:brightness(0) invert(1);opacity:.92;width:56px;height:56px;flex-shrink:0;">
                        <div>
                            <p style="font-size:9px;font-weight:700;letter-spacing:.18em;text-transform:uppercase;color:#b8922a;margin:0 0 4px;">Client</p>
                            <h1 style="font-family:'Georgia',serif;font-size:22px;font-weight:700;color:#fff;margin:0 0 6px;line-height:1.2;">Lodge a Complaint</h1>
                            <p style="font-size:12px;color:#94a3b8;margin:0;">We take all complaints seriously and respond within 5 working days</p>
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
        .page-hdr { padding: 0 !important; position: static !important; }
        .page-hdr-inner { max-width: 100% !important; padding: 0 !important; }
        .app-main { padding-left:0 !important; padding-right:0 !important; padding-top:0 !important; max-width:100% !important; }
    </style>
    @endpush

    <div style="background:#f1f5f9;min-height:100vh;padding:0 0 56px;">
        <div style="max-width:56rem;margin:0 auto;padding:0 2rem;">

            @if ($errors->any())
                <div style="background:#fef2f2;border:1px solid #fecaca;border-left:4px solid #dc2626;border-radius:4px;padding:12px 16px;margin-bottom:20px;">
                    <ul style="margin:0;padding-left:16px;font-size:13px;color:#991b1b;">
                        @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('client.complaints.store') }}">
                @csrf

                <div style="background:#fff;border:1px solid #e2e8f0;border-radius:4px;overflow:hidden;margin-bottom:20px;">
                    <div style="padding:16px 24px;border-bottom:1px solid #e2e8f0;">
                        <h3 style="font-family:'Georgia',serif;font-size:14px;font-weight:700;color:#1a2f4e;margin:0;">Complaint Details</h3>
                        <p style="font-size:12px;color:#94a3b8;margin:4px 0 0;">We take all complaints seriously. We will respond within 5 working days.</p>
                    </div>

                    <div style="padding:20px 24px;display:flex;flex-direction:column;gap:20px;">

                        {{-- Subject --}}
                        <div>
                            <label for="subject" style="display:block;font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#475569;margin-bottom:6px;">
                                Subject <span style="color:#dc2626;">*</span>
                            </label>
                            <input type="text" id="subject" name="subject"
                                   value="{{ old('subject') }}"
                                   placeholder="Brief description of your complaint"
                                   style="width:100%;padding:8px 12px;border:1px solid {{ $errors->has('subject') ? '#dc2626' : '#cbd5e1' }};border-radius:3px;font-size:13px;color:#1e293b;background:{{ $errors->has('subject') ? '#fef2f2' : '#fff' }};box-sizing:border-box;">
                            @error('subject')<p style="margin:4px 0 0;font-size:12px;color:#dc2626;">{{ $message }}</p>@enderror
                        </div>

                        {{-- Date of Incident --}}
                        <div>
                            <label for="incident_date" style="display:block;font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#475569;margin-bottom:6px;">
                                Date of Incident <span style="color:#dc2626;">*</span>
                            </label>
                            <input type="date" id="incident_date" name="incident_date"
                                   value="{{ old('incident_date', now()->toDateString()) }}"
                                   max="{{ now()->toDateString() }}"
                                   style="width:100%;padding:8px 12px;border:1px solid {{ $errors->has('incident_date') ? '#dc2626' : '#cbd5e1' }};border-radius:3px;font-size:13px;color:#1e293b;background:#fff;box-sizing:border-box;">
                            @error('incident_date')<p style="margin:4px 0 0;font-size:12px;color:#dc2626;">{{ $message }}</p>@enderror
                        </div>

                        {{-- Complaint Types --}}
                        <div>
                            <label style="display:block;font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#475569;margin-bottom:8px;">
                                Type of Complaint <span style="color:#dc2626;">*</span>
                                <span style="font-weight:400;font-style:italic;text-transform:none;letter-spacing:0;color:#94a3b8;">&nbsp;— select all that apply</span>
                            </label>
                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;">
                                @foreach(\App\Models\Kstl\Complaint::COMPLAINT_TYPES as $value => $label)
                                    @php $checked = in_array($value, old('complaint_types', [])); @endphp
                                    <label style="display:flex;align-items:center;gap:12px;padding:10px 14px;border-radius:3px;border:1px solid {{ $checked ? '#dc2626' : '#e2e8f0' }};background:{{ $checked ? '#fef2f2' : '#fff' }};cursor:pointer;font-size:13px;color:{{ $checked ? '#991b1b' : '#374151' }};">
                                        <input type="checkbox"
                                               name="complaint_types[]"
                                               value="{{ $value }}"
                                               {{ $checked ? 'checked' : '' }}
                                               style="width:15px;height:15px;accent-color:#dc2626;">
                                        <span style="font-weight:600;">{{ $label }}</span>
                                    </label>
                                @endforeach
                            </div>
                            @error('complaint_types')<p style="margin:4px 0 0;font-size:12px;color:#dc2626;">{{ $message }}</p>@enderror
                        </div>

                        {{-- Related Submission --}}
                        @if(isset($submissions) && $submissions->isNotEmpty())
                            <div>
                                <label for="submission_id" style="display:block;font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#475569;margin-bottom:6px;">
                                    Related Submission
                                    <span style="font-weight:400;font-style:italic;text-transform:none;letter-spacing:0;color:#94a3b8;">— optional</span>
                                </label>
                                <select id="submission_id" name="submission_id"
                                        style="width:100%;padding:8px 12px;border:1px solid #cbd5e1;border-radius:3px;font-size:13px;color:#1e293b;background:#fff;">
                                    <option value="">— Not related to a specific submission —</option>
                                    @foreach($submissions as $sub)
                                        <option value="{{ $sub->id }}" {{ old('submission_id') == $sub->id ? 'selected' : '' }}>
                                            {{ $sub->reference_number }} — {{ $sub->sample_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        {{-- Description --}}
                        <div>
                            <label for="description" style="display:block;font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#475569;margin-bottom:6px;">
                                Description <span style="color:#dc2626;">*</span>
                            </label>
                            <textarea id="description" name="description" rows="6"
                                      placeholder="Provide a detailed description including dates, names of staff involved, and the outcome you are seeking..."
                                      style="width:100%;padding:8px 12px;border:1px solid {{ $errors->has('description') ? '#dc2626' : '#cbd5e1' }};border-radius:3px;font-size:13px;color:#1e293b;background:{{ $errors->has('description') ? '#fef2f2' : '#fff' }};resize:vertical;box-sizing:border-box;">{{ old('description') }}</textarea>
                            <p style="margin:4px 0 0;font-size:11px;color:#94a3b8;">Minimum 20 characters. Be as specific as possible.</p>
                            @error('description')<p style="margin:4px 0 0;font-size:12px;color:#dc2626;">{{ $message }}</p>@enderror
                        </div>

                    </div>
                </div>

                {{-- Actions --}}
                <div style="display:flex;align-items:center;justify-content:space-between;padding-bottom:40px;">
                    <a href="{{ route('client.complaints.index') }}"
                       style="display:inline-flex;align-items:center;gap:8px;padding:8px 20px;background:#fff;color:#1a2f4e;font-size:12px;font-weight:700;letter-spacing:.06em;border:1px solid #1a2f4e;border-radius:3px;text-decoration:none;">
                        Cancel
                    </a>
                    <button type="submit"
                            style="display:inline-flex;align-items:center;gap:8px;padding:8px 20px;background:#dc2626;color:#fff;font-size:12px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;border-radius:3px;border:none;cursor:pointer;">
                        <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                        Submit Complaint
                    </button>
                </div>

            </form>
        </div>
    </div>
</x-app-layout>
