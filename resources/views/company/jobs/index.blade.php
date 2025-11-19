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
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div style="overflow-x:auto;">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Status</th>
                            <th>Location</th>
                            <th>Posted / Expires</th>
                            <th width="250">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($jobs as $job)
                            <tr>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 8px;">
                                        <strong>{{ $job->title }}</strong>
                                        @if($job->featured)
                                            <span class="badge badge-warning" style="font-size: 10px;">⭐ Featured</span>
                                        @endif
                                    </div>
                                    <p style="margin:4px 0 0; color:#6b7280; font-size:13px;">{{ $job->job_type ?? 'N/A' }} • {{ $job->experience_level ?? 'Any experience' }}</p>
                                </td>
                                <td>
                                    @if($job->isExpired())
                                        <span class="badge badge-danger">Expired</span>
                                    @else
                                        <span class="badge badge-{{ $job->status === 'published' ? 'success' : ($job->status === 'closed' ? 'secondary' : 'warning') }}">{{ ucfirst($job->status) }}</span>
                                    @endif
                                </td>
                                <td>{{ $job->location ?? '—' }}</td>
                                <td>
                                    <div style="font-size: 13px;">
                                        <div><strong>Posted:</strong> {{ optional($job->posted_at)->format('M d, Y') ?? '—' }}</div>
                                        @if($job->expires_at)
                                            <div style="color: {{ $job->isExpired() ? '#ef4444' : '#64748b' }};">
                                                <strong>Expires:</strong> {{ $job->expires_at->format('M d, Y') }}
                                                @if($job->isExpired())
                                                    <span class="badge badge-danger" style="font-size: 10px; margin-left: 4px;">Expired</span>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="d-flex gap-2 flex-wrap">
                                    @if($job->isExpired())
                                        <form action="{{ route('company.jobs.renew', $job) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success">Renew</button>
                                        </form>
                                    @endif
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

