<aside class="sidebar">
    <div class="sidebar-logo header-content">
        <div class="app-logo">
            <div class="app-logo-icon">🏪</div>
            <span class="app-logo-text">Warung Madura</span>
        </div>
        <button id="sidebarClose" class="btn btn-ghost btn-icon visible-mobile text-muted hover:text-white">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>

    <nav class="sidebar-nav">
        <div class="sidebar-nav-group">
            <div class="sidebar-nav-title">Dashboard</div>
            <a href="{{ route('dashboard') }}"
                class="sidebar-nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <span class="sidebar-nav-icon">📊</span>
                <span>Dashboard</span>
            </a>
        </div>

        <div class="sidebar-nav-group">
            <div class="sidebar-nav-title">Monitoring</div>
            <a href="{{ route('admin.order.index') }}"
                class="sidebar-nav-item {{ request()->routeIs('admin.order.*') ? 'active' : '' }}">
                <span class="sidebar-nav-icon">📦</span>
                <span>Order</span>
            </a>
            <a href="{{ route('admin.log.index') }}"
                class="sidebar-nav-item {{ request()->routeIs('admin.log.*') ? 'active' : '' }}">
                <span class="sidebar-nav-icon">📋</span>
                <span>Log Aktivitas</span>
            </a>
        </div>

        <div class="sidebar-nav-group">
            <div class="sidebar-nav-title">Laporan</div>
            <a href="{{ route('admin.laporan.harian') }}"
                class="sidebar-nav-item {{ request()->routeIs('admin.laporan.harian') ? 'active' : '' }}">
                <span class="sidebar-nav-icon">📅</span>
                <span>Laporan Harian</span>
            </a>
            <a href="{{ route('admin.laporan.bulanan') }}"
                class="sidebar-nav-item {{ request()->routeIs('admin.laporan.bulanan') ? 'active' : '' }}">
                <span class="sidebar-nav-icon">📈</span>
                <span>Laporan Bulanan</span>
            </a>
        </div>

        <div class="sidebar-nav-group">
            <div class="sidebar-nav-title">Master Data</div>
            <a href="{{ route('admin.kategori.index') }}"
                class="sidebar-nav-item {{ request()->routeIs('admin.kategori.*') ? 'active' : '' }}">
                <span class="sidebar-nav-icon">🏷️</span>
                <span>Kategori</span>
            </a>
            <a href="{{ route('admin.barang.index') }}"
                class="sidebar-nav-item {{ request()->routeIs('admin.barang.*') ? 'active' : '' }}">
                <span class="sidebar-nav-icon">📦</span>
                <span>Barang</span>
            </a>
            <a href="{{ route('admin.user.index') }}"
                class="sidebar-nav-item {{ request()->routeIs('admin.user.*') ? 'active' : '' }}">
                <span class="sidebar-nav-icon">👥</span>
                <span>Pengguna</span>
            </a>
        </div>
    </nav>

    <div class="sidebar-user">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-ghost btn-block">
                <span>🚪</span>
                <span>Keluar</span>
            </button>
        </form>
    </div>
</aside>