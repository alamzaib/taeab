@php
    use Illuminate\Support\Facades\Storage;
    use App\Models\CompanySize;
    use App\Models\Industry;
    use App\Models\OrganizationType;
    
    $companySizes = CompanySize::where('is_active', true)->orderBy('sort_order')->orderBy('name')->get();
    $industries = Industry::where('is_active', true)->orderBy('sort_order')->orderBy('name')->get();
    $organizationTypes = OrganizationType::where('is_active', true)->orderBy('sort_order')->orderBy('name')->get();
@endphp

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
    <form action="{{ route('admin.users.companies.update', $company) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="card-body">
            <h3 class="primary-text" style="margin-bottom: 20px;">Personal Details</h3>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name">Contact Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $company->name) }}" required>
                        @error('name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $company->phone) }}">
                        @error('phone')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="email">Email Address <span class="text-danger">*</span></label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $company->email) }}" required>
                        @error('email')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="company_name">Company Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('company_name') is-invalid @enderror" id="company_name" name="company_name" value="{{ old('company_name', $company->company_name) }}" required>
                        @error('company_name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <h3 class="primary-text" style="margin: 20px 0 10px;">Company Information</h3>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="company_size">Company Size</label>
                        <select class="form-control @error('company_size') is-invalid @enderror" id="company_size" name="company_size">
                            <option value="">Select Company Size</option>
                            @foreach($companySizes as $size)
                                <option value="{{ $size->name }}" {{ old('company_size', $company->company_size) == $size->name ? 'selected' : '' }}>
                                    {{ $size->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('company_size')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="industry">Industry</label>
                        <select class="form-control @error('industry') is-invalid @enderror" id="industry" name="industry">
                            <option value="">Select Industry</option>
                            @foreach($industries as $ind)
                                <option value="{{ $ind->name }}" {{ old('industry', $company->industry) == $ind->name ? 'selected' : '' }}>
                                    {{ $ind->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('industry')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="organization_type">Organization Type</label>
                        <select class="form-control @error('organization_type') is-invalid @enderror" id="organization_type" name="organization_type">
                            <option value="">Select Organization Type</option>
                            @foreach($organizationTypes as $orgType)
                                <option value="{{ $orgType->name }}" {{ old('organization_type', $company->organization_type) == $orgType->name ? 'selected' : '' }}>
                                    {{ $orgType->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('organization_type')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="website">Website</label>
                        <input type="url" class="form-control @error('website') is-invalid @enderror" id="website" name="website" value="{{ old('website', $company->website) }}" placeholder="https://example.com">
                        @error('website')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <h3 class="primary-text" style="margin: 20px 0 10px;">Location Information</h3>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="address">Address</label>
                        <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address" value="{{ old('address', $company->address) }}">
                        @error('address')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="city">City</label>
                        <input type="text" class="form-control @error('city') is-invalid @enderror" id="city" name="city" value="{{ old('city', $company->city) }}">
                        @error('city')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="country">Country</label>
                        <input type="text" class="form-control @error('country') is-invalid @enderror" id="country" name="country" value="{{ old('country', $company->country) }}">
                        @error('country')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="latitude">Latitude</label>
                        <input type="text" class="form-control @error('latitude') is-invalid @enderror" id="latitude" name="latitude" value="{{ old('latitude', $company->latitude) }}">
                        @error('latitude')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="longitude">Longitude</label>
                        <input type="text" class="form-control @error('longitude') is-invalid @enderror" id="longitude" name="longitude" value="{{ old('longitude', $company->longitude) }}">
                        @error('longitude')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <h3 class="primary-text" style="margin: 20px 0 10px;">Company Media</h3>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="logo">Company Logo</label>
                        <input type="file" class="form-control @error('logo') is-invalid @enderror" id="logo" name="logo" accept="image/*">
                        <small class="form-text text-muted">Max size: 50KB</small>
                        @if($company->logo_path)
                            <small class="form-text text-muted">
                                <a href="{{ storage_url($company->logo_path) }}" target="_blank">View current logo</a>
                            </small>
                        @endif
                        @error('logo')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="banner">Profile Banner</label>
                        <input type="file" class="form-control @error('banner') is-invalid @enderror" id="banner" name="banner" accept="image/*">
                        <small class="form-text text-muted">Max size: 500KB</small>
                        @if($company->banner_path)
                            <small class="form-text text-muted">
                                <a href="{{ storage_url($company->banner_path) }}" target="_blank">View current banner</a>
                            </small>
                        @endif
                        @error('banner')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="about">About Company</label>
                <textarea class="form-control @error('about') is-invalid @enderror" id="about" name="about" rows="4">{{ old('about', $company->about) }}</textarea>
                @error('about')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <h3 class="primary-text" style="margin: 20px 0 10px;">Account Security</h3>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password">
                        <small class="form-text text-muted">Leave blank to keep current password</small>
                        @error('password')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="password_confirmation">Confirm Password</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                    </div>
                </div>
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
