@php
    $jobs = $agent->jobs()->with('company')->latest()->paginate(15);
@endphp

<h2 class="primary-text" style="margin:0 0 24px; font-size:28px;">My Jobs</h2>
<p style="color:#64748b; margin-bottom:24px;">Manage all jobs you've created on behalf of companies.</p>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div style="margin-bottom:20px;">
    <a href="{{ route('agent.jobs.create') }}" class="btn-primary">Create Job</a>
</div>

<div style="overflow-x:auto;">
    <table class="table">
        <thead>
            <tr>
                <th>Title</th>
                <th>Company</th>
                <th>Status</th>
                <th>Location</th>
                <th>Posted</th>
                <th width="150">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($jobs as $job)
                <tr>
                    <td>
                        <strong>{{ $job->title }}</strong>
                        <p style="margin:4px 0 0; color:#6b7280; font-size:13px;">{{ $job->job_type ?? 'N/A' }} • {{ $job->experience_level ?? 'Any experience' }}</p>
                    </td>
                    <td>{{ $job->company->company_name ?? '—' }}</td>
                    <td>
                        <span class="badge badge-{{ $job->status === 'published' ? 'success' : ($job->status === 'closed' ? 'secondary' : 'warning') }}">{{ ucfirst($job->status) }}</span>
                    </td>
                    <td>{{ $job->location ?? '—' }}</td>
                    <td>{{ optional($job->posted_at)->format('M d, Y') ?? '—' }}</td>
                    <td>
                        <a href="{{ route('agent.jobs.edit', $job) }}" class="btn btn-sm btn-primary">Edit</a>
                        <form action="{{ route('agent.jobs.destroy', $job) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this job?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-5">
                        <p style="margin: 0;">No jobs created yet.</p>
                        <a href="{{ route('agent.jobs.create') }}" class="btn btn-primary mt-3">Create Your First Job</a>
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

