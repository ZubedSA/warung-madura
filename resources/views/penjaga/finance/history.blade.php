@extends('layouts.app')

@section('title', 'Riwayat Keuangan')

@section('content')
    <div class="page-header">
        <h1 class="page-title">📋 Riwayat Keuangan</h1>
        <p class="page-subtitle">{{ \Carbon\Carbon::parse($startDate)->format('d M') }} -
            {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</p>
    </div>

    {{-- Date Filter --}}
    <div class="card mb-3">
        <div class="card-body">
            <form action="" method="GET" class="flex gap-2" style="flex-wrap: wrap;">
                <div style="flex: 1; min-width: 120px;">
                    <input type="date" name="start_date" class="form-input" value="{{ $startDate }}">
                </div>
                <div style="flex: 1; min-width: 120px;">
                    <input type="date" name="end_date" class="form-input" value="{{ $endDate }}">
                </div>
                <button type="submit" class="btn btn-primary">Filter</button>
            </form>
        </div>
    </div>

    {{-- Summary --}}
    <div class="stat-grid mb-3">
        <div class="stat-card stat-success">
            <div class="stat-value money" style="font-size: 1.25rem;">
                Rp {{ number_format($totalIncome, 0, ',', '.') }}
            </div>
            <div class="stat-label">Pemasukan</div>
        </div>
        <div class="stat-card stat-danger">
            <div class="stat-value money" style="font-size: 1.25rem;">
                Rp {{ number_format($totalExpense, 0, ',', '.') }}
            </div>
            <div class="stat-label">Pengeluaran</div>
        </div>
    </div>

    {{-- Profit/Loss --}}
    @php $profit = $totalIncome - $totalExpense; @endphp
    <div class="profit-card {{ $profit >= 0 ? 'profit' : 'loss' }} mb-3">
        <div class="profit-label">{{ $profit >= 0 ? 'UNTUNG' : 'RUGI' }}</div>
        <div class="profit-value money">
            Rp {{ number_format(abs($profit), 0, ',', '.') }}
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="menu-grid">
        <a href="{{ route('penjaga.pemasukan.index') }}" class="menu-item">
            <span class="menu-icon">💰</span>
            <span class="menu-label">Pemasukan</span>
        </a>
        <a href="{{ route('penjaga.pengeluaran.index') }}" class="menu-item">
            <span class="menu-icon">💸</span>
            <span class="menu-label">Pengeluaran</span>
        </a>
    </div>
@endsection