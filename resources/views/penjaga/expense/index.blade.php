@extends('layouts.app')

@section('title', 'Pengeluaran')

@section('content')
    <div class="page-header">
        <h1 class="page-title">💸 Pengeluaran</h1>
        <p class="page-subtitle">Riwayat pengeluaran</p>
    </div>

    {{-- Today Summary --}}
    <div class="card mb-3" style="border-color: var(--danger);">
        <div class="card-body text-center">
            <div class="text-muted mb-1">Pengeluaran Hari Ini</div>
            <div class="money money-lg money-negative">
                Rp {{ number_format($todayTotal, 0, ',', '.') }}
            </div>
        </div>
    </div>

    {{-- Add Button --}}
    <a href="{{ route('penjaga.pengeluaran.create') }}" class="btn btn-danger btn-lg btn-block mb-3">
        + Tambah Pengeluaran
    </a>

    {{-- History --}}
    <h3 class="mb-2" style="font-size: 1rem; color: var(--text-muted);">RIWAYAT</h3>
    <div class="product-list">
        @forelse($expenses as $expense)
            <div class="product-item">
                <div class="product-icon" style="background: rgba(239, 68, 68, 0.2);">
                    {{ $expense->category_info['icon'] }}
                </div>
                <div class="product-info">
                    <div class="product-name money money-negative">
                        Rp {{ number_format($expense->amount, 0, ',', '.') }}
                    </div>
                    <div class="product-meta">
                        {{ $expense->category_info['label'] }} • {{ $expense->date->format('d/m/Y') }}
                        @if($expense->notes)
                            • {{ Str::limit($expense->notes, 20) }}
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <div class="empty-icon">💸</div>
                <div class="empty-title">Belum Ada Pengeluaran</div>
                <div class="empty-text">Klik tombol di atas untuk menambah pengeluaran</div>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="mt-3">
        {{ $expenses->links() }}
    </div>
@endsection