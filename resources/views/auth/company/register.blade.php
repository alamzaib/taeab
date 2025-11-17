@extends('layouts.app')

@section('title', 'Company Register - Job Portal UAE')

@section('content')
<div class="container">
    <div class="card" style="max-width: 500px; margin: 50px auto;">
        <h2 class="primary-text" style="margin-bottom: 20px; font-size: 28px;">Company Registration</h2>
        
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('company.register') }}">
            @csrf
            <div class="form-group">
                <label for="name">Contact Person Name</label>
                <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" required autofocus>
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required>
            </div>

            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="text" id="phone" name="phone" class="form-control" value="{{ old('phone') }}" required>
            </div>

            <div class="form-group">
                <label for="company_name">Company Name</label>
                <input type="text" id="company_name" name="company_name" class="form-control" value="{{ old('company_name') }}" required>
            </div>

            <div class="form-group">
                <label for="company_size">Company Size</label>
                <select id="company_size" name="company_size" class="form-control">
                    <option value="">Select Size</option>
                    <option value="1-10">1-10 employees</option>
                    <option value="11-50">11-50 employees</option>
                    <option value="51-200">51-200 employees</option>
                    <option value="201-500">201-500 employees</option>
                    <option value="500+">500+ employees</option>
                </select>
            </div>

            <div class="form-group">
                <label for="industry">Industry</label>
                <input type="text" id="industry" name="industry" class="form-control" value="{{ old('industry') }}">
            </div>

            <div class="form-group">
                <label for="website">Website</label>
                <input type="url" id="website" name="website" class="form-control" value="{{ old('website') }}">
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
                <p>Already have an account? <a href="{{ route('company.login') }}" class="primary-text">Login here</a></p>
            </div>
        </form>
    </div>
</div>
@endsection

