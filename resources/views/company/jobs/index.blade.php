@extends('layouts.app')

@section('title', 'My Jobs - Company Dashboard')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2 class="primary-text mb-0">My Jobs</h2>
            <a href="{{ route('company.jobs.create') }}" class="btn-primary">Post New Job</a>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <table class="table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Status</th>
                        <th>Location</th>
                        <th>Posted</th>
                        <th width="150">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jobs as $job)
                        <tr>
                            <td>{{ $job->title }}</td>
                            <td><span class="badge badge-{{ $job->status === 'published' ? 'success' : ($job->status === 'closed' ? 'secondary' : 'warning') }}">{{ ucfirst($job->status) }}</span></td>
                            <td>{{ $job->location ?? '—' }}</td>
                            <td>{{ optional($job->posted_at)->format('M d, Y') ?? '—' }}</td>
                            <td>
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

            <div class="mt-3">
                {{ $jobs->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

