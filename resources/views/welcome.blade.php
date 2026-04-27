<x-guest-layout>
<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=noto-serif:400,400i,600,700|noto-sans:300,400,500,600" rel="stylesheet">

<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
html{scroll-behavior:smooth}
:root{
    --navy:#0b2040;
    --navy2:#133060;
    --blue:#1a4fa0;
    --blue2:#2563c4;
    --gold:#b8922a;
    --gold2:#d4a84a;
    --gold-light:#fdf6e3;
    --gold-border:#e8d090;
    --bg:#f4f3f0;
    --surface:#ffffff;
    --border:#dddad4;
    --border2:#c8c4bc;
    --ink:#1a1916;
    --ink2:#2e2c28;
    --muted:#6b6760;
    --subtle:#9c9890;
    --green:#1a6b45;
    --green-bg:#edf7f1;
    --green-border:#b8e0ca;
    --red:#a0241c;
    --red-bg:#fdf0ee;
    --red-border:#f0c8c4;
}
body{font-family:'Noto Sans',sans-serif;font-weight:400;background:var(--bg);color:var(--ink);overflow-x:hidden;min-height:100vh}

/* ── GOVERNMENT TOP STRIPE ── */
.gov-stripe{
    height:6px;
    background:linear-gradient(90deg, var(--navy) 0%, var(--navy) 33%, var(--gold) 33%, var(--gold) 66%, var(--blue2) 66%, var(--blue2) 100%);
}

/* ── GOV TOPBAR ── */
.gov-top{
    background:var(--navy);
    padding:0 2rem;
    height:44px;
    display:flex;align-items:center;justify-content:space-between;
}
.gt-left{display:flex;align-items:center;gap:10px}
.gt-emblem{
    width:28px;height:28px;
    border:1px solid rgba(255,255,255,.2);
    border-radius:50%;
    display:flex;align-items:center;justify-content:center;
}
.gt-emblem svg{width:14px;height:14px;stroke:rgba(255,255,255,.7)}
.gt-gov{font-size:11px;font-weight:500;color:rgba(255,255,255,.6);letter-spacing:.04em}
.gt-gov strong{font-weight:600;color:rgba(255,255,255,.85)}
.gt-right{font-size:10px;color:rgba(255,255,255,.35);letter-spacing:.06em;text-transform:uppercase}

/* ── NAV ── */
nav{
    background:var(--surface);
    border-bottom:2px solid var(--gold);
    padding:0 2rem;
    display:flex;align-items:center;justify-content:space-between;
    height:64px;
    position:sticky;top:0;z-index:50;
}
.nav-brand{display:flex;align-items:center;gap:14px;text-decoration:none}
.nb-crest{
    width:42px;height:42px;
    background:var(--navy);
    border-radius:4px;
    display:flex;align-items:center;justify-content:center;
    flex-shrink:0;
}
.nb-crest svg{width:22px;height:22px;stroke:#f4f3f0}
.nb-text{}
.nb-main{font-family:'Noto Serif',serif;font-size:15px;font-weight:700;color:var(--navy);line-height:1.2;letter-spacing:-.01em}
.nb-sub{font-size:10px;font-weight:400;color:var(--muted);letter-spacing:.04em;margin-top:1px}
.nav-sep{width:1px;height:24px;background:var(--border2)}
.nav-ministry{font-size:11px;font-weight:400;color:var(--muted);letter-spacing:.02em;font-style:italic}
.nav-mict{font-size:10px;font-weight:400;color:var(--subtle);letter-spacing:.03em;font-style:normal;padding-left:1rem;border-left:1px solid var(--border2)}
.nav-right{display:flex;align-items:center;gap:6px}
.nav-in{
    font-size:12px;font-weight:400;color:var(--muted);
    text-decoration:none;padding:7px 14px;
    border:1px solid var(--border2);border-radius:3px;
    background:var(--bg);transition:all .15s;
}
.nav-in:hover{border-color:var(--navy);color:var(--navy);background:var(--surface)}
.nav-cta{
    font-size:12px;font-weight:600;color:var(--surface);
    background:var(--navy);border:1px solid var(--navy);
    text-decoration:none;padding:8px 18px;border-radius:3px;
    transition:background .15s;letter-spacing:.01em;
}
.nav-cta:hover{background:var(--navy2)}

/* ── LAYOUT ── */
.layout{display:flex;flex-direction:column;gap:0}

/* ── PAGE HEADER ── */
.page-header{
    background:var(--navy);
    border-radius:6px 6px 0 0;
    padding:2.5rem 3rem;
    display:grid;grid-template-columns:1fr auto;
    gap:3rem;align-items:start;
    border-bottom:3px solid var(--gold);
    position:relative;overflow:hidden;
}
/* Decorative pattern */
.page-header::before{
    content:'';
    position:absolute;right:0;top:0;bottom:0;width:280px;
    background:linear-gradient(to right,transparent,rgba(255,255,255,.025));
    pointer-events:none;
}
/* Diagonal lines pattern */
.page-header::after{
    content:'';
    position:absolute;right:50px;top:-20px;bottom:-20px;width:200px;
    background:repeating-linear-gradient(
        135deg,
        transparent,
        transparent 10px,
        rgba(255,255,255,.015) 10px,
        rgba(255,255,255,.015) 20px
    );
    pointer-events:none;
}

.ph-left{position:relative;z-index:1}
.ph-kicker{
    display:inline-flex;align-items:center;gap:7px;
    background:rgba(184,146,42,.15);
    border:1px solid rgba(184,146,42,.35);
    border-radius:2px;
    padding:4px 12px;
    font-size:10px;font-weight:600;letter-spacing:.14em;text-transform:uppercase;
    color:var(--gold2);margin-bottom:1.25rem;
}
.pk-dot{width:5px;height:5px;border-radius:50%;background:var(--gold2)}
.ph-h1{
    font-family:'Noto Serif',serif;
    font-size:clamp(1.9rem,3.5vw,2.8rem);
    font-weight:700;line-height:1.15;letter-spacing:-.02em;
    color:#f4f3f0;margin-bottom:.75rem;
}
.ph-sub{
    font-size:14px;font-weight:300;line-height:1.8;
    color:rgba(244,243,240,.55);max-width:540px;
}

.ph-right{position:relative;z-index:1;display:flex;flex-direction:column;gap:6px;min-width:220px}
.ph-info-card{
    background:rgba(255,255,255,.06);
    border:1px solid rgba(255,255,255,.1);
    border-radius:4px;padding:.85rem 1rem;
}
.pic-label{font-size:9px;font-weight:600;letter-spacing:.16em;text-transform:uppercase;color:rgba(244,243,240,.35);margin-bottom:4px}
.pic-val{font-size:12px;font-weight:500;color:rgba(244,243,240,.75)}

.ph-btns{display:flex;flex-direction:column;gap:7px;margin-top:4px}
.phb-p{
    display:flex;align-items:center;justify-content:center;gap:6px;
    background:var(--gold);color:var(--navy);
    font-size:12px;font-weight:700;letter-spacing:.02em;
    text-decoration:none;padding:11px;border-radius:3px;
    transition:background .15s;
}
.phb-p:hover{background:var(--gold2)}
.phb-s{
    display:flex;align-items:center;justify-content:center;
    background:rgba(255,255,255,.07);color:rgba(244,243,240,.6);
    border:1px solid rgba(255,255,255,.12);
    font-size:12px;font-weight:400;
    text-decoration:none;padding:10px;border-radius:3px;
    transition:all .15s;
}
.phb-s:hover{background:rgba(255,255,255,.12);color:#f4f3f0}

/* ── NOTICE BAND ── */
.notice-band{
    background:var(--gold-light);
    border-left:4px solid var(--gold);
    border-right:1px solid var(--border);
    border-bottom:1px solid var(--border);
    padding:.85rem 1.5rem;
    display:flex;align-items:flex-start;gap:10px;
}
.nb-icon{flex-shrink:0;margin-top:1px}
.nb-icon svg{width:14px;height:14px;stroke:var(--gold);fill:none}
.nb-text{font-size:12px;font-weight:400;color:var(--ink2);line-height:1.6}
.nb-text strong{font-weight:600;color:var(--navy)}

/* ── SECTION DIVIDER ── */
.sec-div{
    border-top:1px solid var(--border);
    margin:2rem 0 .75rem;
    display:flex;align-items:center;gap:12px;
}
.sd-label{
    font-size:10px;font-weight:600;letter-spacing:.18em;text-transform:uppercase;
    color:var(--muted);background:var(--bg);padding:0 .75rem 0 0;
    margin-top:-1px;position:relative;top:-.5px;
}
.sd-ref{
    font-size:10px;font-weight:400;color:var(--subtle);
    margin-left:auto;letter-spacing:.06em;
}

/* ── ACTION CARDS ── */
.action-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:12px}
.ac{
    background:var(--surface);
    border:1px solid var(--border);
    border-top:3px solid transparent;
    border-radius:4px;
    padding:1.5rem;
    display:flex;flex-direction:column;gap:.6rem;
    transition:border-color .2s,box-shadow .15s;
}
.ac-1{border-top-color:var(--navy)}
.ac-2{border-top-color:var(--blue2)}
.ac-3{border-top-color:var(--gold)}
.ac:hover{border-color:var(--border2);box-shadow:0 2px 12px rgba(11,32,64,.07)}
.ac-step{
    font-size:9px;font-weight:700;letter-spacing:.2em;text-transform:uppercase;
    color:var(--subtle);
}
.ac-h{
    font-family:'Noto Serif',serif;
    font-size:15px;font-weight:700;color:var(--navy);
    letter-spacing:-.01em;line-height:1.3;
}
.ac-p{font-size:12px;font-weight:400;color:var(--muted);line-height:1.7;flex:1}
.ac-note{
    font-size:11px;font-weight:400;color:var(--subtle);
    border-top:1px solid var(--border);padding-top:.6rem;
    line-height:1.55;font-style:italic;
}
.ac-foot{margin-top:.25rem}
.btn-navy{
    display:inline-flex;align-items:center;gap:6px;
    background:var(--navy);color:#f4f3f0;
    font-size:11px;font-weight:500;letter-spacing:.02em;
    text-decoration:none;padding:8px 16px;border-radius:3px;
    transition:background .15s;
}
.btn-navy:hover{background:var(--navy2)}
.btn-line{
    display:inline-flex;align-items:center;gap:6px;
    background:transparent;color:var(--ink2);
    border:1px solid var(--border2);
    font-size:11px;font-weight:400;
    text-decoration:none;padding:8px 14px;border-radius:3px;
    transition:all .15s;
}
.btn-line:hover{border-color:var(--navy);color:var(--navy)}
.ac-link{
    display:inline-flex;align-items:center;gap:4px;
    font-size:12px;font-weight:500;color:var(--blue);
    text-decoration:none;transition:color .15s;
}
.ac-link:hover{color:var(--navy)}
.ac-link svg{width:12px;height:12px}

/* ── STATUS PILLS ── */
.pill{display:inline-flex;align-items:center;gap:5px;font-size:10px;font-weight:500;padding:3px 9px;border-radius:2px;letter-spacing:.04em;border:1px solid}
.pill-green{background:var(--green-bg);color:var(--green);border-color:var(--green-border)}
.pill-red{background:var(--red-bg);color:var(--red);border-color:var(--red-border)}
.pill-gold{background:var(--gold-light);color:var(--gold);border-color:var(--gold-border)}
.pill-gray{background:var(--bg);color:var(--muted);border-color:var(--border2)}

/* ── PROCESS TABLE ── */
.process-wrap{
    background:var(--surface);
    border:1px solid var(--border);
    border-radius:4px;overflow:hidden;
}
.process-head{
    background:var(--navy);padding:1rem 1.5rem;
    display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:.75rem;
}
.prch-title{font-family:'Noto Serif',serif;font-size:13px;font-weight:700;color:#f4f3f0;letter-spacing:-.01em}
.prch-sub{font-size:11px;color:rgba(244,243,240,.45)}
.prch-pills{display:flex;gap:5px;flex-wrap:wrap}
.process-grid{display:grid;grid-template-columns:repeat(4,1fr)}
.pg{
    padding:1.25rem 1.5rem;
    border-right:1px solid var(--border);
    position:relative;
}
.pg:last-child{border-right:none}
.pg-num{
    display:inline-flex;align-items:center;justify-content:center;
    width:22px;height:22px;
    background:var(--navy);
    color:#f4f3f0;
    font-size:10px;font-weight:700;
    border-radius:2px;margin-bottom:.75rem;
}
.pg-title{font-family:'Noto Serif',serif;font-size:13px;font-weight:700;color:var(--navy);margin-bottom:.35rem}
.pg-desc{font-size:11px;font-weight:400;color:var(--muted);line-height:1.65}
/* connector arrow */
.pg:not(:last-child)::after{
    content:'›';
    position:absolute;right:-8px;top:1.25rem;
    font-size:16px;color:var(--border2);line-height:1;
    z-index:1;background:var(--surface);padding:0 2px;
}
.process-foot{
    padding:1rem 1.5rem;
    border-top:1px solid var(--border);
    background:var(--bg);
    display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:.75rem;
}
.pf-note{font-size:11px;font-weight:400;color:var(--muted);line-height:1.6;font-style:italic;max-width:560px}
.pf-actions{display:flex;gap:8px;flex-wrap:wrap}

/* ── FOOTER ── */
.footer-wrap{
    margin-top:2rem;
    border-top:2px solid var(--navy);
}
footer{
    background:var(--navy);
    padding:1.75rem 2rem;
    display:flex;align-items:flex-start;justify-content:space-between;flex-wrap:wrap;gap:1.5rem;
}
.f-l{}
.fl-name{font-family:'Noto Serif',serif;font-size:13px;font-weight:700;color:rgba(244,243,240,.8);margin-bottom:5px}
.fl-info{font-size:11px;font-weight:300;color:rgba(244,243,240,.35);line-height:1.75}
.f-r{text-align:right}
.fr-ref{font-size:10px;font-weight:500;letter-spacing:.08em;text-transform:uppercase;color:rgba(244,243,240,.25);margin-bottom:4px}
.fr-copy{font-size:11px;color:rgba(244,243,240,.2)}

/* ── RESPONSIVE ── */
@media(max-width:960px){
    .page-header{grid-template-columns:1fr}
    .ph-right{flex-direction:row;flex-wrap:wrap;gap:8px}
    .ph-info-card{flex:1;min-width:160px}
    .ph-btns{flex-direction:row;width:100%}
    .page-header::after{display:none}
}
@media(max-width:780px){
    nav{padding:0 1.25rem}
    .nav-sep,.nav-ministry,.nav-mict{display:none}
    .page-header{padding:2rem 1.5rem}
    .action-grid{grid-template-columns:1fr}
    .process-grid{grid-template-columns:1fr 1fr}
    .pg:nth-child(2){border-right:none}
    .pg:nth-child(2)::after{display:none}
    .pg:nth-child(3){border-top:1px solid var(--border)}
    footer{padding:1.5rem 1.25rem;flex-direction:column}
    .f-r{text-align:left}
}
@media(max-width:480px){
    .process-grid{grid-template-columns:1fr}
    .pg{border-right:none;border-bottom:1px solid var(--border)}
    .pg:last-child{border-bottom:none}
    .pg::after{display:none}
    .gov-top{padding:0 1.25rem}
    .gt-right{display:none}
}
</style>

<div style="min-height:100vh;display:flex;flex-direction:column">

{{-- GOV STRIPE --}}
<div class="gov-stripe"></div>

{{-- GOV TOP BAR --}}
<div class="gov-top">
    <div class="gt-left">
        <div class="gt-emblem">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21"/>
            </svg>
        </div>
        <span class="gt-gov"><strong>Government of Kiribati</strong> — Ministry of Fisheries and Ocean Resources</span>
    </div>
    <span class="gt-right">Official government portal</span>
</div>

{{-- NAV --}}
<nav>
    <a href="/" class="nav-brand">
        <div class="nb-crest">
            <svg fill="none" stroke="currentColor" stroke-width="1.4" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
            </svg>
        </div>
        <div class="nb-text">
            <div class="nb-main">Seafood Laboratory Portal</div>
            <div class="nb-sub">Kiribati Seafood Toxicology Laboratory · LIMS</div>
        </div>
    </a>
    <div style="display:flex;align-items:center;gap:14px">
        <div class="nav-sep"></div>
        <span class="nav-ministry">Ministry of Fisheries &amp; Ocean Resources</span>
        <span class="nav-mict">Developed with MICT DTO Division &middot; Tarawa</span>
    </div>
    @if(Route::has('login'))
    <div class="nav-right">
        @auth
            <a href="{{ url('/dashboard') }}" class="nav-cta">Dashboard</a>
        @else
            <a href="{{ route('login') }}" class="nav-in">Sign in</a>
            @if(Route::has('register'))
            <a href="{{ route('register') }}" class="nav-cta">Register</a>
            @endif
        @endauth
    </div>
    @endif
</nav>

{{-- MAIN --}}
<main style="flex:1">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-12">

        <div class="layout">

            {{-- PAGE HEADER --}}
            <div class="page-header">
                <div class="ph-left">
                    <div class="ph-kicker">
                        <span class="pk-dot"></span>
                        Official Laboratory Management System
                    </div>
                    <h1 class="ph-h1">
                        Kiribati Seafood Toxicology<br>Laboratory Portal
                    </h1>
                    <p class="ph-sub">
                        Submit seafood samples for testing, track laboratory analysis and download certified toxicology reports through this official government portal.
                    </p>
                </div>
                <div class="ph-right">
                    @foreach([
                        ['Access','Clients + Lab Staff'],
                        ['Testing','Microbiology &amp; Chemistry'],
                        ['Output','Certified PDF Reports'],
                    ] as [$l,$v])
                    <div class="ph-info-card">
                        <div class="pic-label">{{ $l }}</div>
                        <div class="pic-val">{!! $v !!}</div>
                    </div>
                    @endforeach
                    <div class="ph-btns">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="phb-p">
                                Open Dashboard
                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="phb-p">
                                Create Account
                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                            </a>
                            <a href="{{ route('login') }}" class="phb-s">Sign In</a>
                        @endauth
                    </div>
                </div>
            </div>

            {{-- NOTICE BAND --}}
            <div class="notice-band">
                <div class="nb-icon">
                    <svg viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/></svg>
                </div>
                <div class="nb-text">
                    <strong>Who should use this portal:</strong> Seafood exporters, vessel owners, aquaculture operators and public health partners requiring toxicology testing under Kiribati fisheries regulations. Laboratory staff access the full management system after logging in.
                </div>
            </div>

            {{-- SECTION 1 --}}
            <div class="sec-div" style="margin-top:2rem">
                <span class="sd-label">Select an option</span>
                <span class="sd-ref">Form ref: Schedule 1 · KSTL-APP-001</span>
            </div>

            {{-- ACTION CARDS --}}
            <div class="action-grid">
                <div class="ac ac-1">
                    <div class="ac-step">Option 01</div>
                    <h3 class="ac-h">Create a client account</h3>
                    <p class="ac-p">Register your organisation on the portal. You will need your company name, address, responsible officer details and contact information before you begin.</p>
                    <p class="ac-note">Required once. Your account remains active for all future submissions.</p>
                    <div class="ac-foot">
                        @guest
                            @if(Route::has('register'))
                            <a href="{{ route('register') }}" class="btn-navy">
                                Begin registration
                                <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                            </a>
                            @endif
                        @else
                            <span class="pill pill-green">✓ Registered</span>
                        @endguest
                    </div>
                </div>
                <div class="ac ac-2">
                    <div class="ac-step">Option 02</div>
                    <h3 class="ac-h">Submit a Schedule 1 form</h3>
                    <p class="ac-p">Lodge a new testing request for your samples. The portal will guide you through species information, sampling date, quantity, transport method and testing requirements.</p>
                    <p class="ac-note">Estimated completion time: 5–10 minutes per submission.</p>
                    <div class="ac-foot">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="btn-navy">Go to dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="btn-line">Sign in to submit</a>
                        @endauth
                    </div>
                </div>
                <div class="ac ac-3">
                    <div class="ac-step">Option 03</div>
                    <h3 class="ac-h">Track samples &amp; download reports</h3>
                    <p class="ac-p">View the current status of your submitted samples and download certified test reports once laboratory analysis and quality review are complete.</p>
                    <p class="ac-note">Reports are available for download when marked as <strong>Completed</strong> and payment is confirmed.</p>
                    <div class="ac-foot">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="ac-link">
                                View my samples
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="ac-link">
                                Sign in to track
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        @endauth
                    </div>
                </div>
            </div>

            {{-- SECTION 2 --}}
            <div class="sec-div">
                <span class="sd-label">Laboratory process</span>
            </div>

            {{-- PROCESS TABLE --}}
            <div class="process-wrap">
                <div class="process-head">
                    <div>
                        <div class="prch-title">What happens after you submit</div>
                        <div class="prch-sub">Internal laboratory stages — visible to you through the portal at each step</div>
                    </div>
                    <div class="prch-pills">
                        <span class="pill pill-green">● Completed</span>
                        <span class="pill pill-gold">● In progress</span>
                        <span class="pill pill-gray">● Awaiting</span>
                    </div>
                </div>
                <div class="process-grid">
                    @foreach([
                        ['01','Register','Laboratory staff verify your Schedule 1, assign a unique Sample ID and collect payment.'],
                        ['02','Analyse','Samples are allocated to Microbiology and Chemistry sections using validated standard methods.'],
                        ['03','QA Review','Authorised QA officers review all results against acceptance criteria before any report is issued.'],
                        ['04','Report','The certified PDF report with unique Report Number is released once review and payment are complete.'],
                    ] as [$n,$t,$d])
                    <div class="pg">
                        <div class="pg-num">{{ $n }}</div>
                        <div class="pg-title">{{ $t }}</div>
                        <p class="pg-desc">{{ $d }}</p>
                    </div>
                    @endforeach
                </div>
                <div class="process-foot">
                    <p class="pf-note">You can return to this portal at any stage to view progress. You do not need to contact the laboratory directly — all status updates appear in your dashboard.</p>
                    <div class="pf-actions">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="btn-line">Open dashboard</a>
                        @else
                            <a href="{{ route('register') }}" class="btn-navy">Register now</a>
                        @endauth
                    </div>
                </div>
            </div>

        </div>
    </div>
</main>

</x-guest-layout>