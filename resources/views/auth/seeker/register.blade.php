@extends('layouts.app')

@section('title', 'Job Seeker Register - Job Portal UAE')

@section('content')
<div class="container">
    <div class="card" style="max-width: 500px; margin: 50px auto;">
        <h2 class="primary-text" style="margin-bottom: 20px; font-size: 28px;">Job Seeker Registration</h2>
        
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('seeker.register') }}">
            @csrf
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" required autofocus>
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required>
            </div>

            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="text" id="phone" name="phone" class="form-control" value="{{ old('phone') }}">
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirm Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
            </div>

            <div class="form-group">
                <button type="submit" class="btn-primary" style="width: 100%;">Register</button>
            </div>

            <div style="text-align: center; margin-top: 15px;">
                <p>Already have an account? <a href="{{ route('seeker.login') }}" class="primary-text">Login here</a></p>
            </div>
        </form>

        <div style="text-align: center; margin-top: 30px;">
            <hr style="margin: 25px 0;">
            <p style="margin-bottom: 15px;">or sign up with</p>
            <a href="{{ route('seeker.login.linkedin') }}" class="btn-secondary" style="width: 100%; display: inline-flex; align-items: center; justify-content: center; gap: 10px;">
                <img src="https://cdn.jsdelivr.net/npm/simple-icons@v11/icons/linkedin.svg" alt="LinkedIn" style="width: 18px; filter: invert(100%);">
                Continue with LinkedIn
            </a>
        </div>
    </div>
</div>
@endsection

