@php
    use Illuminate\Support\Facades\Storage;
    use App\Models\CompanySize;
    use App\Models\Industry;
    use App\Models\OrganizationType;
    
    $companySizes = CompanySize::where('is_active', true)->orderBy('sort_order')->orderBy('name')->get();
    $industries = Industry::where('is_active', true)->orderBy('sort_order')->orderBy('name')->get();
    $organizationTypes = OrganizationType::where('is_active', true)->orderBy('sort_order')->orderBy('name')->get();
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
                <input type="email" id="email" class="form-control" value="{{ $company->email }}" readonly style="background-color: #f5f5f5; cursor: not-allowed;">
                <small style="color: #6b7280; font-size: 12px;">Email address cannot be changed</small>
            </div>
            <div class="form-group">
                <label for="company_name">Company Name</label>
                <input type="text" id="company_name" name="company_name" class="form-control" value="{{ old('company_name', $company->company_name) }}" required>
            </div>
        </div>

        <div class="auth-dual">
            <div class="form-group">
                <label for="company_size">Company Size</label>
                <select id="company_size" name="company_size" class="form-control">
                    <option value="">Select Company Size</option>
                    @foreach($companySizes as $size)
                        <option value="{{ $size->name }}" {{ old('company_size', $company->company_size) == $size->name ? 'selected' : '' }}>
                            {{ $size->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="industry">Industry</label>
                <select id="industry" name="industry" class="form-control">
                    <option value="">Select Industry</option>
                    @foreach($industries as $ind)
                        <option value="{{ $ind->name }}" {{ old('industry', $company->industry) == $ind->name ? 'selected' : '' }}>
                            {{ $ind->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="auth-dual">
            <div class="form-group">
                <label for="organization_type">Organization Type</label>
                <select id="organization_type" name="organization_type" class="form-control">
                    <option value="">Select Organization Type</option>
                    @foreach($organizationTypes as $orgType)
                        <option value="{{ $orgType->name }}" {{ old('organization_type', $company->organization_type) == $orgType->name ? 'selected' : '' }}>
                            {{ $orgType->name }}
                        </option>
                    @endforeach
                </select>
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
                <small style="color: #6b7280; font-size: 12px;">Max size: 50KB</small>
                @if($company->logo_path)
                    <small><a href="{{ storage_url($company->logo_path) }}" target="_blank">View current logo</a></small>
                @endif
            </div>
        </div>

        <div class="form-group">
            <label for="banner">Profile Banner</label>
            <input type="file" id="banner" name="banner" class="form-control">
            <small style="color: #6b7280; font-size: 12px;">Max size: 500KB</small>
            @if($company->banner_path)
                <small><a href="{{ storage_url($company->banner_path) }}" target="_blank">View current banner</a></small>
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

