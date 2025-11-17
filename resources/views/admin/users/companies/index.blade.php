@extends('admin.layouts.app')

@section('title', 'Companies Management')
@section('page-title', 'Companies')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.users.companies.index') }}">Companies</a></li>
    <li class="breadcrumb-item active">List</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Companies List</h3>
        <div class="card-tools">
            <a href="{{ route('admin.users.companies.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Add New Company
            </a>
        </div>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('admin.users.companies.index') }}" class="mb-3">
            <div class="row">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Search by name, email, company, or phone" value="{{ request('search') }}">
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
                    <a href="{{ route('admin.users.companies.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </div>
        </form>

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Contact Name</th>
                    <th>Email</th>
                    <th>Company Name</th>
                    <th>Phone</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($companies as $company)
                <tr>
                    <td>{{ $company->id }}</td>
                    <td>{{ $company->name }}</td>
                    <td>{{ $company->email }}</td>
                    <td>{{ $company->company_name }}</td>
                    <td>{{ $company->phone ?? 'N/A' }}</td>
                    <td>
                        <span class="badge badge-{{ $company->status == 'active' ? 'success' : 'danger' }}">
                            {{ ucfirst($company->status) }}
                        </span>
                    </td>
                    <td>{{ $company->created_at->format('M d, Y') }}</td>
                    <td>
                        <a href="{{ route('admin.users.companies.edit', $company) }}" class="btn btn-sm btn-info">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.users.companies.destroy', $company) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this company?');">
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
                    <td colspan="8" class="text-center">No companies found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-3">
            {{ $companies->links() }}
        </div>
    </div>
</div>
@endsection

