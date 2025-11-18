@php
    $jobs = $company->jobs()->latest()->paginate(15);
@endphp

<h2 class="primary-text" style="margin:0 0 24px; font-size:28px;">My Jobs</h2>
<p style="color:#64748b; margin-bottom:24px;">Manage all your job listings in one place.</p>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div style="margin-bottom:20px;">
    <a href="{{ route('company.jobs.create') }}" class="btn-primary">Post New Job</a>
</div>

<div style="overflow-x:auto;">
    <table class="table">
        <thead>
            <tr>
                <th>Title</th>
                <th>Status</th>
                <th>Location</th>
                <th>Job Type</th>
                <th>Posted</th>
                <th width="220">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($jobs as $job)
                <tr>
                    <td>
                        <strong>{{ $job->title }}</strong>
                        <p style="margin:4px 0 0; color:#6b7280; font-size:13px;">{{ $job->experience_level ?? 'Any experience' }}</p>
                    </td>
                    <td>
                        <span class="badge badge-{{ $job->status === 'published' ? 'success' : ($job->status === 'closed' ? 'secondary' : 'warning') }}">{{ ucfirst($job->status) }}</span>
                    </td>
                    <td>{{ $job->location ?? '—' }}</td>
                    <td>{{ $job->job_type ?? '—' }}</td>
                    <td>{{ optional($job->posted_at)->format('M d, Y') ?? '—' }}</td>
                    <td class="d-flex gap-2 flex-wrap">
                        <a href="{{ route('company.dashboard', ['tab' => 'applications', 'job' => $job->id]) }}" class="btn btn-sm btn-outline-primary">Applicants</a>
                        <a href="{{ route('company.jobs.edit', $job) }}" class="btn btn-sm btn-primary">Edit</a>
                        <form action="{{ route('company.jobs.destroy', $job) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this job?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-5">
                        <p style="margin: 0;">No jobs posted yet.</p>
                        <a href="{{ route('company.jobs.create') }}" class="btn btn-primary mt-3">Post Your First Job</a>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@if($jobs->hasPages())
    <div class="mt-3">
        {{ $jobs->links('components.pagination') }}
    </div>
@endif

