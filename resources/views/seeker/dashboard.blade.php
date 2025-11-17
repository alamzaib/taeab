@extends('layouts.app')

@section('title', 'Job Seeker Dashboard - Job Portal UAE')

@section('content')
<div class="container">
    <div class="card">
        <h1 class="primary-text" style="font-size: 32px; margin-bottom: 10px;">Job Seeker Dashboard</h1>
        <p style="color: #666; margin-bottom: 30px;">Welcome, {{ $seeker->name }}!</p>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-top: 30px;">
            <div class="card" style="background: linear-gradient(135deg, #235181 0%, #1a3d63 100%); color: white;">
                <h3 style="margin-bottom: 10px;">Applications Sent</h3>
                <p style="font-size: 36px; font-weight: bold;">0</p>
            </div>
            <div class="card" style="background: linear-gradient(135deg, #235181 0%, #1a3d63 100%); color: white;">
                <h3 style="margin-bottom: 10px;">Saved Jobs</h3>
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
                <a href="#" class="btn-primary">Browse Jobs</a>
                <a href="#" class="btn-primary">Upload Resume</a>
                <a href="#" class="btn-primary">Edit Profile</a>
            </div>
        </div>
    </div>
</div>
@endsection

