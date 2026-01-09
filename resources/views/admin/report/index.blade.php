@extends('layouts.app')

@section('title', 'Laporan')

@section('content')
    <div class="page-header">
        <h1 class="page-title">📊 Laporan</h1>
        <p class="page-subtitle">Pilih jenis laporan</p>
    </div>

    <div class="menu-grid">
        <a href="{{ route('admin.laporan.harian') }}" class="menu-item">
            <span class="menu-icon">📅</span>
            <span class="menu-label">Laporan Harian</span>
        </a>
        <a href="{{ route('admin.laporan.bulanan') }}" class="menu-item">
            <span class="menu-icon">📈</span>
            <span class="menu-label">Laporan Bulanan</span>
        </a>
    </div>
@endsection