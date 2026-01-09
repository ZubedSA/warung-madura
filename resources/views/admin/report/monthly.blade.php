@extends('layouts.app')

@section('title', 'Laporan Bulanan')

@section('content')
    <div class="page-header">
        <h1 class="page-title">📈 Laporan Bulanan</h1>
        <p class="page-subtitle">{{ \Carbon\Carbon::parse($month)->translatedFormat('F Y') }}</p>
    </div>

    {{-- Month Filter --}}
    <div class="card mb-3">
        <div class="card-body">
            <form action="" method="GET" class="flex gap-2">
                <input type="month" name="month" class="form-input" value="{{ $month }}" style="flex: 1;">
                <button type="submit" class="btn btn-primary">Lihat</button>
            </form>
        </div>
    </div>

    {{-- Profit/Loss Card --}}
    <div class="profit-card {{ $totalProfit >= 0 ? 'profit' : 'loss' }} mb-3">
        <div class="profit-label">{{ $totalProfit >= 0 ? 'UNTUNG' : 'RUGI' }} BULAN INI</div>
        <div class="profit-value money">
            Rp {{ number_format(abs($totalProfit), 0, ',', '.') }}
        </div>
    </div>

    {{-- Summary --}}
    <div class="stat-grid mb-3">
        <div class="stat-card stat-success">
            <div class="stat-value money" style="font-size: 1.25rem;">
                Rp {{ number_format($totalIncome, 0, ',', '.') }}
            </div>
            <div class="stat-label">Total Pemasukan</div>
        </div>
        <div class="stat-card stat-danger">
            <div class="stat-value money" style="font-size: 1.25rem;">
                Rp {{ number_format($totalExpense, 0, ',', '.') }}
            </div>
            <div class="stat-label">Total Pengeluaran</div>
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
                    @php
                        $percentage = $totalExpense > 0 ? ($amount / $totalExpense) * 100 : 0;
                    @endphp
                    <div class="mb-3">
                        <div class="flex-between mb-1">
                            <span>
                                {{ \App\Models\Expense::CATEGORIES[$category]['icon'] }}
                                {{ \App\Models\Expense::CATEGORIES[$category]['label'] }}
                            </span>
                            <span class="money">Rp {{ number_format($amount, 0, ',', '.') }}</span>
                        </div>
                        <div style="height: 8px; background: var(--bg-dark); border-radius: 4px; overflow: hidden;">
                            <div style="height: 100%; width: {{ $percentage }}%; background: var(--danger); border-radius: 4px;">
                            </div>
                        </div>
                        <div class="text-muted" style="font-size: 0.75rem; text-align: right;">
                            {{ number_format($percentage, 1) }}%
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Daily Breakdown --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">📅 Detail Harian</h3>
        </div>
        <div class="card-body" style="max-height: 400px; overflow-y: auto;">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th style="text-align: right;">Masuk</th>
                            <th style="text-align: right;">Keluar</th>
                            <th style="text-align: right;">Untung/Rugi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dailyData as $day)
                            @if($day['income'] > 0 || $day['expense'] > 0)
                                <tr>
                                    <td>{{ $day['date'] }}</td>
                                    <td style="text-align: right;" class="money money-positive">
                                        {{ number_format($day['income'], 0, ',', '.') }}
                                    </td>
                                    <td style="text-align: right;" class="money money-negative">
                                        {{ number_format($day['expense'], 0, ',', '.') }}
                                    </td>
                                    <td style="text-align: right;"
                                        class="money {{ $day['profit'] >= 0 ? 'money-positive' : 'money-negative' }}">
                                        {{ number_format($day['profit'], 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection