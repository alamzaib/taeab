@extends('admin.layouts.app')

@section('title', 'Create Blog Post')
@section('page-title', 'Create Blog Post')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.blog-posts.index') }}">Blog Posts</a></li>
    <li class="breadcrumb-item active">Create</li>
@endsection

@section('content')
<form action="{{ route('admin.blog-posts.store') }}" method="POST" enctype="multipart/form-data">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">New Blog Post</h3>
        </div>

        @include('admin.blog-posts._form')
    </div>
</form>
@endsection

