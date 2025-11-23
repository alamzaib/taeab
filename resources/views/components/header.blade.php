<header class="header">
    <div class="header-container">
        <div class="header-content">
            <a href="{{ url('/') }}" class="logo">
                @php
                    $settings = \App\Models\Setting::getAll();
                    $logoPath = !empty($settings['application_logo'])
                        ? Storage::url($settings['application_logo'])
                        : asset('images/logo.svg');
                @endphp
                <img src="{{ $logoPath }}" alt="Job Portal UAE" class="logo-img"
                    onerror="this.style.display='none'; this.nextElementSibling.style.display='inline';">
            </a>
            <nav class="main-nav">
                <a href="{{ url('/') }}" class="nav-link {{ request()->is('/') ? 'active' : '' }}">Home</a>
                <a href="{{ url('/jobs') }}" class="nav-link {{ request()->is('jobs*') ? 'active' : '' }}">Jobs</a>
                <a href="{{ url('/companies') }}"
                    class="nav-link {{ request()->is('companies*') ? 'active' : '' }}">Companies</a>
                <div class="account-dropdown">
                    <a href="#" class="nav-link account-link" style="position:relative;" id="account-link">
                        Account <i class="dropdown-icon">▼</i>
                        @if(isset($headerNotificationCount) && $headerNotificationCount > 0)
                            <span class="header-notification-badge" id="header-notification-badge" data-count="{{ $headerNotificationCount }}">{{ $headerNotificationCount }}</span>
                        @endif
                    </a>
                    <div class="dropdown-menu">
                        @auth('agent')
                            <a href="{{ route('agent.dashboard') }}" class="dropdown-item">Agent Dashboard</a>
                            <form action="{{ route('agent.logout') }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="dropdown-item logout-btn">Logout</button>
                            </form>
                        @endauth
                        @auth('seeker')
                            <a href="{{ route('seeker.dashboard') }}" class="dropdown-item">Seeker Dashboard</a>
                            <a href="{{ route('seeker.notifications.index') }}" class="dropdown-item" id="notifications-link">
                                Notifications
                                @if(isset($headerNotificationCount) && $headerNotificationCount > 0)
                                    <span style="background:#dc3545; color:white; border-radius:10px; padding:2px 6px; font-size:11px; margin-left:8px;">{{ $headerNotificationCount }}</span>
                                @endif
                            </a>
                            <form action="{{ route('seeker.logout') }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="dropdown-item logout-btn">Logout</button>
                            </form>
                        @endauth
                        @auth('company')
                            <a href="{{ route('company.dashboard') }}" class="dropdown-item">Company Dashboard</a>
                            <a href="{{ route('company.notifications.index') }}" class="dropdown-item" id="notifications-link">
                                Notifications
                                @if(isset($headerNotificationCount) && $headerNotificationCount > 0)
                                    <span style="background:#dc3545; color:white; border-radius:10px; padding:2px 6px; font-size:11px; margin-left:8px;">{{ $headerNotificationCount }}</span>
                                @endif
                            </a>
                            <form action="{{ route('company.logout') }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="dropdown-item logout-btn">Logout</button>
                            </form>
                        @endauth
                        @auth('admin')
                            <a href="{{ route('admin.dashboard') }}" class="dropdown-item">Admin Dashboard</a>
                            <form action="{{ route('admin.logout') }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="dropdown-item logout-btn">Logout</button>
                            </form>
                        @endauth
                        @if(!auth('seeker')->check() && !auth('company')->check() && !auth('agent')->check())
                            <a href="{{ route('seeker.login') }}" class="dropdown-item">Job Seeker Login</a>
                            <a href="{{ route('company.login') }}" class="dropdown-item">Company Login</a>
                            <a href="{{ route('agent.login') }}" class="dropdown-item">Agent Login</a>
                        @endif
                    </div>
                </div>
            </nav>
        </div>
    </div>
</header>

<style>
    .header {
        background-color: #235181;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        position: sticky;
        top: 0;
        z-index: 1000;
    }

    .header-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }

    .header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 0;
    }

    .logo {
        display: flex;
        align-items: center;
        gap: 12px;
        text-decoration: none;
        color: white;
        font-size: 20px;
        font-weight: bold;
    }

    .logo-img {
        height: 40px;
        width: auto;
    }

    .logo-text {
        color: white;
    }

    .main-nav {
        display: flex;
        align-items: center;
        gap: 30px;
    }

    .nav-link {
        color: white;
        text-decoration: none;
        font-size: 16px;
        transition: opacity 0.3s;
        padding: 8px 0;
    }

    .nav-link:hover,
    .nav-link.active {
        opacity: 0.8;
        border-bottom: 2px solid white;
    }

    .account-dropdown {
        position: relative;
    }

    .account-link {
        display: flex;
        align-items: center;
        gap: 5px;
        position: relative;
    }

    .dropdown-icon {
        font-size: 10px;
    }

    .header-notification-badge {
        position: absolute;
        top: -4px;
        right: -8px;
        background-color: #dc3545;
        color: white;
        border-radius: 50%;
        min-width: 18px;
        height: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        font-weight: 600;
        line-height: 1;
        border: 2px solid #235181;
        padding: 0 4px;
        cursor: pointer;
        transition: transform 0.2s;
    }

    .header-notification-badge:hover {
        transform: scale(1.1);
    }

    .dropdown-menu {
        display: none;
        position: absolute;
        top: 100%;
        right: 0;
        background: white;
        min-width: 200px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        border-radius: 5px;
        margin-top: -4px;
        overflow: hidden;
    }

    .account-dropdown:hover .dropdown-menu {
        display: block;
    }

    .dropdown-item {
        display: block;
        padding: 12px 20px;
        color: #333;
        text-decoration: none;
        transition: background-color 0.3s;
        border: none;
        background: none;
        width: 100%;
        text-align: left;
        cursor: pointer;
        font-size: 14px;
    }

    .dropdown-item:hover {
        background-color: #f5f5f5;
    }

    .logout-btn {
        color: #d32f2f;
    }

    .logout-btn:hover {
        background-color: #ffebee;
    }

    @media (max-width: 768px) {
        .main-nav {
            gap: 15px;
        }

        .nav-link {
            font-size: 14px;
        }

        .logo-text {
            display: none;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const badge = document.getElementById('header-notification-badge');
        const accountLink = document.getElementById('account-link');
        
        // Make badge clickable to go to notifications
        if (badge) {
            badge.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                @auth('seeker')
                    window.location.href = '{{ route("seeker.notifications.index") }}';
                @endauth
                @auth('company')
                    window.location.href = '{{ route("company.notifications.index") }}';
                @endauth
            });
        }

        // Function to update notification count
        function updateNotificationCount(newCount) {
            const badge = document.getElementById('header-notification-badge');
            const notificationsLink = document.getElementById('notifications-link');
            
            if (badge) {
                if (newCount > 0) {
                    badge.textContent = newCount;
                    badge.setAttribute('data-count', newCount);
                    badge.style.display = 'flex';
                    
                    // Update count in dropdown menu
                    if (notificationsLink) {
                        const span = notificationsLink.querySelector('span');
                        if (span) {
                            span.textContent = newCount;
                        } else {
                            const newSpan = document.createElement('span');
                            newSpan.style.cssText = 'background:#dc3545; color:white; border-radius:10px; padding:2px 6px; font-size:11px; margin-left:8px;';
                            newSpan.textContent = newCount;
                            notificationsLink.appendChild(newSpan);
                        }
                    }
                } else {
                    badge.style.display = 'none';
                    
                    // Remove count from dropdown menu
                    if (notificationsLink) {
                        const span = notificationsLink.querySelector('span');
                        if (span) {
                            span.remove();
                        }
                    }
                }
            }
        }

        // Listen for notification updates from other pages
        window.addEventListener('storage', function(e) {
            if (e.key === 'notificationCount') {
                updateNotificationCount(parseInt(e.newValue) || 0);
            }
        });

        // Check for updates periodically (every 30 seconds)
        setInterval(function() {
            @auth('seeker')
                fetch('{{ route("seeker.notifications.count") }}', {
                    headers: {'X-Requested-With': 'XMLHttpRequest'}
                })
                .then(response => response.json())
                .then(data => {
                    updateNotificationCount(data.count);
                    localStorage.setItem('notificationCount', data.count);
                })
                .catch(() => {});
            @endauth
            @auth('company')
                fetch('{{ route("company.notifications.count") }}', {
                    headers: {'X-Requested-With': 'XMLHttpRequest'}
                })
                .then(response => response.json())
                .then(data => {
                    updateNotificationCount(data.count);
                    localStorage.setItem('notificationCount', data.count);
                })
                .catch(() => {});
            @endauth
        }, 30000);

        // Expose update function globally
        window.updateNotificationCount = updateNotificationCount;
    });
</script>
