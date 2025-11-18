@extends('layouts.app')

@section('title', 'Company Dashboard - Job Portal UAE')

@section('content')
@php
    use Illuminate\Support\Facades\Storage;
    $activeTab = request()->get('tab', 'overview');
@endphp
<div class="container company-dashboard" style="max-width:1400px;">
    <div class="card" style="padding:0; overflow:hidden;">
        @include('company.components.hero', [
            'heroTitle' => $company->company_name,
            'heroDescription' => 'Track active mandates, meet promising talent, and keep leadership in the loop from one streamlined workspace.',
            'heroActions' => [
                ['label' => 'Post new job', 'route' => route('company.jobs.create'), 'variant' => 'primary'],
                ['label' => 'View applicants', 'route' => route('company.dashboard', ['tab' => 'applications']), 'variant' => 'ghost'],
            ],
            'stats' => $stats
        ])

        <div style="display:flex; min-height:600px;">
            <!-- Left Sidebar Menu -->
            <div style="width:280px; background:#f8fafc; border-right:1px solid #e2e8f0; padding:24px 0;">
                <nav style="display:flex; flex-direction:column; gap:4px;">
                    <a href="{{ route('company.dashboard', ['tab' => 'overview']) }}" class="company-nav-item {{ $activeTab === 'overview' ? 'active' : '' }}">
                        <span>📊</span> Overview
                    </a>
                    <a href="{{ route('company.dashboard', ['tab' => 'profile']) }}" class="company-nav-item {{ $activeTab === 'profile' ? 'active' : '' }}">
                        <span>🏢</span> Company Profile
                    </a>
                    <a href="{{ route('company.dashboard', ['tab' => 'jobs']) }}" class="company-nav-item {{ $activeTab === 'jobs' ? 'active' : '' }}">
                        <span>💼</span> My Jobs
                    </a>
                    <a href="{{ route('company.dashboard', ['tab' => 'applications']) }}" class="company-nav-item {{ $activeTab === 'applications' ? 'active' : '' }}">
                        <span>👥</span> Applicants
                    </a>
                    <a href="{{ route('company.dashboard', ['tab' => 'messages']) }}" class="company-nav-item {{ $activeTab === 'messages' ? 'active' : '' }}">
                        <span>💬</span> Messages
                    </a>
                </nav>
            </div>

            <!-- Right Content Area -->
            <div style="flex:1; padding:32px; overflow-y:auto;">
                @if($activeTab === 'overview')
                    @include('company.dashboard.tabs.overview')
                @elseif($activeTab === 'profile')
                    @include('company.dashboard.tabs.profile')
                @elseif($activeTab === 'jobs')
                    @include('company.dashboard.tabs.jobs')
                @elseif($activeTab === 'applications')
                    @include('company.dashboard.tabs.applications')
                @elseif($activeTab === 'messages')
                    @include('company.dashboard.tabs.messages')
                @endif
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
</style>
@endsection
