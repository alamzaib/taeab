@extends('layouts.app')

@section('title', 'Admin Dashboard - Job Portal UAE')

@section('content')
    <div class="container">
        <div class="card">
            <h1 class="primary-text" style="font-size: 32px; margin-bottom: 10px;">Admin Dashboard</h1>
            <p style="color: #666; margin-bottom: 30px;">Welcome, {{ $admin->name }}!</p>

            <div
                style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-top: 30px;">
                <div class="card" style="background: linear-gradient(135deg, #235181 0%, #1a3d63 100%); color: white;">
                    <h3 style="margin-bottom: 10px;">Total Users</h3>
                    <p style="font-size: 36px; font-weight: bold;">0</p>
                </div>
                <div class="card" style="background: linear-gradient(135deg, #235181 0%, #1a3d63 100%); color: white;">
                    <h3 style="margin-bottom: 10px;">Total Jobs</h3>
                    <p style="font-size: 36px; font-weight: bold;">0</p>
                </div>
                <div class="card" style="background: linear-gradient(135deg, #235181 0%, #1a3d63 100%); color: white;">
                    <h3 style="margin-bottom: 10px;">Total Companies</h3>
                    <p style="font-size: 36px; font-weight: bold;">0</p>
                </div>
                <div class="card" style="background: linear-gradient(135deg, #235181 0%, #1a3d63 100%); color: white;">
                    <h3 style="margin-bottom: 10px;">Total Applications</h3>
                    <p style="font-size: 36px; font-weight: bold;">0</p>
                </div>
            </div>

            <div style="margin-top: 30px;">
                <h2 class="primary-text" style="margin-bottom: 20px;">Quick Actions</h2>
                <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                    <a href="#" class="btn-primary">Manage Users</a>
                    <a href="#" class="btn-primary">Manage Jobs</a>
                    <a href="#" class="btn-primary">Manage Companies</a>
                    <a href="{{ route('admin.settings.index') }}" class="btn-primary">System Settings</a>
                </div>
            </div>
        </div>
    </div>
@endsection
