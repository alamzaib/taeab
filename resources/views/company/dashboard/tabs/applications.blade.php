@php
    $applicationsQuery = \App\Models\JobApplication::with(['job', 'seeker'])
        ->whereHas('job', function ($query) use ($company) {
            $query->where('company_id', $company->id);
        })
        ->latest();

    if (request()->filled('job')) {
        $applicationsQuery->where('job_id', request()->input('job'));
    }

    $applications = $applicationsQuery->paginate(12)->withQueryString();
    $jobs = \App\Models\Job::where('company_id', $company->id)->orderBy('title')->get(['id', 'title']);
@endphp

<h2 class="primary-text" style="margin:0 0 24px; font-size:28px;">Applicants</h2>
<p style="color:#64748b; margin-bottom:24px;">View everyone who applied to your openings and start a conversation.</p>

<form method="GET" class="row g-3 mb-4">
    <div class="col-md-6">
        <label class="form-label">Filter by job</label>
        <select name="job" class="form-control" onchange="this.form.submit()">
            <option value="">All jobs</option>
            @foreach($jobs as $job)
                <option value="{{ $job->id }}" {{ request('job') == $job->id ? 'selected' : '' }}>
                    {{ $job->title }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6 d-flex align-items-end">
        <a href="{{ route('company.dashboard', ['tab' => 'applications']) }}" class="btn btn-link ms-auto">Reset</a>
    </div>
</form>

<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th>Job</th>
                <th>Applicant</th>
                <th>Applied on</th>
                <th>Status</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($applications as $application)
                <tr>
                    <td>
                        <strong>{{ $application->job->title }}</strong>
                        <div style="font-size: 13px; color: #6b7280; margin-top: 4px;">
                            {{ $application->job->location ?? 'UAE' }}
                        </div>
                    </td>
                    <td>{{ $application->seeker->name }}</td>
                    <td>{{ $application->created_at->format('M d, Y') }}</td>
                    <td>
                        @php
                            $status = $application->status ?? 'submitted';
                            $statusColors = [
                                'submitted' => ['bg' => 'bg-info', 'text' => 'text-white'],
                                'reviewed' => ['bg' => 'bg-primary', 'text' => 'text-white'],
                                'shortlisted' => ['bg' => 'bg-warning', 'text' => 'text-dark'],
                                'interviewed' => ['bg' => 'bg-secondary', 'text' => 'text-white'],
                                'accepted' => ['bg' => 'bg-success', 'text' => 'text-white'],
                                'rejected' => ['bg' => 'bg-danger', 'text' => 'text-white'],
                            ];
                            $color = $statusColors[$status] ?? ['bg' => 'bg-light', 'text' => 'text-dark'];
                        @endphp
                        <span class="badge {{ $color['bg'] }} {{ $color['text'] }}" style="font-weight: 500;">
                            {{ ucfirst($status) }}
                        </span>
                    </td>
                    <td class="text-end">
                        <a href="{{ route('seekers.public.show', $application->seeker->unique_code) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                            View Profile
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-5">
                        <p style="margin: 0;">No applications yet.</p>
                        <a href="{{ route('company.jobs.create') }}" class="btn btn-primary mt-3">Post a Job</a>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@if($applications->hasPages())
    <div class="mt-3">
        {{ $applications->links('components.pagination') }}
    </div>
@endif

