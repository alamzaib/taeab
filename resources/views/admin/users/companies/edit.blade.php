@extends('admin.layouts.app')

@section('title', 'Edit Company')
@section('page-title', 'Edit Company')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.users.companies.index') }}">Companies</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Edit Company</h3>
    </div>
    <form action="{{ route('admin.users.companies.update', $company) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
            <div class="form-group">
                <label for="name">Contact Person Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $company->name) }}" required>
                @error('name')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">Email Address <span class="text-danger">*</span></label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $company->email) }}" required>
                @error('email')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $company->phone) }}">
                @error('phone')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="company_name">Company Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('company_name') is-invalid @enderror" id="company_name" name="company_name" value="{{ old('company_name', $company->company_name) }}" required>
                @error('company_name')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="company_size">Company Size</label>
                <select class="form-control @error('company_size') is-invalid @enderror" id="company_size" name="company_size">
                    <option value="">Select Size</option>
                    <option value="1-10" {{ old('company_size', $company->company_size) == '1-10' ? 'selected' : '' }}>1-10 employees</option>
                    <option value="11-50" {{ old('company_size', $company->company_size) == '11-50' ? 'selected' : '' }}>11-50 employees</option>
                    <option value="51-200" {{ old('company_size', $company->company_size) == '51-200' ? 'selected' : '' }}>51-200 employees</option>
                    <option value="201-500" {{ old('company_size', $company->company_size) == '201-500' ? 'selected' : '' }}>201-500 employees</option>
                    <option value="500+" {{ old('company_size', $company->company_size) == '500+' ? 'selected' : '' }}>500+ employees</option>
                </select>
                @error('company_size')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="industry">Industry</label>
                <input type="text" class="form-control @error('industry') is-invalid @enderror" id="industry" name="industry" value="{{ old('industry', $company->industry) }}">
                @error('industry')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="website">Website</label>
                <input type="url" class="form-control @error('website') is-invalid @enderror" id="website" name="website" value="{{ old('website', $company->website) }}" placeholder="https://example.com">
                @error('website')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password">
                <small class="form-text text-muted">Leave blank to keep current password</small>
                @error('password')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirm Password</label>
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
            </div>

            <div class="form-group">
                <label for="status">Status <span class="text-danger">*</span></label>
                <select class="form-control @error('status') is-invalid @enderror" id="status" name="status" required>
                    <option value="active" {{ old('status', $company->status) == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status', $company->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
                @error('status')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Update Company</button>
            <a href="{{ route('admin.users.companies.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection

