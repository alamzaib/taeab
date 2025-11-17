@extends('admin.layouts.app')

@section('title', 'Jobs')
@section('page-title', 'Jobs')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.jobs.index') }}">Jobs</a></li>
    <li class="breadcrumb-item active">List</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Jobs List</h3>
        <div class="card-tools">
            <a href="{{ route('admin.jobs.import') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-file-upload"></i> Import Jobs
            </a>
            <a href="{{ route('admin.jobs.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Add Job
            </a>
        </div>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('admin.jobs.index') }}" class="mb-3">
            <div class="form-row">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Search by title or location"
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="company_id" class="form-control">
                        <option value="">All Companies</option>
                        @foreach($companies as $id => $name)
                            <option value="{{ $id }}" {{ request('company_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-control">
                        <option value="">All Status</option>
                        @foreach(['draft' => 'Draft', 'published' => 'Published', 'closed' => 'Closed'] as $value => $label)
                            <option value="{{ $value }}" {{ request('status') === $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-info btn-block">Filter</button>
                </div>
            </div>
        </form>

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Company</th>
                    <th>Agent</th>
                    <th>Status</th>
                    <th>Location</th>
                    <th>Posted</th>
                    <th width="120">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($jobs as $job)
                    <tr>
                        <td>{{ $job->id }}</td>
                        <td>{{ $job->title }}</td>
                        <td>{{ $job->company->company_name }}</td>
                        <td>{{ $job->agent->name ?? 'N/A' }}</td>
                        <td>
                            <span class="badge badge-{{ $job->status === 'published' ? 'success' : ($job->status === 'closed' ? 'secondary' : 'warning') }}">
                                {{ ucfirst($job->status) }}
                            </span>
                        </td>
                        <td>{{ $job->location ?? '—' }}</td>
                        <td>{{ optional($job->posted_at)->format('M d, Y') ?? '—' }}</td>
                        <td>
                            <a href="{{ route('admin.jobs.edit', $job) }}" class="btn btn-sm btn-info">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.jobs.destroy', $job) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Delete this job?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">No jobs found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-3">
            {{ $jobs->links() }}
        </div>
    </div>
</div>
@endsection

