@extends('admin.layouts.app')

@section('title', 'Blog Posts')
@section('page-title', 'Blog Posts')
@section('breadcrumb')
    <li class="breadcrumb-item active">Blog Posts</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">All Blog Posts</h3>
        <a href="{{ route('admin.blog-posts.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus mr-1"></i> New Post
        </a>
    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Status</th>
                    <th>Published</th>
                    <th>Updated</th>
                    <th style="width: 150px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($posts as $post)
                    <tr>
                        <td>
                            <strong>{{ $post->title }}</strong>
                            <br>
                            <small class="text-muted">Slug: {{ $post->slug }}</small>
                        </td>
                        <td>
                            @if($post->status === 'published')
                                <span class="badge badge-success">Published</span>
                            @else
                                <span class="badge badge-secondary">Draft</span>
                            @endif
                        </td>
                        <td>
                            {{ optional($post->published_at)->format('M d, Y') ?? '—' }}
                        </td>
                        <td>{{ $post->updated_at->format('M d, Y') }}</td>
                        <td>
                            <a href="{{ route('admin.blog-posts.edit', $post) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                            <form action="{{ route('admin.blog-posts.destroy', $post) }}" method="POST" class="d-inline"
                                onsubmit="return confirm('Delete this post?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">No blog posts found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">
        {{ $posts->links() }}
    </div>
</div>
@endsection

