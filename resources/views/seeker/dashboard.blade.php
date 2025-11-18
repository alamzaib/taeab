@extends('layouts.app')

@section('title', 'Job Seeker Dashboard - Job Portal UAE')

@section('content')
@php
    use Illuminate\Support\Facades\Storage;
    $firstName = trim($seeker->name);
    if (str_contains($firstName, ' ')) {
        $firstName = explode(' ', $firstName)[0];
    }
    $activeTab = request()->get('tab', 'overview');
@endphp
<div class="container" style="max-width:1400px;">
    <div class="card" style="padding:0; overflow:hidden;">
        <div style="background:{{ $seeker->profile_cover_style }}; padding:32px; position:relative;">
            <div style="position:absolute; inset:0; background:linear-gradient(120deg, rgba(35,81,129,.9), rgba(24,56,94,.6)); opacity:.65;"></div>
            <div style="position:relative; z-index:2; color:white; display:flex; flex-wrap:wrap; gap:24px; align-items:flex-end;">
                <div style="flex:1 1 260px;">
                    <p style="margin:0; letter-spacing:.05em; text-transform:uppercase; font-size:12px; opacity:.85;">Seeker HQ</p>
                    <h1 style="margin:6px 0 10px; font-size:36px;">Hi {{ $firstName }}, ready for the next big move?</h1>
                    <p style="max-width:520px; color:rgba(255,255,255,.85);">Your dashboard keeps applications, saved roles, and documents in sync so recruiters instantly understand your story.</p>
                    <div style="margin-top:14px; display:flex; flex-wrap:wrap; gap:18px; align-items:center;">
                        <span style="font-size:13px; letter-spacing:.08em; text-transform:uppercase;">Profile ID: <strong>{{ $seeker->unique_code }}</strong></span>
                        <span style="font-size:13px;">Last refreshed: {{ optional($seeker->profile_refreshed_at)->diffForHumans() ?? 'Never' }}</span>
                        <form action="{{ route('seeker.profile.refresh') }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-light" style="background:rgba(255,255,255,.15); border:1px solid rgba(255,255,255,.4); color:white;">Refresh profile</button>
                        </form>
                    </div>
                </div>
                <div style="flex:0 0 220px; background:rgba(3,7,18,.45); backdrop-filter:blur(8px); padding:18px 22px; border-radius:16px;">
                    <p style="margin:0; text-transform:uppercase; letter-spacing:.08em; font-size:11px; color:#c7d2fe;">Profile snapshot</p>
                    <div style="display:flex; align-items:center; gap:14px; margin-top:14px;">
                        <div style="width:72px; height:72px; border-radius:50%; border:3px solid rgba(255,255,255,.45); overflow:hidden;">
                            <img src="{{ $seeker->profile_photo_url }}" alt="Profile" style="width:100%; height:100%; object-fit:cover;">
                        </div>
                        <div>
                            <strong style="display:block; font-size:18px;">{{ $seeker->name }}</strong>
                            <span style="font-size:13px; color:rgba(255,255,255,.75);">{{ $seeker->current_company ?? 'No company listed' }}</span>
                        </div>
                    </div>
                    <div style="margin-top:16px;">
                        <div style="display:flex; justify-content:space-between; font-size:12px; margin-bottom:4px;">
                            <span>Profile completeness</span>
                            <span>80%</span>
                        </div>
                        <div style="height:6px; border-radius:999px; background:rgba(255,255,255,.25); overflow:hidden;">
                            <div style="width:80%; background:#22c55e; height:100%;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div style="display:flex; min-height:600px;">
            <!-- Left Sidebar Menu -->
            <div style="width:280px; background:#f8fafc; border-right:1px solid #e2e8f0; padding:24px 0;">
                <nav style="display:flex; flex-direction:column; gap:4px;">
                    <a href="{{ route('seeker.dashboard', ['tab' => 'overview']) }}" class="seeker-nav-item {{ $activeTab === 'overview' ? 'active' : '' }}">
                        <span>📊</span> Overview
                    </a>
                    <a href="{{ route('seeker.dashboard', ['tab' => 'profile']) }}" class="seeker-nav-item {{ $activeTab === 'profile' ? 'active' : '' }}">
                        <span>👤</span> My Profile
                    </a>
                    <a href="{{ route('seeker.dashboard', ['tab' => 'applications']) }}" class="seeker-nav-item {{ $activeTab === 'applications' ? 'active' : '' }}">
                        <span>📝</span> Applications
                    </a>
                    <a href="{{ route('seeker.dashboard', ['tab' => 'messages']) }}" class="seeker-nav-item {{ $activeTab === 'messages' ? 'active' : '' }}">
                        <span>💬</span> Messages
                    </a>
                    <a href="{{ route('seeker.dashboard', ['tab' => 'resume']) }}" class="seeker-nav-item {{ $activeTab === 'resume' ? 'active' : '' }}">
                        <span>📄</span> Documents
                    </a>
                    <a href="{{ route('seeker.dashboard', ['tab' => 'resume-builder']) }}" class="seeker-nav-item {{ $activeTab === 'resume-builder' ? 'active' : '' }}">
                        <span>🧱</span> Build Resume
                    </a>
                    <a href="{{ route('seeker.dashboard', ['tab' => 'favorites']) }}" class="seeker-nav-item {{ $activeTab === 'favorites' ? 'active' : '' }}">
                        <span>⭐</span> Favourite Jobs
                    </a>
                </nav>
            </div>

            <!-- Right Content Area -->
            <div style="flex:1; padding:32px; overflow-y:auto;">
                @if($activeTab === 'overview')
                    @include('seeker.dashboard.tabs.overview')
                @elseif($activeTab === 'profile')
                    @include('seeker.dashboard.tabs.profile')
                @elseif($activeTab === 'applications')
                    @include('seeker.dashboard.tabs.applications')
                @elseif($activeTab === 'messages')
                    @include('seeker.dashboard.tabs.messages')
                @elseif($activeTab === 'resume')
                    @include('seeker.dashboard.tabs.resume')
                @elseif($activeTab === 'resume-builder')
                    @include('seeker.dashboard.tabs.resume-builder')
                @elseif($activeTab === 'favorites')
                    @include('seeker.dashboard.tabs.favorites')
                @endif
            </div>
        </div>
    </div>
</div>

<style>
.seeker-nav-item {
    display:flex;
    align-items:center;
    gap:12px;
    padding:12px 24px;
    color:#475569;
    text-decoration:none;
    font-weight:500;
    transition:all 0.2s;
    border-left:3px solid transparent;
}
.seeker-nav-item:hover {
    background:#f1f5f9;
    color:#235181;
}
.seeker-nav-item.active {
    background:#eef2ff;
    color:#235181;
    border-left-color:#235181;
    font-weight:600;
}
.seeker-nav-item span {
    font-size:18px;
}
</style>
@endsection
