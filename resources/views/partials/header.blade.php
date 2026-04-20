{{-- Seafood Toxicology Laboratory — Header Component --}}
<style>
    .stld-header {
        font-family: 'DM Sans', sans-serif;
        background: #042C53;
        color: #E6F1FB;
        padding: 1rem 0;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.15);
        position: sticky;
        top: 0;
        z-index: 1000;
    }

    .stld-header .header-inner {
        max-width: 1100px;
        margin: 0 auto;
        padding: 0 2rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .stld-header .logo-section {
        display: flex;
        align-items: center;
        gap: 12px;
        text-decoration: none;
        color: inherit;
    }

    .stld-header .logo-hex {
        width: 48px;
        height: 48px;
        flex-shrink: 0;
    }

    .stld-header .brand-name {
        font-family: 'DM Serif Display', serif;
        font-size: 18px;
        font-weight: 400;
        line-height: 1.2;
        color: #E6F1FB;
    }

    .stld-header .brand-name span {
        display: block;
        font-family: 'DM Sans', sans-serif;
        font-size: 11px;
        font-weight: 500;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: #378ADD;
        margin-top: 2px;
    }

    .stld-header .nav-menu {
        display: flex;
        gap: 2rem;
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .stld-header .nav-menu li a {
        color: #B5D4F4;
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
        transition: color 0.2s;
    }

    .stld-header .nav-menu li a:hover {
        color: #E6F1FB;
    }

    .stld-header .header-actions {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .stld-header .btn {
        padding: 8px 18px;
        font-size: 13px;
        font-weight: 500;
        border-radius: 4px;
        text-decoration: none;
        transition: all 0.2s;
    }

    .stld-header .btn-primary {
        background: #378ADD;
        color: white;
        border: none;
    }

    .stld-header .btn-primary:hover {
        background: #2a7bc9;
        transform: translateY(-1px);
    }

    .stld-header .btn-outline {
        border: 1px solid #378ADD;
        color: #B5D4F4;
    }

    .stld-header .btn-outline:hover {
        background: rgba(55, 138, 221, 0.1);
        color: #E6F1FB;
    }

    /* Mobile Menu */
    @media (max-width: 768px) {
        .stld-header .header-inner {
            flex-direction: column;
            align-items: flex-start;
        }
        .stld-header .nav-menu {
            flex-direction: column;
            gap: 1rem;
            width: 100%;
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid rgba(181, 212, 244, 0.2);
        }
    }
</style>

{{-- Google Fonts (add to <head> if not already included) --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=DM+Sans:wght@400;500&display=swap" rel="stylesheet">

<header class="stld-header">
    <div class="header-inner">
        <!-- Logo -->
        <a href="#" class="logo-section">
            <svg class="logo-hex" viewBox="0 0 38 38" fill="none" xmlns="http://www.w3.org/2000/svg">
                <polygon points="19,2 35,11 35,27 19,36 3,27 3,11" fill="none" stroke="#378ADD" stroke-width="1.8"/>
                <polygon points="19,7 30,13.5 30,24.5 19,31 8,24.5 8,13.5" fill="rgba(55,138,221,0.15)"/>
                <path d="M13 19 Q16 14 19 16 Q22 18 25 14" stroke="#5DCAA5" stroke-width="1.8" stroke-linecap="round" fill="none"/>
                <circle cx="19" cy="22" r="2.8" fill="none" stroke="#378ADD" stroke-width="1.5"/>
                <line x1="19" y1="8" x2="19" y2="13" stroke="#378ADD" stroke-width="1.2"/>
            </svg>
            <div class="brand-name">
                Seafood Toxicology Laboratory
                <span>STLD · Kiribati</span>
            </div>
        </a>

        <!-- Navigation -->
        <ul class="nav-menu">
            <li><a href="#">Home</a></li>
            <li><a href="#">Services</a></li>
            <li><a href="#">Submit Sample</a></li>
            <li><a href="#">Results</a></li>
            <li><a href="#">Information</a></li>
            <li><a href="#">Contact</a></li>
        </ul>

        <!-- Actions -->
        <div class="header-actions">
            <a href="#" class="btn btn-outline">Login</a>
            <a href="#" class="btn btn-primary">Submit Sample</a>
        </div>
    </div>
</header>