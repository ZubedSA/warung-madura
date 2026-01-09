@extends('layouts.app')

@section('title', 'Laporan Harian')

@section('content')
    <div class="page-header">
        <h1 class="page-title">📅 Laporan Harian</h1>
        <p class="page-subtitle">{{ \Carbon\Carbon::parse($date)->translatedFormat('l, d F Y') }}</p>
    </div>

    {{-- Date Filter --}}
    <div class="card mb-3">
        <div class="card-body">
            <form action="" method="GET" class="flex gap-2">
                <input type="date" name="date" class="form-input" value="{{ $date }}" style="flex: 1;">
                <button type="submit" class="btn btn-primary">Lihat</button>
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
    <div class="profit-card {{ $profit >= 0 ? 'profit' : 'loss' }} mb-3">
        <div class="profit-label">{{ $profit >= 0 ? 'UNTUNG' : 'RUGI' }} HARI INI</div>
        <div class="profit-value money">
            Rp {{ number_format(abs($profit), 0, ',', '.') }}
        </div>
    </div>

    {{-- Expense Breakdown --}}
    @if($expenseByCategory->count() > 0)
        <div class="card mb-3">
            <div class="card-header">
                <h3 class="card-title">💸 Rincian Pengeluaran</h3>
            </div>
            <div class="card-body">
                @foreach($expenseByCategory as $category => $amount)
                    <div class="flex-between mb-2">
                        <span>
                            {{ \App\Models\Expense::CATEGORIES[$category]['icon'] }}
                            {{ \App\Models\Expense::CATEGORIES[$category]['label'] }}
                        </span>
                        <span class="money">Rp {{ number_format($amount, 0, ',', '.') }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Income Details --}}
    <div class="card mb-3">
        <div class="card-header">
            <h3 class="card-title">💰 Detail Pemasukan</h3>
        </div>
        <div class="card-body">
            @forelse($incomes as $income)
                <div class="flex-between mb-2">
                    <div>
                        <div style="font-weight: 500;">{{ $income->user->name }}</div>
                        @if($income->notes)
                            <div class="text-muted" style="font-size: 0.75rem;">{{ $income->notes }}</div>
                        @endif
                    </div>
                    <span class="money money-positive">Rp {{ number_format($income->amount, 0, ',', '.') }}</span>
                </div>
            @empty
                <p class="text-muted text-center">Tidak ada pemasukan</p>
            @endforelse
        </div>
    </div>

    {{-- Expense Details --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">💸 Detail Pengeluaran</h3>
        </div>
        <div class="card-body">
            @forelse($expenses as $expense)
                <div class="flex-between mb-2">
                    <div>
                        <div style="font-weight: 500;">
                            {{ $expense->category_info['icon'] }} {{ $expense->category_info['label'] }}
                        </div>
                        <div class="text-muted" style="font-size: 0.75rem;">
                            {{ $expense->user->name }}
                            @if($expense->notes) • {{ $expense->notes }} @endif
                        </div>
                    </div>
                    <span class="money money-negative">Rp {{ number_format($expense->amount, 0, ',', '.') }}</span>
                </div>
            @empty
                <p class="text-muted text-center">Tidak ada pengeluaran</p>
            @endforelse
        </div>
    </div>
@endsection