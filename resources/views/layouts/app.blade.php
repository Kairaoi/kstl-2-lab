<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'KSTL') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=noto-serif:400,400i,600,700|noto-sans:300,400,500,600&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css'])
    @vite(['resources/css/professional-admin.css', 'resources/js/app.js'])

    <!-- jQuery and DataTables -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>

    <style>
        :root {
            --navy:#0b2040; --navy2:#133060; --blue:#1a4fa0; --blue2:#2563c4;
            --gold:#b8922a; --gold2:#d4a84a; --gold-light:#fdf6e3; --gold-border:#e8d090;
            --bg:#f4f3f0; --surface:#ffffff; --border:#dddad4; --border2:#c8c4bc;
            --ink:#1a1916; --ink2:#2e2c28; --muted:#6b6760; --subtle:#9c9890;
        }
        *, *::before, *::after { box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body {
            font-family: 'Noto Sans', sans-serif;
            font-weight: 400;
            background: var(--bg);
            color: var(--ink);
            min-height: 100vh;
            margin: 0; padding: 0;
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

        /* ── FIXED HEADER OFFSET ── */
        /* gov-stripe(5px) + gov-top(36px) + nav(58px) = 99px */
        .header-offset { height: 99px; }
        .header-with-page { height: 99px; } /* when page header also present */

        /* ── PROFESSIONAL TABLE (keep existing) ── */
        .professional-table { width:100%;border-collapse:collapse;margin:1rem 0;background:white;border-radius:8px;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,.1); }
        .professional-table th,.professional-table td { padding:12px 16px;text-align:left;border-bottom:1px solid #e5e7eb; }
        .professional-table th { background-color:#f9fafb;font-weight:600;color:#374151; }
        .professional-table tr:hover { background-color:#f9fafb; }
        .btn { padding:8px 16px;border-radius:6px;text-decoration:none;display:inline-block;font-weight:500;text-align:center;transition:all .2s ease;cursor:pointer;border:none;font-size:.875rem; }
        .btn-primary { background-color:#2563eb;color:white; }
        .btn-primary:hover { background-color:#1d4ed8;transform:translateY(-1px); }
        .btn-danger { background-color:#dc2626;color:white; }
        .btn-danger:hover { background-color:#b91c1c;transform:translateY(-1px); }

        /* ── PAGE HEADER ── */
        .page-hdr {
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            padding: .8rem 2rem;
            position: fixed; top: 99px; left: 0; width: 100%; z-index: 48;
            box-shadow: 0 1px 4px rgba(11,32,64,.05);
        }
        .page-hdr-inner { max-width: 80rem; margin: 0 auto; }
        .page-hdr-inner h2 {
            font-family: 'Noto Serif', serif;
            font-size: 18px; font-weight: 700;
            color: var(--navy); letter-spacing: -.01em;
        }
        /* Extra push when page header is shown */
        .with-page-header { margin-top: 57px; }

        /* ── FLASH MESSAGES ── */
        .flash {
            display: flex; align-items: flex-start; gap: 9px;
            padding: .8rem 1rem; border-radius: 3px;
            border-left: 4px solid; font-size: 13px;
            margin-bottom: 1.1rem; line-height: 1.5;
        }
        .flash svg { flex-shrink: 0; margin-top: 1px; width: 14px; height: 14px; }
        .flash-success { background: #edf7f1; border-color: #1a6b45; color: #1a4a32; }
        .flash-error   { background: #fdf0ee; border-color: #a0241c; color: #7a1c16; }
        .flash-warning { background: var(--gold-light); border-color: var(--gold); color: #7a5f10; }
        .flash-info    { background: #eef4fd; border-color: var(--blue2); color: #1a3a70; }

        /* ── MAIN ── */
        .app-main { max-width: 80rem; margin: 0 auto; padding: 1.75rem 2rem 4rem; width: 100%; }


        @media(max-width:768px) {
            .gt-right { display:none; }
            .app-main { padding:1.25rem 1rem 3rem; }
            .app-footer { padding:1.2rem 1rem;flex-direction:column; }
            .af-right { text-align:left; }
            .af-links { justify-content:flex-start; }
        }
    </style>

    @stack('styles')
</head>
<body>
<div style="min-height:100vh;display:flex;flex-direction:column">

    {{-- Government stripe + top bar (fixed) --}}
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

    {{-- Fixed nav --}}
    @include('navigation-menu')

    {{-- Spacer for fixed header (stripe + gov-top + nav) --}}
    <div class="header-offset"></div>

    {{-- Page Header slot --}}
    @isset($header)
        <div class="page-hdr">
            <div class="page-hdr-inner">{{ $header }}</div>
        </div>
    @endisset

    {{-- Flash messages --}}
    @if(session('success') || session('error') || session('warning') || session('info'))
        <div style="max-width:80rem;margin:0 auto;padding:1.25rem 2rem 0;width:100%" class="{{ isset($header) ? 'with-page-header' : '' }}">
            @if(session('success'))
                <div class="flash flash-success">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="flash flash-error">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ session('error') }}
                </div>
            @endif
            @if(session('warning'))
                <div class="flash flash-warning">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    {{ session('warning') }}
                </div>
            @endif
            @if(session('info'))
                <div class="flash flash-info">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ session('info') }}
                </div>
            @endif
        </div>
    @endif

    {{-- Page content --}}
    <main class="flex-1 {{ isset($header) ? 'with-page-header' : '' }}">
        <div class="app-main">
            {{ $slot }}
        </div>
    </main>

    {{-- Footer --}}
    @include('partials.footer')

</div>
@stack('scripts')
</body>
</html>