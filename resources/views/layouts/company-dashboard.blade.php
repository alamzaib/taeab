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

        <button class="dashboard-menu-toggle" id="dashboard-menu-toggle" aria-label="Toggle menu">
            <span>☰</span> Menu
        </button>
        <div class="dashboard-layout" style="display:flex; min-height:600px;">
            <div class="dashboard-sidebar" style="width:280px; background:#f8fafc; border-right:1px solid #e2e8f0; padding:24px 0;">
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
                    <a href="{{ route('company.notifications.index') }}" class="company-nav-item {{ $currentTab === 'notifications' ? 'active' : '' }}" style="position:relative;">
                        <span>🔔</span> Notifications
                        @if(isset($unreadNotificationCount) && $unreadNotificationCount > 0)
                            <span class="notification-badge">{{ $unreadNotificationCount }}</span>
                        @endif
                    </a>
                </nav>
            </div>
            <div class="dashboard-content" style="flex:1; padding:32px; overflow-y:auto;">
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
.company-nav-item {
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

    .company-nav-item {
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
        const navLinks = sidebar.querySelectorAll('.company-nav-item');
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

