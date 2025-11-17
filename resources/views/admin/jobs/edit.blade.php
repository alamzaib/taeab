@extends('admin.layouts.app')

@section('title', 'Edit Job')
@section('page-title', 'Edit Job')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.jobs.index') }}">Jobs</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Edit Job</h3>
    </div>
    <form action="{{ route('admin.jobs.update', $job) }}" method="POST">
        @method('PUT')
        <div class="card-body">
            @include('admin.jobs.form', ['job' => $job])
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Update Job</button>
            <a href="{{ route('admin.jobs.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection

