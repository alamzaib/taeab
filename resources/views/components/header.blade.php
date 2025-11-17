<header class="header">
    <div class="header-container">
        <div class="header-content">
            <a href="{{ url('/') }}" class="logo">
                @php
                    $settings = \App\Models\Setting::getAll();
                    $logoPath = !empty($settings['application_logo']) ? Storage::url($settings['application_logo']) : asset('images/logo.svg');
                @endphp
                <img src="{{ $logoPath }}" alt="Job Portal UAE" class="logo-img" onerror="this.style.display='none'; this.nextElementSibling.style.display='inline';">
                <span class="logo-text">Job Portal UAE</span>
            </a>
            <nav class="main-nav">
                <a href="{{ url('/') }}" class="nav-link {{ request()->is('/') ? 'active' : '' }}">Home</a>
                <a href="{{ url('/jobs') }}" class="nav-link {{ request()->is('jobs*') ? 'active' : '' }}">Jobs</a>
                <a href="{{ url('/companies') }}" class="nav-link {{ request()->is('companies*') ? 'active' : '' }}">Companies</a>
                <div class="account-dropdown">
                    <a href="#" class="nav-link account-link">
                        Account <i class="dropdown-icon">▼</i>
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
                            <form action="{{ route('seeker.logout') }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="dropdown-item logout-btn">Logout</button>
                            </form>
                        @endauth
                        @auth('company')
                            <a href="{{ route('company.dashboard') }}" class="dropdown-item">Company Dashboard</a>
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
                        @guest
                            <a href="{{ route('seeker.login') }}" class="dropdown-item">Job Seeker Login</a>
                            <a href="{{ route('company.login') }}" class="dropdown-item">Company Login</a>
                            <a href="{{ route('agent.login') }}" class="dropdown-item">Agent Login</a>
                            <a href="{{ route('admin.login') }}" class="dropdown-item">Admin Login</a>
                        @endguest
                    </div>
                </div>
            </nav>
        </div>
    </div>
</header>

<style>
.header {
    background-color: #235181;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
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
}

.dropdown-icon {
    font-size: 10px;
}

.dropdown-menu {
    display: none;
    position: absolute;
    top: 100%;
    right: 0;
    background: white;
    min-width: 200px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    border-radius: 5px;
    margin-top: 10px;
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

