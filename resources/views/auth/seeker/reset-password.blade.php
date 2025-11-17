@extends('layouts.app')

@section('title', 'Reset Password - Job Portal UAE')

@section('content')
<div class="auth-wrapper">
    <div class="auth-card">
        <div class="auth-panel">
            <h2 class="primary-text">Reset password</h2>
            <p class="subtext">Enter your email, phone number, and new password to reset your account.</p>

            <form method="POST" action="#">
                <div class="auth-input">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="auth-input">
                    <label for="phone">Phone Number</label>
                    <input type="text" id="phone" name="phone" required>
                </div>
                <div class="auth-dual">
                    <div class="auth-input">
                        <label for="password">New Password</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    <div class="auth-input">
                        <label for="password_confirmation">Confirm Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required>
                    </div>
                </div>
                <button class="btn-primary" type="submit" style="width:100%; margin-top:20px;">Update Password</button>
            </form>
        </div>
        <div class="auth-side">
            <h3>Need additional help?</h3>
            <p>Contact support if you no longer have access to your email or phone number.</p>
            <a href="{{ route('seeker.login') }}" class="btn" style="background:white; color:#235181; text-align:center; margin-top:15px;">Back to login</a>
        </div>
    </div>
</div>
@endsection

