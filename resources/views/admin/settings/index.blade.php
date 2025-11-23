@extends('admin.layouts.app')

@section('title', 'Settings')
@section('page-title', 'Application Settings')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.settings.index') }}">Settings</a></li>
    <li class="breadcrumb-item active">{{ ucfirst($tab ?? 'general') }}</li>
@endsection

@section('content')
@php
    $activeTab = $tab ?? 'general';
@endphp

<ul class="nav nav-tabs" role="tablist" style="margin-bottom: 20px;">
    <li class="nav-item">
        <a class="nav-link {{ $activeTab === 'general' ? 'active' : '' }}" href="{{ route('admin.settings.index', ['tab' => 'general']) }}">
            General Settings
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $activeTab === 'countries' ? 'active' : '' }}" href="{{ route('admin.settings.countries') }}">
            Countries
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $activeTab === 'cities' ? 'active' : '' }}" href="{{ route('admin.settings.cities') }}">
            Cities
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $activeTab === 'job-types' ? 'active' : '' }}" href="{{ route('admin.settings.job-types') }}">
            Job Types
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $activeTab === 'experience-levels' ? 'active' : '' }}" href="{{ route('admin.settings.experience-levels') }}">
            Experience Levels
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $activeTab === 'smtp' ? 'active' : '' }}" href="{{ route('admin.settings.smtp') }}">
            SMTP Settings
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $activeTab === 'company-attributes' ? 'active' : '' }}" href="{{ route('admin.settings.company-attributes') }}">
            Company Attributes
        </a>
    </li>
</ul>

@if($activeTab === 'general')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Application Settings</h3>
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
            @csrf

            <!-- Logo Settings -->
            <div class="settings-section">
                <h2 class="section-title">Logo & Icons</h2>
                
                <div class="form-group">
                    <label for="application_logo">Application Logo</label>
                    @if(!empty($settings['application_logo']))
                        <div style="margin-bottom: 10px;">
                            <img src="{{ Storage::url($settings['application_logo']) }}" alt="Current Logo" style="max-height: 100px; margin-bottom: 10px;">
                            <br>
                            <label>
                                <input type="checkbox" name="delete_application_logo" value="1"> Delete current logo
                            </label>
                        </div>
                    @endif
                    <input type="file" id="application_logo" name="application_logo" class="form-control" accept="image/*">
                    <small class="form-text">Recommended size: 200x60px. Max size: 2MB</small>
                </div>

                <div class="form-group">
                    <label for="footer_logo">Footer Logo</label>
                    @if(!empty($settings['footer_logo']))
                        <div style="margin-bottom: 10px;">
                            <img src="{{ Storage::url($settings['footer_logo']) }}" alt="Current Footer Logo" style="max-height: 100px; margin-bottom: 10px;">
                            <br>
                            <label>
                                <input type="checkbox" name="delete_footer_logo" value="1"> Delete current logo
                            </label>
                        </div>
                    @endif
                    <input type="file" id="footer_logo" name="footer_logo" class="form-control" accept="image/*">
                    <small class="form-text">Recommended size: 200x60px. Max size: 2MB</small>
                </div>

                <div class="form-group">
                    <label for="favicon">Favicon</label>
                    @if(!empty($settings['favicon']))
                        <div style="margin-bottom: 10px;">
                            <img src="{{ Storage::url($settings['favicon']) }}" alt="Current Favicon" style="max-height: 32px; margin-bottom: 10px;">
                            <br>
                            <label>
                                <input type="checkbox" name="delete_favicon" value="1"> Delete current favicon
                            </label>
                        </div>
                    @endif
                    <input type="file" id="favicon" name="favicon" class="form-control" accept="image/*">
                    <small class="form-text">Recommended size: 32x32px or 16x16px. Max size: 512KB</small>
                </div>
            </div>

            <!-- SEO Settings -->
            <div class="settings-section">
                <h2 class="section-title">SEO Settings</h2>
                
                <div class="form-group">
                    <label for="meta_title">Homepage Meta Title</label>
                    <input type="text" id="meta_title" name="meta_title" class="form-control" value="{{ $settings['meta_title'] ?? '' }}" placeholder="Job Portal UAE - Find Your Dream Job">
                    <small class="form-text">This will appear in browser tabs and search results</small>
                </div>

                <div class="form-group">
                    <label for="meta_description">Meta Description</label>
                    <textarea id="meta_description" name="meta_description" class="form-control" rows="3" placeholder="Find your dream job in UAE...">{{ $settings['meta_description'] ?? '' }}</textarea>
                    <small class="form-text">Recommended: 150-160 characters</small>
                </div>

                <div class="form-group">
                    <label for="meta_keywords">Meta Keywords</label>
                    <input type="text" id="meta_keywords" name="meta_keywords" class="form-control" value="{{ $settings['meta_keywords'] ?? '' }}" placeholder="jobs, uae, employment, career">
                    <small class="form-text">Comma-separated keywords</small>
                </div>
            </div>

            <!-- Analytics & Custom Code -->
            <div class="settings-section">
                <h2 class="section-title">Analytics & Custom Code</h2>
                
                <div class="form-group">
                    <label for="google_analytics_code">Google Analytics Code</label>
                    <textarea id="google_analytics_code" name="google_analytics_code" class="form-control" rows="5" placeholder="<!-- Google Analytics code here -->">{{ $settings['google_analytics_code'] ?? '' }}</textarea>
                    <small class="form-text">Paste your Google Analytics tracking code</small>
                </div>

                <div class="form-group">
                    <label for="custom_css">Custom CSS (Head Section)</label>
                    <textarea id="custom_css" name="custom_css" class="form-control" rows="8" placeholder="<style>/* Your custom CSS here */</style>">{{ $settings['custom_css'] ?? '' }}</textarea>
                    <small class="form-text">Custom CSS code to be added in &lt;head&gt; section</small>
                </div>

                <div class="form-group">
                    <label for="custom_javascript">Custom JavaScript (Head Section)</label>
                    <textarea id="custom_javascript" name="custom_javascript" class="form-control" rows="8" placeholder="<script>/* Your custom JavaScript here */</script>">{{ $settings['custom_javascript'] ?? '' }}</textarea>
                    <small class="form-text">Custom JavaScript code to be added in &lt;head&gt; section</small>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="settings-section">
                <h2 class="section-title">Contact Information</h2>
                
                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="text" id="phone" name="phone" class="form-control" value="{{ $settings['phone'] ?? '' }}" placeholder="+971 4 XXX XXXX">
                </div>

                <div class="form-group">
                    <label for="official_email">Official Email</label>
                    <input type="email" id="official_email" name="official_email" class="form-control" value="{{ $settings['official_email'] ?? '' }}" placeholder="info@jobportaluae.com">
                </div>

                <div class="form-group">
                    <label for="address">Address</label>
                    <textarea id="address" name="address" class="form-control" rows="3" placeholder="Street Address">{{ $settings['address'] ?? '' }}</textarea>
                </div>

                <div class="form-row">
                    <div class="form-group" style="flex: 1;">
                        <label for="city">City</label>
                        <input type="text" id="city" name="city" class="form-control" value="{{ $settings['city'] ?? '' }}" placeholder="Dubai">
                    </div>

                    <div class="form-group" style="flex: 1;">
                        <label for="country">Country</label>
                        <input type="text" id="country" name="country" class="form-control" value="{{ $settings['country'] ?? 'United Arab Emirates' }}" placeholder="United Arab Emirates">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group" style="flex: 1;">
                        <label for="latitude">Latitude</label>
                        <input type="text" id="latitude" name="latitude" class="form-control" value="{{ $settings['latitude'] ?? '' }}" placeholder="25.2048">
                    </div>

                    <div class="form-group" style="flex: 1;">
                        <label for="longitude">Longitude</label>
                        <input type="text" id="longitude" name="longitude" class="form-control" value="{{ $settings['longitude'] ?? '' }}" placeholder="55.2708">
                    </div>
                </div>
            </div>

        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Save Settings</button>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@else
    @if($activeTab === 'countries')
        @include('admin.settings.tabs.countries')
    @elseif($activeTab === 'cities')
        @include('admin.settings.tabs.cities')
    @elseif($activeTab === 'job-types')
        @include('admin.settings.tabs.job-types')
    @elseif($activeTab === 'experience-levels')
        @include('admin.settings.tabs.experience-levels')
    @elseif($activeTab === 'smtp')
        @include('admin.settings.tabs.smtp')
    @elseif($activeTab === 'company-attributes')
        @include('admin.settings.tabs.company-attributes')
    @endif
@endif

<style>
.settings-section {
    margin-bottom: 40px;
    padding-bottom: 30px;
    border-bottom: 1px solid #e0e0e0;
}

.settings-section:last-child {
    border-bottom: none;
}

.section-title {
    color: #235181;
    font-size: 24px;
    margin-bottom: 20px;
    font-weight: bold;
}

.form-group {
    margin-bottom: 20px;
}

.form-row {
    display: flex;
    gap: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: 600;
    color: #333;
}

.form-control {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 16px;
    font-family: inherit;
}

.form-control:focus {
    outline: none;
    border-color: #235181;
    box-shadow: 0 0 0 2px rgba(35, 81, 129, 0.2);
}

textarea.form-control {
    resize: vertical;
    font-family: 'Courier New', monospace;
}

.form-text {
    display: block;
    margin-top: 5px;
    font-size: 12px;
    color: #666;
}

.btn-secondary {
    background-color: #6c757d;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    text-decoration: none;
    display: inline-block;
    transition: background-color 0.3s;
}

.btn-secondary:hover {
    background-color: #5a6268;
}

@media (max-width: 768px) {
    .form-row {
        flex-direction: column;
        gap: 0;
    }
}
</style>
@endsection

