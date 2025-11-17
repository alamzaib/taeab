@extends('admin.layouts.app')

@section('title', 'Agents Management')
@section('page-title', 'Agents')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.users.agents.index') }}">Agents</a></li>
    <li class="breadcrumb-item active">List</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Agents List</h3>
        <div class="card-tools">
            <a href="{{ route('admin.users.agents.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Add New Agent
            </a>
        </div>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('admin.users.agents.index') }}" class="mb-3">
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
                    <a href="{{ route('admin.users.agents.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </div>
        </form>

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($agents as $agent)
                <tr>
                    <td>{{ $agent->id }}</td>
                    <td>{{ $agent->name }}</td>
                    <td>{{ $agent->email }}</td>
                    <td>{{ $agent->phone ?? 'N/A' }}</td>
                    <td>
                        <span class="badge badge-{{ $agent->status == 'active' ? 'success' : 'danger' }}">
                            {{ ucfirst($agent->status) }}
                        </span>
                    </td>
                    <td>{{ $agent->created_at->format('M d, Y') }}</td>
                    <td>
                        <a href="{{ route('admin.users.agents.edit', $agent) }}" class="btn btn-sm btn-info">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.users.agents.destroy', $agent) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this agent?');">
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
                    <td colspan="7" class="text-center">No agents found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-3">
            {{ $agents->links() }}
        </div>
    </div>
</div>
@endsection

