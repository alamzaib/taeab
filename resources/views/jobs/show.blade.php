@php
    use Illuminate\Support\Str;
    use Illuminate\Support\Facades\Storage;
    $isSeeker = auth('seeker')->check();
@endphp

@extends('layouts.app')

@section('title', $job->title . ' - Job Details')
@section('meta_description', Str::limit(strip_tags($job->short_description ?? $job->description ?? ''), 150))

@section('content')
<div class="job-detail-shell container">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="job-hero card">
        <div class="job-hero-top">
            <a href="{{ route('jobs.index') }}" class="back-link">← Back to all jobs</a>
            <div class="job-hero-actions">
                @if($isSeeker)
                    <form action="{{ route('jobs.favorite', $job->slug) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-light btn-sm">
                            {{ $isFavorited ? '★ Favorited' : '☆ Save job' }}
                        </button>
                    </form>
                @else
                    <button id="favorite-btn" class="btn btn-light btn-sm">☆ Save job</button>
                @endif
                <button class="btn btn-light btn-sm" onclick="navigator.share ? navigator.share({title: '{{ $job->title }}', url: '{{ request()->fullUrl() }}'}) : window.alert('Copy link: {{ request()->fullUrl() }}')">Share</button>
            </div>
        </div>
        <div class="job-hero-main">
            <div>
                <p class="eyebrow">Opportunity</p>
                <h1>{{ $job->title }}</h1>
                <p class="job-meta">{{ $job->company->company_name ?? 'Company confidential' }} • {{ $job->location ?? 'UAE' }}</p>
                <div class="job-meta-grid">
                    @if($job->job_type)
                        <div>
                            <span>Job type</span>
                            <strong>{{ $job->job_type }}</strong>
                        </div>
                    @endif
                    @if($job->experience_level)
                        <div>
                            <span>Experience</span>
                            <strong>{{ $job->experience_level }}</strong>
                        </div>
                    @endif
                    <div>
                        <span>Salary range</span>
                        <strong>
                            @if($job->salary_min || $job->salary_max)
                                @if($job->salary_min) AED {{ number_format($job->salary_min) }} @endif
                                @if($job->salary_min && $job->salary_max) – @endif
                                @if($job->salary_max) AED {{ number_format($job->salary_max) }} @endif
                            @else
                                Not specified
                            @endif
                        </strong>
                    </div>
                    <div>
                        <span>Posted on</span>
                        <strong>{{ optional($job->posted_at)->format('M d, Y') ?? $job->created_at->format('M d, Y') }}</strong>
                    </div>
                </div>
            </div>
            <div class="job-hero-cta">
                @if($isSeeker)
                    @if($hasApplied)
                        <span class="badge badge-success">Application submitted</span>
                    @else
                        <a href="{{ route('jobs.apply.form', $job->slug) }}" class="btn-primary btn-apply-large">Apply now</a>
                        <small>You'll review documents before submitting.</small>
                    @endif
                @else
                    <button id="apply-btn" class="btn-primary btn-apply-large">Apply now</button>
                    <p class="apply-login-text">Login as a job seeker to start application</p>
                @endif
            </div>
        </div>
    </div>

    <div class="job-body-grid">
        <div class="job-content card">
            @if($job->short_description)
                <div class="job-section">
                    <h2>Role snapshot</h2>
                    <div class="page-content">
                        {!! nl2br(e($job->short_description)) !!}
                    </div>
                </div>
            @endif
            @if($job->description)
                <div class="job-section">
                    <h2>Job description</h2>
                    <div class="page-content">
                        {!! $job->description !!}
                    </div>
                </div>
            @endif
            @if($job->requirements)
                <div class="job-section">
                    <h2>Requirements</h2>
                    <div class="page-content">
                        {!! $job->requirements !!}
                    </div>
                </div>
            @endif
        </div>

        <aside class="job-sidebar">
            <div class="card job-company-card">
                <h3>About the company</h3>
                <p class="company-name">{{ $job->company->company_name ?? 'Company confidential' }}</p>
                <p class="company-meta">
                    {{ $job->company->industry ?? 'Industry not specified' }}<br>
                    {{ $job->company->city ?? '—' }}
                    @if($job->company->city && $job->company->country) • @endif
                    {{ $job->company->country ?? '' }}
                </p>
                <a href="{{ route('companies.show', $job->company) }}" class="btn btn-light btn-sm">View company profile</a>
            </div>
            @if($isSeeker && !$hasApplied)
                <div class="card job-documents">
                    <h3>Your default documents</h3>
                    <ul>
                        <li>
                            <span>Resume</span>
                            @if($defaultResume)
                                <a href="{{ Storage::disk('public')->url($defaultResume->file_path) }}" target="_blank">{{ $defaultResume->file_name }}</a>
                            @else
                                <em>Not set</em>
                            @endif
                        </li>
                        <li>
                            <span>Cover letter</span>
                            @if($defaultCover)
                                <a href="{{ Storage::disk('public')->url($defaultCover->file_path) }}" target="_blank">{{ $defaultCover->file_name }}</a>
                            @else
                                <em>Optional</em>
                            @endif
                        </li>
                    </ul>
                    <p class="muted-text">These files are pre-selected when you apply.</p>
                </div>
            @endif
        </aside>
    </div>
</div>

<style>
    .job-detail-shell {
        margin-bottom: 40px;
    }
    .job-hero {
        padding: 28px;
        margin-bottom: 24px;
    }
    .job-hero-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        margin-bottom: 12px;
    }
    .back-link {
        color: #6b7280;
        text-decoration: none;
    }
    .job-hero-actions {
        display: flex;
        gap: 10px;
    }
    .job-hero-main {
        display: flex;
        justify-content: space-between;
        gap: 24px;
        flex-wrap: wrap;
    }
    .job-hero-main h1 {
        margin: 4px 0 8px;
        font-size: 32px;
    }
    .job-meta {
        margin: 0 0 18px;
        color: #64748b;
        font-size: 15px;
    }
    .job-meta-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
        gap: 14px;
    }
    .job-meta-grid span {
        display: block;
        font-size: 12px;
        letter-spacing: .08em;
        text-transform: uppercase;
        color: #94a3b8;
        margin-bottom: 4px;
    }
    .job-hero-cta {
        min-width: 240px;
        text-align: right;
    }
    .btn-apply-large {
        padding: 16px 32px !important;
        font-size: 18px !important;
        font-weight: 600 !important;
        border-radius: 8px !important;
        width: 100%;
        max-width: 300px;
        display: inline-block;
        text-align: center;
    }
    .apply-login-text {
        margin-top: 12px;
        color: #64748b;
        font-size: 14px;
        text-align: center;
    }
    .job-body-grid {
        display: grid;
        grid-template-columns: minmax(0, 2fr) minmax(260px, 1fr);
        gap: 24px;
    }
    .job-content, .job-company-card, .job-documents {
        border-radius: 18px;
        padding: 24px;
    }
    .job-section + .job-section {
        margin-top: 32px;
    }
    .job-sidebar {
        display: flex;
        flex-direction: column;
        gap: 18px;
    }
    .job-company-card h3, .job-documents h3 {
        margin-top: 0;
    }
    .job-company-card p {
        margin: 4px 0;
    }
    .job-documents ul {
        list-style: none;
        padding: 0;
        margin: 0 0 10px 0;
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    .job-documents li {
        display: flex;
        justify-content: space-between;
        gap: 8px;
        color: #475569;
    }
    .job-documents span {
        font-weight: 600;
    }
    @media screen and (max-width: 1024px) {
        .job-body-grid {
            grid-template-columns: 1fr;
        }
        .job-hero-main {
            flex-direction: column;
        }
        .job-hero-cta {
            text-align: left;
        }
        .btn-apply-large {
            max-width: 100%;
        }
        .apply-login-text {
            text-align: left;
        }
    }
</style>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const applyBtn = document.getElementById('apply-btn');
    const favoriteBtn = document.getElementById('favorite-btn');

    const promptLogin = () => {
        Swal.fire({
            title: 'Login required',
            text: 'Please log in as a job seeker to continue.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Login',
            cancelButtonText: 'Cancel',
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "{{ route('seeker.login') }}?redirect={{ urlencode(request()->fullUrl()) }}";
            }
        });
    };

    if (applyBtn) {
        applyBtn.addEventListener('click', promptLogin);
    }

    if (favoriteBtn) {
        favoriteBtn.addEventListener('click', promptLogin);
    }
});
</script>
@endpush

