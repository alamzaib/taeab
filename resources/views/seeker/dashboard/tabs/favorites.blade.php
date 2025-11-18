@php
    $favorites = $seeker->favorites()->with('job.company')->latest()->paginate(12);
@endphp

<h2 class="primary-text" style="margin:0 0 24px; font-size:28px;">Favourite Jobs</h2>
<p style="color:#64748b; margin-bottom:24px;">Your saved jobs for easy access.</p>

@if($favorites->isEmpty())
    <div class="card" style="border:1px solid #e2e8f0; padding:40px; text-align:center;">
        <p style="color:#94a3b8; margin-bottom:16px;">No saved jobs yet.</p>
        <a href="{{ route('jobs.index') }}" class="btn btn-primary">Browse Jobs</a>
    </div>
@else
    <div style="display:grid; grid-template-columns:repeat(auto-fill,minmax(320px,1fr)); gap:20px;">
        @foreach($favorites as $favorite)
            <div class="card" style="border:1px solid #e2e8f0; padding:20px;">
                <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:12px;">
                    <div style="flex:1;">
                        <h3 style="margin:0 0 8px; font-size:18px;">
                            <a href="{{ route('jobs.show', $favorite->job->slug) }}" style="color:#0f172a; text-decoration:none;">
                                {{ $favorite->job->title }}
                            </a>
                        </h3>
                        <p style="margin:0; color:#64748b; font-size:14px;">
                            {{ $favorite->job->company->company_name ?? 'Company' }}
                        </p>
                    </div>
                    <form action="{{ route('jobs.favorite', $favorite->job->slug) }}" method="POST">
                        @csrf
                        <button class="btn btn-sm btn-outline-secondary" type="submit" title="Remove from favorites">✕</button>
                    </form>
                </div>
                <div style="margin-bottom:12px;">
                    <span style="font-size:13px; color:#6b7280;">
                        {{ $favorite->job->location ?? 'UAE' }} • {{ $favorite->job->job_type ?? 'Full-time' }}
                    </span>
                </div>
                @if($favorite->job->salary_range)
                    <div style="margin-bottom:12px;">
                        <span style="font-size:13px; color:#16a34a; font-weight:500;">
                            {{ $favorite->job->salary_range }}
                        </span>
                    </div>
                @endif
                <div style="display:flex; gap:8px; margin-top:16px;">
                    <a href="{{ route('jobs.show', $favorite->job->slug) }}" class="btn btn-sm btn-primary">View Details</a>
                    @auth('seeker')
                        @php
                            $hasApplied = auth('seeker')->user()->applications()->where('job_id', $favorite->job->id)->exists();
                        @endphp
                        @if(!$hasApplied)
                            <a href="{{ route('jobs.apply.form', $favorite->job->slug) }}" class="btn btn-sm btn-outline-primary">Apply Now</a>
                        @else
                            <span class="btn btn-sm btn-secondary" style="cursor:default;">Applied</span>
                        @endif
                    @endauth
                </div>
            </div>
        @endforeach
    </div>
    @if($favorites->hasPages())
        <div class="mt-3">
            {{ $favorites->links('components.pagination') }}
        </div>
    @endif
@endif

