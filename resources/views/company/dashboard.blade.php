@extends('layouts.app')

@section('title', 'Company Dashboard - Job Portal UAE')

@section('content')
<div class="container">
    <div class="card">
        <h1 class="primary-text" style="font-size: 32px; margin-bottom: 10px;">Company Dashboard</h1>
        <p style="color: #666; margin-bottom: 10px;">Welcome, {{ $company->name }}!</p>
        <p style="color: #666; margin-bottom: 30px;">{{ $company->company_name }}</p>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-top: 30px;">
            <div class="card" style="background: linear-gradient(135deg, #235181 0%, #1a3d63 100%); color: white;">
                <h3 style="margin-bottom: 10px;">Active Jobs</h3>
                <p style="font-size: 36px; font-weight: bold;">0</p>
            </div>
            <div class="card" style="background: linear-gradient(135deg, #235181 0%, #1a3d63 100%); color: white;">
                <h3 style="margin-bottom: 10px;">Total Applications</h3>
                <p style="font-size: 36px; font-weight: bold;">0</p>
            </div>
            <div class="card" style="background: linear-gradient(135deg, #235181 0%, #1a3d63 100%); color: white;">
                <h3 style="margin-bottom: 10px;">Profile Views</h3>
                <p style="font-size: 36px; font-weight: bold;">0</p>
            </div>
        </div>

        <div style="margin-top: 30px;">
            <h2 class="primary-text" style="margin-bottom: 20px;">Quick Actions</h2>
            <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                <a href="#" class="btn-primary">Post a Job</a>
                <a href="#" class="btn-primary">View Applications</a>
                <a href="#" class="btn-primary">Company Profile</a>
            </div>
        </div>
    </div>
</div>
@endsection

