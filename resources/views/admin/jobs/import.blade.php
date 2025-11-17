@extends('admin.layouts.app')

@section('title', 'Import Jobs')
@section('page-title', 'Import Jobs')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.jobs.index') }}">Jobs</a></li>
    <li class="breadcrumb-item active">Import</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Bulk Job Import</h3>
    </div>
    <div class="card-body">
        <p>Upload a CSV file with the following columns:</p>
        <ul>
            <li><strong>title</strong> (string)</li>
            <li><strong>company_email</strong> (must match an existing company email)</li>
            <li><strong>location</strong></li>
            <li><strong>job_type</strong></li>
            <li><strong>status</strong> (draft/published/closed)</li>
            <li><strong>salary_min</strong></li>
            <li><strong>salary_max</strong></li>
            <li><strong>short_description</strong></li>
            <li><strong>description</strong></li>
        </ul>

        <div class="alert alert-info">
            <strong>Tip:</strong> Export sample from this page, fill it out, then re-upload.
        </div>

        <form action="{{ route('admin.jobs.import.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="file">CSV File <span class="text-danger">*</span></label>
                <input type="file" name="file" id="file" class="form-control @error('file') is-invalid @enderror" accept=".csv" required>
                @error('file')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary">Import Jobs</button>
            <a href="{{ route('admin.jobs.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection

