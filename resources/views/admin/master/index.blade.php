@extends('layouts.app')

@section('title', 'Master Data')

@section('content')
    <div class="page-header">
        <h1 class="page-title">⚙️ Master Data</h1>
        <p class="page-subtitle">Kelola data master aplikasi</p>
    </div>

    <div class="menu-grid">
        <a href="{{ route('admin.kategori.index') }}" class="menu-item">
            <span class="menu-icon">🏷️</span>
            <span class="menu-label">Kategori</span>
        </a>
        <a href="{{ route('admin.barang.index') }}" class="menu-item">
            <span class="menu-icon">📦</span>
            <span class="menu-label">Barang</span>
        </a>
        <a href="{{ route('admin.user.index') }}" class="menu-item menu-item-wide">
            <span class="menu-icon">👥</span>
            <span class="menu-label">Pengguna</span>
        </a>
    </div>
@endsection