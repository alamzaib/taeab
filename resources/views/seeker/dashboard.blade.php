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

        <button class="dashboard-menu-toggle" id="dashboard-menu-toggle" aria-label="Toggle menu">
            <span>☰</span> Menu
        </button>
        <div class="dashboard-layout" style="display:flex; min-height:600px;">
            <!-- Left Sidebar Menu -->
            <div class="dashboard-sidebar" style="width:280px; background:#f8fafc; border-right:1px solid #e2e8f0; padding:24px 0;">
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
                    <a href="{{ route('seeker.notifications.index') }}" class="seeker-nav-item {{ $activeTab === 'notifications' ? 'active' : '' }}" style="position:relative;">
                        <span>🔔</span> Notifications
                        @if(isset($unreadNotificationCount) && $unreadNotificationCount > 0)
                            <span class="notification-badge">{{ $unreadNotificationCount }}</span>
                        @endif
                    </a>
                    <a href="{{ route('seeker.dashboard', ['tab' => 'resume']) }}" class="seeker-nav-item {{ $activeTab === 'resume' ? 'active' : '' }}">
                        <span>📄</span> Resume
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
            <div class="dashboard-content" style="flex:1; padding:32px; overflow-y:auto;">
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
.seeker-nav-item {
    position:relative;
}
.notification-badge {
    position:absolute;
    top:8px;
    right:16px;
    background:#dc3545;
    color:white;
    border-radius:50%;
    width:20px;
    height:20px;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:11px;
    font-weight:600;
}

.dashboard-menu-toggle {
    display: none;
    position: fixed;
    top: 70px;
    left: 20px;
    z-index: 999;
    background: #235181;
    color: white;
    border: none;
    padding: 12px 20px;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
    font-weight: 500;
    box-shadow: 0 2px 8px rgba(0,0,0,0.2);
}

.dashboard-menu-toggle span {
    margin-right: 8px;
    font-size: 20px;
}

@media (max-width: 768px) {
    .dashboard-menu-toggle {
        display: block;
    }

    .dashboard-layout {
        flex-direction: column;
    }

    .dashboard-sidebar {
        position: fixed;
        top: 0;
        left: -100%;
        width: 280px;
        height: 100vh;
        z-index: 998;
        transition: left 0.3s ease;
        box-shadow: 2px 0 10px rgba(0,0,0,0.2);
        overflow-y: auto;
    }

    .dashboard-sidebar.active {
        left: 0;
    }

    .dashboard-sidebar::before {
        content: '';
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        display: none;
        z-index: -1;
    }

    .dashboard-sidebar.active::before {
        display: block;
    }

    .dashboard-content {
        width: 100%;
        padding: 20px 15px !important;
    }

    .container {
        padding: 10px !important;
    }

    .card {
        padding: 15px !important;
    }

    div[style*="padding:32px"] {
        padding: 20px 15px !important;
    }

    div[style*="padding:24px"] {
        padding: 15px !important;
    }

    h1[style*="font-size:36px"] {
        font-size: 24px !important;
    }

    div[style*="flex:0 0 220px"] {
        flex: 1 1 100% !important;
        margin-top: 20px;
    }
}

@media (max-width: 480px) {
    .dashboard-sidebar {
        width: 100%;
    }

    .dashboard-menu-toggle {
        left: 10px;
        top: 60px;
        padding: 10px 15px;
        font-size: 14px;
    }

    .seeker-nav-item {
        padding: 10px 20px !important;
        font-size: 14px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const menuToggle = document.getElementById('dashboard-menu-toggle');
    const sidebar = document.querySelector('.dashboard-sidebar');
    
    if (menuToggle && sidebar) {
        menuToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            sidebar.classList.toggle('active');
            document.body.style.overflow = sidebar.classList.contains('active') ? 'hidden' : '';
        });

        // Close sidebar when clicking outside
        document.addEventListener('click', function(e) {
            if (!sidebar.contains(e.target) && !menuToggle.contains(e.target)) {
                sidebar.classList.remove('active');
                document.body.style.overflow = '';
            }
        });

        // Close sidebar when clicking on a nav link
        const navLinks = sidebar.querySelectorAll('.seeker-nav-item');
        navLinks.forEach(link => {
            link.addEventListener('click', function() {
                sidebar.classList.remove('active');
                document.body.style.overflow = '';
            });
        });
    }
});
</script>
@endsection
