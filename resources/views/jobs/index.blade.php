@php
    use Illuminate\Support\Str;
@endphp

@extends('layouts.app')

@section('title', 'Browse Jobs - Job Portal UAE')

@section('content')
@php
    $selectedJobTypes = (array) request('job_types', []);
    $selectedExperience = (array) request('experience_levels', []);
    $postedWithin = request('posted_within', 'any');
    $view = request('view', 'list');
@endphp

<div class="jobs-shell container">
    <div class="card jobs-wrapper">
        <div class="jobs-grid">
            <aside class="jobs-filter-panel">
                <div class="filter-panel-header">
                    <div>
                        <p class="eyebrow">Fine-tune your search</p>
                        <h2>Filters</h2>
                    </div>
                    <a href="{{ route('jobs.index') }}" class="filter-reset">Clear all</a>
                </div>
                <form action="{{ route('jobs.index') }}" method="GET" class="filter-form">
                    <input type="hidden" name="view" value="{{ $view }}">
                    <input type="hidden" name="sort" value="{{ request('sort', 'newest') }}">
                    <div class="filter-group">
                        <label class="filter-label" for="q">Keywords</label>
                        <input type="text" id="q" name="q" class="form-control"
                               placeholder="e.g. Product Manager" value="{{ request('q') }}">
                    </div>
                    <div class="filter-group">
                        <label class="filter-label" for="location">Location</label>
                        <input type="text" id="location" name="location" class="form-control"
                               placeholder="City or Emirate" value="{{ request('location') }}">
                    </div>
                    <div class="filter-group">
                        <div class="filter-label">Job type</div>
                        <div class="filter-pill-list">
                            @forelse($jobTypes as $type)
                                <label class="pill-checkbox">
                                    <input type="checkbox" name="job_types[]" value="{{ $type }}"
                                           {{ in_array($type, $selectedJobTypes) ? 'checked' : '' }}>
                                    <span>{{ $type }}</span>
                                </label>
                            @empty
                                <p class="muted-text">No job types yet.</p>
                            @endforelse
                        </div>
                    </div>
                    <div class="filter-group">
                        <div class="filter-label">Experience level</div>
                        <div class="filter-pill-list">
                            @forelse($experienceLevels as $level)
                                <label class="pill-checkbox">
                                    <input type="checkbox" name="experience_levels[]" value="{{ $level }}"
                                           {{ in_array($level, $selectedExperience) ? 'checked' : '' }}>
                                    <span>{{ $level }}</span>
                                </label>
                            @empty
                                <p class="muted-text">No experience levels found.</p>
                            @endforelse
                        </div>
                    </div>
                    <div class="filter-group">
                        <div class="filter-label">Salary range (AED)</div>
                        <div class="salary-inputs">
                            <input type="number" name="salary_min" class="form-control" placeholder="Min"
                                   value="{{ request('salary_min') }}" min="0">
                            <span>to</span>
                            <input type="number" name="salary_max" class="form-control" placeholder="Max"
                                   value="{{ request('salary_max') }}" min="0">
                        </div>
                        @if(($salaryRange['min'] ?? 0) && ($salaryRange['max'] ?? 0))
                            <p class="muted-text" style="margin-top:6px;">Typical pay ranges between
                                AED {{ number_format((int) $salaryRange['min']) }} – AED {{ number_format((int) $salaryRange['max']) }}</p>
                        @endif
                    </div>
                    <div class="filter-group">
                        <div class="filter-label">Posted within</div>
                        @php
                            $postedOptions = [
                                'any' => 'Any time',
                                '24h' => 'Last 24 hours',
                                '3d' => 'Last 3 days',
                                '7d' => 'Last 7 days',
                                '30d' => 'Last 30 days',
                            ];
                        @endphp
                        <div class="radio-stack">
                            @foreach($postedOptions as $value => $label)
                                <label class="filter-radio">
                                    <input type="radio" name="posted_within" value="{{ $value }}"
                                           {{ $postedWithin === $value ? 'checked' : '' }}>
                                    <span>{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                    <div class="filter-group">
                        <label class="filter-label" for="company_name">Company</label>
                        <input type="text" id="company_name" name="company_name" class="form-control"
                               placeholder="Search by company name" value="{{ request('company_name') }}">
                    </div>
                    <div class="filter-actions">
                        <a href="{{ route('jobs.index') }}" class="btn btn-light">Reset</a>
                        <button type="submit" class="btn-primary">Apply filters</button>
                    </div>
                </form>
            </aside>

            <section class="jobs-results">
                <div class="jobs-results-header">
                    <div>
                        <p class="eyebrow">{{ $jobs->total() }} opportunities</p>
                        <h1>Browse Jobs</h1>
                    </div>
                    <div class="jobs-results-controls">
                        <div class="view-toggle" role="group">
                            <a href="{{ route('jobs.index', array_merge(request()->except('view'), ['view' => 'list'])) }}"
                               class="view-btn {{ $view === 'list' ? 'active' : '' }}">List</a>
                            <a href="{{ route('jobs.index', array_merge(request()->except('view'), ['view' => 'grid'])) }}"
                               class="view-btn {{ $view === 'grid' ? 'active' : '' }}">Grid</a>
                        </div>
                        <form method="GET" action="{{ route('jobs.index') }}" class="sort-form">
                            @foreach(request()->except('sort') as $param => $value)
                                @if(is_array($value))
                                    @foreach($value as $item)
                                        <input type="hidden" name="{{ $param }}[]" value="{{ $item }}">
                                    @endforeach
                                @else
                                    <input type="hidden" name="{{ $param }}" value="{{ $value }}">
                                @endif
                            @endforeach
                            <label for="sort" class="filter-label" style="margin:0;">Sort by</label>
                            <select name="sort" id="sort" class="form-control" onchange="this.form.submit()">
                                <option value="newest" {{ request('sort', 'newest') === 'newest' ? 'selected' : '' }}>Newest</option>
                                <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Oldest</option>
                                <option value="salary_high" {{ request('sort') === 'salary_high' ? 'selected' : '' }}>Highest salary</option>
                                <option value="salary_low" {{ request('sort') === 'salary_low' ? 'selected' : '' }}>Lowest salary</option>
                            </select>
                        </form>
                    </div>
                </div>

                @if($jobs->isEmpty())
                    <div class="empty-state">
                        <h3>No roles match your filters</h3>
                        <p>Try broadening your search or resetting the filters to explore more opportunities.</p>
                        <a href="{{ route('jobs.index') }}" class="btn-primary btn-sm">Reset filters</a>
                    </div>
                @else
                    <div class="{{ $view === 'grid' ? 'jobs-result-grid' : 'jobs-result-list' }}">
                        @foreach($jobs as $job)
                            <article class="job-card {{ $view === 'grid' ? 'job-card-compact' : '' }} {{ $job->featured ? 'job-card-featured' : '' }}">
                                <div class="job-card-header">
                                    <div>
                                        <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                                            <a href="{{ route('jobs.show', $job->slug) }}" class="job-card-title">{{ $job->title }}</a>
                                            @if($job->featured)
                                                <span class="badge badge-warning" style="font-size: 10px; padding: 4px 8px; background: #fbbf24; color: #78350f;">⭐ Featured</span>
                                            @endif
                                        </div>
                                        <p class="job-card-meta">
                                            {{ $job->company->company_name ?? 'Company confidential' }}
                                            @if($job->location)
                                                • {{ $job->location }}
                                            @endif
                                        </p>
                                    </div>
                                    <div class="job-card-salary">
                                        @if($job->salary_min || $job->salary_max)
                                            <p>
                                                @if($job->salary_min)
                                                    AED {{ number_format((int) $job->salary_min) }}
                                                @endif
                                                @if($job->salary_min && $job->salary_max)
                                                    –
                                                @endif
                                                @if($job->salary_max)
                                                    AED {{ number_format((int) $job->salary_max) }}
                                                @endif
                                            </p>
                                        @else
                                            <p class="muted-text">Salary confidential</p>
                                        @endif
                                        <small>Posted {{ optional($job->posted_at)->diffForHumans() ?? $job->created_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                                <div class="job-card-tags">
                                    @if($job->job_type)
                                        <span class="tag">{{ $job->job_type }}</span>
                                    @endif
                                    @if($job->experience_level)
                                        <span class="tag">Experience: {{ $job->experience_level }}</span>
                                    @endif
                                </div>
                                @if($job->short_description)
                                    <p class="job-card-summary">
                                        {{ Str::limit(strip_tags($job->short_description), 180) }}
                                    </p>
                                @endif
                                <div class="job-card-actions">
                                    <a href="{{ route('jobs.show', $job->slug) }}" class="btn btn-light">View details</a>
                                </div>
                            </article>
                        @endforeach
                    </div>

                    <div class="jobs-pagination">
                        {{ $jobs->appends(request()->query())->links('components.pagination') }}
                    </div>
                @endif
            </section>
        </div>
    </div>
</div>

<style>
    .jobs-shell {
        margin-bottom: 40px;
    }
    .jobs-wrapper {
        padding: 32px;
    }
    .jobs-grid {
        display: grid;
        grid-template-columns: 320px 1fr;
        gap: 30px;
    }
    .jobs-filter-panel {
        border: 1px solid #e2e8f0;
        border-radius: 18px;
        padding: 24px;
        background: #f8fafc;
        position: sticky;
        top: 24px;
        align-self: start;
    }
    .filter-panel-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 18px;
    }
    .filter-panel-header h2 {
        margin: 0;
        font-size: 20px;
    }
    .filter-reset {
        font-size: 14px;
        color: #2563eb;
    }
    .filter-group {
        margin-bottom: 18px;
    }
    .filter-label {
        font-weight: 600;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: .08em;
        color: #475569;
        margin-bottom: 6px;
        display: block;
    }
    .filter-pill-list {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }
    .pill-checkbox {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        border: 1px solid #cbd5f5;
        border-radius: 999px;
        padding: 6px 12px;
        font-size: 13px;
        cursor: pointer;
        background: #fff;
    }
    .pill-checkbox input {
        accent-color: #235181;
    }
    .salary-inputs {
        display: flex;
        gap: 10px;
        align-items: center;
    }
    .salary-inputs span {
        color: #94a3b8;
        font-size: 14px;
    }
    .radio-stack {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    .filter-radio {
        display: flex;
        gap: 8px;
        font-size: 14px;
        align-items: center;
    }
    .filter-actions {
        display: flex;
        gap: 12px;
        margin-top: 10px;
    }
    .jobs-results-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 16px;
        margin-bottom: 24px;
    }
    .jobs-results-header h1 {
        margin: 4px 0 0;
        font-size: 26px;
    }
    .jobs-results-controls {
        display: flex;
        gap: 16px;
        align-items: center;
    }
    .view-toggle {
        display: inline-flex;
        border: 1px solid #e2e8f0;
        border-radius: 999px;
        overflow: hidden;
    }
    .view-btn {
        padding: 6px 14px;
        font-size: 14px;
        text-decoration: none;
        color: #475569;
    }
    .view-btn.active {
        background: #235181;
        color: #fff;
    }
    .sort-form {
        display: flex;
        gap: 8px;
        align-items: center;
    }
    .jobs-result-list {
        display: flex;
        flex-direction: column;
        gap: 18px;
    }
    .jobs-result-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 18px;
    }
    .job-card {
        border: 1px solid #e2e8f0;
        border-radius: 18px;
        padding: 20px;
        background: #fff;
        display: flex;
        flex-direction: column;
        gap: 14px;
        height: 100%;
    }
    .job-card-compact {
        padding: 18px;
    }
    .job-card-header {
        display: flex;
        justify-content: space-between;
        gap: 18px;
    }
    .job-card-title {
        font-size: 20px;
        font-weight: 600;
        text-decoration: none;
        color: #0f172a;
    }
    .job-card-meta {
        margin: 4px 0 0;
        color: #64748b;
        font-size: 14px;
    }
    .job-card-salary {
        text-align: right;
        min-width: 160px;
    }
    .job-card-salary p {
        margin: 0;
        font-weight: 600;
    }
    .job-card-salary small {
        color: #94a3b8;
    }
    .job-card-tags {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }
    .tag {
        background: #ecf2ff;
        color: #1d4ed8;
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 600;
    }
    .job-card-summary {
        margin: 0;
        color: #475569;
    }
    .job-card-actions {
        margin-top: auto;
    }
    .jobs-pagination {
        margin-top: 24px;
    }
    .empty-state {
        border: 1px dashed #cbd5f5;
        border-radius: 16px;
        padding: 32px;
        text-align: center;
    }
    .eyebrow {
        margin: 0;
        font-size: 13px;
        letter-spacing: .08em;
        color: #94a3b8;
        text-transform: uppercase;
    }
    .muted-text {
        color: #94a3b8;
        font-size: 13px;
        margin: 0;
    }
    @media screen and (max-width: 1024px) {
        .jobs-grid {
            grid-template-columns: 1fr;
        }
        .jobs-filter-panel {
            position: static;
        }
        .jobs-results-header {
            flex-direction: column;
        }
        .jobs-results-controls {
            width: 100%;
            justify-content: space-between;
        }
    }
</style>
@endsection

