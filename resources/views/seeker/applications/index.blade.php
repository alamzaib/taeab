@extends('layouts.app')

@section('title', 'My Applications - Seeker Dashboard')

@section('content')
<div class="container">
    <div style="margin-bottom:15px;">
        <a href="{{ route('seeker.dashboard') }}" class="btn btn-secondary">← Back to dashboard</a>
    </div>
    <div class="card">
        <div class="card-header">
            <div>
                <h2 class="primary-text mb-1">My Applications</h2>
                <p class="mb-0 text-muted">Track your submissions and continue conversations with employers.</p>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Role</th>
                            <th>Company</th>
                            <th>Applied on</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($applications as $application)
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
                            <tr>
                                <td>
                                    <strong>{{ $application->job->title }}</strong>
                                    <div style="font-size: 13px; color: #6b7280; margin-top: 4px;">
                                        {{ $application->job->location ?? 'UAE' }} • {{ $application->job->job_type ?? 'Full-time' }}
                                    </div>
                                </td>
                                <td>{{ $application->job->company->company_name ?? $application->job->company->name }}</td>
                                <td>{{ $application->created_at->format('M d, Y') }}</td>
                                <td>
                                    <span class="badge {{ $color['bg'] }} {{ $color['text'] }}" style="font-weight: 500;">
                                        {{ ucfirst($status) }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('jobs.show', $application->job->slug) }}" class="btn btn-sm btn-outline-secondary" style="margin-right: 5px;">
                                        View Job
                                    </a>
                                    <a href="{{ route('seeker.applications.show', $application) }}" class="btn btn-sm btn-primary">
                                        Messages
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-5">
                                    <p style="margin: 0;">No applications yet.</p>
                                    <a href="{{ route('jobs.index') }}" class="btn btn-primary mt-3">Browse Jobs</a>
                                </td>
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
@endsection

