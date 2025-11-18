@extends('layouts.app')

@section('title', 'My Jobs - Company Dashboard')

@section('content')
<div class="container company-dashboard">
    <div class="card" style="padding:0; overflow:hidden;">
        @include('company.components.hero', [
            'heroTitle' => 'Manage Listings',
            'heroDescription' => 'Monitor every vacancy and jump into applicants directly.',
            'heroActions' => [
                ['label' => 'Post new job', 'route' => route('company.jobs.create'), 'variant' => 'primary'],
                ['label' => 'Applicants inbox', 'route' => route('company.applications.index'), 'variant' => 'ghost'],
            ],
            'stats' => ['active_jobs' => $company->jobs()->where('status', 'published')->count()]
        ])
        <div style="display:flex; flex-wrap:wrap;">
            @include('company.components.sidebar')
            <div style="flex:1; padding:32px;">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div style="overflow-x:auto;">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Status</th>
                            <th>Location</th>
                            <th>Posted</th>
                            <th width="220">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($jobs as $job)
                            <tr>
                                <td>
                                    <strong>{{ $job->title }}</strong>
                                    <p style="margin:4px 0 0; color:#6b7280; font-size:13px;">{{ $job->job_type ?? 'N/A' }} • {{ $job->experience_level ?? 'Any experience' }}</p>
                                </td>
                                <td>
                                    <span class="badge badge-{{ $job->status === 'published' ? 'success' : ($job->status === 'closed' ? 'secondary' : 'warning') }}">{{ ucfirst($job->status) }}</span>
                                </td>
                                <td>{{ $job->location ?? '—' }}</td>
                                <td>{{ optional($job->posted_at)->format('M d, Y') ?? '—' }}</td>
                                <td class="d-flex gap-2 flex-wrap">
                                    <a href="{{ route('company.applications.index', ['job' => $job->id]) }}" class="btn btn-sm btn-outline-primary">Applicants</a>
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
                                <td colspan="5" class="text-center">No jobs posted yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $jobs->links('components.pagination') }}
            </div>
            </div>
        </div>
    </div>
</div>
@endsection

