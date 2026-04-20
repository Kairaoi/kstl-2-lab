<x-guest-layout>
<style>
    .login-wrap {
        min-height: calc(100vh - 99px);
        display: flex; align-items: center; justify-content: center;
        padding: 2rem 1rem;
        background: var(--bg);
    }
    .login-card {
        width: 100%; max-width: 420px;
        background: var(--surface);
        border: 1px solid var(--border);
        border-top: 3px solid var(--navy);
        border-radius: 4px;
        box-shadow: 0 2px 16px rgba(11,32,64,.08);
        overflow: hidden;
    }
    .login-header {
        background: var(--navy);
        padding: 1.75rem 2rem 1.5rem;
        border-bottom: 3px solid var(--gold);
    }
    .lh-kicker {
        display: inline-flex; align-items: center; gap: 6px;
        background: rgba(184,146,42,.15);
        border: 1px solid rgba(184,146,42,.3);
        border-radius: 2px;
        padding: 3px 10px;
        font-size: 9px; font-weight: 600; letter-spacing: .14em; text-transform: uppercase;
        color: var(--gold2); margin-bottom: 1rem;
    }
    .lh-dot { width: 4px; height: 4px; border-radius: 50%; background: var(--gold2); }
    .lh-title {
        font-family: 'Noto Serif', serif;
        font-size: 20px; font-weight: 700; color: #f4f3f0;
        letter-spacing: -.01em; line-height: 1.2;
        margin-bottom: .35rem;
    }
    .lh-sub { font-size: 12px; font-weight: 300; color: rgba(244,243,240,.45); }

    .login-body { padding: 1.75rem 2rem; }

    /* Flash status */
    .login-status {
        background: var(--green-bg, #edf7f1);
        border-left: 3px solid var(--green, #1a6b45);
        color: var(--green, #1a6b45);
        font-size: 12px; padding: .65rem .9rem;
        border-radius: 2px; margin-bottom: 1.25rem;
    }

    /* Validation errors */
    .login-errors {
        background: var(--red-bg, #fdf0ee);
        border-left: 3px solid var(--red, #a0241c);
        padding: .65rem .9rem; border-radius: 2px;
        margin-bottom: 1.25rem;
    }
    .login-errors p { font-size: 12px; font-weight: 600; color: var(--red, #a0241c); margin-bottom: .3rem; }
    .login-errors ul { margin: 0; padding-left: 1.1rem; }
    .login-errors li { font-size: 12px; color: var(--red, #a0241c); line-height: 1.7; }

    /* Form fields */
    .field { margin-bottom: 1.1rem; }
    .field label {
        display: block;
        font-size: 11px; font-weight: 600; letter-spacing: .04em; text-transform: uppercase;
        color: var(--ink2); margin-bottom: .4rem;
    }
    .field input {
        width: 100%; padding: .6rem .85rem;
        border: 1px solid var(--border2); border-radius: 3px;
        background: var(--bg); color: var(--ink);
        font-size: 13px; font-family: inherit;
        transition: border-color .15s, box-shadow .15s;
        outline: none; box-sizing: border-box;
    }
    .field input:focus {
        border-color: var(--navy);
        box-shadow: 0 0 0 3px rgba(11,32,64,.08);
        background: var(--surface);
    }
    .field input::placeholder { color: var(--subtle); }

    /* Remember me */
    .remember-row {
        display: flex; align-items: center; gap: 8px;
        margin-bottom: 1.5rem;
    }
    .remember-row input[type=checkbox] {
        width: 14px; height: 14px;
        accent-color: var(--navy);
        cursor: pointer; flex-shrink: 0;
    }
    .remember-row label {
        font-size: 12px; color: var(--muted); cursor: pointer;
    }

    /* Submit row */
    .login-foot {
        display: flex; align-items: center; justify-content: space-between; gap: 1rem;
    }
    .login-forgot {
        font-size: 11px; color: var(--muted); text-decoration: none;
        transition: color .15s;
    }
    .login-forgot:hover { color: var(--navy); }
    .login-btn {
        display: inline-flex; align-items: center; gap: 6px;
        background: var(--navy); color: #f4f3f0;
        font-size: 12px; font-weight: 600; letter-spacing: .02em;
        padding: 9px 22px; border-radius: 3px; border: none;
        cursor: pointer; font-family: inherit;
        transition: background .15s;
    }
    .login-btn:hover { background: var(--navy2); }
    .login-btn svg { width: 11px; height: 11px; }

    /* Register link */
    .login-register {
        text-align: center;
        padding: 1rem 2rem 1.5rem;
        border-top: 1px solid var(--border);
        font-size: 12px; color: var(--muted);
    }
    .login-register a {
        color: var(--navy); font-weight: 600; text-decoration: none;
    }
    .login-register a:hover { text-decoration: underline; }
</style>

<div class="login-wrap">
    <div class="login-card">

        {{-- Header --}}
        <div class="login-header">
            <div class="lh-kicker"><span class="lh-dot"></span>Official Portal</div>
            <div class="lh-title">Sign In</div>
            <div class="lh-sub">Kiribati Seafood Toxicology Laboratory &middot; LIMS</div>
        </div>

        <div class="login-body">

            {{-- Session status --}}
            @session('status')
                <div class="login-status">{{ $value }}</div>
            @endsession

            {{-- Validation errors --}}
            @if ($errors->any())
                <div class="login-errors">
                    <p>Please correct the following errors:</p>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="field">
                    <label for="email">Email address</label>
                    <input id="email" type="email" name="email"
                           value="{{ old('email') }}"
                           required autofocus autocomplete="username"
                           placeholder="you@example.com">
                </div>

                <div class="field">
                    <label for="password">Password</label>
                    <input id="password" type="password" name="password"
                           required autocomplete="current-password"
                           placeholder="••••••••">
                </div>

                <div class="remember-row">
                    <input id="remember_me" type="checkbox" name="remember">
                    <label for="remember_me">Remember me on this device</label>
                </div>

                <div class="login-foot">
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="login-forgot">Forgot password?</a>
                    @else
                        <span></span>
                    @endif

                    <button type="submit" class="login-btn">
                        Sign In
                        <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </button>
                </div>
            </form>
        </div>

        @if (Route::has('register'))
            <div class="login-register">
                Don't have an account? <a href="{{ route('register') }}">Register here</a>
            </div>
        @endif

    </div>
</div>

</x-guest-layout>