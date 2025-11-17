@php
    use Illuminate\Support\Facades\Storage;
@endphp

@extends('layouts.app')

@section('title', 'Edit Profile - Seeker Dashboard')

@section('content')
<div class="container">
    <div class="card" style="padding:0;">
        <div style="background:{{ $seeker->profile_cover_style }};color:white;padding:35px;border-radius:18px 18px 0 0; position:relative;">
            <div style="position:relative; z-index:2;">
                <p style="margin:0;opacity:.8;">Profile center</p>
                <h1 style="margin:5px 0 0;font-size:32px;">{{ $seeker->name }}</h1>
                <p style="margin:8px 0 0;color:rgba(255,255,255,.85);">Maintain a polished profile so recruiters understand your story.</p>
            </div>
            <div style="position:absolute; bottom:-40px; right:35px; background:white; border-radius:50%; padding:6px; box-shadow:0 10px 30px rgba(15,23,42,0.15);">
                <img src="{{ $seeker->profile_photo_url }}" alt="Profile Photo" style="height:80px; width:80px; border-radius:50%; object-fit:cover;">
            </div>
        </div>

        <div style="padding:30px;">
            <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px; margin-bottom:25px;">
                <div>
                    <a href="{{ route('seeker.dashboard') }}" class="btn btn-secondary">← Back to dashboard</a>
                </div>
                <div style="display:flex; gap:10px;">
                    <a href="{{ route('jobs.index') }}" class="btn btn-secondary">Browse jobs</a>
                    <a href="{{ route('seeker.documents.index') }}" class="btn btn-secondary">Documents</a>
                </div>
            </div>

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

        <form action="{{ route('seeker.profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <h3 class="primary-text" style="margin-bottom:10px;">Personal Details</h3>
            <div class="auth-dual">
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $seeker->name) }}" required>
                </div>
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" class="form-control" value="{{ old('email', $seeker->email) }}" required>
                </div>
            </div>

            <div class="auth-dual">
                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="text" id="phone" name="phone" class="form-control" value="{{ old('phone', $seeker->phone) }}" required>
                </div>
                <div class="form-group">
                    <label for="whatsapp_number">WhatsApp Number</label>
                    <input type="text" id="whatsapp_number" name="whatsapp_number" class="form-control" value="{{ old('whatsapp_number', $seeker->whatsapp_number) }}">
                </div>
            </div>

            <div class="auth-dual">
                <div class="form-group">
                    <label for="residence_country">Current Residence Country</label>
                    <input type="text" id="residence_country" name="residence_country" class="form-control" value="{{ old('residence_country', $seeker->residence_country) }}">
                </div>
                <div class="form-group">
                    <label for="nationality">Nationality</label>
                    <input type="text" id="nationality" name="nationality" class="form-control" value="{{ old('nationality', $seeker->nationality) }}">
                </div>
            </div>

            <div class="auth-dual">
                <div class="form-group">
                    <label for="date_of_birth">Date of Birth</label>
                    <input type="date" id="date_of_birth" name="date_of_birth" class="form-control" value="{{ old('date_of_birth', $seeker->date_of_birth) }}">
                </div>
                <div class="form-group">
                    <label for="address">Address</label>
                    <input type="text" id="address" name="address" class="form-control" value="{{ old('address', $seeker->address) }}">
                </div>
            </div>

            <div class="form-group">
                <label for="linkedin_url">LinkedIn Profile</label>
                <input type="url" id="linkedin_url" name="linkedin_url" class="form-control" value="{{ old('linkedin_url', $seeker->linkedin_url) }}">
            </div>

            <h3 class="primary-text" style="margin:20px 0 10px;">Professional Summary</h3>
            <div class="form-group">
                <label for="about">About Me</label>
                <textarea id="about" name="about" rows="4" class="form-control">{{ old('about', $seeker->about) }}</textarea>
            </div>

            <div class="auth-dual">
            <div class="form-group">
                <label for="skills">Skills</label>
                <select id="skills" name="skills[]" class="form-control" multiple>
                    @php
                        $selectedSkills = old('skills', $seeker->skills ? explode(',', $seeker->skills) : []);
                        $commonSkills = ['Project Management','Sales','Marketing','Customer Support','JavaScript','PHP','Laravel','React','UI/UX','Data Analysis','Finance','HR','Operations','Business Development','Cloud Computing'];
                    @endphp
                    @foreach($commonSkills as $skill)
                        <option value="{{ $skill }}" {{ in_array($skill, $selectedSkills) ? 'selected' : '' }}>{{ $skill }}</option>
                    @endforeach
                </select>
                <div style="margin-top:6px;">
                    <small style="color:#6b7280;">Hold Ctrl (Cmd on Mac) to select multiple skills.</small>
                </div>
            </div>
                <div class="form-group">
                    <label for="current_company">Current Company</label>
                    <input type="text" id="current_company" name="current_company" class="form-control" value="{{ old('current_company', $seeker->current_company) }}">
                </div>
            </div>

            <div class="auth-dual">
                <div class="form-group">
                    <label for="current_salary">Current Salary</label>
                    <input type="text" id="current_salary" name="current_salary" class="form-control" value="{{ old('current_salary', $seeker->current_salary) }}">
                </div>
                <div class="form-group">
                    <label for="target_salary">Target Salary</label>
                    <input type="text" id="target_salary" name="target_salary" class="form-control" value="{{ old('target_salary', $seeker->target_salary) }}">
                </div>
            </div>

            <h3 class="primary-text" style="margin:20px 0 10px;">Media</h3>
            <div class="auth-dual">
                <div class="form-group">
                    <label for="profile_photo">Profile Photo</label>
                    <input type="file" id="profile_photo" name="profile_photo" class="form-control">
                    @if($seeker->profile_photo_path)
                        <small><a href="{{ Storage::disk('public')->url($seeker->profile_photo_path) }}" target="_blank">View current photo</a></small>
                    @endif
                </div>
                <div class="form-group">
                    <label for="profile_cover">Cover Image</label>
                    <input type="file" id="profile_cover" name="profile_cover" class="form-control">
                    @if($seeker->profile_cover_path)
                        <small><a href="{{ Storage::disk('public')->url($seeker->profile_cover_path) }}" target="_blank">View current cover</a></small>
                    @endif
                </div>
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

            <div style="display:flex; justify-content:flex-end; gap:10px; margin-top:20px;">
                <a href="{{ route('seeker.dashboard') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn-primary">Save Changes</button>
            </div>
        </form>
    </div>
</div>
@endsection

