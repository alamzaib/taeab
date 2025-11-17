@extends('admin.layouts.app')

@section('title', 'Pages')
@section('page-title', 'Static Pages')

@section('breadcrumb')
    <li class="breadcrumb-item active">Pages</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Pages</h3>
        <div class="card-tools">
            <a href="{{ route('admin.pages.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Add Page
            </a>
        </div>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.pages.index') }}" method="GET" class="mb-3">
            <div class="form-row">
                <div class="col-md-6">
                    <input type="text" name="search" class="form-control" placeholder="Search by title or slug" value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-control">
                        <option value="">All Status</option>
                        <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-info btn-block">Filter</button>
                </div>
            </div>
        </form>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Slug</th>
                    <th>Status</th>
                    <th>Updated</th>
                    <th width="140">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pages as $page)
                    <tr>
                        <td>{{ $page->title }}</td>
                        <td>{{ $page->slug }}</td>
                        <td>
                            <span class="badge badge-{{ $page->status === 'published' ? 'success' : 'secondary' }}">
                                {{ ucfirst($page->status) }}
                            </span>
                        </td>
                        <td>{{ $page->updated_at->format('M d, Y') }}</td>
                        <td>
                            <a href="{{ route('admin.pages.edit', $page) }}" class="btn btn-sm btn-info">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.pages.destroy', $page) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this page?');">
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
                        <td colspan="5" class="text-center">No pages found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-3">
            {{ $pages->links() }}
        </div>
    </div>
</div>
@endsection

