<nav class="bottom-nav">
    <div class="nav-items">
        <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <span class="nav-icon">🏠</span>
            <span class="nav-label">Home</span>
        </a>
        <a href="{{ route('penjaga.stok.index') }}"
            class="nav-item {{ request()->routeIs('penjaga.stok.*') ? 'active' : '' }}">
            <span class="nav-icon">📦</span>
            <span class="nav-label">Stok</span>
        </a>
        <a href="{{ route('penjaga.order.index') }}"
            class="nav-item {{ request()->routeIs('penjaga.order.*') ? 'active' : '' }}">
            <span class="nav-icon">🛒</span>
            <span class="nav-label">Order</span>
        </a>
        <a href="{{ route('penjaga.keuangan.index') }}"
            class="nav-item {{ request()->routeIs('penjaga.keuangan.*') || request()->routeIs('penjaga.pemasukan.*') || request()->routeIs('penjaga.pengeluaran.*') ? 'active' : '' }}">
            <span class="nav-icon">💰</span>
            <span class="nav-label">Keuangan</span>
        </a>
    </div>
</nav>