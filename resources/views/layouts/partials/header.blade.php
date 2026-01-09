<header class="app-header">
    <div class="header-content">
        <a href="{{ route('dashboard') }}" class="app-logo">
            <div class="app-logo-icon">🏪</div>
            <span class="app-logo-text">Warung Madura</span>
        </a>

        <div class="header-actions">
            <div class="user-menu">
                <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="user-menu-btn">
                        <div class="user-avatar">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <span>Keluar</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>