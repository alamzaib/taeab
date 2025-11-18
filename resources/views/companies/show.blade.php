@php
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;
@endphp

@extends('layouts.app')

@section('title', $company->company_name . ' - Company Profile')

@section('content')
    <div class="company-profile-shell container">
        <div class="company-hero card">
            <div class="company-hero-banner"
                style="background:{{ $company->banner_path ? 'url(' . Storage::disk('public')->url($company->banner_path) . ') center/cover' : 'linear-gradient(135deg,#0f4c75,#1b262c)' }};">
                <div class="company-hero-overlay">
                    <div class="company-hero-top">
                        <div class="company-hero-meta">
                            <p class="eyebrow">Company profile</p>
                            <h1>{{ $company->company_name ?? $company->name }}</h1>
                            <p class="company-industry">{{ $company->industry ?? 'Industry not specified' }}</p>
                            @if ($company->city || $company->country)
                                <p class="company-location">
                                    {{ $company->city }}
                                    @if ($company->city && $company->country)
                                        •
                                    @endif
                                    {{ $company->country }}
                                </p>
                            @endif
                        </div>
                        <div class="company-hero-actions">
                            @if ($company->website)
                                <a href="{{ $company->website }}" target="_blank" rel="noopener" class="hero-chip hero-chip-light">
                                    Visit website
                                </a>
                            @endif
                            <a href="#openings" class="hero-chip hero-chip-primary">View open roles</a>
                            <a href="{{ route('companies.index') }}" class="hero-chip hero-chip-outline">Back to listings</a>
                        </div>
                    </div>
                    <div class="company-hero-stats">
                        <div class="stat-card">
                            <p class="eyebrow">Total roles</p>
                            <div class="stat-value">{{ $stats['total_jobs'] }}</div>
                        </div>
                        <div class="stat-card">
                            <p class="eyebrow">Active openings</p>
                            <div class="stat-value">{{ $stats['active_jobs'] }}</div>
                        </div>
                        <div class="stat-card">
                            <p class="eyebrow">Company size</p>
                            <div class="stat-value">{{ $company->company_size ?? 'N/A' }}</div>
                        </div>
                        <div class="stat-card">
                            <p class="eyebrow">Org type</p>
                            <div class="stat-value">{{ $company->organization_type ?? 'N/A' }}</div>
                        </div>
                    </div>
                    <div class="company-quick-info">
                        <div class="info-chip">
                            <span>Email</span>
                            <strong>{{ $company->email }}</strong>
                        </div>
                        <div class="info-chip">
                            <span>Phone</span>
                            <strong>{{ $company->phone ?? '—' }}</strong>
                        </div>
                        <div class="info-chip">
                            <span>Address</span>
                            <strong>{{ $company->address ?? '—' }}</strong>
                        </div>
                        <div class="info-chip">
                            <span>Country</span>
                            <strong>{{ $company->country ?? '—' }}</strong>
                        </div>
                    </div>
                </div>
                @if ($company->logo_path)
                    <div class="company-logo">
                        <img src="{{ Storage::disk('public')->url($company->logo_path) }}"
                            alt="{{ $company->company_name ?? $company->name }}">
                    </div>
                @endif
            </div>
        </div>

        <div class="company-content-grid">
            <section class="company-about card">
                <h2>About {{ $company->company_name ?? $company->name }}</h2>
                <p class="about-copy">
                    {{ $company->about ?? 'This employer has not yet added a description. Encourage them to showcase their culture, mission, and growth.' }}
                </p>
                <div class="company-details-grid">
                    <div>
                        <p class="detail-label">Email</p>
                        <p>{{ $company->email }}</p>
                    </div>
                    <div>
                        <p class="detail-label">Phone</p>
                        <p>{{ $company->phone ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="detail-label">Address</p>
                        <p>{{ $company->address ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="detail-label">Coordinates</p>
                        <p>
                            @if ($company->latitude && $company->longitude)
                                {{ $company->latitude }}, {{ $company->longitude }}
                            @else
                                —
                            @endif
                        </p>
                    </div>
                </div>
            </section>
            @if ($company->latitude && $company->longitude)
                <section class="company-map card">
                    <div class="section-heading">
                        <div>
                            <p class="eyebrow">Office location</p>
                            <h2>Find us on the map</h2>
                        </div>
                    </div>
                    <div class="map-embed">
                        <iframe
                            src="https://www.google.com/maps?q={{ $company->latitude }},{{ $company->longitude }}&z=14&output=embed"
                            loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                </section>
            @endif
            <section class="company-side card">
                <h3>Highlights</h3>
                <div class="quick-info-grid">
                    <div class="info-tile">
                        <span>Industry</span>
                        <strong>{{ $company->industry ?? 'Not specified' }}</strong>
                    </div>
                    <div class="info-tile">
                        <span>Company size</span>
                        <strong>{{ $company->company_size ?? 'N/A' }}</strong>
                    </div>
                    <div class="info-tile">
                        <span>Organisation type</span>
                        <strong>{{ $company->organization_type ?? 'N/A' }}</strong>
                    </div>
                    <div class="info-tile">
                        <span>Status</span>
                        <strong style="text-transform: capitalize;">{{ $company->status ?? 'inactive' }}</strong>
                    </div>
                </div>
            </section>
        </div>

        <section id="openings" class="company-openings card">
            <div class="section-heading">
                <div>
                    <p class="eyebrow">Latest roles</p>
                    <h2>Open positions</h2>
                </div>
                <a href="{{ route('jobs.index', ['company_name' => $company->company_name ?? $company->name]) }}"
                    class="btn btn-light btn-sm">View all jobs</a>
            </div>
            @if ($activeJobs->isEmpty())
                <p class="muted-text">No active roles at the moment.</p>
            @else
                <div class="company-openings-list">
                    @foreach ($activeJobs as $job)
                        <article class="opening-card">
                            <div>
                                <a href="{{ route('jobs.show', $job->slug) }}"
                                    class="opening-title">{{ $job->title }}</a>
                                <p class="opening-meta">
                                    {{ $job->location ?? 'UAE' }}
                                    • Posted
                                    {{ optional($job->posted_at)->diffForHumans() ?? $job->created_at->diffForHumans() }}
                                </p>
                            </div>
                            <a href="{{ route('jobs.show', $job->slug) }}" class="btn btn-light btn-sm">View</a>
                        </article>
                    @endforeach
                </div>
            @endif
        </section>

        <section class="company-reviews card">
            <div class="section-heading">
                <div>
                    <p class="eyebrow">Voices from applicants</p>
                    <h2>Job seeker feedback</h2>
                </div>
            </div>
            @if ($reviews->isEmpty())
                <p class="muted-text">No reviews yet. Encourage your applicants to share their experience.</p>
            @else
                <div class="reviews-grid">
                    @foreach ($reviews as $review)
                        <article class="review-card">
                            <div class="review-author">
                                <strong>{{ $review['seeker'] }}</strong>
                                <span>{{ $review['submitted_at']->diffForHumans() }}</span>
                            </div>
                            <p class="review-role">Applied for {{ $review['job_title'] }}</p>
                            <p class="review-content">
                                {{ Str::limit(strip_tags($review['content']), 240) }}
                            </p>
                        </article>
                    @endforeach
                </div>
            @endif
        </section>
    </div>

    <style>
        .company-profile-shell {
            margin-bottom: 40px;
        }

        .company-hero {
            padding: 0;
            overflow: hidden;
        }

        .company-hero-banner {
            position: relative;
            min-height: 280px;
            border-radius: 20px;
        }

        .company-hero-overlay {
            backdrop-filter: blur(2px);
            background: linear-gradient(135deg, rgba(15, 22, 47, 0.78), rgba(13, 60, 112, 0.78));
            height: 100%;
            padding: 32px;
            color: #fff;
            border-radius: 20px;
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .company-hero-top {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 20px;
        }

        .company-hero-meta h1 {
            margin: 8px 0;
            font-size: 34px;
        }

        .company-industry,
        .company-location {
            margin: 0;
            color: rgba(255, 255, 255, .85);
        }

        .company-hero-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .company-hero-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: 14px;
        }

        .stat-card {
            border: 1px solid rgba(255, 255, 255, .2);
            border-radius: 16px;
            padding: 12px;
            text-align: center;
        }

        .stat-value {
            font-size: 28px;
            font-weight: 700;
            margin-top: 4px;
        }

        .company-logo {
            position: absolute;
            bottom: -35px;
            right: 35px;
            width: 86px;
            height: 86px;
            border-radius: 24px;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 8px;
            box-shadow: 0 15px 30px rgba(15, 23, 42, 0.25);
        }

        .company-logo img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        .company-content-grid {
            display: grid;
            grid-template-columns: minmax(0, 2fr) minmax(260px, 1fr);
            gap: 24px;
            margin-top: 60px;
        }

        .company-about,
        .company-side,
        .company-openings,
        .company-map,
        .company-reviews {
            border-radius: 20px;
            padding: 28px;
        }

        .company-about h2 {
            margin-top: 0;
        }

        .about-copy {
            color: #475569;
            line-height: 1.6;
        }

        .company-details-grid {
            margin-top: 20px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 16px;
        }

        .detail-label {
            margin: 0;
            font-size: 12px;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: #94a3b8;
        }

        .company-side h3 {
            margin-top: 0;
        }

        .quick-info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 12px;
        }

        .quick-info-grid span {
            font-size: 12px;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: .08em;
        }

        .company-openings .section-heading {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 18px;
            gap: 12px;
        }

        .company-openings-list {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .opening-card {
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 16px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
        }

        .opening-title {
            font-weight: 600;
            color: #0f172a;
            text-decoration: none;
        }

        .opening-meta {
            margin: 4px 0 0;
            color: #94a3b8;
            font-size: 14px;
        }

        .hero-action-button {
            padding: 8px 18px;
            border-radius: 999px;
            font-weight: 600;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: .08em;
            border: 1px solid rgba(255, 255, 255, .4);
        }

        .hero-action-outline {
            background: rgba(255, 255, 255, .1);
            color: #fff;
        }

        .hero-action-light {
            background: #fff;
            color: #1e3a8a;
        }

        .hero-action-primary {
            background: #ffb347;
            color: #1a2a44;
            border-color: #ffb347;
        }

        .muted-text {
            color: #94a3b8;
        }

        .company-map .map-embed iframe {
            width: 100%;
            height: 280px;
            border: 0;
            border-radius: 16px;
        }

        .company-reviews {
            margin-top: 24px;
        }

        .reviews-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 18px;
        }

        .review-card {
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 18px;
            background: #fff;
        }

        .review-author {
            display: flex;
            justify-content: space-between;
            font-size: 14px;
            color: #475569;
        }

        .review-role {
            margin: 6px 0;
            color: #1d4ed8;
            font-weight: 600;
        }

        .review-content {
            margin: 0;
            color: #475569;
            line-height: 1.5;
        }

        @media screen and (max-width: 1024px) {
            .company-content-grid {
                grid-template-columns: 1fr;
            }

            .company-hero-actions {
                justify-content: flex-start;
            }

            .company-logo {
                position: static;
                margin-top: 16px;
            }
        }
    </style>
@endsection
