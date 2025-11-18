@extends('admin.layouts.app')

@section('title', 'Job Seekers Management')
@section('page-title', 'Job Seekers')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.users.seekers.index') }}">Job Seekers</a></li>
    <li class="breadcrumb-item active">List</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Job Seekers List</h3>
        <div class="card-tools">
            <a href="{{ route('admin.users.seekers.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Add New Seeker
            </a>
        </div>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('admin.users.seekers.index') }}" class="mb-3">
            <div class="row">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Search by name, email, or phone" value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-control">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-info">Search</button>
                </div>
                <div class="col-md-3 text-right">
                    <a href="{{ route('admin.users.seekers.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </div>
        </form>

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Status</th>
                    <th>Last Refresh</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($seekers as $seeker)
                <tr>
                    <td><span class="badge badge-info">{{ $seeker->unique_code }}</span></td>
                    <td>{{ $seeker->name }}</td>
                    <td>{{ $seeker->email }}</td>
                    <td>{{ $seeker->phone ?? 'N/A' }}</td>
                    <td>
                        <span class="badge badge-{{ $seeker->status == 'active' ? 'success' : 'danger' }}">
                            {{ ucfirst($seeker->status) }}
                        </span>
                    </td>
                    <td>
                        @if($seeker->profile_refreshed_at)
                            {{ $seeker->profile_refreshed_at->diffForHumans() }}
                        @else
                            <span class="text-muted">Never</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.users.seekers.edit', $seeker) }}" class="btn btn-sm btn-info">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.users.seekers.destroy', $seeker) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this seeker?');">
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
                    <td colspan="7" class="text-center">No seekers found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-3">
            {{ $seekers->links() }}
        </div>
    </div>
</div>
@endsection

