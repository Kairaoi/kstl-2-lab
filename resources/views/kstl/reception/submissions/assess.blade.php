{{-- resources/views/kstl/reception/submissions/assess.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <div style="position:relative;overflow:hidden;background:linear-gradient(135deg,#0f2240 0%,#1a2f4e 60%,#1e3a5f 100%);">
            <div style="height:3px;background:linear-gradient(90deg,#1a2f4e,#b8922a 30%,#b8922a 70%,#1a2f4e);"></div>
            <div style="max-width:80rem;margin:0 auto;padding:28px 2rem 32px;">
                <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;">
                    <div style="display:flex;align-items:center;gap:20px;">
                        <img src="{{ asset('images/mfor-logo.png') }}" alt="MFOR" style="filter:brightness(0) invert(1);opacity:.92;width:56px;height:56px;flex-shrink:0;">
                        <div>
                            <p style="font-size:9px;font-weight:700;letter-spacing:.18em;text-transform:uppercase;color:#b8922a;margin:0 0 4px;">Sample Assessment</p>
                            <h1 style="font-family:'Georgia',serif;font-size:22px;font-weight:700;color:#fff;margin:0 0 6px;line-height:1.2;">Assess Samples &mdash; {{ $submission->reference_number }}</h1>
                            <p style="font-size:12px;color:#94a3b8;margin:0;">{{ $samples->count() }} sample{{ $samples->count() !== 1 ? 's' : '' }} to assess</p>
                        </div>
                    </div>
                    <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                        <span style="display:inline-flex;align-items:center;padding:5px 12px;background:rgba(255,255,255,.12);color:#e2e8f0;font-size:10px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;border-radius:20px;">
                            Assessment in Progress
                        </span>
                        <a href="{{ route('reception.dashboard') }}"
                           style="display:inline-flex;align-items:center;gap:8px;padding:8px 20px;background:rgba(255,255,255,.12);color:#e2e8f0;font-size:12px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;border-radius:3px;text-decoration:none;border:1px solid rgba(255,255,255,.2);">
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
        <div style="max-width:80rem;margin:0 auto;padding:0 2rem;">

            {{-- Flash Messages --}}
            @if(session('success'))
                <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-left:4px solid #16a34a;border-radius:4px;padding:12px 16px;margin-bottom:20px;font-size:13px;color:#166534;">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div style="background:#fef2f2;border:1px solid #fecaca;border-left:4px solid #dc2626;border-radius:4px;padding:12px 16px;margin-bottom:20px;font-size:13px;color:#991b1b;">{{ session('error') }}</div>
            @endif

            <form method="POST"
                  action="{{ route('reception.submissions.assess.store', $submission->id) }}"
                  id="assessment-form">

                @csrf

                @foreach($samples as $i => $sample)
                    <input type="hidden"
                           name="assessments[{{ $i }}][sample_id]"
                           value="{{ $sample->id }}">

                    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:4px;overflow:hidden;margin-bottom:24px;">

                        {{-- Sample Header --}}
                        <div style="padding:16px 24px;border-bottom:1px solid #e2e8f0;background:#f8fafc;display:flex;align-items:center;justify-content:space-between;">
                            <div>
                                <h3 style="font-family:'Georgia',serif;font-size:15px;font-weight:700;color:#1a2f4e;margin:0 0 3px;">
                                    {{ $sample->sample_code }} &mdash; {{ $sample->common_name }}
                                </h3>
                                <p style="font-size:12px;color:#64748b;margin:0;">
                                    {{ $sample->quantity }} {{ $sample->quantity_unit }}
                                    @if($sample->scientific_name)
                                        &middot; <em>{{ $sample->scientific_name }}</em>
                                    @endif
                                </p>
                            </div>
                            <span style="font-size:11px;font-family:monospace;background:#fff;padding:4px 12px;border-radius:20px;border:1px solid #e2e8f0;color:#374151;">
                                Sample {{ $i + 1 }} of {{ $samples->count() }}
                            </span>
                        </div>

                        <div style="padding:24px;display:flex;flex-direction:column;gap:0;">

                            @php
                                $criteria = [
                                    'temperature' => 'Temperature',
                                    'storage'     => 'Storage Condition',
                                    'transport'   => 'Transport Condition',
                                    'packaging'   => 'Packaging Integrity',
                                    'colour'      => 'Colour / Appearance',
                                    'odour'       => 'Odour',
                                    'weight'      => 'Weight / Quantity Check',
                                ];
                            @endphp

                            @foreach($criteria as $key => $label)
                                <div style="display:grid;grid-template-columns:3fr 4fr 5fr;gap:16px;align-items:flex-start;border-bottom:1px solid #f1f5f9;padding:16px 0;{{ $loop->last ? 'border-bottom:none;' : '' }}">

                                    {{-- Label --}}
                                    <div>
                                        <p style="font-size:13px;font-weight:600;color:#1e293b;margin:0;">{{ $label }}</p>
                                    </div>

                                    {{-- Pass / Fail --}}
                                    <div>
                                        <div style="display:flex;gap:24px;">
                                            <label style="display:flex;align-items:center;gap:8px;cursor:pointer;">
                                                <input type="radio"
                                                       name="assessments[{{ $i }}][{{ $key }}_ok]"
                                                       value="1"
                                                       required
                                                       style="width:16px;height:16px;accent-color:#16a34a;">
                                                <span style="font-size:13px;font-weight:700;color:#16a34a;">Pass</span>
                                            </label>

                                            <label style="display:flex;align-items:center;gap:8px;cursor:pointer;">
                                                <input type="radio"
                                                       name="assessments[{{ $i }}][{{ $key }}_ok]"
                                                       value="0"
                                                       style="width:16px;height:16px;accent-color:#dc2626;">
                                                <span style="font-size:13px;font-weight:700;color:#dc2626;">Fail</span>
                                            </label>
                                        </div>
                                    </div>

                                    {{-- Notes --}}
                                    <div>
                                        <input type="text"
                                               name="assessments[{{ $i }}][{{ $key }}_notes]"
                                               placeholder="Notes / observations (optional)"
                                               style="width:100%;padding:8px 12px;border:1px solid #cbd5e1;border-radius:3px;font-size:13px;color:#1e293b;background:#fff;box-sizing:border-box;">
                                    </div>
                                </div>
                            @endforeach

                            {{-- Additional Observations --}}
                            <div style="padding-top:16px;">
                                <label style="display:block;font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#475569;margin-bottom:6px;">
                                    Additional Observations
                                </label>
                                <textarea name="assessments[{{ $i }}][additional_observations]"
                                          rows="3"
                                          style="width:100%;padding:8px 12px;border:1px solid #cbd5e1;border-radius:3px;font-size:13px;color:#1e293b;background:#fff;box-sizing:border-box;resize:vertical;"
                                          placeholder="Any other relevant observations..."></textarea>
                            </div>

                            {{-- Rejection Reason (shown only if any Fail is selected) --}}
                            <div x-data="{ showRejection: false }" style="padding-top:16px;">
                                <div style="display:flex;align-items:center;gap:8px;margin-bottom:8px;">
                                    <input type="checkbox"
                                           id="has_rejection_{{ $i }}"
                                           @change="showRejection = $el.checked"
                                           style="width:16px;height:16px;accent-color:#dc2626;cursor:pointer;">
                                    <label for="has_rejection_{{ $i }}" style="font-size:13px;font-weight:700;color:#dc2626;cursor:pointer;">
                                        This sample should be rejected
                                    </label>
                                </div>

                                <div x-show="showRejection" x-cloak>
                                    <label style="display:block;font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#dc2626;margin-bottom:6px;">
                                        Rejection Reason <span style="color:#dc2626;">*</span>
                                    </label>
                                    <textarea name="assessments[{{ $i }}][rejection_reason]"
                                              rows="3"
                                              style="width:100%;padding:8px 12px;border:1px solid #fca5a5;border-radius:3px;font-size:13px;color:#1e293b;background:#fff;box-sizing:border-box;resize:vertical;"
                                              placeholder="Please explain why this sample is being rejected..."></textarea>
                                </div>
                            </div>

                        </div>
                    </div>
                @endforeach

                {{-- Action Buttons --}}
                <div style="display:flex;align-items:center;justify-content:space-between;padding-top:8px;padding-bottom:24px;">
                    <a href="{{ route('reception.submissions.show', $submission->id) }}"
                       style="display:inline-flex;align-items:center;gap:8px;padding:8px 20px;background:#fff;color:#1a2f4e;font-size:12px;font-weight:700;letter-spacing:.06em;border:1px solid #1a2f4e;border-radius:3px;text-decoration:none;">
                        &larr; Back to Submission Details
                    </a>

                    <button type="submit"
                            onclick="return confirm('Are you sure you want to submit this assessment? This action cannot be undone.')"
                            style="display:inline-flex;align-items:center;gap:10px;padding:10px 28px;background:#1a2f4e;color:#fff;font-size:13px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;border-radius:3px;border:none;cursor:pointer;">
                        <svg style="width:18px;height:18px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Submit Assessment Results
                    </button>
                </div>

            </form>

        </div>
    </div>

    @push('scripts')
    <script>
        // Optional: Auto-show rejection reason when any "Fail" is selected
        document.addEventListener('change', function(e) {
            if (e.target.type === 'radio' && e.target.value === '0') {
                const sampleIndex = e.target.name.match(/\[(\d+)\]/)[1];
                const rejectionSection = document.querySelector(`#has_rejection_${sampleIndex}`);
                if (rejectionSection) rejectionSection.checked = true;
            }
        });
    </script>
    @endpush

</x-app-layout>
