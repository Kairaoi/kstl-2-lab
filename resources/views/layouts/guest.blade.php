<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'KSTL') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=noto-serif:400,400i,600,700|noto-sans:300,400,500,600&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --navy:#0b2040; --navy2:#133060; --blue:#1a4fa0; --blue2:#2563c4;
            --gold:#b8922a; --gold2:#d4a84a; --gold-light:#fdf6e3; --gold-border:#e8d090;
            --bg:#f4f3f0; --surface:#ffffff; --border:#dddad4; --border2:#c8c4bc;
            --ink:#1a1916; --ink2:#2e2c28; --muted:#6b6760; --subtle:#9c9890;
        }
        *, *::before, *::after { box-sizing: border-box; }
        body {
            font-family: 'Noto Sans', sans-serif;
            background: var(--bg);
            color: var(--ink);
            min-height: 100vh;
            margin: 0; padding: 0;
            display: flex; flex-direction: column;
        }

        /* ── GOV STRIPE ── */
        .gov-stripe {
            height: 5px;
            background: linear-gradient(90deg,
                var(--navy) 0%, var(--navy) 33%,
                var(--gold) 33%, var(--gold) 66%,
                var(--blue2) 66%, var(--blue2) 100%);
            position: fixed; top: 0; left: 0; width: 100%; z-index: 60;
        }

        /* ── GOV TOP BAR ── */
        .gov-top {
            background: var(--navy);
            padding: 0 2rem; height: 36px;
            display: flex; align-items: center; justify-content: space-between;
            position: fixed; top: 5px; left: 0; width: 100%; z-index: 59;
        }
        .gt-left { display: flex; align-items: center; gap: 9px; }
        .gt-emblem {
            width: 22px; height: 22px;
            border: 1px solid rgba(255,255,255,.2); border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
        }
        .gt-emblem svg { width: 11px; height: 11px; stroke: rgba(255,255,255,.6); fill: none; }
        .gt-gov { font-size: 10px; color: rgba(255,255,255,.5); letter-spacing: .03em; }
        .gt-gov strong { font-weight: 600; color: rgba(255,255,255,.8); }
        .gt-right { font-size: 10px; color: rgba(255,255,255,.3); letter-spacing: .06em; text-transform: uppercase; }

        /* ── GUEST NAV ── */
        .guest-nav {
            background: var(--surface);
            border-bottom: 2px solid var(--gold);
            padding: 0 2rem; height: 58px;
            display: flex; align-items: center; justify-content: space-between;
            position: fixed; top: 41px; left: 0; width: 100%; z-index: 58;
            box-shadow: 0 1px 6px rgba(11,32,64,.07);
        }
        .guest-brand { display: flex; align-items: center; gap: 11px; text-decoration: none; }
        .gb-crest {
            width: 36px; height: 36px;
            background: var(--navy); border-radius: 3px;
            display: flex; align-items: center; justify-content: center; flex-shrink: 0;
        }
        .gb-crest svg { width: 19px; height: 19px; stroke: #f4f3f0; fill: none; }
        .gb-main { font-family: 'Noto Serif', serif; font-size: 13px; font-weight: 700; color: var(--navy); line-height: 1.2; letter-spacing: -.01em; }
        .gb-sub  { font-size: 9px; color: var(--muted); letter-spacing: .04em; margin-top: 1px; }
        .guest-nav-links { display: flex; align-items: center; gap: 6px; }
        .gn-link {
            font-size: 12px; font-weight: 400; color: var(--muted);
            text-decoration: none; padding: 6px 14px;
            border: 1px solid var(--border); border-radius: 3px;
            background: var(--bg); transition: all .15s; letter-spacing: .01em;
        }
        .gn-link:hover { border-color: var(--border2); color: var(--navy); background: var(--surface); }
        .gn-cta {
            font-size: 12px; font-weight: 600;
            color: var(--surface); background: var(--navy);
            border: 1px solid var(--navy);
            text-decoration: none; padding: 7px 16px; border-radius: 3px;
            transition: background .15s; letter-spacing: .01em;
        }
        .gn-cta:hover { background: var(--navy2); }

        /* ── MAIN CONTENT ── */
        /* fixed: stripe(5) + gov-top(36) + nav(58) = 99px */
        .guest-main { flex: 1; padding-top: 99px; }


        /* keep existing utility classes */
        .professional-table { width:100%;border-collapse:collapse;margin:1rem 0;background:white;border-radius:8px;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,.1); }
        .professional-table th,.professional-table td { padding:12px 16px;text-align:left;border-bottom:1px solid #e5e7eb; }
        .professional-table th { background-color:#f9fafb;font-weight:600;color:#374151; }
        .professional-table tr:hover { background-color:#f9fafb; }
        .btn { padding:8px 16px;border-radius:6px;text-decoration:none;display:inline-block;font-weight:500;text-align:center;transition:all .2s;cursor:pointer;border:none;font-size:.875rem; }
        .btn-primary { background:#2563eb;color:white; }
        .btn-primary:hover { background:#1d4ed8;transform:translateY(-1px); }
        .btn-danger { background:#dc2626;color:white; }
        .btn-danger:hover { background:#b91c1c;transform:translateY(-1px); }

        @media(max-width:768px) {
            .guest-nav { padding:0 1rem; }
            .gt-right { display:none; }
        }
    </style>
</head>
<body>

    {{-- Gov stripe + top bar — welcome page renders its own --}}
    @if(!request()->is('/') && !request()->routeIs('welcome'))
    <div class="gov-stripe"></div>

    <div class="gov-top">
        <div class="gt-left">
            <div class="gt-emblem">
                <svg viewBox="0 0 24 24" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21"/>
                </svg>
            </div>
            <span class="gt-gov"><strong>Government of Kiribati</strong> &mdash; Ministry of Fisheries &amp; Ocean Resources</span>
        </div>
        <span class="gt-right">Official portal</span>
    </div>
    @endif

    {{-- Guest nav bar — hidden on welcome page which has its own nav --}}
    @if(!request()->is('/') && !request()->routeIs('welcome'))
    <nav class="guest-nav">
        <a href="/" class="guest-brand">
            <div class="gb-crest">
                <svg viewBox="0 0 24 24" stroke-width="1.4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                </svg>
            </div>
            <div>
                <div class="gb-main">KSTL Portal</div>
                <div class="gb-sub">Kiribati Seafood Toxicology Laboratory &middot; LIMS</div>
            </div>
        </a>
        @if(Route::has('login'))
            <div class="guest-nav-links">
                @auth
                    <a href="{{ url('/dashboard') }}" class="gn-cta">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="gn-link">Sign In</a>
                    @if(Route::has('register'))
                        <a href="{{ route('register') }}" class="gn-cta">Register</a>
                    @endif
                @endauth
            </div>
        @endif
    </nav>
    @endif

    {{-- Main content --}}
    {{-- Push content down: stripe(5) + gov-top(36) + nav(58) = 99px, or just stripe+gov-top(41) on welcome --}}
    <main class="guest-main" style="{{ (!request()->is('/') && !request()->routeIs('welcome')) ? '' : 'padding-top:41px' }}">
        {{ $slot }}
    </main>

    {{-- Footer --}}
    @include('partials.footer')

</body>
</html>