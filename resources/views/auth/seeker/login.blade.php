@extends('layouts.app')

@section('title', 'Job Seeker Login - Job Portal UAE')

@section('content')
<div class="container">
    <div class="card" style="max-width: 500px; margin: 50px auto;">
        <h2 class="primary-text" style="margin-bottom: 20px; font-size: 28px;">Job Seeker Login</h2>
        
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

        <form method="POST" action="{{ route('seeker.login') }}">
            @csrf
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>

            <div class="form-group">
                <label>
                    <input type="checkbox" name="remember"> Remember me
                </label>
            </div>

            <div class="form-group">
                <button type="submit" class="btn-primary" style="width: 100%;">Login</button>
            </div>

            <div style="text-align: center; margin-top: 15px;">
                <p>Don't have an account? <a href="{{ route('seeker.register') }}" class="primary-text">Register here</a></p>
            </div>
        </form>

        <div style="text-align: center; margin-top: 30px;">
            <hr style="margin: 25px 0;">
            <p style="margin-bottom: 15px;">or continue with</p>
            <a href="{{ route('seeker.login.linkedin') }}" class="btn-secondary" style="width: 100%; display: inline-flex; align-items: center; justify-content: center; gap: 10px;">
                <img src="https://cdn.jsdelivr.net/npm/simple-icons@v11/icons/linkedin.svg" alt="LinkedIn" style="width: 18px; filter: invert(100%);">
                Continue with LinkedIn
            </a>
        </div>
    </div>
</div>
@endsection

