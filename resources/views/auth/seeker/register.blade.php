@extends('layouts.app')

@section('title', 'Job Seeker Register - Job Portal UAE')

@section('content')
<div class="auth-wrapper">
    <div class="auth-card">
        <div class="auth-panel">
            <h2 class="primary-text">Create your seeker account</h2>
            <p class="subtext">Email, phone number, and password are mandatory so employers can reach you.</p>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul style="margin: 0; padding-left: 20px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <a href="{{ route('seeker.login.linkedin') }}" class="social-button">
                <img src="https://cdn.jsdelivr.net/npm/simple-icons@v11/icons/linkedin.svg" alt="LinkedIn">
                Sign up with LinkedIn
            </a>

            <form method="POST" action="{{ route('seeker.register') }}" id="seekerRegisterForm">
                @csrf
                <input type="hidden" name="redirect" value="{{ request('redirect') }}">
                <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">
                <div class="auth-input">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus>
                </div>
                <div class="auth-input">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required>
                </div>
                <div class="auth-input">
                    <label for="phone">Phone Number</label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone') }}" required>
                </div>
                <div class="auth-dual">
                    <div class="auth-input">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    <div class="auth-input">
                        <label for="password_confirmation">Confirm Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required>
                    </div>
                </div>
                <button type="submit" class="btn-primary" style="width:100%; margin-top:20px;">Create Account</button>
            </form>
            
            @push('scripts')
            <script>
            document.getElementById('seekerRegisterForm').addEventListener('submit', function(e) {
                e.preventDefault();
                const form = this;
                
                if (window.recaptchaSiteKey) {
                    executeRecaptcha('register').then(function(token) {
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
            <h3>Why join Job Portal UAE?</h3>
            <p>Thousands of curated roles, CV and cover-letter management, and transparent application tracking.</p>
            <ul style="margin:0; padding-left:18px;">
                <li>Single profile, endless opportunities</li>
                <li>Save multiple CVs & cover letters</li>
                <li>Real-time application status</li>
            </ul>
            <a href="{{ route('seeker.login', ['redirect' => request('redirect')]) }}" class="btn"
                style="background:white; color:#235181; text-align:center; margin-top:15px;">Already registered?</a>
        </div>
    </div>
</div>
@endsection

