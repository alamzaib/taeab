@php
    use Illuminate\Support\Str;
@endphp

@extends('layouts.app')

@section('title', 'Browse Jobs - Job Portal UAE')

@section('content')
<div class="container">
    <div class="card">
        <h1 class="primary-text" style="font-size: 32px; margin-bottom: 25px;">Browse Jobs</h1>

        <div style="display: grid; grid-template-columns: 280px 1fr; gap: 25px;">
            <aside style="border: 1px solid #e5e7eb; border-radius: 12px; padding: 20px; align-self: start;">
                <form action="{{ route('jobs.index') }}" method="GET">
                    <div class="form-group">
                        <label for="q">Keywords</label>
                        <input type="text" name="q" id="q" class="form-control" placeholder="Job title, keyword..." value="{{ request('q') }}">
                    </div>
                    <div class="form-group">
                        <label for="location">Location</label>
                        <input type="text" name="location" id="location" class="form-control" placeholder="City or Emirate" value="{{ request('location') }}">
                    </div>
                    <div class="form-group">
                        <label for="job_type">Job Type</label>
                        <select name="job_type" id="job_type" class="form-control">
                            <option value="">All Types</option>
                            @foreach($jobTypes as $type)
                                <option value="{{ $type }}" {{ request('job_type') === $type ? 'selected' : '' }}>{{ $type }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn-primary" style="width: 100%;">Apply Filters</button>
                </form>
            </aside>

            <section>
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                    <div>
                        <strong>{{ $jobs->total() }}</strong> jobs found
                    </div>
                    <div>
                        <form method="GET" action="{{ route('jobs.index') }}" style="display: flex; gap: 10px; align-items: center;">
                            <input type="hidden" name="q" value="{{ request('q') }}">
                            <input type="hidden" name="location" value="{{ request('location') }}">
                            <input type="hidden" name="job_type" value="{{ request('job_type') }}">
                            <label for="sort" style="font-size: 14px; color: #6b7280;">Sort by:</label>
                            <select name="sort" id="sort" class="form-control" style="width: 180px;" onchange="this.form.submit()">
                                <option value="newest" {{ request('sort', 'newest') === 'newest' ? 'selected' : '' }}>Newest</option>
                                <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Oldest</option>
                                <option value="salary_high" {{ request('sort') === 'salary_high' ? 'selected' : '' }}>Highest Salary</option>
                                <option value="salary_low" {{ request('sort') === 'salary_low' ? 'selected' : '' }}>Lowest Salary</option>
                            </select>
                        </form>
                    </div>
                </div>

                @php $view = request('view', 'list'); @endphp
                <div style="display: flex; justify-content: flex-end; gap: 10px; margin-bottom: 15px;">
                    <a href="{{ route('jobs.index', array_merge(request()->query(), ['view' => 'list'])) }}"
                       class="btn {{ $view === 'list' ? 'btn-primary' : 'btn-secondary' }} btn-sm">List View</a>
                    <a href="{{ route('jobs.index', array_merge(request()->query(), ['view' => 'grid'])) }}"
                       class="btn {{ $view === 'grid' ? 'btn-primary' : 'btn-secondary' }} btn-sm">Grid View</a>
                </div>

                @if($jobs->isEmpty())
                    <p style="text-align: center; color: #6b7280;">No jobs found. Try adjusting your search.</p>
                @else
                    <div style="display: {{ $view === 'grid' ? 'grid' : 'block' }}; gap: 20px; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));">
                        @foreach($jobs as $job)
                            <div class="card" style="margin-bottom: 20px;">
                                <div style="display: flex; justify-content: space-between; align-items: center;">
                                    <div>
                                        <h2 class="primary-text" style="margin-bottom: 5px;">
                                            <a href="{{ route('jobs.show', $job->slug) }}" style="color: inherit; text-decoration: none;">
                                                {{ $job->title }}
                                            </a>
                                        </h2>
                                        <p style="margin: 0; color: #6b7280;">
                                            {{ $job->company->company_name ?? 'Company' }} • {{ $job->location ?? 'UAE' }}
                                        </p>
                                        @if($job->job_type)
                                            <span class="badge badge-secondary" style="margin-top: 10px; display: inline-block;">{{ $job->job_type }}</span>
                                        @endif
                                    </div>
                                    <div style="text-align: right;">
                                        @if($job->salary_min || $job->salary_max)
                                            <p style="margin: 0; font-weight: 600;">
                                                @if($job->salary_min)
                                                    AED {{ number_format($job->salary_min) }}
                                                @endif
                                                -
                                                @if($job->salary_max)
                                                    AED {{ number_format($job->salary_max) }}
                                                @endif
                                            </p>
                                        @endif
                                        <small style="color: #9ca3af;">Posted {{ optional($job->posted_at)->diffForHumans() ?? $job->created_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                                @if($job->short_description)
                                    <p style="margin-top: 15px;">{{ Str::limit(strip_tags($job->short_description), 200) }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <div style="margin-top: 20px;">
                        {{ $jobs->appends(request()->query())->links('components.pagination') }}
                    </div>
                @endif
            </section>
        </div>
    </div>
</div>
@endsection

