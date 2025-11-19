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
                                <a href="{{ $company->website }}" target="_blank" rel="noopener"
                                    class="hero-action-button hero-action-light">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2">
                                        <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                                        <polyline points="15 3 21 3 21 9"></polyline>
                                        <line x1="10" y1="14" x2="21" y2="3"></line>
                                    </svg>
                                    Visit website
                                </a>
                            @endif
                            <a href="#openings" class="hero-action-button hero-action-primary">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                    <line x1="16" y1="2" x2="16" y2="6"></line>
                                    <line x1="8" y1="2" x2="8" y2="6"></line>
                                    <line x1="3" y1="10" x2="21" y2="10"></line>
                                </svg>
                                View open roles
                            </a>
                            <a href="{{ route('companies.index') }}" class="hero-action-button hero-action-outline">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <polyline points="15 18 9 12 15 6"></polyline>
                                </svg>
                                Back to listings
                            </a>
                        </div>
                    </div>
                    <div class="company-hero-stats">
                        <div class="stat-card stat-card-primary">
                            <div class="stat-icon">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="9" cy="7" r="4"></circle>
                                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                </svg>
                            </div>
                            <p class="eyebrow">Total roles</p>
                            <div class="stat-value">{{ $stats['total_jobs'] }}</div>
                        </div>
                        <div class="stat-card stat-card-success">
                            <div class="stat-icon">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
                                </svg>
                            </div>
                            <p class="eyebrow">Active openings</p>
                            <div class="stat-value">{{ $stats['active_jobs'] }}</div>
                        </div>
                        <div class="stat-card stat-card-info">
                            <div class="stat-icon">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="9" cy="7" r="4"></circle>
                                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                </svg>
                            </div>
                            <p class="eyebrow">Company size</p>
                            <div class="stat-value">{{ $company->company_size ?? 'N/A' }}</div>
                        </div>
                        <div class="stat-card stat-card-warning">
                            <div class="stat-icon">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2">
                                    </rect>
                                    <line x1="3" y1="9" x2="21" y2="9"></line>
                                    <line x1="9" y1="21" x2="9" y2="9"></line>
                                </svg>
                            </div>
                            <p class="eyebrow">Org type</p>
                            <div class="stat-value">{{ $company->organization_type ?? 'N/A' }}</div>
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
                        <p class="detail-label"></p>
                    </div>

                </div>

                <div class="quick-info-grid">
                    <div class="quick-info-card quick-info-card-1">
                        <div class="quick-info-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path d="M12 2L2 7l10 5 10-5-10-5z"></path>
                                <path d="M2 17l10 5 10-5"></path>
                                <path d="M2 12l10 5 10-5"></path>
                            </svg>
                        </div>
                        <div class="quick-info-content">
                            <span class="quick-info-label">Industry</span>
                            <strong class="quick-info-value">{{ $company->industry ?? 'Not specified' }}</strong>
                        </div>
                        <div class="quick-info-hover-effect"></div>
                    </div>
                    <div class="quick-info-card quick-info-card-2">
                        <div class="quick-info-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                            </svg>
                        </div>
                        <div class="quick-info-content">
                            <span class="quick-info-label">Company size</span>
                            <strong class="quick-info-value">{{ $company->company_size ?? 'N/A' }}</strong>
                        </div>
                        <div class="quick-info-hover-effect"></div>
                    </div>
                    <div class="quick-info-card quick-info-card-3">
                        <div class="quick-info-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                <line x1="3" y1="9" x2="21" y2="9"></line>
                                <line x1="9" y1="21" x2="9" y2="9"></line>
                            </svg>
                        </div>
                        <div class="quick-info-content">
                            <span class="quick-info-label">Organisation type</span>
                            <strong class="quick-info-value">{{ $company->organization_type ?? 'N/A' }}</strong>
                        </div>
                        <div class="quick-info-hover-effect"></div>
                    </div>
                    <div class="quick-info-card quick-info-card-4">
                        <div class="quick-info-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                        </div>
                        <div class="quick-info-content">
                            <span class="quick-info-label">Status</span>
                            <strong class="quick-info-value"
                                style="text-transform: capitalize;">{{ $company->status ?? 'inactive' }}</strong>
                        </div>
                        <div class="quick-info-hover-effect"></div>
                    </div>
                </div>
            </section>
            @if ($company->latitude && $company->longitude)
                <section class="company-map card">
                    <div class="section-heading">
                        <div>
                            <h2>Location</h2>
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
        </div>

        <section id="openings" class="company-openings card">
            <div class="section-heading">
                <div>
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
                    <p class="eyebrow">Company reviews</p>
                    <h2>What job seekers say</h2>
                </div>
            </div>

            @if ($totalReviews > 0)
                <div class="reviews-summary">
                    <div class="rating-display">
                        <div class="rating-number">{{ number_format($averageRating, 1) }}</div>
                        <div class="rating-stars">
                            @for ($i = 1; $i <= 5; $i++)
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="{{ $i <= round($averageRating) ? '#ffb347' : '#e2e8f0' }}" stroke="{{ $i <= round($averageRating) ? '#ffb347' : '#cbd5e1' }}" stroke-width="1">
                                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                                </svg>
                            @endfor
                        </div>
                        <div class="rating-text">Based on {{ $totalReviews }} {{ Str::plural('review', $totalReviews) }}</div>
                    </div>
                </div>
            @endif

            @auth('seeker')
                @if (!$userReview)
                    <div class="review-form-section">
                        <h3>Write a review</h3>
                        <form action="{{ route('companies.reviews.store', $company) }}" method="POST" class="review-form">
                            @csrf
                            <div class="form-group">
                                <label>Your rating</label>
                                <div class="star-rating-input">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <input type="radio" name="rating" value="{{ $i }}" id="star{{ $i }}" required>
                                        <label for="star{{ $i }}" class="star-label">
                                            <svg width="32" height="32" viewBox="0 0 24 24" fill="currentColor">
                                                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                                            </svg>
                                        </label>
                                    @endfor
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="review">Your review</label>
                                <textarea name="review" id="review" rows="4" class="form-control" placeholder="Share your experience with this company..." required minlength="10" maxlength="1000"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit review</button>
                        </form>
                    </div>
                @endif
            @else
                <div class="review-login-prompt">
                    <p><a href="{{ route('seeker.login') }}">Login as a job seeker</a> to write a review</p>
                </div>
            @endauth

            @if ($topReviews->isEmpty())
                <p class="muted-text">No reviews yet. Be the first to review this company!</p>
            @else
                <div class="reviews-list">
                    @foreach ($topReviews as $review)
                        <article class="review-card" id="review-{{ $review->id }}">
                            <div class="review-header">
                                <div class="review-author-info">
                                    <strong class="review-author-name">{{ $review->seeker->name ?? 'Anonymous' }}</strong>
                                    <div class="review-rating" id="rating-display-{{ $review->id }}">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="{{ $i <= $review->rating ? '#ffb347' : '#e2e8f0' }}">
                                                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                                            </svg>
                                        @endfor
                                    </div>
                                </div>
                                <div class="review-header-right">
                                    <span class="review-date">
                                        {{ $review->created_at->diffForHumans() }}
                                        @if ($review->edited_at)
                                            <span class="edited-badge" title="Edited {{ $review->edited_at->diffForHumans() }}">(edited)</span>
                                        @endif
                                    </span>
                                    @auth('seeker')
                                        @if (auth()->guard('seeker')->id() == $review->seeker_id)
                                            <button type="button" class="btn-edit-review" onclick="openEditReview({{ $review->id }}, {{ $review->rating }}, '{{ addslashes($review->review) }}')">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                                </svg>
                                                Edit
                                            </button>
                                        @endif
                                    @endauth
                                </div>
                            </div>
                            <div id="review-content-{{ $review->id }}">
                                <p class="review-content">{{ $review->review }}</p>
                            </div>
                            <div id="review-edit-form-{{ $review->id }}" class="review-edit-form" style="display: none;">
                                <form action="{{ route('companies.reviews.update', $review) }}" method="POST" onsubmit="return validateEditForm({{ $review->id }})">
                                    @csrf
                                    @method('PUT')
                                    <div class="form-group">
                                        <label>Your rating</label>
                                        <div class="star-rating-input" id="edit-rating-{{ $review->id }}">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <input type="radio" name="rating" value="{{ $i }}" id="edit-star{{ $review->id }}-{{ $i }}" required>
                                                <label for="edit-star{{ $review->id }}-{{ $i }}" class="star-label">
                                                    <svg width="32" height="32" viewBox="0 0 24 24" fill="currentColor">
                                                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                                                    </svg>
                                                </label>
                                            @endfor
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="edit-review-{{ $review->id }}">Your review</label>
                                        <textarea name="review" id="edit-review-{{ $review->id }}" rows="4" class="form-control" required minlength="10" maxlength="1000"></textarea>
                                    </div>
                                    <div class="edit-form-actions">
                                        <button type="submit" class="btn btn-primary btn-sm">Save changes</button>
                                        <button type="button" class="btn btn-light btn-sm" onclick="cancelEditReview({{ $review->id }})">Cancel</button>
                                    </div>
                                </form>
                            </div>
                            
                            @if ($review->company_reply)
                                <div class="company-reply">
                                    <div class="company-reply-header">
                                        <strong>Company response</strong>
                                        <span>{{ $review->replied_at->diffForHumans() }}</span>
                                    </div>
                                    <p>{{ $review->company_reply }}</p>
                                </div>
                            @elseif (auth()->guard('company')->check() && auth()->guard('company')->id() == $company->id)
                                <form action="{{ route('companies.reviews.reply', $review) }}" method="POST" class="reply-form">
                                    @csrf
                                    <textarea name="company_reply" rows="2" placeholder="Reply to this review..." required minlength="10" maxlength="1000"></textarea>
                                    <button type="submit" class="btn btn-sm btn-primary">Reply</button>
                                </form>
                            @endif
                        </article>
                    @endforeach
                </div>

                @if ($totalReviews > 5)
                    <div class="reviews-footer">
                        <button type="button" class="btn btn-light" onclick="openReviewsModal({{ $company->id }})">
                            Read all {{ $totalReviews }} reviews
                        </button>
                    </div>
                @endif
            @endif
        </section>

        <!-- Reviews Modal -->
        <div id="reviewsModal" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>All Reviews</h2>
                    <span class="modal-close" onclick="closeReviewsModal()">&times;</span>
                </div>
                <div class="modal-body" id="allReviewsContainer">
                    <div class="loading-spinner">Loading reviews...</div>
                </div>
            </div>
        </div>
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
            gap: 12px;
            flex-wrap: wrap;
            justify-content: flex-end;
            align-items: center;
        }

        .company-hero-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 16px;
        }

        .stat-card {
            position: relative;
            border: 1px solid rgba(255, 255, 255, .25);
            border-radius: 20px;
            padding: 20px 16px;
            text-align: center;
            background: rgba(255, 255, 255, .08);
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            border-color: rgba(255, 255, 255, .4);
            background: rgba(255, 255, 255, .12);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
        }

        .stat-card:hover::before {
            opacity: 1;
        }

        .stat-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 48px;
            height: 48px;
            border-radius: 12px;
            margin-bottom: 12px;
            background: rgba(255, 255, 255, .15);
            color: rgba(255, 255, 255, .95);
            transition: all 0.3s ease;
        }

        .stat-card:hover .stat-icon {
            transform: scale(1.1) rotate(5deg);
            background: rgba(255, 255, 255, .25);
        }

        .stat-card-primary .stat-icon {
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.3), rgba(139, 92, 246, 0.3));
        }

        .stat-card-success .stat-icon {
            background: linear-gradient(135deg, rgba(34, 197, 94, 0.3), rgba(16, 185, 129, 0.3));
        }

        .stat-card-info .stat-icon {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.3), rgba(37, 99, 235, 0.3));
        }

        .stat-card-warning .stat-icon {
            background: linear-gradient(135deg, rgba(251, 191, 36, 0.3), rgba(245, 158, 11, 0.3));
        }

        .stat-card .eyebrow {
            font-size: 11px;
            letter-spacing: 0.1em;
            margin-bottom: 8px;
            opacity: 0.9;
        }

        .stat-value {
            font-size: 32px;
            font-weight: 800;
            margin-top: 4px;
            background: linear-gradient(135deg, #ffffff, rgba(255, 255, 255, 0.9));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            line-height: 1.2;
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

        .quick-info-header {
            margin-bottom: 24px;
        }

        .quick-info-header h3 {
            margin: 0 0 6px 0;
            font-size: 22px;
            color: #0f172a;
        }

        .quick-info-subtitle {
            margin: 0;
            font-size: 13px;
            color: #64748b;
            font-weight: 400;
        }

        .quick-info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: 14px;
        }

        .quick-info-card {
            position: relative;
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border: 2px solid #e2e8f0;
            border-radius: 16px;
            padding: 20px;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
            cursor: pointer;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .quick-info-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, transparent, rgba(35, 81, 129, 0.3), transparent);
            transform: scaleX(0);
            transition: transform 0.4s ease;
        }

        .quick-info-card:hover {
            transform: translateY(-6px);
            border-color: #cbd5e1;
            box-shadow: 0 12px 32px rgba(15, 23, 42, 0.12);
        }

        .quick-info-card:hover::before {
            transform: scaleX(1);
        }

        .quick-info-hover-effect {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(35, 81, 129, 0.08) 0%, transparent 70%);
            transform: translate(-50%, -50%);
            transition: width 0.6s ease, height 0.6s ease;
            pointer-events: none;
        }

        .quick-info-card:hover .quick-info-hover-effect {
            width: 200px;
            height: 200px;
        }

        .quick-info-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
            color: #235181;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            flex-shrink: 0;
        }

        .quick-info-card:hover .quick-info-icon {
            transform: scale(1.1) rotate(5deg);
            box-shadow: 0 8px 16px rgba(35, 81, 129, 0.2);
        }

        .quick-info-card-1:hover .quick-info-icon {
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.15), rgba(139, 92, 246, 0.15));
            color: #6366f1;
        }

        .quick-info-card-2:hover .quick-info-icon {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.15), rgba(37, 99, 235, 0.15));
            color: #3b82f6;
        }

        .quick-info-card-3:hover .quick-info-icon {
            background: linear-gradient(135deg, rgba(34, 197, 94, 0.15), rgba(16, 185, 129, 0.15));
            color: #22c55e;
        }

        .quick-info-card-4:hover .quick-info-icon {
            background: linear-gradient(135deg, rgba(251, 191, 36, 0.15), rgba(245, 158, 11, 0.15));
            color: #fbbf24;
        }

        .quick-info-content {
            display: flex;
            flex-direction: column;
            gap: 6px;
            position: relative;
            z-index: 1;
        }

        .quick-info-label {
            font-size: 11px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .quick-info-card:hover .quick-info-label {
            color: #475569;
        }

        .quick-info-value {
            font-size: 16px;
            color: #0f172a;
            font-weight: 700;
            line-height: 1.3;
            transition: color 0.3s ease;
        }

        .quick-info-card:hover .quick-info-value {
            color: #235181;
        }

        .quick-info-card-1:hover .quick-info-value {
            color: #6366f1;
        }

        .quick-info-card-2:hover .quick-info-value {
            color: #3b82f6;
        }

        .quick-info-card-3:hover .quick-info-value {
            color: #22c55e;
        }

        .quick-info-card-4:hover .quick-info-value {
            color: #f59e0b;
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
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 14px;
            text-transform: none;
            letter-spacing: 0.02em;
            border: 2px solid transparent;
            text-decoration: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .hero-action-button::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .hero-action-button:hover::before {
            width: 300px;
            height: 300px;
        }

        .hero-action-button svg {
            transition: transform 0.3s ease;
            flex-shrink: 0;
        }

        .hero-action-button:hover svg {
            transform: scale(1.15);
        }

        .hero-action-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.25);
        }

        .hero-action-button:active {
            transform: translateY(0);
        }

        .hero-action-outline {
            background: rgba(255, 255, 255, .12);
            color: #fff;
            border-color: rgba(255, 255, 255, .3);
            backdrop-filter: blur(10px);
        }

        .hero-action-outline:hover {
            background: rgba(255, 255, 255, .2);
            border-color: rgba(255, 255, 255, .5);
        }

        .hero-action-light {
            background: #ffffff;
            color: #1e3a8a;
            border-color: #ffffff;
        }

        .hero-action-light:hover {
            background: #f8f9fa;
            color: #1e40af;
            border-color: #ffffff;
        }

        .hero-action-primary {
            background: linear-gradient(135deg, #ffb347, #ff9500);
            color: #1a2a44;
            border-color: #ffb347;
            font-weight: 700;
        }

        .hero-action-primary:hover {
            background: linear-gradient(135deg, #ff9500, #ff7b00);
            border-color: #ff9500;
            box-shadow: 0 6px 20px rgba(255, 179, 71, 0.4);
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

        .reviews-summary {
            margin-bottom: 32px;
            padding: 24px;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border-radius: 16px;
            border: 1px solid #e2e8f0;
        }

        .rating-display {
            display: flex;
            align-items: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        .rating-number {
            font-size: 48px;
            font-weight: 800;
            color: #0f172a;
            line-height: 1;
        }

        .rating-stars {
            display: flex;
            gap: 4px;
        }

        .rating-text {
            color: #64748b;
            font-size: 14px;
        }

        .review-form-section {
            margin-bottom: 32px;
            padding: 24px;
            background: #f8fafc;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
        }

        .review-form-section h3 {
            margin: 0 0 20px 0;
            font-size: 20px;
            color: #0f172a;
        }

        .review-form .form-group {
            margin-bottom: 20px;
        }

        .review-form label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #1e293b;
        }

        .star-rating-input {
            display: flex;
            gap: 8px;
            position: relative;
        }

        .star-rating-input input[type="radio"] {
            display: none;
        }

        .star-rating-input .star-label {
            cursor: pointer;
            color: #cbd5e1;
            transition: color 0.2s;
            position: relative;
            display: inline-block;
        }

        .star-rating-input .star-label svg {
            transition: fill 0.2s ease;
            fill: #cbd5e1;
        }

        /* Selected stars - will be overridden by JavaScript for better control */
        .star-rating-input input[type="radio"]:checked + .star-label svg {
            fill: #ffb347;
        }

        .review-login-prompt {
            margin-bottom: 24px;
            padding: 16px;
            background: #fef3c7;
            border-radius: 12px;
            text-align: center;
        }

        .review-login-prompt a {
            color: #235181;
            font-weight: 600;
            text-decoration: none;
        }

        .review-login-prompt a:hover {
            text-decoration: underline;
        }

        .reviews-list {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .review-card {
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 20px;
            background: #fff;
            transition: box-shadow 0.3s ease;
        }

        .review-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .review-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 12px;
            flex-wrap: wrap;
            gap: 12px;
        }

        .review-header-right {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 8px;
        }

        .review-author-info {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .review-author-name {
            font-size: 16px;
            color: #0f172a;
        }

        .review-rating {
            display: flex;
            gap: 2px;
        }

        .review-date {
            font-size: 13px;
            color: #64748b;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .edited-badge {
            font-size: 11px;
            color: #94a3b8;
            font-style: italic;
        }

        .btn-edit-review {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            color: #475569;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-edit-review:hover {
            background: #e2e8f0;
            border-color: #cbd5e1;
            color: #334155;
        }

        .btn-edit-review svg {
            width: 14px;
            height: 14px;
        }

        .review-edit-form {
            margin-top: 16px;
            padding: 20px;
            background: #f8fafc;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
        }

        .edit-form-actions {
            display: flex;
            gap: 10px;
            margin-top: 16px;
        }

        .review-content {
            margin: 0 0 16px 0;
            color: #475569;
            line-height: 1.6;
        }

        .company-reply {
            margin-top: 16px;
            padding: 16px;
            background: #f8fafc;
            border-radius: 12px;
            border-left: 3px solid #235181;
        }

        .company-reply-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
            flex-wrap: wrap;
            gap: 8px;
        }

        .company-reply-header strong {
            color: #235181;
            font-size: 14px;
        }

        .company-reply-header span {
            font-size: 12px;
            color: #64748b;
        }

        .company-reply p {
            margin: 0;
            color: #475569;
            line-height: 1.6;
        }

        .reply-form {
            margin-top: 16px;
            padding-top: 16px;
            border-top: 1px solid #e2e8f0;
        }

        .reply-form textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 14px;
            margin-bottom: 10px;
            resize: vertical;
        }

        .reply-form textarea:focus {
            outline: none;
            border-color: #235181;
            box-shadow: 0 0 0 3px rgba(35, 81, 129, 0.1);
        }

        .reviews-footer {
            margin-top: 24px;
            text-align: center;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
            animation: fadeIn 0.3s ease;
        }

        .modal.show {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background-color: #fff;
            border-radius: 20px;
            width: 90%;
            max-width: 800px;
            max-height: 90vh;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: slideUp 0.3s ease;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 24px;
            border-bottom: 1px solid #e2e8f0;
        }

        .modal-header h2 {
            margin: 0;
            font-size: 24px;
            color: #0f172a;
        }

        .modal-close {
            font-size: 32px;
            font-weight: 300;
            color: #64748b;
            cursor: pointer;
            line-height: 1;
            transition: color 0.2s;
        }

        .modal-close:hover {
            color: #0f172a;
        }

        .modal-body {
            padding: 24px;
            overflow-y: auto;
            flex: 1;
        }

        .loading-spinner {
            text-align: center;
            padding: 40px;
            color: #64748b;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        @keyframes slideUp {
            from {
                transform: translateY(30px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @media screen and (max-width: 1024px) {
            .company-content-grid {
                grid-template-columns: 1fr;
            }

            .company-hero-actions {
                justify-content: flex-start;
                width: 100%;
            }

            .hero-action-button {
                flex: 1;
                min-width: 140px;
                justify-content: center;
            }

            .company-hero-stats {
                grid-template-columns: repeat(2, 1fr);
            }

            .stat-value {
                font-size: 28px;
            }

            .company-logo {
                position: static;
                margin-top: 16px;
            }

            .quick-info-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media screen and (max-width: 640px) {
            .company-hero-top {
                flex-direction: column;
            }

            .company-hero-actions {
                flex-direction: column;
            }

            .hero-action-button {
                width: 100%;
            }

            .company-hero-stats {
                grid-template-columns: 1fr;
            }

            .stat-card {
                padding: 18px 14px;
            }

            .stat-icon {
                width: 44px;
                height: 44px;
            }

            .stat-value {
                font-size: 26px;
            }

            .quick-info-grid {
                grid-template-columns: 1fr;
                gap: 12px;
            }

            .quick-info-card {
                padding: 16px;
            }

            .quick-info-icon {
                width: 44px;
                height: 44px;
            }

            .quick-info-value {
                font-size: 15px;
            }
        }
    </style>

    @if (session('success'))
        <div class="alert alert-success" style="position: fixed; top: 20px; right: 20px; z-index: 9999; padding: 16px 24px; background: #10b981; color: white; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); animation: slideInRight 0.3s ease;">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-error" style="position: fixed; top: 20px; right: 20px; z-index: 9999; padding: 16px 24px; background: #ef4444; color: white; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); animation: slideInRight 0.3s ease;">
            {{ session('error') }}
        </div>
    @endif

    <script>
        // Auto-hide alerts
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                alert.style.opacity = '0';
                alert.style.transform = 'translateX(100%)';
                alert.style.transition = 'all 0.3s ease';
                setTimeout(() => alert.remove(), 300);
            });
        }, 5000);

        // Modal functions
        function openReviewsModal(companyId) {
            const modal = document.getElementById('reviewsModal');
            const container = document.getElementById('allReviewsContainer');
            
            modal.classList.add('show');
            container.innerHTML = '<div class="loading-spinner">Loading reviews...</div>';
            
            fetch(`/companies/${companyId}/reviews`)
                .then(response => response.json())
                .then(data => {
                    if (data.length === 0) {
                        container.innerHTML = '<p class="muted-text">No reviews found.</p>';
                        return;
                    }
                    
                    let html = '<div class="reviews-list">';
                    data.forEach(review => {
                        const stars = Array.from({length: 5}, (_, i) => 
                            i < review.rating ? 
                                '<svg width="16" height="16" viewBox="0 0 24 24" fill="#ffb347"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>' :
                                '<svg width="16" height="16" viewBox="0 0 24 24" fill="#e2e8f0"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>'
                        ).join('');
                        
                        const date = new Date(review.created_at);
                        const dateStr = date.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
                        const editedBadge = review.edited_at ? 
                            `<span class="edited-badge" title="Edited ${new Date(review.edited_at).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })}">(edited)</span>` : '';
                        
                        html += `
                            <article class="review-card">
                                <div class="review-header">
                                    <div class="review-author-info">
                                        <strong class="review-author-name">${review.seeker ? review.seeker.name : 'Anonymous'}</strong>
                                        <div class="review-rating">${stars}</div>
                                    </div>
                                    <div class="review-header-right">
                                        <span class="review-date">${dateStr} ${editedBadge}</span>
                                    </div>
                                </div>
                                <p class="review-content">${review.review}</p>
                                ${review.company_reply ? `
                                    <div class="company-reply">
                                        <div class="company-reply-header">
                                            <strong>Company response</strong>
                                            <span>${new Date(review.replied_at).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })}</span>
                                        </div>
                                        <p>${review.company_reply}</p>
                                    </div>
                                ` : ''}
                            </article>
                        `;
                    });
                    html += '</div>';
                    container.innerHTML = html;
                })
                .catch(error => {
                    console.error('Error loading reviews:', error);
                    container.innerHTML = '<p class="muted-text">Error loading reviews. Please try again.</p>';
                });
        }

        function closeReviewsModal() {
            const modal = document.getElementById('reviewsModal');
            modal.classList.remove('show');
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('reviewsModal');
            if (event.target === modal) {
                closeReviewsModal();
            }
        }

        // Close modal with Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeReviewsModal();
            }
        });

        // Edit review functions
        function openEditReview(reviewId, currentRating, currentReview) {
            const contentDiv = document.getElementById('review-content-' + reviewId);
            const editForm = document.getElementById('review-edit-form-' + reviewId);
            
            // Hide content, show form
            contentDiv.style.display = 'none';
            editForm.style.display = 'block';
            
            // Set current values
            const reviewTextarea = document.getElementById('edit-review-' + reviewId);
            if (reviewTextarea) {
                reviewTextarea.value = currentReview.replace(/\\'/g, "'").replace(/\\"/g, '"');
            }
            
            // Set rating
            const ratingInput = document.getElementById('edit-star' + reviewId + '-' + currentRating);
            if (ratingInput) {
                ratingInput.checked = true;
            }
            
            // Update star colors
            updateEditStarColors(reviewId, currentRating);
            
            // Initialize hover effects
            initializeEditStarHover(reviewId);
        }

        function cancelEditReview(reviewId) {
            const contentDiv = document.getElementById('review-content-' + reviewId);
            const editForm = document.getElementById('review-edit-form-' + reviewId);
            
            // Show content, hide form
            contentDiv.style.display = 'block';
            editForm.style.display = 'none';
        }

        function validateEditForm(reviewId) {
            const rating = document.querySelector('input[name="rating"]:checked', document.getElementById('review-edit-form-' + reviewId));
            const review = document.getElementById('edit-review-' + reviewId).value.trim();
            
            if (!rating) {
                alert('Please select a rating');
                return false;
            }
            
            if (review.length < 10) {
                alert('Review must be at least 10 characters long');
                return false;
            }
            
            if (review.length > 1000) {
                alert('Review must be less than 1000 characters');
                return false;
            }
            
            return true;
        }

        // Update star colors when rating changes in edit form
        document.addEventListener('change', function(e) {
            if (e.target.name === 'rating' && e.target.id.startsWith('edit-star')) {
                const reviewId = e.target.id.split('-')[2];
                const rating = parseInt(e.target.value);
                updateEditStarColors(reviewId, rating);
            }
        });

        function updateEditStarColors(reviewId, rating) {
            const ratingContainer = document.getElementById('edit-rating-' + reviewId);
            if (!ratingContainer) return;
            
            const stars = ratingContainer.querySelectorAll('label');
            stars.forEach((star, index) => {
                const svg = star.querySelector('svg');
                if (svg) {
                    // Note: index is 0-based, rating is 1-based
                    // So we highlight stars where index < rating (0-4 for rating 1-5)
                    if (index < rating) {
                        svg.style.fill = '#ffb347';
                    } else {
                        svg.style.fill = '#cbd5e1';
                    }
                }
            });
        }

        // Add hover effects for edit form stars
        function initializeEditStarHover(reviewId) {
            const ratingContainer = document.getElementById('edit-rating-' + reviewId);
            if (!ratingContainer) return;

            const labels = ratingContainer.querySelectorAll('.star-label');
            
            // Remove any existing event listeners by cloning
            labels.forEach((label, index) => {
                // Get the input associated with this label
                const input = document.getElementById('edit-star' + reviewId + '-' + (index + 1));
                const starValue = index + 1; // 1-based rating
                
                label.addEventListener('mouseenter', function() {
                    // Highlight all stars up to and including this one (left side)
                    // index is 0-based, so for index 2 (3rd star), we highlight 0, 1, 2
                    labels.forEach((l, i) => {
                        const svg = l.querySelector('svg');
                        if (svg) {
                            if (i <= index) {
                                svg.style.fill = '#ffb347';
                            } else {
                                svg.style.fill = '#cbd5e1';
                            }
                        }
                    });
                });

                label.addEventListener('mouseleave', function() {
                    // Restore to selected rating
                    const checkedInput = ratingContainer.querySelector('input[type="radio"]:checked');
                    if (checkedInput) {
                        const selectedRating = parseInt(checkedInput.value);
                        updateEditStarColors(reviewId, selectedRating);
                    } else {
                        // No rating selected, reset all to gray
                        labels.forEach((l) => {
                            const svg = l.querySelector('svg');
                            if (svg) {
                                svg.style.fill = '#cbd5e1';
                            }
                        });
                    }
                });

                label.addEventListener('click', function() {
                    // After click, update colors based on selected rating
                    setTimeout(() => {
                        const checkedInput = ratingContainer.querySelector('input[type="radio"]:checked');
                        if (checkedInput) {
                            const selectedRating = parseInt(checkedInput.value);
                            updateEditStarColors(reviewId, selectedRating);
                        }
                    }, 10);
                });
            });
        }

        // Initialize star colors on page load for edit forms
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize hover effects for the initial review form
            initializeStarHoverForNewReview();
        });

        // Add hover effects for new review form stars
        function initializeStarHoverForNewReview() {
            const ratingContainer = document.querySelector('.review-form .star-rating-input');
            if (!ratingContainer) return;

            const labels = ratingContainer.querySelectorAll('.star-label');
            
            labels.forEach((label, index) => {
                const starValue = index + 1; // 1-based rating (1-5)
                
                label.addEventListener('mouseenter', function() {
                    // Highlight all stars up to and including this one (left side)
                    // index is 0-based, so for index 2 (3rd star), we highlight 0, 1, 2
                    labels.forEach((l, i) => {
                        const svg = l.querySelector('svg');
                        if (svg) {
                            if (i <= index) {
                                svg.style.fill = '#ffb347';
                            } else {
                                svg.style.fill = '#cbd5e1';
                            }
                        }
                    });
                });

                label.addEventListener('mouseleave', function() {
                    // Restore to selected rating
                    const checkedInput = ratingContainer.querySelector('input[type="radio"]:checked');
                    if (checkedInput) {
                        const selectedRating = parseInt(checkedInput.value);
                        labels.forEach((l, i) => {
                            const svg = l.querySelector('svg');
                            if (svg) {
                                if (i < selectedRating) {
                                    svg.style.fill = '#ffb347';
                                } else {
                                    svg.style.fill = '#cbd5e1';
                                }
                            }
                        });
                    } else {
                        // No rating selected, reset all to gray
                        labels.forEach((l) => {
                            const svg = l.querySelector('svg');
                            if (svg) {
                                svg.style.fill = '#cbd5e1';
                            }
                        });
                    }
                });

                label.addEventListener('click', function() {
                    // After click, update colors based on selected rating
                    setTimeout(() => {
                        const checkedInput = ratingContainer.querySelector('input[type="radio"]:checked');
                        if (checkedInput) {
                            const selectedRating = parseInt(checkedInput.value);
                            labels.forEach((l, i) => {
                                const svg = l.querySelector('svg');
                                if (svg) {
                                    if (i < selectedRating) {
                                        svg.style.fill = '#ffb347';
                                    } else {
                                        svg.style.fill = '#cbd5e1';
                                    }
                                }
                            });
                        }
                    }, 10);
                });
            });
        }
    </script>
@endsection
