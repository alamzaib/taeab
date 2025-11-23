@extends('layouts.app')

@section('title', 'Job Seeker Login - Job Portal UAE')

@section('content')
<div class="auth-wrapper">
    <div class="auth-card">
        <div class="auth-panel">
            <h2 class="primary-text">Welcome back</h2>
            <p class="subtext">Sign in with your credentials or continue with LinkedIn.</p>

            <a href="{{ route('seeker.login.linkedin') }}" class="social-button">
                <img src="https://cdn.jsdelivr.net/npm/simple-icons@v11/icons/linkedin.svg" alt="LinkedIn">
                Continue with LinkedIn
            </a>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul style="margin: 0; padding-left: 20px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('seeker.login') }}" id="seekerLoginForm">
                @csrf
                <input type="hidden" name="redirect" value="{{ request('redirect') }}">
                <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">
                <div class="auth-input">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
                </div>
                <div class="auth-input">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="auth-extra">
                    <label style="display:flex; align-items:center; gap:8px; color:#6b7280;">
                        <input type="checkbox" name="remember"> Remember me
                    </label>
                    <a href="{{ route('seeker.password.reset') }}">Forgot password?</a>
                </div>
                <button type="submit" class="btn-primary" style="width:100%; margin-top:20px;">Sign In</button>
            </form>
            
            @push('scripts')
            <script>
            document.getElementById('seekerLoginForm').addEventListener('submit', function(e) {
                e.preventDefault();
                const form = this;
                
                if (window.recaptchaSiteKey) {
                    executeRecaptcha('login').then(function(token) {
                        document.getElementById('g-recaptcha-response').value = token;
                        form.submit();
                    });
                } else {
                    form.submit();
                }
            });
            </script>
            @endpush
        </div>
        <div class="auth-side">
            <h3>New to Job Portal?</h3>
            <p>Join thousands of seekers finding careers across the UAE.</p>
            <ul style="margin:0; padding-left:18px;">
                <li>Personalized job alerts</li>
                <li>Multiple CV & cover letter profiles</li>
                <li>Track every application</li>
            </ul>
            <a href="{{ route('seeker.register', ['redirect' => request('redirect')]) }}" class="btn"
                style="background:white; color:#235181; text-align:center; margin-top:15px;">Create Account</a>
        </div>
    </div>
</div>
@endsection
