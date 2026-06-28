{{-- resources/views/kstl/client/create.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div style="position:relative;overflow:hidden;background:linear-gradient(135deg,#0f2240 0%,#1a2f4e 60%,#1e3a5f 100%);">
            <div style="height:3px;background:linear-gradient(90deg,#1a2f4e,#b8922a 30%,#b8922a 70%,#1a2f4e);"></div>
            <div style="max-width:80rem;margin:0 auto;padding:28px 2rem 32px;">
                <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;">
                    <div style="display:flex;align-items:center;gap:20px;">
                        <img src="{{ asset('images/mfor-logo.png') }}" alt="MFOR" style="filter:brightness(0) invert(1);opacity:.92;width:56px;height:56px;flex-shrink:0;">
                        <div>
                            <p style="font-size:9px;font-weight:700;letter-spacing:.18em;text-transform:uppercase;color:#b8922a;margin:0 0 4px;">Administration</p>
                            <h1 style="font-family:'Georgia',serif;font-size:22px;font-weight:700;color:#fff;margin:0 0 6px;line-height:1.2;">Register New Client</h1>
                            <p style="font-size:12px;color:#94a3b8;margin:0;">Create a new client account</p>
                        </div>
                    </div>
                    <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                        <a href="{{ route('client.index') }}" style="display:inline-flex;align-items:center;gap:8px;padding:8px 20px;background:#fff;color:#1a2f4e;font-size:12px;font-weight:700;letter-spacing:.06em;border:1px solid rgba(255,255,255,.5);border-radius:3px;text-decoration:none;">
                            &larr; Back to Clients
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
        <div style="max-width:64rem;margin:0 auto;padding:0 2rem;">

            @if($errors->any())
                <div style="background:#fef2f2;border:1px solid #fecaca;border-left:4px solid #dc2626;border-radius:4px;padding:12px 16px;margin-bottom:20px;">
                    <ul style="margin:0;padding-left:16px;font-size:13px;color:#991b1b;">
                        @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('client.store') }}">
                @csrf

                {{-- Company Information --}}
                <div style="background:#fff;border:1px solid #e2e8f0;border-radius:4px;overflow:hidden;margin-bottom:24px;">
                    <div style="padding:16px 24px;border-bottom:1px solid #e2e8f0;">
                        <h3 style="font-family:'Georgia',serif;font-size:14px;font-weight:700;color:#1a2f4e;margin:0;">Company Information</h3>
                    </div>
                    <div style="padding:20px 24px;">
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
                            <div style="grid-column:1/-1;">
                                <label style="display:block;font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#475569;margin-bottom:6px;">Company Name <span style="color:#dc2626;">*</span></label>
                                <input type="text" name="company_name" value="{{ old('company_name') }}" required
                                       style="width:100%;padding:8px 12px;border:1px solid #cbd5e1;border-radius:3px;font-size:13px;color:#1e293b;background:#fff;box-sizing:border-box;">
                            </div>
                            <div style="grid-column:1/-1;">
                                <label style="display:block;font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#475569;margin-bottom:6px;">Address <span style="color:#dc2626;">*</span></label>
                                <textarea name="address" rows="3" required
                                          style="width:100%;padding:8px 12px;border:1px solid #cbd5e1;border-radius:3px;font-size:13px;color:#1e293b;background:#fff;resize:vertical;box-sizing:border-box;">{{ old('address') }}</textarea>
                            </div>
                            <div>
                                <label style="display:block;font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#475569;margin-bottom:6px;">Company Phone</label>
                                <input type="text" name="company_phone" value="{{ old('company_phone') }}"
                                       style="width:100%;padding:8px 12px;border:1px solid #cbd5e1;border-radius:3px;font-size:13px;color:#1e293b;background:#fff;box-sizing:border-box;">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Responsible Officer --}}
                <div style="background:#fff;border:1px solid #e2e8f0;border-radius:4px;overflow:hidden;margin-bottom:24px;">
                    <div style="padding:16px 24px;border-bottom:1px solid #e2e8f0;">
                        <h3 style="font-family:'Georgia',serif;font-size:14px;font-weight:700;color:#1a2f4e;margin:0;">Responsible Officer</h3>
                    </div>
                    <div style="padding:20px 24px;">
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
                            <div>
                                <label style="display:block;font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#475569;margin-bottom:6px;">Full Name</label>
                                <input type="text" name="responsible_officer_name" value="{{ old('responsible_officer_name') }}"
                                       style="width:100%;padding:8px 12px;border:1px solid #cbd5e1;border-radius:3px;font-size:13px;color:#1e293b;background:#fff;box-sizing:border-box;">
                            </div>
                            <div>
                                <label style="display:block;font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#475569;margin-bottom:6px;">Email</label>
                                <input type="email" name="responsible_officer_email" value="{{ old('responsible_officer_email') }}"
                                       style="width:100%;padding:8px 12px;border:1px solid #cbd5e1;border-radius:3px;font-size:13px;color:#1e293b;background:#fff;box-sizing:border-box;">
                            </div>
                            <div>
                                <label style="display:block;font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#475569;margin-bottom:6px;">Phone</label>
                                <input type="text" name="responsible_officer_phone" value="{{ old('responsible_officer_phone') }}"
                                       style="width:100%;padding:8px 12px;border:1px solid #cbd5e1;border-radius:3px;font-size:13px;color:#1e293b;background:#fff;box-sizing:border-box;">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- User Account --}}
                <div style="background:#fff;border:1px solid #e2e8f0;border-radius:4px;overflow:hidden;margin-bottom:24px;">
                    <div style="padding:16px 24px;border-bottom:1px solid #e2e8f0;">
                        <h3 style="font-family:'Georgia',serif;font-size:14px;font-weight:700;color:#1a2f4e;margin:0;">Portal Account</h3>
                    </div>
                    <div style="padding:20px 24px;">
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
                            <div>
                                <label style="display:block;font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#475569;margin-bottom:6px;">First Name <span style="color:#dc2626;">*</span></label>
                                <input type="text" name="first_name" value="{{ old('first_name') }}" required
                                       style="width:100%;padding:8px 12px;border:1px solid #cbd5e1;border-radius:3px;font-size:13px;color:#1e293b;background:#fff;box-sizing:border-box;">
                            </div>
                            <div>
                                <label style="display:block;font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#475569;margin-bottom:6px;">Last Name <span style="color:#dc2626;">*</span></label>
                                <input type="text" name="last_name" value="{{ old('last_name') }}" required
                                       style="width:100%;padding:8px 12px;border:1px solid #cbd5e1;border-radius:3px;font-size:13px;color:#1e293b;background:#fff;box-sizing:border-box;">
                            </div>
                            <div>
                                <label style="display:block;font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#475569;margin-bottom:6px;">Email Address <span style="color:#dc2626;">*</span></label>
                                <input type="email" name="email" value="{{ old('email') }}" required
                                       style="width:100%;padding:8px 12px;border:1px solid #cbd5e1;border-radius:3px;font-size:13px;color:#1e293b;background:#fff;box-sizing:border-box;">
                            </div>
                            <div>
                                <label style="display:block;font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#475569;margin-bottom:6px;">Temporary Password <span style="color:#dc2626;">*</span></label>
                                <input type="password" name="password" required
                                       style="width:100%;padding:8px 12px;border:1px solid #cbd5e1;border-radius:3px;font-size:13px;color:#1e293b;background:#fff;box-sizing:border-box;">
                            </div>
                            <div>
                                <label style="display:block;font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#475569;margin-bottom:6px;">Confirm Password <span style="color:#dc2626;">*</span></label>
                                <input type="password" name="password_confirmation" required
                                       style="width:100%;padding:8px 12px;border:1px solid #cbd5e1;border-radius:3px;font-size:13px;color:#1e293b;background:#fff;box-sizing:border-box;">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Internal Notes --}}
                <div style="background:#fff;border:1px solid #e2e8f0;border-radius:4px;overflow:hidden;margin-bottom:24px;">
                    <div style="padding:16px 24px;border-bottom:1px solid #e2e8f0;">
                        <h3 style="font-family:'Georgia',serif;font-size:14px;font-weight:700;color:#1a2f4e;margin:0;">Internal Notes</h3>
                    </div>
                    <div style="padding:20px 24px;">
                        <textarea name="internal_notes" rows="3"
                                  placeholder="Admin-only notes (not visible to the client)..."
                                  style="width:100%;padding:8px 12px;border:1px solid #cbd5e1;border-radius:3px;font-size:13px;color:#1e293b;background:#fff;resize:vertical;box-sizing:border-box;">{{ old('internal_notes') }}</textarea>
                    </div>
                </div>

                {{-- Actions --}}
                <div style="display:flex;align-items:center;justify-content:space-between;gap:12px;">
                    <a href="{{ route('client.index') }}" style="display:inline-flex;align-items:center;gap:8px;padding:8px 20px;background:#fff;color:#1a2f4e;font-size:12px;font-weight:700;letter-spacing:.06em;border:1px solid #1a2f4e;border-radius:3px;text-decoration:none;">
                        Cancel
                    </a>
                    <button type="submit" style="display:inline-flex;align-items:center;gap:8px;padding:8px 20px;background:#1a2f4e;color:#fff;font-size:12px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;border-radius:3px;border:none;cursor:pointer;">
                        Register Client
                    </button>
                </div>

            </form>
        </div>
    </div>
</x-app-layout>
