@extends('layouts.app')

@section('title', 'Company Dashboard - Job Portal UAE')

@section('content')
<div class="container">
    <div class="card" style="padding:0;">
        <div style="position:relative; background:{{ $company->banner_path ? 'url('.Storage::disk('public')->url($company->banner_path).') center/cover' : 'linear-gradient(135deg,#0f4c75,#073046)' }};color:white;padding:30px;border-radius:18px 18px 0 0;">
            <div style="position:relative; z-index:2;">
                <p style="margin:0;opacity:.8;">Welcome back,</p>
                <h1 style="margin:5px 0 0;font-size:32px;">{{ $company->company_name }}</h1>
                <p style="margin:8px 0 0;color:rgba(255,255,255,.85);">Monitor hiring performance and launch new roles faster.</p>
            </div>
            @if($company->logo_path)
                <div style="position:absolute; bottom:-35px; right:30px; background:white; border-radius:12px; padding:8px; box-shadow:0 15px 40px rgba(15,23,42,0.2);">
                    <img src="{{ Storage::disk('public')->url($company->logo_path) }}" alt="Logo" style="height:70px; width:auto;">
                </div>
            @endif
        </div>

        <div style="padding:30px;display:grid;gap:20px;">
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:15px;">
                <div class="card" style="background:#e0f2fe;">
                    <p style="color:#0369a1;margin-bottom:6px;">Active jobs</p>
                    <p style="font-size:32px;font-weight:700;color:#0c4a6e;">{{ $stats['active_jobs'] }}</p>
                </div>
                <div class="card" style="background:#fef3c7;">
                    <p style="color:#b45309;margin-bottom:6px;">Total jobs</p>
                    <p style="font-size:32px;font-weight:700;color:#92400e;">{{ $stats['total_jobs'] }}</p>
                </div>
                <div class="card" style="background:#ede9fe;">
                    <p style="color:#6d28d9;margin-bottom:6px;">Interviews scheduled</p>
                    <p style="font-size:32px;font-weight:700;color:#5b21b6;">5</p>
                </div>
            </div>

            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(230px,1fr));gap:15px;">
                <div class="card" style="border:1px dashed #d1d5db;">
                    <h3 class="primary-text" style="margin-bottom:8px;">Post a job</h3>
                    <p style="color:#6b7280;">Launch a new vacancy with your saved templates.</p>
                    <a href="{{ route('company.jobs.create') }}" class="btn-primary" style="margin-top:10px;">Create listing</a>
                </div>
                <div class="card" style="border:1px dashed #d1d5db;">
                    <h3 class="primary-text" style="margin-bottom:8px;">Manage listings</h3>
                    <p style="color:#6b7280;">Edit descriptions, review applicants, and close filled roles.</p>
                    <a href="{{ route('company.jobs.index') }}" class="btn-primary" style="margin-top:10px;">View jobs</a>
                </div>
                <div class="card" style="border:1px dashed #d1d5db;">
                    <h3 class="primary-text" style="margin-bottom:8px;">Company profile</h3>
                    <p style="color:#6b7280;">Keep brand, website, and contact information up to date.</p>
                    <a href="{{ route('company.profile.edit') }}" class="btn-primary" style="margin-top:10px;">Update profile</a>
                </div>
            </div>

            <div style="border:1px solid #e5e7eb;border-radius:12px;padding:20px;">
                <h3 class="primary-text" style="margin-bottom:12px;">Hiring health</h3>
                <ul style="margin:0;padding-left:18px;color:#4b5563;">
                    <li>5 interviews scheduled for this week</li>
                    <li>12 new applicants awaiting review</li>
                    <li>3 listings expiring soon — consider refreshing</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

