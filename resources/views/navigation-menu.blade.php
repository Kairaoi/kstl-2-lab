{{-- resources/views/navigation-menu.blade.php --}}
{{-- Styled to match the KSTL government design system (navy / gold) --}}

<style>
    /* ── APP NAV ── */
    .app-nav {
        background: var(--surface);
        border-bottom: 2px solid var(--gold);
        padding: 0 2rem;
        height: 58px;
        display: flex; align-items: center; justify-content: space-between;
        position: fixed; top: 41px; left: 0; width: 100%; z-index: 50;
        box-shadow: 0 1px 6px rgba(11,32,64,.07);
    }

    /* Brand / logo left side */
    .an-brand { display: flex; align-items: center; gap: 11px; text-decoration: none; }
    .an-crest {
        width: 38px; height: 38px;
        display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }
    .an-crest img { width: 38px; height: 38px; object-fit: contain; }
    .an-main { font-family: 'Noto Serif', serif; font-size: 13px; font-weight: 700; color: var(--navy); line-height: 1.2; letter-spacing: -.01em; }
    .an-sub  { font-size: 9px; color: var(--muted); letter-spacing: .04em; margin-top: 1px; }

    /* Desktop links */
    .an-links { display: flex; align-items: center; gap: 2px; margin-left: 1.5rem; }
    .an-link {
        font-size: 12px; font-weight: 400; color: var(--muted);
        text-decoration: none; padding: 6px 12px; border-radius: 3px;
        letter-spacing: .01em; transition: color .15s, background .15s;
        white-space: nowrap;
    }
    .an-link:hover { color: var(--navy); background: var(--bg); }
    .an-link.active {
        color: var(--navy); font-weight: 600;
        border-bottom: 2px solid var(--gold);
        border-radius: 0;
    }

    /* Right side */
    .an-right { display: flex; align-items: center; gap: 6px; }

    /* Bell */
    .an-bell-wrap {
        position: relative;
    }
    .an-bell {
        position: relative;
        width: 34px; height: 34px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        color: var(--muted); text-decoration: none;
        transition: background .15s, color .15s;
        cursor: pointer;
    }
    .an-bell:hover { background: var(--bg); color: var(--navy); }
    .an-bell svg { width: 16px; height: 16px; }
    .an-bell-badge {
        position: absolute; top: 1px; right: 1px;
        width: 15px; height: 15px;
        background: #c0392b; color: #fff;
        font-size: 9px; font-weight: 700;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        border: 1.5px solid var(--surface);
    }
    /* Bell dropdown */
    .an-bell-drop {
        display: none;
        position: absolute; top: calc(100% + 6px); right: 0;
        width: 320px;
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        box-shadow: 0 8px 24px rgba(0,0,0,.12);
        z-index: 9999;
        overflow: hidden;
    }
    .an-bell-wrap:hover .an-bell-drop { display: block; }
    .an-bell-drop-hdr {
        padding: 10px 14px;
        background: #1a2f4e;
        display: flex; align-items: center; justify-content: space-between;
    }
    .an-bell-drop-hdr span { font-size: 11px; font-weight: 700; color: #fff; letter-spacing: .06em; text-transform: uppercase; }
    .an-bell-drop-hdr a  { font-size: 10px; color: #93c5fd; text-decoration: none; }
    .an-bell-drop-hdr a:hover { color: #fff; }
    .an-bell-drop-item {
        padding: 10px 14px;
        border-bottom: 1px solid #f1f5f9;
        display: flex; gap: 10px; align-items: flex-start;
    }
    .an-bell-drop-item:last-child { border-bottom: none; }
    .an-bell-drop-dot {
        width: 7px; height: 7px; border-radius: 50%;
        background: #1a2f4e; flex-shrink: 0; margin-top: 4px;
    }
    .an-bell-drop-dot.read { background: #cbd5e1; }
    .an-bell-drop-title { font-size: 12px; font-weight: 600; color: #1e293b; margin: 0 0 2px; line-height: 1.3; }
    .an-bell-drop-time  { font-size: 10px; color: #94a3b8; margin: 0; }
    .an-bell-drop-empty { padding: 20px 14px; text-align: center; font-size: 12px; color: #94a3b8; }

    /* User dropdown trigger */
    .an-user-btn {
        display: flex; align-items: center; gap: 7px;
        padding: 5px 10px 5px 6px;
        border: 1px solid var(--border2); border-radius: 3px;
        background: var(--bg); cursor: pointer;
        font-size: 12px; color: var(--ink2); font-weight: 400;
        transition: border-color .15s, background .15s;
        position: relative;
    }
    .an-user-btn:hover { border-color: var(--navy); background: var(--surface); }
    .an-avatar {
        width: 24px; height: 24px; border-radius: 50%;
        object-fit: cover; display: block;
    }
    .an-avatar-init {
        width: 24px; height: 24px; border-radius: 50%;
        background: var(--navy); color: #f4f3f0;
        font-size: 10px; font-weight: 700;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .an-user-name { max-width: 120px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .an-user-role { font-size: 9px; color: var(--subtle); letter-spacing: .04em; text-transform: capitalize; }
    .an-chevron { width: 12px; height: 12px; stroke: var(--muted); flex-shrink: 0; }

    /* Dropdown panel */
    .an-dropdown {
        position: absolute; top: calc(100% + 6px); right: 0;
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 4px;
        box-shadow: 0 4px 20px rgba(11,32,64,.12);
        min-width: 180px; z-index: 100;
        display: none;
    }
    .an-dropdown.open { display: block; }
    .an-dd-header {
        padding: .65rem 1rem;
        border-bottom: 1px solid var(--border);
    }
    .an-dd-name { font-size: 12px; font-weight: 600; color: var(--navy); }
    .an-dd-role { font-size: 10px; color: var(--subtle); text-transform: capitalize; margin-top: 1px; }
    .an-dd-item {
        display: block; padding: .55rem 1rem;
        font-size: 12px; color: var(--ink2); text-decoration: none;
        transition: background .12s, color .12s;
        cursor: pointer; background: none; border: none; width: 100%; text-align: left;
    }
    .an-dd-item:hover { background: var(--bg); color: var(--navy); }
    .an-dd-sep { border-top: 1px solid var(--border); margin: 3px 0; }
    .an-dd-logout { color: var(--red, #a0241c); }
    .an-dd-logout:hover { background: var(--red-bg, #fdf0ee); color: var(--red, #a0241c); }

    /* ── MOBILE HAMBURGER ── */
    .an-hamburger {
        display: none;
        align-items: center; justify-content: center;
        width: 36px; height: 36px; border-radius: 3px;
        border: 1px solid var(--border2); background: var(--bg);
        cursor: pointer; color: var(--muted);
        transition: border-color .15s, color .15s;
    }
    .an-hamburger:hover { border-color: var(--navy); color: var(--navy); }
    .an-hamburger svg { width: 18px; height: 18px; }

    /* ── MOBILE DRAWER ── */
    .an-mobile {
        display: none;
        background: var(--surface);
        border-top: 1px solid var(--border);
        padding: .75rem 0;
    }
    .an-mobile.open { display: block; }
    .an-mob-link {
        display: block; padding: .6rem 1.5rem;
        font-size: 13px; color: var(--ink2); text-decoration: none;
        border-left: 3px solid transparent;
        transition: background .12s, color .12s, border-color .12s;
    }
    .an-mob-link:hover { background: var(--bg); color: var(--navy); border-left-color: var(--border2); }
    .an-mob-link.active { color: var(--navy); font-weight: 600; border-left-color: var(--gold); background: var(--gold-light); }
    .an-mob-sep { border-top: 1px solid var(--border); margin: .5rem 0; }
    .an-mob-user {
        padding: .75rem 1.5rem;
        display: flex; align-items: center; gap: 10px;
    }
    .an-mob-info .an-dd-name { font-size: 13px; }
    .an-mob-info .an-dd-role { font-size: 11px; }

    @media(max-width: 768px) {
        .an-links { display: none; }
        .an-bell, .an-user-btn { display: none; }
        .an-hamburger { display: flex; }
    }
</style>

<div x-data="{ open: false, dropOpen: false }" @click.away="dropOpen = false">

    <nav class="app-nav">

        {{-- Brand --}}
        <a href="{{ auth()->user()->hasRole('client') ? route('client.dashboard') : route('dashboard') }}" class="an-brand">
            <div class="an-crest">
                <img src="{{ asset('images/mfor-logo.png') }}" alt="MFOR">
            </div>
            <div>
                <div class="an-main">KSTL</div>
                <div class="an-sub">Kiribati Seafood Toxicology Laboratory &middot; LIMS</div>
            </div>
        </a>

        {{-- Desktop nav links --}}
        <div class="an-links" style="flex:1">

            @if(auth()->user()->hasAnyRole(['super_admin', 'admin']))
                <a href="/admin" class="an-link {{ request()->is('admin*') ? 'active' : '' }}">Admin Panel</a>
            @endif

            @role('client')
                <a href="{{ route('client.dashboard') }}"         class="an-link {{ request()->routeIs('client.dashboard')       ? 'active' : '' }}">Dashboard</a>
                <a href="{{ route('client.submissions.index') }}" class="an-link {{ request()->routeIs('client.submissions.*')   ? 'active' : '' }}">Submissions</a>
                <a href="{{ route('client.results.index') }}"     class="an-link {{ request()->routeIs('client.results.*')       ? 'active' : '' }}">Results</a>
                <a href="{{ route('client.invoices.index') }}"    class="an-link {{ request()->routeIs('client.invoices.*')      ? 'active' : '' }}">Invoices</a>
            @endrole

            {{-- Lab staff --}}
            @hasanyrole('reception|analyst')
                <a href="{{ route('reception.dashboard') }}"      class="an-link {{ request()->routeIs('reception.dashboard')    ? 'active' : '' }}">Reception</a>
                <a href="{{ route('reception.dashboard') }}"      class="an-link {{ request()->routeIs('reception.submissions.*')? 'active' : '' }}">Submissions</a>
                <a href="{{ route('analyst.dashboard') }}"        class="an-link {{ request()->routeIs('analyst.dashboard')      ? 'active' : '' }}">Analyst</a>
                <a href="{{ route('analyst.tests.index') }}"      class="an-link {{ request()->routeIs('analyst.tests.*')        ? 'active' : '' }}">Test Queue</a>
                <a href="{{ route('analyst.results.index') }}"    class="an-link {{ request()->routeIs('analyst.results.*')      ? 'active' : '' }}">Results</a>
                <a href="{{ route('staff.documents.index') }}"    class="an-link {{ request()->routeIs('staff.documents.*')      ? 'active' : '' }}">Documents</a>
            @endhasanyrole

            {{-- Auditor --}}
            @role('auditor')
                <a href="{{ route('auditor.audit.index') }}" class="an-link {{ request()->routeIs('auditor.*') ? 'active' : '' }}">Audit Log</a>
                <a href="{{ route('reports.index') }}"       class="an-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">Reports</a>
            @endrole

            {{-- Admin / Super Admin --}}
            @hasanyrole('admin|super_admin')
                <a href="{{ route('reports.index') }}" class="an-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">Reports</a>
            @endhasanyrole

            @role('director')
                <a href="{{ route('director.dashboard') }}"       class="an-link {{ request()->routeIs('director.dashboard')     ? 'active' : '' }}">Dashboard</a>
                <a href="{{ route('director.dashboard') }}"       class="an-link {{ request()->routeIs('director.submissions.*') ? 'active' : '' }}">Authorisations</a>
                <a href="{{ route('director.invoices.index') }}"  class="an-link {{ request()->routeIs('director.invoices.*')    ? 'active' : '' }}">Invoices</a>
                <a href="{{ route('director.agreements.index') }}" class="an-link {{ request()->routeIs('director.agreements.*') ? 'active' : '' }}">Agreements</a>
                <a href="{{ route('staff.documents.index') }}"    class="an-link {{ request()->routeIs('staff.documents.*')       ? 'active' : '' }}">Documents</a>
                <a href="{{ route('reports.index') }}"            class="an-link {{ request()->routeIs('reports.*')              ? 'active' : '' }}">Analytics</a>
            @endrole


        </div>

        {{-- Right side: bell + user dropdown --}}
        <div class="an-right">

            {{-- Notification bell --}}
            @auth
            @php
                $unreadCount = \DB::table('notifications')
                    ->where('notifiable_type', 'App\Models\User')
                    ->where('notifiable_id', auth()->id())
                    ->whereNull('read_at')
                    ->count();

                $bellRoute = match(true) {
                    auth()->user()->hasAnyRole(['director','admin','super_admin']) => route('director.notifications.index'),
                    auth()->user()->hasRole('analyst')   => route('analyst.notifications.index'),
                    auth()->user()->hasRole('reception')  => route('reception.notifications.index'),
                    auth()->user()->hasRole('auditor')    => route('reports.index'),
                    default                               => route('client.notifications.index'),
                };

                $notifReadRouteName = match(true) {
                    auth()->user()->hasAnyRole(['director','admin','super_admin']) => 'director.notifications.read',
                    auth()->user()->hasRole('analyst')                             => 'analyst.notifications.read',
                    auth()->user()->hasRole('reception')                           => 'reception.notifications.read',
                    default                                                        => 'client.notifications.read',
                };

                $recentNotifs = \DB::table('notifications')
                    ->where('notifiable_type', 'App\Models\User')
                    ->where('notifiable_id', auth()->id())
                    ->orderByDesc('created_at')
                    ->limit(5)
                    ->get()
                    ->map(function($n) use ($notifReadRouteName) {
                        $data = json_decode($n->data, true) ?? [];
                        return [
                            'id'       => $n->id,
                            'read'     => !is_null($n->read_at),
                            'title'    => $data['subject'] ?? $data['title'] ?? 'Notification',
                            'message'  => $data['message'] ?? $data['body'] ?? '',
                            'time'     => \Carbon\Carbon::parse($n->created_at)->diffForHumans(),
                            'readUrl'  => route($notifReadRouteName, ['id' => $n->id]),
                        ];
                    });
            @endphp
            <div class="an-bell-wrap">
                <a href="{{ $bellRoute }}" class="an-bell" title="Notifications">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    @if($unreadCount > 0)
                        <span class="an-bell-badge">{{ $unreadCount > 9 ? '9+' : $unreadCount }}</span>
                    @endif
                </a>

                {{-- Hover dropdown --}}
                <div class="an-bell-drop">
                    <div class="an-bell-drop-hdr">
                        <span>Notifications{{ $unreadCount > 0 ? " ({$unreadCount} new)" : '' }}</span>
                        <a href="{{ $bellRoute }}">View all &rarr;</a>
                    </div>
                    @if($recentNotifs->isEmpty())
                        <p class="an-bell-drop-empty">No notifications yet.</p>
                    @else
                        @foreach($recentNotifs as $n)
                            <div class="an-bell-drop-item"
                                 x-data="{ open: false, read: {{ $n['read'] ? 'true' : 'false' }} }"
                                 @click="open = !open; if (!read) { read = true; fetch('{{ $n['readUrl'] }}', { method: 'POST', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content } }) }"
                                 style="cursor:pointer;">
                                <div class="an-bell-drop-dot" :class="read ? 'read' : ''"></div>
                                <div style="flex:1;min-width:0;">
                                    <p class="an-bell-drop-title">{{ $n['title'] }}</p>
                                    <p class="an-bell-drop-time">{{ $n['time'] }}</p>
                                    @if($n['message'])
                                        <p x-show="open"
                                           style="margin:6px 0 0;font-size:12px;color:#374151;line-height:1.5;border-top:1px solid #f1f5f9;padding-top:6px;display:none;">
                                            {{ $n['message'] }}
                                        </p>
                                    @endif
                                </div>
                                <svg x-show="!open" style="width:12px;height:12px;flex-shrink:0;color:#94a3b8;margin-top:2px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                <svg x-show="open"  style="width:12px;height:12px;flex-shrink:0;color:#94a3b8;margin-top:2px;display:none;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
            @endauth

            {{-- User dropdown --}}
            <div style="position:relative">
                <button class="an-user-btn" @click="dropOpen = !dropOpen" type="button">
                    @if(Laravel\Jetstream\Jetstream::managesProfilePhotos() && Auth::user()->profile_photo_url)
                        <img class="an-avatar" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}">
                    @else
                        <div class="an-avatar-init">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                    @endif
                    <div>
                        <div class="an-user-name">{{ Auth::user()->name }}</div>
                        <div class="an-user-role">{{ Auth::user()->getRoleNames()->first() }}</div>
                    </div>
                    <svg class="an-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
                    </svg>
                </button>

                <div class="an-dropdown" :class="{ open: dropOpen }">
                    <div class="an-dd-header">
                        <div class="an-dd-name">{{ Auth::user()->name }}</div>
                        <div class="an-dd-role">{{ Auth::user()->getRoleNames()->first() }}</div>
                    </div>

                    @role('client')
                        <a href="{{ route('client.profile.show') }}" class="an-dd-item">Profile</a>
                    @else
                        <a href="{{ route('profile.show') }}" class="an-dd-item">Profile</a>
                    @endrole

                    <div class="an-dd-sep"></div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="an-dd-item an-dd-logout">Log Out</button>
                    </form>
                </div>
            </div>

            {{-- Mobile hamburger --}}
            <button class="an-hamburger" @click="open = !open" type="button">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    <path x-show="open"  stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

        </div>
    </nav>

    {{-- Mobile drawer --}}
    <div class="an-mobile" :class="{ open: open }">

        @role('client')
            <a href="{{ route('client.dashboard') }}"         class="an-mob-link {{ request()->routeIs('client.dashboard')      ? 'active' : '' }}">Dashboard</a>
            <a href="{{ route('client.submissions.index') }}" class="an-mob-link {{ request()->routeIs('client.submissions.*')  ? 'active' : '' }}">Submissions</a>
            <a href="{{ route('client.results.index') }}"     class="an-mob-link {{ request()->routeIs('client.results.*')      ? 'active' : '' }}">Results</a>
            <a href="{{ route('client.invoices.index') }}"    class="an-mob-link {{ request()->routeIs('client.invoices.*')     ? 'active' : '' }}">Invoices</a>
        @endrole

        @hasanyrole('reception|analyst')
            <a href="{{ route('reception.dashboard') }}"      class="an-mob-link {{ request()->routeIs('reception.dashboard')   ? 'active' : '' }}">Reception</a>
            <a href="{{ route('reception.dashboard') }}"      class="an-mob-link {{ request()->routeIs('reception.submissions.*') ? 'active' : '' }}">Submissions</a>
            <a href="{{ route('analyst.dashboard') }}"        class="an-mob-link {{ request()->routeIs('analyst.dashboard')     ? 'active' : '' }}">Analyst</a>
            <a href="{{ route('analyst.tests.index') }}"      class="an-mob-link {{ request()->routeIs('analyst.tests.*')       ? 'active' : '' }}">Sample Tests</a>
            <a href="{{ route('analyst.results.index') }}"    class="an-mob-link {{ request()->routeIs('analyst.results.*')     ? 'active' : '' }}">Results</a>
            <a href="{{ route('staff.documents.index') }}"    class="an-mob-link {{ request()->routeIs('staff.documents.*')     ? 'active' : '' }}">Documents</a>
        @endhasanyrole

        {{-- Auditor --}}
        @role('auditor')
            <a href="{{ route('auditor.audit.index') }}" class="an-mob-link {{ request()->routeIs('auditor.*') ? 'active' : '' }}">Audit Log</a>
            <a href="{{ route('reports.index') }}"       class="an-mob-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">Reports</a>
        @endrole

        {{-- Reports for admin / super admin --}}
        @hasanyrole('admin|super_admin')
            <a href="{{ route('reports.index') }}" class="an-mob-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">Reports</a>
        @endhasanyrole

        @role('director')
            <a href="{{ route('director.dashboard') }}"       class="an-mob-link {{ request()->routeIs('director.dashboard')    ? 'active' : '' }}">Dashboard</a>
            <a href="{{ route('director.dashboard') }}"       class="an-mob-link {{ request()->routeIs('director.submissions.*') ? 'active' : '' }}">Authorisations</a>
            <a href="{{ route('director.invoices.index') }}"  class="an-mob-link {{ request()->routeIs('director.invoices.*')   ? 'active' : '' }}">Invoices</a>
            <a href="{{ route('director.agreements.index') }}" class="an-mob-link {{ request()->routeIs('director.agreements.*') ? 'active' : '' }}">Agreements</a>
            <a href="{{ route('staff.documents.index') }}"    class="an-mob-link {{ request()->routeIs('staff.documents.*')     ? 'active' : '' }}">Documents</a>
            <a href="{{ route('reports.index') }}"            class="an-mob-link {{ request()->routeIs('reports.*')             ? 'active' : '' }}">Analytics</a>
        @endrole


        @if(auth()->user()->hasAnyRole(['super_admin', 'admin']))
            <a href="/admin" class="an-mob-link {{ request()->is('admin*') ? 'active' : '' }}">Admin Panel</a>
        @endif

        <div class="an-mob-sep"></div>

        <div class="an-mob-user">
            @if(Laravel\Jetstream\Jetstream::managesProfilePhotos() && Auth::user()->profile_photo_url)
                <img class="an-avatar" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" style="width:36px;height:36px">
            @else
                <div class="an-avatar-init" style="width:36px;height:36px;font-size:13px">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
            @endif
            <div class="an-mob-info">
                <div class="an-dd-name">{{ Auth::user()->name }}</div>
                <div class="an-dd-role">{{ Auth::user()->getRoleNames()->first() }}</div>
                <div style="font-size:10px;color:var(--subtle)">{{ Auth::user()->email }}</div>
            </div>
        </div>

        @role('client')
            <a href="{{ route('client.profile.show') }}" class="an-mob-link">Profile</a>
        @else
            <a href="{{ route('profile.show') }}" class="an-mob-link">Profile</a>
        @endrole

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="an-mob-link" style="color:var(--red,#a0241c);width:100%;text-align:left;background:none;border:none;cursor:pointer;font-size:13px">
                Log Out
            </button>
        </form>

    </div>

</div>