{{-- Seafood Toxicology Laboratory — Footer Component (Routes Removed) --}}
<style>
    .stld-footer {
        font-family: 'DM Sans', sans-serif;
        background: #042C53;
        color: #B5D4F4;
        padding: 3rem 0 0 0;
        width: 100%;
        margin-top: auto;
    }
    .stld-footer .footer-wave {
        width: 100%;
        height: 6px;
        background: linear-gradient(90deg, #0C447C 0%, #378ADD 40%, #1D9E75 70%, #0F6E56 100%);
        opacity: 0.7;
    }
    .stld-footer .footer-inner {
        max-width: 1100px;
        margin: 0 auto;
        padding: 0 2rem;
    }
    .stld-footer .footer-top {
        display: grid;
        grid-template-columns: 2fr 1fr 1fr 1.2fr;
        gap: 2.5rem;
        padding-bottom: 2.5rem;
        border-bottom: 0.5px solid rgba(181, 212, 244, 0.2);
    }
    .stld-footer .logo-mark {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 1rem;
    }
    .stld-footer .logo-hex {
        width: 38px;
        height: 38px;
        flex-shrink: 0;
    }
    .stld-footer .brand-name {
        font-family: 'DM Serif Display', serif;
        font-size: 15px;
        font-weight: 400;
        color: #E6F1FB;
        line-height: 1.3;
    }
    .stld-footer .brand-name span {
        display: block;
        font-family: 'DM Sans', sans-serif;
        font-size: 11px;
        font-weight: 500;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: #378ADD;
        margin-top: 2px;
    }
    .stld-footer .brand-desc {
        font-size: 13px;
        line-height: 1.7;
        color: #85B7EB;
        margin-bottom: 1.25rem;
        max-width: 260px;
    }
    .stld-footer .iso-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 11px;
        font-weight: 500;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        background: rgba(55, 138, 221, 0.15);
        border: 0.5px solid rgba(55, 138, 221, 0.4);
        color: #85B7EB;
        padding: 5px 10px;
        border-radius: 4px;
    }
    .stld-footer .iso-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: #378ADD;
        flex-shrink: 0;
    }
    .stld-footer .footer-col h4 {
        font-size: 11px;
        font-weight: 500;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: #E6F1FB;
        margin: 0 0 1rem 0;
    }
    .stld-footer .footer-col ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .stld-footer .footer-col ul li {
        margin-bottom: 0.5rem;
    }
    .stld-footer .footer-col ul li a {
        font-size: 13px;
        color: #85B7EB;
        text-decoration: none;
        transition: color 0.15s;
    }
    .stld-footer .footer-col ul li a:hover {
        color: #E6F1FB;
    }
    .stld-footer .contact-item {
        display: flex;
        align-items: flex-start;
        gap: 8px;
        margin-bottom: 0.75rem;
    }
    .stld-footer .contact-icon {
        flex-shrink: 0;
        margin-top: 1px;
    }
    .stld-footer .contact-text {
        font-size: 13px;
        color: #85B7EB;
        line-height: 1.5;
    }
    .stld-footer .contact-text strong {
        display: block;
        color: #B5D4F4;
        font-weight: 500;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        margin-bottom: 1px;
    }
    .stld-footer .hours-pill {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background: rgba(29, 158, 117, 0.15);
        border: 0.5px solid rgba(29, 158, 117, 0.35);
        color: #5DCAA5;
        font-size: 11px;
        font-weight: 500;
        padding: 3px 8px;
        border-radius: 3px;
        margin-top: 4px;
    }
    .stld-footer .hours-dot {
        width: 5px;
        height: 5px;
        border-radius: 50%;
        background: #1D9E75;
        animation: stld-pulse 2s infinite;
    }
    @keyframes stld-pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.4; }
    }
    .stld-footer .footer-bottom {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1.25rem 0;
        flex-wrap: wrap;
        gap: 0.75rem;
    }
    .stld-footer .footer-legal {
        font-size: 12px;
        color: rgba(133, 183, 235, 0.6);
        margin: 0;
    }
    .stld-footer .footer-links-bottom {
        display: flex;
        gap: 1.5rem;
    }
    .stld-footer .footer-links-bottom a {
        font-size: 12px;
        color: rgba(133, 183, 235, 0.6);
        text-decoration: none;
    }
    .stld-footer .footer-links-bottom a:hover {
        color: #85B7EB;
    }
    @media (max-width: 768px) {
        .stld-footer .footer-top {
            grid-template-columns: 1fr 1fr;
        }
        .stld-footer .brand-col {
            grid-column: 1 / -1;
        }
        .stld-footer .footer-bottom {
            flex-direction: column;
            align-items: flex-start;
        }
    }
    @media (max-width: 480px) {
        .stld-footer .footer-top {
            grid-template-columns: 1fr;
        }
    }
    .stld-footer .footer-mict {
        text-align: center;
        font-size: 11px;
        color: rgba(133, 183, 235, 0.4);
        padding: 0.6rem 0 1rem 0;
        border-top: 0.5px solid rgba(181, 212, 244, 0.08);
        letter-spacing: 0.03em;
    }
    .stld-footer .footer-mict strong {
        color: rgba(133, 183, 235, 0.6);
        font-weight: 500;
    }
</style>

{{-- Google Fonts (recommended to add in <head>) --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=DM+Sans:wght@400;500&display=swap" rel="stylesheet">

<footer class="stld-footer">
    <div class="footer-wave"></div>
    <div class="footer-inner">
        <div class="footer-top">
            {{-- Brand Column --}}
            <div class="footer-col brand-col">
                <div class="logo-mark">
                    <svg class="logo-hex" viewBox="0 0 38 38" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <polygon points="19,2 35,11 35,27 19,36 3,27 3,11" fill="none" stroke="#378ADD" stroke-width="1.2"/>
                        <polygon points="19,7 30,13.5 30,24.5 19,31 8,24.5 8,13.5" fill="rgba(55,138,221,0.12)"/>
                        <path d="M13 19 Q16 14 19 16 Q22 18 25 14" stroke="#5DCAA5" stroke-width="1.5" stroke-linecap="round" fill="none"/>
                        <circle cx="19" cy="22" r="2.5" fill="none" stroke="#378ADD" stroke-width="1.2"/>
                        <line x1="19" y1="8" x2="19" y2="12" stroke="#378ADD" stroke-width="1"/>
                    </svg>
                    <div class="brand-name">
                        Seafood Toxicology<br>Laboratory
                        <span>STLD · Official Portal</span>
                    </div>
                </div>
                <p class="brand-desc">
                    Providing accurate, timely, and reliable seafood safety testing to protect public health
                    and support fisheries compliance with national and international standards.
                </p>
                <div class="iso-badge">
                    <div class="iso-dot"></div>
                    ISO 17025 Accredited
                </div>
            </div>

            {{-- Services Column --}}
            <div class="footer-col">
                <h4>Services</h4>
                <ul>
                    <li><a href="#">Submit a Sample</a></li>
                    <li><a href="#">Microbiology Testing</a></li>
                    <li><a href="#">Food Chemistry Testing</a></li>
                    <li><a href="#">Urgent Requests</a></li>
                    <li><a href="#">View Test Results</a></li>
                    <li><a href="#">Service Agreement</a></li>
                </ul>
            </div>

            {{-- Information Column --}}
            <div class="footer-col">
                <h4>Information</h4>
                <ul>
                    <li><a href="#">Lab Handbook</a></li>
                    <li><a href="#">Sample Requirements</a></li>
                    <li><a href="#">Turnaround Times</a></li>
                    <li><a href="#">Fees &amp; Payments</a></li>
                    <li><a href="#">Submit Feedback</a></li>
                    <li><a href="#">Complaint Form</a></li>
                </ul>
            </div>

            {{-- Contact Column --}}
            <div class="footer-col">
                <h4>Contact</h4>
                <div class="contact-item">
                    <svg class="contact-icon" width="14" height="14" viewBox="0 0 14 14" fill="none">
                        <path d="M7 1.5C4.79 1.5 3 3.29 3 5.5C3 8.5 7 12.5 7 12.5C7 12.5 11 8.5 11 5.5C11 3.29 9.21 1.5 7 1.5ZM7 7C6.17 7 5.5 6.33 5.5 5.5C5.5 4.67 6.17 4 7 4C7.83 4 8.5 4.67 8.5 5.5C8.5 6.33 7.83 7 7 7Z" fill="#378ADD"/>
                    </svg>
                    <div class="contact-text">
                        <strong>Address</strong>
                        Seafood Toxicology Laboratory<br>
                        National Fisheries Division
                    </div>
                </div>
                <div class="contact-item">
                    <svg class="contact-icon" width="14" height="14" viewBox="0 0 14 14" fill="none">
                        <path d="M2 3.5C2 2.95 2.45 2.5 3 2.5H5.5L6.5 5L5.25 5.75C5.84 6.95 6.8 7.91 8 8.5L8.75 7.25L11.5 8.25V10.75C11.5 11.3 11.05 11.75 10.5 11.75C5.75 11.75 2 8 2 3.5Z" fill="#378ADD"/>
                    </svg>
                    <div class="contact-text">
                        <strong>Phone</strong>
                        +686 [Your Number]
                    </div>
                </div>
                <div class="contact-item">
                    <svg class="contact-icon" width="14" height="14" viewBox="0 0 14 14" fill="none">
                        <rect x="1.5" y="3" width="11" height="8" rx="1.5" stroke="#378ADD" stroke-width="1" fill="none"/>
                        <path d="M1.5 4.5L7 8L12.5 4.5" stroke="#378ADD" stroke-width="1" stroke-linecap="round"/>
                    </svg>
                    <div class="contact-text">
                        <strong>Email</strong>
                        stld@fisheries.gov.ki
                    </div>
                </div>
                <div class="hours-pill">
                    <div class="hours-dot"></div>
                    Mon–Fri &middot; 9:00am – 5:15pm
                </div>
            </div>
        </div><!-- /.footer-top -->

        <div class="footer-bottom">
            <p class="footer-legal">
                &copy; {{ date('Y') }} Seafood Toxicology Laboratory. All rights reserved.<br>
                Results are confidential and intended solely for the submitting client.
            </p>
            <div class="footer-links-bottom">
                <a href="#">Privacy Policy</a>
                <a href="#">Terms of Use</a>
                <a href="#">Accessibility</a>
            </div>
        </div>
        <div class="footer-mict">
            Developed in partnership with <strong>MICT DTO Division</strong> &middot; Tarawa, Gilbert Islands
        </div>
    </div><!-- /.footer-inner -->
</footer>