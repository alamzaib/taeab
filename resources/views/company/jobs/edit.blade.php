@extends('layouts.app')

@section('title', 'Edit Job - Company Dashboard')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h2 class="primary-text mb-0">Edit Job</h2>
        </div>
        <form action="{{ route('company.jobs.update', $job) }}" method="POST">
            @method('PUT')
            <div class="card-body">
                @include('company.jobs.form', ['job' => $job])
            </div>
            <div class="card-footer">
                <button type="submit" class="btn-primary">Save Changes</button>
                <a href="{{ route('company.jobs.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

