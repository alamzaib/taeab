@extends('layouts.app')

@section('title', 'Applicants - Company Dashboard')

@section('content')
<div class="container company-dashboard">
    <div class="card" style="padding:0; overflow:hidden;">
        @include('company.components.hero', [
            'heroTitle' => 'Candidate Inbox',
            'heroDescription' => 'View everyone who applied to your openings and start a conversation.',
            'heroActions' => [
                ['label' => 'Manage jobs', 'route' => route('company.jobs.index'), 'variant' => 'ghost'],
                ['label' => 'Post new job', 'route' => route('company.jobs.create'), 'variant' => 'ghost'],
            ],
            'stats' => ['active_jobs' => $company->jobs()->where('status', 'published')->count()]
        ])
        <div style="display:flex; flex-wrap:wrap;">
            @include('company.components.sidebar')
            <div class="card-body" style="flex:1; padding:32px;">
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
                    <a href="{{ route('company.applications.index') }}" class="btn btn-link ms-auto">Reset</a>
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
                                <td>{{ $application->job->title }}</td>
                                <td>
                                    <strong>{{ $application->seeker->name }}</strong>
                                    <div class="text-muted">{{ $application->seeker->email }}</div>
                                </td>
                                <td>{{ $application->created_at->format('M d, Y') }}</td>
                                <td>
                                    <span class="badge bg-light text-dark">{{ ucfirst($application->status ?? 'submitted') }}</span>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('company.applications.show', $application) }}" class="btn btn-sm btn-primary">
                                        Open chat
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">No applications yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $applications->links('components.pagination') }}
            </div>
            </div>
        </div>
    </div>
</div>
@endsection

