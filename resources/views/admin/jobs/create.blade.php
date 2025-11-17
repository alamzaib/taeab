@extends('admin.layouts.app')

@section('title', 'Create Job')
@section('page-title', 'Create Job')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.jobs.index') }}">Jobs</a></li>
    <li class="breadcrumb-item active">Create</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Create New Job</h3>
    </div>
    <form action="{{ route('admin.jobs.store') }}" method="POST">
        <div class="card-body">
            @include('admin.jobs.form', ['job' => $job])
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Create Job</button>
            <a href="{{ route('admin.jobs.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection

