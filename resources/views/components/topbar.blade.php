<div class="topbar">
    <div class="topbar-brand">
        <a href="{{ route('dashboard') }}" class="topbar-logo">
            <i class="fas fa-store"></i> HK LOVE
        </a>
    </div>

    <div class="topbar-user">
        <div class="topbar-user-info">
            <div class="topbar-user-name">{{ Auth::user()->name ?? 'Admin User' }}</div>
            <div class="topbar-user-role">{{ Auth::user()->role ?? 'Administrator' }}</div>
        </div>
        <div class="dropdown">
            <a href="#" class="topbar-user-avatar dropdown-toggle" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="{{ Auth::user()->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name ?? 'Admin User') . '&background=random' }}" alt="User Avatar">
            </a>

            <!-- Dropdown menu -->
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                <li>
                    <a class="dropdown-item" href="{{ route('profile.show') }}"
                       onclick="event.preventDefault(); window.location.href='{{ route('profile.show') }}';">
                        <i class="fas fa-user me-2"></i> Thông tin cá nhân
                    </a>
                </li>
                <li>
                    <form method="POST" action="{{ route('logout') }}" id="logout-form">
                        @csrf
                        <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt me-2"></i> Đăng xuất
                        </a>
                    </form>
                </li>
            </ul>
        </div>
        <button class="mobile-toggle" id="mobile-toggle">
            <i class="fas fa-bars"></i>
        </button>
    </div>
</div>
