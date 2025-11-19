@extends('layouts.app')

@section('title', $page->meta_title ?: $page->title)
@section('meta_description', $page->meta_description ?? '')

@section('content')
@php
    $settings = \App\Models\Setting::getAll();
    $pageDescription = trim($page->excerpt ?? '') ?: trim($page->meta_description ?? '');
@endphp

<section class="cms-hero">
    <div class="cms-hero-content">
        <p class="cms-hero-eyebrow">{{ config('app.name', 'Job Portal UAE') }}</p>
        <h1 class="cms-hero-title">{{ $page->title }}</h1>
        @if($pageDescription)
            <p class="cms-hero-subtitle">{{ $pageDescription }}</p>
        @endif
    </div>
</section>

<div class="cms-container">
    <div class="cms-content-shell">
        <div class="cms-content-card">
            <article class="cms-page-content">
                {!! $page->content !!}
            </article>
        </div>

        <aside class="cms-sidebar">
            <div class="cms-info-card">
                <div class="cms-info-icon">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 8v4"></path>
                        <circle cx="12" cy="16" r="1"></circle>
                        <path d="M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9 9 4.03 9 9z"></path>
                    </svg>
                </div>
                <h3>Need assistance?</h3>
                <p>Have questions about this page? Our team is here to help.</p>
                <a href="{{ route('contact') }}" class="cms-button-link">Contact Support</a>
            </div>

            <div class="cms-info-card">
                <div class="cms-info-icon">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M4 4h16v16H4z"></path>
                        <path d="M4 9h16"></path>
                        <path d="M10 4v16"></path>
                    </svg>
                </div>
                <h3>Other Resources</h3>
                <ul class="cms-links-list">
                    <li><a href="{{ url('/jobs') }}">Browse Jobs</a></li>
                    <li><a href="{{ url('/companies') }}">Discover Companies</a></li>
                    <li><a href="{{ route('seeker.register') }}">Seeker Sign-up</a></li>
                    <li><a href="{{ route('company.register') }}">Company Sign-up</a></li>
                </ul>
            </div>
        </aside>
    </div>
</div>

<style>
.cms-hero {
    background: linear-gradient(135deg, #235181, #1a3d63);
    padding: 80px 20px;
    color: white;
    text-align: center;
}

.cms-hero-content {
    max-width: 900px;
    margin: 0 auto;
}

.cms-hero-eyebrow {
    text-transform: uppercase;
    letter-spacing: .3em;
    font-size: 12px;
    margin-bottom: 12px;
    opacity: .8;
}

.cms-hero-title {
    font-size: clamp(32px, 5vw, 48px);
    margin: 0 0 16px;
    font-weight: 700;
    line-height: 1.2;
}

.cms-hero-subtitle {
    font-size: 18px;
    margin: 0;
    opacity: .9;
    line-height: 1.6;
}

.cms-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 60px 20px;
}

.cms-content-shell {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 40px;
    align-items: start;
}

.cms-content-card {
    background: #fff;
    border-radius: 24px;
    padding: 48px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 20px 60px rgba(15, 23, 42, 0.08);
}

.cms-page-content {
    color: #0f172a;
    line-height: 1.8;
    font-size: 17px;
}

.cms-page-content h2,
.cms-page-content h3,
.cms-page-content h4 {
    color: #1a3d63;
    margin-top: 32px;
}

.cms-page-content p {
    margin-bottom: 16px;
}

.cms-page-content ul,
.cms-page-content ol {
    margin: 16px 0;
    padding-left: 24px;
}

.cms-sidebar {
    display: flex;
    flex-direction: column;
    gap: 24px;
}

.cms-info-card {
    background: #f8fafc;
    border-radius: 20px;
    padding: 32px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 10px 30px rgba(15, 23, 42, 0.05);
}

.cms-info-icon {
    width: 56px;
    height: 56px;
    border-radius: 16px;
    background: rgba(35, 81, 129, 0.1);
    color: #235181;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 20px;
}

.cms-info-card h3 {
    margin: 0 0 12px;
    font-size: 20px;
    color: #0f172a;
}

.cms-info-card p {
    margin: 0 0 16px;
    color: #475569;
}

.cms-button-link {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 18px;
    background: #235181;
    color: #fff;
    border-radius: 999px;
    text-decoration: none;
    font-weight: 600;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.cms-button-link:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(35, 81, 129, 0.3);
}

.cms-links-list {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.cms-links-list a {
    color: #235181;
    text-decoration: none;
    font-weight: 500;
}

.cms-links-list a:hover {
    text-decoration: underline;
}

@media (max-width: 992px) {
    .cms-content-shell {
        grid-template-columns: 1fr;
    }

    .cms-content-card {
        padding: 32px;
    }

    .cms-sidebar {
        flex-direction: row;
        flex-wrap: wrap;
    }

    .cms-info-card {
        flex: 1;
    }
}

@media (max-width: 640px) {
    .cms-sidebar {
        flex-direction: column;
    }

    .cms-content-card,
    .cms-info-card {
        padding: 24px;
    }
}
</style>
@endsection

