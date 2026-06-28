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
                            <p style="font-size:9px;font-weight:700;letter-spacing:.18em;text-transform:uppercase;color:#b8922a;margin:0 0 4px;">Client</p>
                            <h1 style="font-family:'Georgia',serif;font-size:22px;font-weight:700;color:#fff;margin:0 0 6px;line-height:1.2;">{{ $client ? 'Update Company Details' : 'Complete Your Company Profile' }}</h1>
                            <p style="font-size:12px;color:#94a3b8;margin:0;">Step 1 of onboarding — your details appear on all reports and invoices</p>
                        </div>
                    </div>
                    <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                        <a href="{{ route('client.dashboard') }}" style="display:inline-flex;align-items:center;gap:8px;padding:8px 20px;background:#fff;color:#1a2f4e;font-size:12px;font-weight:700;letter-spacing:.06em;border:1px solid #1a2f4e;border-radius:3px;text-decoration:none;">
                            Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    @push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@23/build/css/intlTelInput.css">
    <style>
        .page-hdr { padding: 0 !important; position: static !important; }
        .page-hdr-inner { max-width: 100% !important; padding: 0 !important; }
        .app-main { padding-left:0 !important; padding-right:0 !important; padding-top:0 !important; max-width:100% !important; }
        /* Fit iti widget to our input style */
        .iti { width: 100%; }
        .iti__tel-input {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #cbd5e1;
            border-radius: 3px;
            font-size: 13px;
            color: #1e293b;
            background: #fff;
            box-sizing: border-box;
        }
        .iti__tel-input:focus {
            outline: none;
            border-color: #0d9488;
            box-shadow: 0 0 0 2px rgba(13,148,136,.15);
        }
        /* company phone is 50% wide */
        #company_phone_wrap { width: 50%; }
    </style>
    @endpush

    <div style="background:#f1f5f9;min-height:100vh;padding:0 0 56px;">
        <div style="max-width:80rem;margin:0 auto;padding:0 2rem;">

            {{-- Progress Steps --}}
            <div style="background:#fff;border:1px solid #e2e8f0;border-radius:4px;padding:20px 24px;margin-bottom:24px;">
                <div style="display:flex;align-items:center;">

                    {{-- Step 1 --}}
                    <div style="display:flex;align-items:center;gap:10px;">
                        <div style="width:32px;height:32px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;{{ $client ? 'background:#16a34a;color:#fff;' : 'background:#1a2f4e;color:#fff;' }}">
                            {!! $client ? '&#10003;' : '1' !!}
                        </div>
                        <span style="font-size:13px;font-weight:600;{{ $client ? 'color:#16a34a;' : 'color:#1a2f4e;' }}">
                            Company Details
                        </span>
                    </div>

                    <div style="flex:1;height:2px;margin:0 12px;background:{{ $client ? '#86efac' : '#e2e8f0' }};"></div>

                    {{-- Step 2 --}}
                    <div style="display:flex;align-items:center;gap:10px;">
                        <div style="width:32px;height:32px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;{{ $client && $client->service_agreement_signed_at ? 'background:#16a34a;color:#fff;' : 'background:#e2e8f0;color:#64748b;' }}">
                            {!! $client && $client->service_agreement_signed_at ? '&#10003;' : '2' !!}
                        </div>
                        <span style="font-size:13px;font-weight:600;color:#64748b;">
                            Sign Agreement
                        </span>
                    </div>

                    <div style="flex:1;height:2px;margin:0 12px;background:#e2e8f0;"></div>

                    {{-- Step 3 --}}
                    <div style="display:flex;align-items:center;gap:10px;">
                        <div style="width:32px;height:32px;border-radius:50%;background:#e2e8f0;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;color:#64748b;">
                            3
                        </div>
                        <span style="font-size:13px;font-weight:600;color:#64748b;">Access Lab</span>
                    </div>

                </div>
            </div>

            {{-- Info Banner --}}
            @if(! $client)
                <div style="background:#eff6ff;border:1px solid #bfdbfe;border-left:4px solid #1a2f4e;border-radius:4px;padding:12px 16px;margin-bottom:20px;">
                    <p style="font-size:13px;font-weight:700;color:#1a2f4e;margin:0 0 4px;">Step 1 of 2 — Company Details</p>
                    <p style="font-size:13px;color:#1e40af;margin:0;">
                        Please provide your company information before signing the service agreement.
                        This information will appear on your test reports and invoices.
                    </p>
                </div>
            @endif

            {{-- Flash Messages --}}
            @if(session('success'))
                <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-left:4px solid #16a34a;border-radius:4px;padding:12px 16px;margin-bottom:20px;font-size:13px;color:#166534;">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div style="background:#fef2f2;border:1px solid #fecaca;border-left:4px solid #dc2626;border-radius:4px;padding:12px 16px;margin-bottom:20px;font-size:13px;color:#991b1b;">{{ session('error') }}</div>
            @endif

            {{-- Form --}}
            <form method="POST"
                  action="{{ $client ? route('client.profile.company.update') : route('client.profile.company.store') }}">
                @csrf
                @if($client) @method('PUT') @endif

                @if($errors->any())
                    <div style="background:#fef2f2;border:1px solid #fecaca;border-left:4px solid #dc2626;border-radius:4px;padding:12px 16px;margin-bottom:20px;">
                        <ul style="margin:0;padding-left:16px;font-size:13px;color:#991b1b;">
                            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                        </ul>
                    </div>
                @endif

                {{-- Company / Organisation --}}
                <div style="display:grid;grid-template-columns:1fr 2fr;gap:24px;margin-bottom:24px;">
                    <div style="padding:0 4px;">
                        <h3 style="font-family:'Georgia',serif;font-size:15px;font-weight:700;color:#1a2f4e;margin:0 0 6px;">Company / Organisation</h3>
                        <p style="font-size:13px;color:#64748b;margin:0;">
                            Your business details as they will appear on reports and invoices.
                        </p>
                    </div>
                    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:4px;overflow:hidden;">
                        <div style="padding:20px 24px;display:flex;flex-direction:column;gap:20px;">

                            <div>
                                <label for="company_name" style="display:block;font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#475569;margin-bottom:6px;">Company / Organisation Name *</label>
                                <input id="company_name"
                                       type="text"
                                       name="company_name"
                                       value="{{ old('company_name', $client?->company_name) }}"
                                       style="width:100%;padding:8px 12px;border:1px solid #cbd5e1;border-radius:3px;font-size:13px;color:#1e293b;background:#fff;box-sizing:border-box;"
                                       placeholder="e.g. Smith Seafoods Ltd"
                                       required autofocus>
                                @error('company_name')<p style="margin:4px 0 0;font-size:12px;color:#dc2626;">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="address" style="display:block;font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#475569;margin-bottom:6px;">Business Address *</label>
                                <textarea id="address"
                                          name="address"
                                          rows="3"
                                          style="width:100%;padding:8px 12px;border:1px solid #cbd5e1;border-radius:3px;font-size:13px;color:#1e293b;background:#fff;resize:vertical;box-sizing:border-box;"
                                          placeholder="Street address, City, Island"
                                          required>{{ old('address', $client?->address) }}</textarea>
                                @error('address')<p style="margin:4px 0 0;font-size:12px;color:#dc2626;">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="company_phone" style="display:block;font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#475569;margin-bottom:6px;">Company Phone</label>
                                <div id="company_phone_wrap">
                                    <input id="company_phone"
                                           type="tel"
                                           data-iti
                                           value="{{ old('company_phone', $client?->company_phone) }}"
                                           placeholder="12345">
                                    <input type="hidden" name="company_phone" id="company_phone_full">
                                </div>
                                @error('company_phone')<p style="margin:4px 0 0;font-size:12px;color:#dc2626;">{{ $message }}</p>@enderror
                            </div>

                        </div>
                    </div>
                </div>

                <hr style="border:none;border-top:1px solid #e2e8f0;margin:0 0 24px;">

                {{-- Responsible Officer --}}
                <div style="display:grid;grid-template-columns:1fr 2fr;gap:24px;margin-bottom:32px;">
                    <div style="padding:0 4px;">
                        <h3 style="font-family:'Georgia',serif;font-size:15px;font-weight:700;color:#1a2f4e;margin:0 0 6px;">Responsible Officer</h3>
                        <p style="font-size:13px;color:#64748b;margin:0;">
                            The person responsible for this account and who signs agreements.
                            May be different from the login user.
                        </p>
                    </div>
                    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:4px;overflow:hidden;">
                        <div style="padding:20px 24px;display:flex;flex-direction:column;gap:20px;">

                            <div>
                                <label for="responsible_officer_name" style="display:block;font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#475569;margin-bottom:6px;">Full Name *</label>
                                <input id="responsible_officer_name"
                                       type="text"
                                       name="responsible_officer_name"
                                       value="{{ old('responsible_officer_name', $client?->responsible_officer_name) }}"
                                       style="width:100%;padding:8px 12px;border:1px solid #cbd5e1;border-radius:3px;font-size:13px;color:#1e293b;background:#fff;box-sizing:border-box;"
                                       placeholder="e.g. John Smith"
                                       required>
                                @error('responsible_officer_name')<p style="margin:4px 0 0;font-size:12px;color:#dc2626;">{{ $message }}</p>@enderror
                            </div>

                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                                <div>
                                    <label for="responsible_officer_email" style="display:block;font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#475569;margin-bottom:6px;">Email Address *</label>
                                    <input id="responsible_officer_email"
                                           type="email"
                                           name="responsible_officer_email"
                                           value="{{ old('responsible_officer_email', $client?->responsible_officer_email ?? auth()->user()->email) }}"
                                           style="width:100%;padding:8px 12px;border:1px solid #cbd5e1;border-radius:3px;font-size:13px;color:#1e293b;background:#fff;box-sizing:border-box;"
                                           required>
                                    @error('responsible_officer_email')<p style="margin:4px 0 0;font-size:12px;color:#dc2626;">{{ $message }}</p>@enderror
                                </div>

                                <div>
                                    <label for="responsible_officer_phone" style="display:block;font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#475569;margin-bottom:6px;">Phone</label>
                                    <input id="responsible_officer_phone"
                                           type="tel"
                                           data-iti
                                           value="{{ old('responsible_officer_phone', $client?->responsible_officer_phone) }}"
                                           placeholder="12345">
                                    <input type="hidden" name="responsible_officer_phone" id="responsible_officer_phone_full">
                                    @error('responsible_officer_phone')<p style="margin:4px 0 0;font-size:12px;color:#dc2626;">{{ $message }}</p>@enderror
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div style="display:flex;align-items:center;justify-content:space-between;padding-top:8px;padding-bottom:32px;">
                    <a href="{{ route('client.dashboard') }}" style="display:inline-flex;align-items:center;gap:8px;padding:8px 20px;background:#fff;color:#1a2f4e;font-size:12px;font-weight:700;letter-spacing:.06em;border:1px solid #1a2f4e;border-radius:3px;text-decoration:none;">
                        Back to Dashboard
                    </a>

                    <button type="submit"
                            style="display:inline-flex;align-items:center;gap:8px;padding:8px 20px;background:#1a2f4e;color:#fff;font-size:12px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;border-radius:3px;border:none;cursor:pointer;">
                        {{ $client ? 'Update Details' : 'Save & Continue to Agreement' }}
                        @if(! $client)
                            <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        @endif
                    </button>
                </div>

            </form>
        </div>
    </div>
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@23/build/js/intlTelInput.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const itiOptions = {
        initialCountry: 'ki',
        separateDialCode: true,
        preferredCountries: ['ki', 'au', 'nz', 'fj'],
        loadUtils: () => import('https://cdn.jsdelivr.net/npm/intl-tel-input@23/build/js/utils.js'),
    };

    // Pairs of [visible input, hidden full-number input]
    const pairs = [
        ['company_phone',            'company_phone_full'],
        ['responsible_officer_phone','responsible_officer_phone_full'],
    ];

    pairs.forEach(([visId, hidId]) => {
        const visEl = document.getElementById(visId);
        const hidEl = document.getElementById(hidId);
        if (!visEl || !hidEl) return;

        // Strip dial code from stored value so the visible input shows just the local number
        const stored = visEl.value.trim();

        const iti = window.intlTelInput(visEl, itiOptions);

        // If there's a stored E.164 value (starts with +), let the library parse it
        if (stored.startsWith('+')) {
            iti.setNumber(stored);
        }

        // On form submit, copy full E.164 number into the hidden field
        visEl.closest('form').addEventListener('submit', function () {
            hidEl.value = iti.getNumber();
        });
    });
});
</script>
@endpush

</x-app-layout>
