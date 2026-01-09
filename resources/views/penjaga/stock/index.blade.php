@extends('layouts.app')

@section('title', 'Stok Barang')

@section('content')
    <div class="page-header">
        <h1 class="page-title">📦 Stok Barang</h1>
        <p class="page-subtitle">Pilih kategori untuk melihat barang</p>
    </div>

    {{-- Stock Summary --}}
    <div class="stat-grid mb-3">
        <a href="{{ route('penjaga.stok.status', 'kosong') }}" class="stat-card stat-danger" style="text-decoration: none;">
            <div class="stat-value">{{ $stockSummary['kosong'] }}</div>
            <div class="stat-label">Kosong</div>
        </a>
        <a href="{{ route('penjaga.stok.status', 'sedikit') }}" class="stat-card stat-warning"
            style="text-decoration: none;">
            <div class="stat-value">{{ $stockSummary['sedikit'] }}</div>
            <div class="stat-label">Sedikit</div>
        </a>
        <a href="{{ route('penjaga.stok.status', 'cukup') }}" class="stat-card stat-info" style="text-decoration: none;">
            <div class="stat-value">{{ $stockSummary['cukup'] }}</div>
            <div class="stat-label">Cukup</div>
        </a>
        <a href="{{ route('penjaga.stok.status', 'banyak') }}" class="stat-card stat-success"
            style="text-decoration: none;">
            <div class="stat-value">{{ $stockSummary['banyak'] }}</div>
            <div class="stat-label">Banyak</div>
        </a>
    </div>

    {{-- Categories --}}
    <h3 class="mb-2" style="font-size: 1rem; color: var(--text-muted);">KATEGORI</h3>
    <div class="category-grid">
        @forelse($categories as $category)
            <a href="{{ route('penjaga.stok.kategori', $category) }}" class="category-card">
                <span class="category-icon">{{ $category->icon }}</span>
                <span class="category-name">{{ $category->name }}</span>
                <span class="category-count">{{ $category->products_count }} barang</span>
            </a>
        @empty
            <div class="empty-state" style="grid-column: span 2;">
                <div class="empty-icon">📦</div>
                <div class="empty-title">Belum Ada Kategori</div>
                <div class="empty-text">Hubungi admin untuk menambahkan kategori</div>
            </div>
        @endforelse
    </div>
@endsection