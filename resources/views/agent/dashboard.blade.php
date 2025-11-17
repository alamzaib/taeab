@extends('layouts.app')

@section('title', 'Agent Dashboard - Job Portal UAE')

@section('content')
<div class="container">
    <div class="card" style="padding:0;">
        <div style="background:linear-gradient(135deg,#1d4a73,#132c46);color:white;padding:30px;border-radius:18px 18px 0 0;">
            <p style="margin:0;opacity:.8;">Hello,</p>
            <h1 style="margin:5px 0 0;font-size:32px;">{{ $agent->name }}</h1>
            <p style="margin:8px 0 0;color:rgba(255,255,255,.85);">Monitor hiring performance and manage client roles.</p>
        </div>

        <div style="padding:30px;display:grid;gap:20px;">
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:15px;">
                <div class="card" style="background:#eef2ff;">
                    <p style="color:#4c51bf;margin-bottom:6px;">Total jobs posted</p>
                    <p style="font-size:32px;font-weight:700;color:#312e81;">{{ $stats['total_jobs'] }}</p>
                </div>
                <div class="card" style="background:#e0f2fe;">
                    <p style="color:#0369a1;margin-bottom:6px;">Active jobs</p>
                    <p style="font-size:32px;font-weight:700;color:#0c4a6e;">{{ $stats['active_jobs'] }}</p>
                </div>
                <div class="card" style="background:#fce7f3;">
                    <p style="color:#be185d;margin-bottom:6px;">Pending approvals</p>
                    <p style="font-size:32px;font-weight:700;color:#9d174d;">3</p>
                </div>
            </div>

            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(230px,1fr));gap:15px;">
                <div class="card" style="border:1px dashed #d1d5db;">
                    <h3 class="primary-text" style="margin-bottom:8px;">Post a role</h3>
                    <p style="color:#6b7280;">Create new listings for your clients in minutes.</p>
                    <a href="{{ route('agent.jobs.create') }}" class="btn-primary" style="margin-top:10px;">Create job</a>
                </div>
                <div class="card" style="border:1px dashed #d1d5db;">
                    <h3 class="primary-text" style="margin-bottom:8px;">Manage listings</h3>
                    <p style="color:#6b7280;">Edit, pause, or close positions from your pipeline.</p>
                    <a href="{{ route('agent.jobs.index') }}" class="btn-primary" style="margin-top:10px;">Open dashboard</a>
                </div>
                <div class="card" style="border:1px dashed #d1d5db;">
                    <h3 class="primary-text" style="margin-bottom:8px;">Profile & branding</h3>
                    <p style="color:#6b7280;">Keep your contact and agency details fresh.</p>
                    <a href="{{ route('agent.profile.edit') }}" class="btn-primary" style="margin-top:10px;">Update profile</a>
                </div>
            </div>

            <div style="border:1px solid #e5e7eb;border-radius:12px;padding:20px;">
                <h3 class="primary-text" style="margin-bottom:12px;">Next steps</h3>
                <ol style="margin:0;padding-left:18px;color:#4b5563;">
                    <li>Review applications submitted in the last 24 hours.</li>
                    <li>Share shortlists with clients via the manage jobs screen.</li>
                    <li>Highlight key roles on social media for extra reach.</li>
                </ol>
            </div>
        </div>
    </div>
</div>
@endsection

