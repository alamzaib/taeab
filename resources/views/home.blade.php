@extends('layouts.app')

@section('title', 'Job Portal UAE - Find Your Dream Job')

@section('content')
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
}
</style>
@endsection

