@extends('layouts.app')

@section('content')
@php
    $hero = $hero ?? [];
    $heroTitle = $hero['title'] ?? ($company->company_name ?? 'Company Dashboard');
    $heroDescription = $hero['description'] ?? 'Monitor hiring performance and launch new roles faster.';
    $heroActions = $hero['actions'] ?? [];
    $heroStats = $hero['stats'] ?? $stats ?? ['active_jobs' => $company->jobs()->where('status', 'published')->count()];
    $currentTab = $currentTab ?? (function () {
        if (request()->routeIs('company.dashboard')) {
            return request('tab', 'overview');
        }
        if (request()->routeIs('company.profile.*')) {
            return 'profile';
        }
        if (request()->routeIs('company.jobs.*')) {
            return 'jobs';
        }
        if (request()->routeIs('company.applications.*')) {
            return request()->routeIs('company.applications.show') ? 'messages' : 'applications';
        }
        return 'overview';
    })();
@endphp
<div class="container company-dashboard" style="max-width:1400px;">
    <div class="card" style="padding:0; overflow:hidden;">
        @include('company.components.hero', [
            'heroTitle' => $heroTitle,
            'heroDescription' => $heroDescription,
            'heroActions' => $heroActions,
            'stats' => $heroStats
        ])

        <div style="display:flex; min-height:600px;">
            <div style="width:280px; background:#f8fafc; border-right:1px solid #e2e8f0; padding:24px 0;">
                <nav style="display:flex; flex-direction:column; gap:4px;">
                    <a href="{{ route('company.dashboard', ['tab' => 'overview']) }}" class="company-nav-item {{ $currentTab === 'overview' ? 'active' : '' }}">
                        <span>📊</span> Overview
                    </a>
                    <a href="{{ route('company.dashboard', ['tab' => 'profile']) }}" class="company-nav-item {{ $currentTab === 'profile' ? 'active' : '' }}">
                        <span>🏢</span> Company Profile
                    </a>
                    <a href="{{ route('company.dashboard', ['tab' => 'jobs']) }}" class="company-nav-item {{ $currentTab === 'jobs' ? 'active' : '' }}">
                        <span>💼</span> My Jobs
                    </a>
                    <a href="{{ route('company.dashboard', ['tab' => 'applications']) }}" class="company-nav-item {{ $currentTab === 'applications' ? 'active' : '' }}">
                        <span>👥</span> Applicants
                    </a>
                    <a href="{{ route('company.dashboard', ['tab' => 'messages']) }}" class="company-nav-item {{ $currentTab === 'messages' ? 'active' : '' }}">
                        <span>💬</span> Messages
                    </a>
                </nav>
            </div>
            <div style="flex:1; padding:32px; overflow-y:auto;">
                @yield('dashboard-content')
            </div>
        </div>
    </div>
</div>
<style>
.company-nav-item {
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
.company-nav-item:hover {
    background:#f1f5f9;
    color:#235181;
}
.company-nav-item.active {
    background:#eef2ff;
    color:#235181;
    border-left-color:#235181;
    font-weight:600;
}
.company-nav-item span {
    font-size:18px;
}
.company-dashboard a {
    color: #235181;
    text-decoration: none;
}
.company-dashboard a:hover {
    text-decoration: underline;
}
.company-dashboard .btn-primary,
.company-dashboard .btn-primary:visited {
    background-color: #235181 !important;
    color: #fff !important;
    border-color: #235181 !important;
}
.company-dashboard .btn-primary:hover {
    background-color: #1a3d63 !important;
    color: #fff !important;
    border-color: #1a3d63 !important;
}
.company-dashboard .btn-primary:focus,
.company-dashboard .btn-primary:active {
    background-color: #1a3d63 !important;
    color: #fff !important;
    border-color: #1a3d63 !important;
}
</style>
@endsection

