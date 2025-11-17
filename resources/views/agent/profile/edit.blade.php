@extends('layouts.app')

@section('title', 'Edit Profile - Agent Dashboard')

@section('content')
<div class="container">
    <div class="card" style="max-width: 640px; margin: 30px auto;">
        <h1 class="primary-text" style="font-size: 30px; margin-bottom: 10px;">Edit Profile</h1>
        <p style="color:#6b7280; margin-bottom: 20px;">Update your contact information and password.</p>

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

        <form action="{{ route('agent.profile.update') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $agent->name) }}" required>
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" class="form-control" value="{{ old('email', $agent->email) }}" required>
            </div>

            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="text" id="phone" name="phone" class="form-control" value="{{ old('phone', $agent->phone) }}" required>
            </div>

            <div class="form-group">
                <label for="company_name">Company Name (optional)</label>
                <input type="text" id="company_name" name="company_name" class="form-control" value="{{ old('company_name', $agent->company_name) }}">
            </div>

            <div class="form-group">
                <label for="password">New Password (optional)</label>
                <input type="password" id="password" name="password" class="form-control">
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirm Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control">
            </div>

            <div style="display:flex; justify-content:flex-end; gap:10px;">
                <a href="{{ route('agent.dashboard') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn-primary">Save Changes</button>
            </div>
        </form>
    </div>
</div>
@endsection

