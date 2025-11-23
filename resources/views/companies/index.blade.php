@extends('layouts.app')

@php
    use Illuminate\Support\Str;
@endphp

@section('title', 'Companies - Job Portal UAE')

@section('content')
@php
    $selectedStatus = $statusFilter ?? 'active';
    $sort = request('sort', 'name_asc');
@endphp

<div class="companies-shell container">
    <button class="filter-toggle-btn" id="filter-toggle-btn" aria-label="Toggle filters">
        <span>☰</span> Filters
    </button>
    <div class="card companies-wrapper">
        <div class="companies-grid">
            <aside class="companies-filter-panel" id="companies-filter-panel">
                <div class="filter-panel-header">
                    <div>
                        <p class="eyebrow">Filter organisations</p>
                        <h2>Filters</h2>
                    </div>
                    <a href="{{ route('companies.index') }}" class="filter-reset">Clear all</a>
                </div>
                <form action="{{ route('companies.index') }}" method="GET" class="filter-form">
                    <input type="hidden" name="sort" value="{{ $sort }}">
                    <div class="filter-group">
                        <label class="filter-label" for="q">Search</label>
                        <input type="text" id="q" name="q" class="form-control"
                               placeholder="Company or keyword" value="{{ request('q') }}">
                    </div>
                    <div class="filter-group">
                        <label class="filter-label" for="status">Status</label>
                        <select name="status" id="status" class="form-control">
                            <option value="active" {{ $selectedStatus === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ $selectedStatus === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="" {{ $selectedStatus === '' ? 'selected' : '' }}>Any</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label class="filter-label" for="industry">Industry</label>
                        <select name="industry" id="industry" class="form-control">
                            <option value="">All industries</option>
                            @foreach($industries as $industry)
                                <option value="{{ $industry }}" {{ request('industry') === $industry ? 'selected' : '' }}>{{ $industry }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="filter-group">
                        <label class="filter-label" for="company_size">Company size</label>
                        <select name="company_size" id="company_size" class="form-control">
                            <option value="">All sizes</option>
                            @foreach($companySizes as $size)
                                <option value="{{ $size }}" {{ request('company_size') === $size ? 'selected' : '' }}>{{ $size }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="filter-group">
                        <label class="filter-label" for="organization_type">Organisation type</label>
                        <select name="organization_type" id="organization_type" class="form-control">
                            <option value="">Any type</option>
                            @foreach($organizationTypes as $type)
                                <option value="{{ $type }}" {{ request('organization_type') === $type ? 'selected' : '' }}>{{ $type }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="filter-group">
                        <label class="filter-label" for="country">Country</label>
                        <select name="country" id="country" class="form-control">
                            <option value="">Any country</option>
                            @foreach($countries as $country)
                                <option value="{{ $country }}" {{ request('country') === $country ? 'selected' : '' }}>{{ $country }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="filter-group">
                        <label class="filter-label" for="city">City</label>
                        <select name="city" id="city" class="form-control">
                            <option value="">Any city</option>
                            @foreach($cities as $city)
                                <option value="{{ $city }}" {{ request('city') === $city ? 'selected' : '' }}>{{ $city }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="filter-group">
                        <label class="filter-label">Open roles</label>
                        <label class="toggle">
                            <input type="checkbox" name="has_openings" value="1" {{ request()->boolean('has_openings') ? 'checked' : '' }}>
                            <span>Show companies hiring now</span>
                        </label>
                    </div>
                    <div class="filter-actions">
                        <a href="{{ route('companies.index') }}" class="btn btn-light">Reset</a>
                        <button type="submit" class="btn-primary">Apply filters</button>
                    </div>
                </form>
            </aside>

            <section class="companies-results">
                <div class="companies-results-header">
                    <div>
                        <p class="eyebrow">{{ $companies->total() }} companies</p>
                        <h1>Browse Companies</h1>
                    </div>
                    <form method="GET" action="{{ route('companies.index') }}" class="sort-form">
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
                            <option value="name_asc" {{ $sort === 'name_asc' ? 'selected' : '' }}>Name A-Z</option>
                            <option value="name_desc" {{ $sort === 'name_desc' ? 'selected' : '' }}>Name Z-A</option>
                            <option value="recent" {{ $sort === 'recent' ? 'selected' : '' }}>Recently added</option>
                            <option value="jobs_desc" {{ $sort === 'jobs_desc' ? 'selected' : '' }}>Most open roles</option>
                        </select>
                    </form>
                </div>

                @if($companies->isEmpty())
                    <div class="empty-state">
                        <h3>No companies match your filters</h3>
                        <p>Adjust the filters or reset them to discover more employers.</p>
                        <a href="{{ route('companies.index') }}" class="btn-primary btn-sm">Reset filters</a>
                    </div>
                @else
                    <div class="companies-result-grid">
                        @foreach($companies as $company)
                            <article class="company-card">
                                <div class="company-card-header">
                                    <div class="company-logo-section">
                                        <div class="company-avatar">
                                            @if($company->logo_url)
                                                <img src="{{ $company->logo_url }}" alt="{{ $company->company_name ?? $company->name }}">
                                            @else
                                                <span>{{ strtoupper(substr($company->company_name ?? $company->name, 0, 1)) }}</span>
                                            @endif
                                        </div>
                                        <div class="company-status">
                                            <span class="status-chip {{ $company->status === 'active' ? 'status-active' : 'status-muted' }}">
                                                {{ ucfirst($company->status ?? 'inactive') }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="company-card-meta">
                                        @if($company->reviews_avg_rating && $company->reviews_avg_rating > 0)
                                            <div class="company-rating-stars" title="Average rating: {{ number_format($company->reviews_avg_rating, 1) }}/5">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="{{ $i <= round($company->reviews_avg_rating) ? '#ffb347' : '#e2e8f0' }}">
                                                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                                                    </svg>
                                                @endfor
                                            </div>
                                        @endif
                                        <div class="company-name-wrapper">
                                            <a href="{{ route('companies.show', $company) }}" class="company-card-title">
                                                {{ $company->company_name ?? $company->name }}
                                            </a>
                                        </div>
                                        <p class="company-card-location">
                                            {{ trim(($company->city ? $company->city : '') . ' ' . ($company->country ? '• '.$company->country : '')) ?: 'Location undisclosed' }}
                                        </p>
                                    </div>
                                </div>
                                <div class="company-card-tags">
                                    @if($company->industry)
                                        <span class="tag">{{ $company->industry }}</span>
                                    @endif
                                    @if($company->company_size)
                                        <span class="tag">{{ $company->company_size }}</span>
                                    @endif
                                    @if($company->organization_type)
                                        <span class="tag">{{ $company->organization_type }}</span>
                                    @endif
                                </div>
                                <div class="company-card-footer">
                                    <div>
                                        <p class="eyebrow" style="margin-bottom:4px;">Open roles</p>
                                        <p class="company-openings">
                                            {{ $company->active_jobs_count ?? 0 }} active {{ Str::plural('job', $company->active_jobs_count ?? 0) }}
                                        </p>
                                    </div>
                                    <a href="{{ route('companies.show', $company) }}" class="btn-primary btn-sm">View profile</a>
                                </div>
                            </article>
                        @endforeach
                    </div>

                    <div class="companies-pagination">
                        {{ $companies->appends(request()->query())->links('components.pagination') }}
                    </div>
                @endif
            </section>
        </div>
    </div>
</div>

<style>
    .companies-shell {
        margin-bottom: 40px;
    }
    .companies-wrapper {
        padding: 32px;
    }
    .companies-grid {
        display: grid;
        grid-template-columns: 320px 1fr;
        gap: 30px;
    }
    .companies-filter-panel {
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
    .filter-actions {
        display: flex;
        gap: 12px;
    }
    .toggle {
        display: flex;
        gap: 8px;
        font-size: 14px;
        align-items: center;
    }
    .companies-results-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 16px;
        margin-bottom: 24px;
    }
    .companies-results-header h1 {
        margin: 4px 0 0;
        font-size: 26px;
    }
    .companies-result-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 20px;
    }
    .company-card {
        border: 1px solid #e2e8f0;
        border-radius: 18px;
        padding: 20px;
        display: flex;
        flex-direction: column;
        gap: 14px;
        background: #fff;
    }
    .company-card-header {
        display: grid;
        grid-template-columns: auto 1fr;
        gap: 16px;
        align-items: flex-start;
    }

    .company-logo-section {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 10px;
    }
    .company-avatar {
        width: 54px;
        height: 54px;
        border-radius: 16px;
        background: #e0e7ff;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        font-weight: 700;
        color: #1d4ed8;
        font-size: 18px;
    }
    .company-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .company-name-wrapper {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        width: 100%;
    }

    .company-card-title {
        font-size: 20px;
        font-weight: 600;
        text-decoration: none;
        color: #0f172a;
        flex: 1;
        min-width: 0;
        word-wrap: break-word;
        line-height: 1.4;
    }

    .company-rating-stars {
        flex-shrink: 0;
        display: inline-flex;
        align-items: center;
        gap: 3px;
        margin-top: -20px;
        margin-left: 0px;
    }

    .company-rating-stars svg {
        transition: transform 0.2s ease;
    }

    .company-rating-stars:hover svg {
        transform: scale(1.1);
    }
    .company-card-meta {
        display: flex;
        flex-direction: column;
        gap: 4px;
        flex: 1;
        min-width: 0;
    }
    .company-card-location {
        margin: 4px 0 0;
        color: #64748b;
        font-size: 14px;
    }
    .company-status {
        text-align: center;
    }
    .status-chip {
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
    }
    .status-active {
        background: #dcfce7;
        color: #15803d;
    }
    .status-muted {
        background: #e2e8f0;
        color: #475569;
    }
    .company-card-tags {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }
    .tag {
        background: #eef2ff;
        color: #1d4ed8;
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 600;
    }
    .company-card-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .company-openings {
        margin: 0;
        font-size: 18px;
        font-weight: 600;
    }
    .companies-pagination {
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
    .filter-toggle-btn {
        display: none;
        position: fixed;
        top: 70px;
        left: 20px;
        z-index: 999;
        background: #235181;
        color: white;
        border: none;
        padding: 12px 20px;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        font-weight: 500;
        box-shadow: 0 2px 8px rgba(0,0,0,0.2);
    }

    .filter-toggle-btn span {
        margin-right: 8px;
        font-size: 20px;
    }

    @media screen and (max-width: 1024px) {
        .filter-toggle-btn {
            display: block;
        }

        .companies-grid {
            grid-template-columns: 1fr;
        }

        .companies-filter-panel {
            position: fixed;
            top: 0;
            left: -100%;
            width: 320px;
            height: 100vh;
            z-index: 998;
            transition: left 0.3s ease;
            box-shadow: 2px 0 10px rgba(0,0,0,0.2);
            overflow-y: auto;
            border-radius: 0;
            border: none;
        }

        .companies-filter-panel.active {
            left: 0;
        }

        .companies-filter-panel::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            display: none;
            z-index: -1;
        }

        .companies-filter-panel.active::before {
            display: block;
        }

        .companies-results-header {
            flex-direction: column;
            gap: 12px;
        }

        .company-card-header {
            grid-template-columns: 1fr;
            text-align: left;
        }

        .company-logo-section {
            flex-direction: row;
            align-items: center;
            justify-content: flex-start;
        }

        .company-status {
            text-align: left;
        }
    }

    @media screen and (max-width: 768px) {
        .companies-wrapper {
            padding: 20px 15px !important;
        }

        .companies-results-header h1 {
            font-size: 22px;
        }

        .companies-result-grid {
            grid-template-columns: 1fr;
        }

        .company-card {
            padding: 18px;
        }

        .company-card-title {
            font-size: 18px;
        }

        .filter-toggle-btn {
            left: 10px;
            top: 60px;
            padding: 10px 15px;
            font-size: 14px;
        }

        .companies-filter-panel {
            width: 100%;
        }

        .sort-form {
            width: 100%;
        }

        .sort-form select {
            width: 100%;
        }
    }

    @media screen and (max-width: 480px) {
        .companies-wrapper {
            padding: 15px 10px !important;
        }

        .company-card {
            padding: 15px;
        }

        .companies-results-header h1 {
            font-size: 20px;
        }

        .company-card-footer {
            flex-direction: column;
            align-items: flex-start;
            gap: 12px;
        }

        .company-card-footer .btn-primary {
            width: 100%;
        }

        .filter-actions {
            flex-direction: column;
        }

        .filter-actions .btn {
            width: 100%;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterToggle = document.getElementById('filter-toggle-btn');
    const filterPanel = document.getElementById('companies-filter-panel');
    
    if (filterToggle && filterPanel) {
        filterToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            filterPanel.classList.toggle('active');
            document.body.style.overflow = filterPanel.classList.contains('active') ? 'hidden' : '';
        });

        // Close filter panel when clicking outside
        document.addEventListener('click', function(e) {
            if (!filterPanel.contains(e.target) && !filterToggle.contains(e.target)) {
                filterPanel.classList.remove('active');
                document.body.style.overflow = '';
            }
        });

        // Close filter panel when form is submitted
        const filterForm = filterPanel.querySelector('.filter-form');
        if (filterForm) {
            filterForm.addEventListener('submit', function() {
                setTimeout(function() {
                    filterPanel.classList.remove('active');
                    document.body.style.overflow = '';
                }, 100);
            });
        }
    }
});
</script>
@endsection

