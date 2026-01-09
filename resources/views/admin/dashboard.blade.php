@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
    <div class="page-header">
        <h1 class="page-title">Dashboard Pemilik 📊</h1>
        <p class="page-subtitle">{{ now()->translatedFormat('l, d F Y') }}</p>
    </div>

    {{-- Profit/Loss Card --}}
    <div class="profit-card {{ $monthlyProfit >= 0 ? 'profit' : 'loss' }} mb-3">
        <div class="profit-label">{{ $monthlyProfit >= 0 ? 'UNTUNG' : 'RUGI' }} BULAN INI</div>
        <div class="profit-value money">
            Rp {{ number_format(abs($monthlyProfit), 0, ',', '.') }}
        </div>
    </div>

    {{-- Today Stats --}}
    <h3 class="mb-2" style="font-size: 1rem; color: var(--text-muted);">HARI INI</h3>
    <div class="stat-grid mb-3">
        <div class="stat-card stat-success">
            <div class="stat-value money">Rp {{ number_format($todayIncome, 0, ',', '.') }}</div>
            <div class="stat-label">Pemasukan</div>
        </div>
        <div class="stat-card stat-danger">
            <div class="stat-value money">Rp {{ number_format($todayExpense, 0, ',', '.') }}</div>
            <div class="stat-label">Pengeluaran</div>
        </div>
    </div>

    {{-- Stock Summary --}}
    <h3 class="mb-2" style="font-size: 1rem; color: var(--text-muted);">STATUS STOK</h3>
    <div class="stat-grid mb-3">
        <div class="stat-card stat-danger">
            <div class="stat-value">{{ $stockStats['kosong'] }}</div>
            <div class="stat-label">Kosong</div>
        </div>
        <div class="stat-card stat-warning">
            <div class="stat-value">{{ $stockStats['sedikit'] }}</div>
            <div class="stat-label">Sedikit</div>
        </div>
        <div class="stat-card stat-info">
            <div class="stat-value">{{ $stockStats['cukup'] }}</div>
            <div class="stat-label">Cukup</div>
        </div>
        <div class="stat-card stat-success">
            <div class="stat-value">{{ $stockStats['banyak'] }}</div>
            <div class="stat-label">Banyak</div>
        </div>
    </div>

    {{-- Monthly Summary --}}
    <div class="card mb-3">
        <div class="card-header">
            <h3 class="card-title">📅 Ringkasan Bulan Ini</h3>
        </div>
        <div class="card-body">
            <div class="flex-between mb-2">
                <span class="text-muted">Total Pemasukan</span>
                <span class="money money-positive">Rp {{ number_format($monthlyIncome, 0, ',', '.') }}</span>
            </div>
            <div class="flex-between mb-2">
                <span class="text-muted">Total Pengeluaran</span>
                <span class="money money-negative">Rp {{ number_format($monthlyExpense, 0, ',', '.') }}</span>
            </div>
            <hr style="border-color: var(--border-color); margin: 1rem 0;">
            <div class="flex-between">
                <span class="font-semibold">{{ $monthlyProfit >= 0 ? 'Keuntungan' : 'Kerugian' }}</span>
                <span class="money {{ $monthlyProfit >= 0 ? 'money-positive' : 'money-negative' }}"
                    style="font-weight: 700;">
                    Rp {{ number_format(abs($monthlyProfit), 0, ',', '.') }}
                </span>
            </div>
        </div>
    </div>

    {{-- Active Orders --}}
    @if($activeOrders->count() > 0)
        <div class="card mb-3">
            <div class="card-header">
                <h3 class="card-title">📦 Order Aktif</h3>
                <a href="{{ route('admin.order.index') }}" class="btn btn-ghost">Lihat Semua</a>
            </div>
            <div class="card-body">
                @foreach($activeOrders as $order)
                    <div class="order-item">
                        <div class="order-item-info">
                            <div class="order-item-name">{{ $order->order_number }}</div>
                            <div class="order-item-qty">
                                {{ $order->items->count() }} item • {{ $order->user->name }}
                            </div>
                        </div>
                        <span class="status-badge status-{{ $order->status }}">
                            {{ $order->status_info['label'] }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Recent Activity --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">📋 Aktivitas Terbaru</h3>
            <a href="{{ route('admin.log.index') }}" class="btn btn-ghost">Lihat Semua</a>
        </div>
        <div class="card-body">
            @if($recentLogs->count() > 0)
                <div class="activity-list">
                    @foreach($recentLogs as $log)
                        <div class="activity-item">
                            <div class="activity-icon">
                                {{ $log->action_info['icon'] }}
                            </div>
                            <div class="activity-content">
                                <div class="activity-text">{{ $log->description }}</div>
                                <div class="activity-meta">
                                    {{ $log->user->name }} • {{ $log->created_at->diffForHumans() }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-icon">📋</div>
                    <div class="empty-text">Belum ada aktivitas</div>
                </div>
            @endif
        </div>
    </div>
@endsection