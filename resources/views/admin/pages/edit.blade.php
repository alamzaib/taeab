@extends('admin.layouts.app')

@section('title', 'Edit Page')
@section('page-title', 'Edit Page')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.pages.index') }}">Pages</a></li>
    <li class="breadcrumb-item active">{{ $page->title }}</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Edit Page</h3>
    </div>
    <form action="{{ route('admin.pages.update', $page) }}" method="POST">
        @method('PUT')
        <div class="card-body">
            @include('admin.pages.form', ['page' => $page])
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <a href="{{ route('admin.pages.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection

