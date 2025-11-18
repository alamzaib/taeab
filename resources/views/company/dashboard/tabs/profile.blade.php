@php
    use Illuminate\Support\Facades\Storage;
@endphp
<h2 class="primary-text" style="margin:0 0 24px; font-size:28px;">Company Profile</h2>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if ($errors->any())
    <div class="alert alert-danger">
        <ul style="margin: 0; padding-left: 20px;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('company.profile.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div style="display:grid; gap:24px;">
        <div class="auth-dual">
            <div class="form-group">
                <label for="name">Contact Name</label>
                <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $company->name) }}" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="text" id="phone" name="phone" class="form-control" value="{{ old('phone', $company->phone) }}" required>
            </div>
        </div>

        <div class="auth-dual">
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" class="form-control" value="{{ old('email', $company->email) }}" required>
            </div>
            <div class="form-group">
                <label for="company_name">Company Name</label>
                <input type="text" id="company_name" name="company_name" class="form-control" value="{{ old('company_name', $company->company_name) }}" required>
            </div>
        </div>

        <div class="auth-dual">
            <div class="form-group">
                <label for="company_size">Company Size</label>
                <input type="text" id="company_size" name="company_size" class="form-control" value="{{ old('company_size', $company->company_size) }}">
            </div>
            <div class="form-group">
                <label for="industry">Industry</label>
                <input type="text" id="industry" name="industry" class="form-control" value="{{ old('industry', $company->industry) }}">
            </div>
        </div>

        <div class="auth-dual">
            <div class="form-group">
                <label for="organization_type">Organization Type</label>
                <input type="text" id="organization_type" name="organization_type" class="form-control" value="{{ old('organization_type', $company->organization_type) }}">
            </div>
            <div class="form-group">
                <label for="website">Website</label>
                <input type="url" id="website" name="website" class="form-control" value="{{ old('website', $company->website) }}">
            </div>
        </div>

        <div class="auth-dual">
            <div class="form-group">
                <label for="address">Address</label>
                <input type="text" id="address" name="address" class="form-control" value="{{ old('address', $company->address) }}">
            </div>
            <div class="form-group">
                <label for="city">City</label>
                <input type="text" id="city" name="city" class="form-control" value="{{ old('city', $company->city) }}">
            </div>
        </div>

        <div class="auth-dual">
            <div class="form-group">
                <label for="country">Country</label>
                <input type="text" id="country" name="country" class="form-control" value="{{ old('country', $company->country) }}">
            </div>
            <div class="form-group">
                <label for="latitude">Latitude</label>
                <input type="text" id="latitude" name="latitude" class="form-control" value="{{ old('latitude', $company->latitude) }}">
            </div>
        </div>

        <div class="auth-dual">
            <div class="form-group">
                <label for="longitude">Longitude</label>
                <input type="text" id="longitude" name="longitude" class="form-control" value="{{ old('longitude', $company->longitude) }}">
            </div>
            <div class="form-group">
                <label for="logo">Company Logo</label>
                <input type="file" id="logo" name="logo" class="form-control">
                @if($company->logo_path)
                    <small><a href="{{ Storage::disk('public')->url($company->logo_path) }}" target="_blank">View current logo</a></small>
                @endif
            </div>
        </div>

        <div class="form-group">
            <label for="banner">Profile Banner</label>
            <input type="file" id="banner" name="banner" class="form-control">
            @if($company->banner_path)
                <small><a href="{{ Storage::disk('public')->url($company->banner_path) }}" target="_blank">View current banner</a></small>
            @endif
        </div>

        <div class="form-group">
            <label for="about">About Company</label>
            <textarea id="about" name="about" rows="4" class="form-control">{{ old('about', $company->about) }}</textarea>
        </div>

        <h3 class="primary-text" style="margin:20px 0 10px;">Account Security</h3>
        <div class="auth-dual">
            <div class="form-group">
                <label for="password">New Password (optional)</label>
                <input type="password" id="password" name="password" class="form-control">
            </div>
            <div class="form-group">
                <label for="password_confirmation">Confirm Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control">
            </div>
        </div>

        <div style="display:flex; justify-content:flex-end; gap:10px; margin-top:10px;">
            <button type="submit" class="btn-primary">Save Changes</button>
        </div>
    </div>
</form>

