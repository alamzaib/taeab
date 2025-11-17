@extends('admin.layouts.app')

@section('title', 'Create Page')
@section('page-title', 'Create Page')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.pages.index') }}">Pages</a></li>
    <li class="breadcrumb-item active">Create</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">New Static Page</h3>
    </div>
    <form action="{{ route('admin.pages.store') }}" method="POST">
        <div class="card-body">
            @include('admin.pages.form')
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Create Page</button>
            <a href="{{ route('admin.pages.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection

