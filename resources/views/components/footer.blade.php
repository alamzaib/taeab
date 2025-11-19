<footer class="footer">
    <div class="footer-container">
        <div class="footer-content">
            <div class="footer-section">
                <a href="{{ url('/') }}" class="footer-logo">
                    @php
                        $settings = \App\Models\Setting::getAll();
                        $footerLogoPath = !empty($settings['footer_logo']) ? Storage::url($settings['footer_logo']) : asset('images/logo.svg');
                    @endphp
                    <img src="{{ $footerLogoPath }}" alt="Job Portal UAE" class="footer-logo-img" onerror="this.style.display='none'; this.nextElementSibling.style.display='inline';">
                    <span class="footer-logo-text">Job Portal UAE</span>
                </a>
                <p class="footer-description">
                    @php
                        $settings = \App\Models\Setting::getAll();
                        $description = !empty($settings['meta_description']) ? $settings['meta_description'] : 'Connecting talented professionals with top employers across the United Arab Emirates.';
                    @endphp
                    {{ $description }}
                </p>
            </div>
            
            <div class="footer-section">
                <h3 class="footer-heading">Quick Links</h3>
                <ul class="footer-links">
                    <li><a href="{{ url('/') }}">Home</a></li>
                    <li><a href="{{ url('/jobs') }}">Browse Jobs</a></li>
                    <li><a href="{{ url('/companies') }}">Companies</a></li>
                    <li><a href="{{ route('blog.index') }}">Blog</a></li>
                    <li><a href="{{ route('contact') }}">Contact Us</a></li>
                    <li><a href="{{ route('seeker.register') }}">Job Seeker Registration</a></li>
                    <li><a href="{{ route('company.register') }}">Company Registration</a></li>
                </ul>
            </div>
            
            <div class="footer-section">
                <h3 class="footer-heading">For Employers</h3>
                <ul class="footer-links">
                    <li><a href="{{ route('company.login') }}">Post a Job</a></li>
                    <li><a href="{{ route('company.register') }}">Create Account</a></li>
                    <li><a href="#">Pricing</a></li>
                    <li><a href="#">Recruitment Solutions</a></li>
                </ul>
            </div>
            
            <div class="footer-section">
                <h3 class="footer-heading">Contact Us</h3>
                @php
                    $settings = \App\Models\Setting::getAll();
                @endphp
                @if(!empty($settings['phone']))
                <p class="footer-contact">
                    <strong>Phone:</strong> <a href="tel:{{ $settings['phone'] }}" style="color: rgba(255, 255, 255, 0.8); text-decoration: none;">{{ $settings['phone'] }}</a>
                </p>
                @endif
                @if(!empty($settings['official_email']))
                <p class="footer-contact">
                    <strong>Email:</strong> <a href="mailto:{{ $settings['official_email'] }}" style="color: rgba(255, 255, 255, 0.8); text-decoration: none;">{{ $settings['official_email'] }}</a>
                </p>
                @endif
                @if(!empty($settings['address']))
                <p class="footer-contact">
                    <strong>Address:</strong> {{ $settings['address'] }}
                    @if(!empty($settings['city']))
                        , {{ $settings['city'] }}
                    @endif
                    @if(!empty($settings['country']))
                        , {{ $settings['country'] }}
                    @endif
                </p>
                @endif
                <h3 class="footer-heading" style="margin-top: 20px;">Follow Us</h3>
                <div class="social-media">
                    <a href="#" class="social-icon" aria-label="Facebook">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                    </a>
                    <a href="#" class="social-icon" aria-label="Twitter">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                        </svg>
                    </a>
                    <a href="#" class="social-icon" aria-label="LinkedIn">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                        </svg>
                    </a>
                    <a href="#" class="social-icon" aria-label="Instagram">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
        
        <div class="footer-bottom">
            <p class="copyright">
                &copy; {{ date('Y') }} Job Portal UAE. All rights reserved.
            </p>
        </div>
    </div>
</footer>

<style>
.footer {
    background-color: #1a3d63;
    color: white;
    margin-top: 60px;
}

.footer-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 40px 20px 20px;
}

.footer-content {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 40px;
    margin-bottom: 30px;
}

.footer-section {
    display: flex;
    flex-direction: column;
}

.footer-logo {
    display: flex;
    align-items: center;
    gap: 12px;
    text-decoration: none;
    margin-bottom: 15px;
}

.footer-logo-img {
    height: 35px;
    width: auto;
}

.footer-logo-text {
    color: white;
    font-size: 18px;
    font-weight: bold;
}

.footer-description {
    color: rgba(255, 255, 255, 0.8);
    font-size: 14px;
    line-height: 1.6;
    margin-top: 10px;
}

.footer-heading {
    color: white;
    font-size: 18px;
    font-weight: bold;
    margin-bottom: 15px;
}

.footer-links {
    list-style: none;
    padding: 0;
    margin: 0;
}

.footer-links li {
    margin-bottom: 10px;
}

.footer-links a {
    color: rgba(255, 255, 255, 0.8);
    text-decoration: none;
    font-size: 14px;
    transition: color 0.3s;
}

.footer-links a:hover {
    color: white;
}

.footer-contact {
    color: rgba(255, 255, 255, 0.8);
    font-size: 14px;
    margin-bottom: 10px;
    line-height: 1.6;
}

.footer-contact strong {
    color: white;
}

.social-media {
    display: flex;
    gap: 15px;
    margin-top: 10px;
}

.social-icon {
    color: rgba(255, 255, 255, 0.8);
    transition: color 0.3s, transform 0.3s;
    display: flex;
    align-items: center;
    justify-content: center;
}

.social-icon:hover {
    color: white;
    transform: translateY(-2px);
}

.footer-bottom {
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    padding-top: 20px;
    text-align: center;
}

.copyright {
    color: rgba(255, 255, 255, 0.6);
    font-size: 14px;
    margin: 0;
}

@media (max-width: 768px) {
    .footer-content {
        grid-template-columns: 1fr;
        gap: 30px;
    }
}
</style>

