@extends('layouts.app')

@section('title', 'Job Portal UAE - Find Your Dream Job')

@section('content')
@php
    $featuredCompanies = $featuredCompanies ?? collect();
@endphp
<!-- Hero Section -->
<section class="hero-section">
    <div class="hero-container">
        <div class="hero-content">
            <h1 class="hero-title">Find Your Dream Job in UAE</h1>
            <p class="hero-subtitle">Connect with top employers and discover opportunities that match your skills</p>
            
            <!-- Search Bar -->
            <div class="search-container">
                <form action="{{ url('/jobs') }}" method="GET" class="search-form">
                    <div class="search-input-group">
                        <div class="search-field">
                            <i class="search-icon">🔍</i>
                            <input type="text" name="q" placeholder="Job title, keywords, or company" class="search-input" value="{{ request('q') }}">
                        </div>
                        <div class="search-field">
                            <i class="search-icon">📍</i>
                            <select name="location" class="search-input search-select">
                                <option value="">All Locations</option>
                                <option value="Dubai" {{ request('location') == 'Dubai' ? 'selected' : '' }}>Dubai</option>
                                <option value="Abu Dhabi" {{ request('location') == 'Abu Dhabi' ? 'selected' : '' }}>Abu Dhabi</option>
                                <option value="Sharjah" {{ request('location') == 'Sharjah' ? 'selected' : '' }}>Sharjah</option>
                                <option value="Ajman" {{ request('location') == 'Ajman' ? 'selected' : '' }}>Ajman</option>
                                <option value="Ras Al Khaimah" {{ request('location') == 'Ras Al Khaimah' ? 'selected' : '' }}>Ras Al Khaimah</option>
                                <option value="Fujairah" {{ request('location') == 'Fujairah' ? 'selected' : '' }}>Fujairah</option>
                                <option value="Umm Al Quwain" {{ request('location') == 'Umm Al Quwain' ? 'selected' : '' }}>Umm Al Quwain</option>
                            </select>
                        </div>
                        <button type="submit" class="search-button">Search Jobs</button>
                    </div>
                </form>
            </div>
            
            <!-- Quick Links -->
            <div class="hero-links">
                <a href="{{ route('seeker.register') }}" class="hero-link">I'm a Job Seeker</a>
                <a href="{{ route('company.register') }}" class="hero-link">I'm an Employer</a>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="features-section">
    <div class="container">
        <h2 class="section-title">Why Choose Job Portal UAE?</h2>
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">💼</div>
                <h3 class="feature-title">Thousands of Jobs</h3>
                <p class="feature-description">Browse through thousands of job opportunities from top companies in UAE</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🏢</div>
                <h3 class="feature-title">Top Companies</h3>
                <p class="feature-description">Connect with leading employers and build your career with industry leaders</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">⚡</div>
                <h3 class="feature-title">Quick Application</h3>
                <p class="feature-description">Apply to multiple jobs with just one click using our streamlined process</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🔒</div>
                <h3 class="feature-title">Secure Platform</h3>
                <p class="feature-description">Your data is safe and secure with our advanced security measures</p>
            </div>
        </div>
    </div>
</section>

@if($featuredCompanies->isNotEmpty())
<!-- Featured Companies Section -->
<section class="featured-companies-section">
    <div class="container">
        <div class="featured-companies-header">
            <div>
                <p class="section-eyebrow">Premium Employers</p>
                <h2 class="section-title">Platinum Featured Companies</h2>
                <p class="section-subtitle">Trusted organizations invested in premium hiring experiences.</p>
            </div>
            <a href="{{ url('/companies') }}" class="featured-companies-link">Browse all companies</a>
        </div>
        <div class="featured-companies-grid">
            @foreach($featuredCompanies as $company)
                <article class="featured-company-card">
                    <div class="featured-company-logo">
                        @if(!empty($company->logo_url))
                            <img src="{{ $company->logo_url }}" alt="{{ $company->company_name ?? $company->name }}">
                        @else
                            <span>{{ strtoupper(substr($company->company_name ?? $company->name, 0, 1)) }}</span>
                        @endif
                    </div>
                    <div class="featured-company-body">
                        <div class="featured-company-meta">
                            <span class="platinum-chip">Platinum Partner</span>
                            <p class="featured-company-location">
                                {{ trim(($company->city ? $company->city : '') . ($company->country ? ', '.$company->country : '')) ?: 'UAE' }}
                            </p>
                        </div>
                        <h3><a href="{{ route('companies.show', $company) }}">{{ $company->company_name ?? $company->name }}</a></h3>
                        <p class="featured-company-description">
                            {{ $company->short_description ?? 'Leading employer with premium hiring needs.' }}
                        </p>
                    </div>
                    <a href="{{ route('companies.show', $company) }}" class="featured-company-action">
                        View Profile
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                            <polyline points="12 5 19 12 12 19"></polyline>
                        </svg>
                    </a>
                </article>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Popular Jobs Section -->
<section class="jobs-section">
    <div class="container">
        <h2 class="section-title">Popular Job Categories</h2>
        <div class="categories-grid">
            <a href="{{ url('/jobs?category=technology') }}" class="category-card">
                <div class="category-icon">💻</div>
                <h3>Technology</h3>
                <p>1,234 jobs</p>
            </a>
            <a href="{{ url('/jobs?category=finance') }}" class="category-card">
                <div class="category-icon">💰</div>
                <h3>Finance</h3>
                <p>856 jobs</p>
            </a>
            <a href="{{ url('/jobs?category=healthcare') }}" class="category-card">
                <div class="category-icon">🏥</div>
                <h3>Healthcare</h3>
                <p>642 jobs</p>
            </a>
            <a href="{{ url('/jobs?category=education') }}" class="category-card">
                <div class="category-icon">📚</div>
                <h3>Education</h3>
                <p>423 jobs</p>
            </a>
            <a href="{{ url('/jobs?category=engineering') }}" class="category-card">
                <div class="category-icon">🔧</div>
                <h3>Engineering</h3>
                <p>789 jobs</p>
            </a>
            <a href="{{ url('/jobs?category=marketing') }}" class="category-card">
                <div class="category-icon">📢</div>
                <h3>Marketing</h3>
                <p>567 jobs</p>
            </a>
        </div>
    </div>
</section>

<style>
.hero-section {
    background: linear-gradient(135deg, #235181 0%, #1a3d63 100%);
    color: white;
    padding: 80px 20px;
    margin-top: 0;
}

.hero-container {
    max-width: 1200px;
    margin: 0 auto;
}

.hero-content {
    text-align: center;
}

.hero-title {
    font-size: 48px;
    font-weight: bold;
    margin-bottom: 20px;
    line-height: 1.2;
}

.hero-subtitle {
    font-size: 20px;
    margin-bottom: 40px;
    opacity: 0.9;
}

.search-container {
    max-width: 900px;
    margin: 0 auto 40px;
}

.search-form {
    background: white;
    border-radius: 10px;
    padding: 5px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

.search-input-group {
    display: flex;
    gap: 5px;
    align-items: center;
}

.search-field {
    flex: 1;
    position: relative;
    display: flex;
    align-items: center;
}

.search-icon {
    position: absolute;
    left: 15px;
    font-size: 18px;
    z-index: 1;
}

.search-input {
    width: 100%;
    padding: 15px 15px 15px 45px;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    outline: none;
}

.search-select {
    padding-left: 45px;
    cursor: pointer;
}

.search-button {
    background-color: #235181;
    color: white;
    border: none;
    padding: 15px 40px;
    border-radius: 8px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: background-color 0.3s;
    white-space: nowrap;
}

.search-button:hover {
    background-color: #1a3d63;
}

.hero-links {
    display: flex;
    gap: 20px;
    justify-content: center;
    flex-wrap: wrap;
}

.hero-link {
    background: rgba(255, 255, 255, 0.2);
    color: white;
    padding: 12px 30px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    transition: background-color 0.3s;
    border: 2px solid rgba(255, 255, 255, 0.3);
}

.hero-link:hover {
    background: rgba(255, 255, 255, 0.3);
}

.features-section {
    padding: 60px 20px;
    background: #f8f9fa;
}

.section-title {
    text-align: center;
    font-size: 36px;
    color: #235181;
    margin-bottom: 40px;
    font-weight: bold;
}

.features-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 30px;
    max-width: 1200px;
    margin: 0 auto;
}

.feature-card {
    background: white;
    padding: 30px;
    border-radius: 10px;
    text-align: center;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    transition: transform 0.3s, box-shadow 0.3s;
}

.feature-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

.feature-icon {
    font-size: 48px;
    margin-bottom: 15px;
}

.feature-title {
    font-size: 20px;
    color: #235181;
    margin-bottom: 10px;
    font-weight: bold;
}

.feature-description {
    color: #666;
    line-height: 1.6;
}

.jobs-section {
    padding: 60px 20px;
}

.categories-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    max-width: 1200px;
    margin: 0 auto;
}

.category-card {
    background: white;
    padding: 30px 20px;
    border-radius: 10px;
    text-align: center;
    text-decoration: none;
    color: #333;
    border: 2px solid #e0e0e0;
    transition: all 0.3s;
}

.category-card:hover {
    border-color: #235181;
    transform: translateY(-5px);
    box-shadow: 0 4px 8px rgba(35, 81, 129, 0.2);
}

.category-icon {
    font-size: 40px;
    margin-bottom: 15px;
}

.category-card h3 {
    color: #235181;
    margin-bottom: 8px;
    font-size: 18px;
}

.category-card p {
    color: #666;
    font-size: 14px;
    margin: 0;
}

.featured-companies-section {
    padding: 70px 20px;
    background: #ffffff;
}

.featured-companies-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    gap: 20px;
    margin-bottom: 32px;
}

.section-eyebrow {
    text-transform: uppercase;
    letter-spacing: .3em;
    font-size: 12px;
    color: #94a3b8;
    margin-bottom: 6px;
}

.section-subtitle {
    color: #64748b;
    margin-top: 10px;
}

.featured-companies-link {
    color: #235181;
    font-weight: 600;
    text-decoration: none;
}

.featured-companies-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
    gap: 20px;
}

.featured-company-card {
    border: 1px solid #e2e8f0;
    border-radius: 18px;
    padding: 24px;
    display: flex;
    flex-direction: column;
    gap: 16px;
    box-shadow: 0 15px 40px rgba(15, 23, 42, 0.08);
}

.featured-company-logo {
    width: 56px;
    height: 56px;
    border-radius: 16px;
    background: #eef2ff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    color: #4c1d95;
    overflow: hidden;
}

.featured-company-logo img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.platinum-chip {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    border-radius: 999px;
    background: rgba(234, 179, 8, 0.15);
    color: #a16207;
    font-size: 12px;
    font-weight: 600;
}

.featured-company-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 10px;
}

.featured-company-location {
    color: #94a3b8;
    font-size: 13px;
    margin: 0;
}

.featured-company-body h3 {
    margin: 10px 0 8px;
    font-size: 22px;
}

.featured-company-body h3 a {
    text-decoration: none;
    color: #0f172a;
}

.featured-company-description {
    color: #475569;
    margin: 0;
}

.featured-company-action {
    margin-top: auto;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: #235181;
    text-decoration: none;
    font-weight: 600;
}

.featured-company-action svg {
    transition: transform 0.2s ease;
}

.featured-company-action:hover svg {
    transform: translateX(4px);
}

@media (max-width: 768px) {
    .hero-title {
        font-size: 32px;
    }
    
    .hero-subtitle {
        font-size: 16px;
    }
    
    .search-input-group {
        flex-direction: column;
    }
    
    .search-button {
        width: 100%;
    }
    
    .section-title {
        font-size: 28px;
    }

    .featured-companies-header {
        flex-direction: column;
        align-items: flex-start;
    }

    .featured-company-meta {
        flex-direction: column;
        align-items: flex-start;
    }
}
</style>
@endsection

