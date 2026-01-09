@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="page-header">
        <h1 class="page-title">Halo, {{ auth()->user()->name }}! 👋</h1>
        <p class="page-subtitle">{{ now()->translatedFormat('l, d F Y') }}</p>
    </div>

    {{-- Stats --}}
    <div class="stat-grid">
        <div class="stat-card stat-danger">
            <div class="stat-value">{{ $stats['stok_kosong'] }}</div>
            <div class="stat-label">Stok Kosong</div>
        </div>
        <div class="stat-card stat-warning">
            <div class="stat-value">{{ $stats['stok_sedikit'] }}</div>
            <div class="stat-label">Stok Sedikit</div>
        </div>
        <div class="stat-card stat-info">
            <div class="stat-value">{{ $stats['order_draft'] }}</div>
            <div class="stat-label">Draft Order</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">{{ $stats['order_dikirim'] }}</div>
            <div class="stat-label">Order Dikirim</div>
        </div>
    </div>

    {{-- Main Menu --}}
    <div class="menu-grid">
        <a href="{{ route('penjaga.stok.index') }}" class="menu-item">
            <span class="menu-icon">📦</span>
            <span class="menu-label">Stok Barang</span>
        </a>
        <a href="{{ route('penjaga.order.index') }}" class="menu-item">
            <span class="menu-icon">🛒</span>
            <span class="menu-label">Order Barang</span>
        </a>
        <a href="{{ route('penjaga.pemasukan.create') }}" class="menu-item">
            <span class="menu-icon">💰</span>
            <span class="menu-label">Pemasukan</span>
        </a>
        <a href="{{ route('penjaga.pengeluaran.create') }}" class="menu-item">
            <span class="menu-icon">💸</span>
            <span class="menu-label">Pengeluaran</span>
        </a>
        <a href="{{ route('penjaga.keuangan.index') }}" class="menu-item menu-item-wide">
            <span class="menu-icon">📋</span>
            <span class="menu-label">Riwayat Keuangan</span>
        </a>
    </div>

    {{-- Quick Actions --}}
    @if($stats['order_dikirim'] > 0)
        <div class="card mt-3">
            <div class="card-header">
                <h3 class="card-title">⏳ Order Menunggu</h3>
            </div>
            <div class="card-body">
                <p class="text-muted mb-2">Ada {{ $stats['order_dikirim'] }} order yang sudah dikirim dan menunggu barang
                    datang.</p>
                <a href="{{ route('penjaga.order.index') }}?status=dikirim" class="btn btn-primary">
                    Lihat Order
                </a>
            </div>
        </div>
    @endif
@endsection