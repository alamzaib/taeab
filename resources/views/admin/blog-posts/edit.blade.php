@extends('admin.layouts.app')

@section('title', 'Edit Blog Post')
@section('page-title', 'Edit Blog Post')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.blog-posts.index') }}">Blog Posts</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<form action="{{ route('admin.blog-posts.update', $post) }}" method="POST" enctype="multipart/form-data">
    @method('PUT')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Editing: {{ $post->title }}</h3>
        </div>

        @include('admin.blog-posts._form')
    </div>
</form>
@endsection

