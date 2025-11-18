@extends('layouts.app')

@section('title', 'Agent Dashboard - Job Portal UAE')

@section('content')
@php
    $activeTab = request()->get('tab', 'overview');
@endphp
<div class="container agent-dashboard" style="max-width:1400px;">
    <div class="card" style="padding:0; overflow:hidden;">
        <div style="background:linear-gradient(135deg,#0f172a,#1d4a73); padding:32px; color:white;">
            <div style="position:absolute; inset:0; background:linear-gradient(120deg, rgba(15,23,42,.9), rgba(29,74,115,.6)); opacity:.65;"></div>
            <div style="position:relative; z-index:2; display:flex; flex-wrap:wrap; gap:24px; align-items:flex-end;">
                <div style="flex:1 1 320px;">
                    <p style="margin:0; text-transform:uppercase; letter-spacing:.08em; font-size:11px; opacity:.8;">Agency cockpit</p>
                    <h1 style="margin:10px 0 12px; font-size:34px;">Welcome back, {{ $agent->name }}</h1>
                    <p style="max-width:520px; color:rgba(255,255,255,.78);">Stay on top of every client search, accelerate interviews, and collaborate with employers without leaving your dashboard.</p>
                    <div style="margin-top:18px; display:flex; gap:12px; flex-wrap:wrap;">
                        <a href="{{ route('agent.jobs.create') }}" class="btn-primary" style="background:white;color:#0f172a;">Create brief</a>
                        <a href="{{ route('agent.dashboard', ['tab' => 'jobs']) }}" class="btn btn-light" style="background:rgba(255,255,255,.12); color:white; border:1px solid rgba(255,255,255,.35);">Manage pipeline</a>
                    </div>
                    <div style="margin-top:14px; font-size:13px; letter-spacing:.08em; text-transform:uppercase;">
                        Profile ID: <strong>{{ $agent->unique_code }}</strong>
                    </div>
                </div>
                <div style="flex:0 0 260px; background:rgba(15,23,42,.7); border-radius:18px; padding:20px;">
                    <p style="margin:0; text-transform:uppercase; letter-spacing:.1em; font-size:11px; color:#c7d2fe;">This week</p>
                    <div style="display:flex; justify-content:space-between; margin-top:14px;">
                        <div>
                            <strong style="display:block; font-size:28px;">{{ $stats['total_jobs'] }}</strong>
                            <span style="font-size:13px; color:#e2e8f0;">Jobs owned</span>
                        </div>
                        <div>
                            <strong style="display:block; font-size:28px;">{{ $stats['active_jobs'] }}</strong>
                            <span style="font-size:13px; color:#e2e8f0;">Live roles</span>
                        </div>
                    </div>
                    <div style="margin-top:16px;">
                        <div style="display:flex; justify-content:space-between; font-size:12px;">
                            <span>Client satisfaction</span>
                            <span>92%</span>
                        </div>
                        <div style="height:6px; border-radius:999px; background:rgba(255,255,255,.25); overflow:hidden; margin-top:6px;">
                            <div style="width:92%; background:#4ade80; height:100%;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div style="display:flex; min-height:600px;">
            <!-- Left Sidebar Menu -->
            <div style="width:280px; background:#f8fafc; border-right:1px solid #e2e8f0; padding:24px 0;">
                <nav style="display:flex; flex-direction:column; gap:4px;">
                    <a href="{{ route('agent.dashboard', ['tab' => 'overview']) }}" class="agent-nav-item {{ $activeTab === 'overview' ? 'active' : '' }}">
                        <span>📊</span> Overview
                    </a>
                    <a href="{{ route('agent.dashboard', ['tab' => 'profile']) }}" class="agent-nav-item {{ $activeTab === 'profile' ? 'active' : '' }}">
                        <span>👤</span> My Profile
                    </a>
                    <a href="{{ route('agent.dashboard', ['tab' => 'jobs']) }}" class="agent-nav-item {{ $activeTab === 'jobs' ? 'active' : '' }}">
                        <span>💼</span> My Jobs
                    </a>
                </nav>
            </div>

            <!-- Right Content Area -->
            <div style="flex:1; padding:32px; overflow-y:auto;">
                @if($activeTab === 'overview')
                    @include('agent.dashboard.tabs.overview')
                @elseif($activeTab === 'profile')
                    @include('agent.dashboard.tabs.profile')
                @elseif($activeTab === 'jobs')
                    @include('agent.dashboard.tabs.jobs')
                @endif
            </div>
        </div>
    </div>
</div>

<style>
.agent-nav-item {
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
.agent-nav-item:hover {
    background:#f1f5f9;
    color:#235181;
}
.agent-nav-item.active {
    background:#eef2ff;
    color:#235181;
    border-left-color:#235181;
    font-weight:600;
}
.agent-nav-item span {
    font-size:18px;
}
.agent-dashboard .btn-primary,
.agent-dashboard .btn-primary:visited {
    color:#fff !important;
}
.agent-dashboard .btn-primary:hover {
    color:#fff;
}
</style>
@endsection
