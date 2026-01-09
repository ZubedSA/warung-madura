@extends('layouts.app')

@section('title', 'Pemasukan')

@section('content')
    <div class="page-header">
        <h1 class="page-title">💰 Pemasukan</h1>
        <p class="page-subtitle">Riwayat pemasukan harian</p>
    </div>

    {{-- Today Summary --}}
    <div class="card mb-3" style="border-color: var(--success);">
        <div class="card-body text-center">
            <div class="text-muted mb-1">Pemasukan Hari Ini</div>
            <div class="money money-lg money-positive">
                Rp {{ number_format($todayTotal, 0, ',', '.') }}
            </div>
        </div>
    </div>

    {{-- Add Button --}}
    <a href="{{ route('penjaga.pemasukan.create') }}" class="btn btn-success btn-lg btn-block mb-3">
        + Tambah Pemasukan
    </a>

    {{-- History --}}
    <h3 class="mb-2" style="font-size: 1rem; color: var(--text-muted);">RIWAYAT</h3>
    <div class="product-list">
        @forelse($incomes as $income)
            <div class="product-item">
                <div class="product-icon" style="background: rgba(34, 197, 94, 0.2);">
                    💰
                </div>
                <div class="product-info">
                    <div class="product-name money money-positive">
                        Rp {{ number_format($income->amount, 0, ',', '.') }}
                    </div>
                    <div class="product-meta">
                        {{ $income->date->format('d/m/Y') }}
                        @if($income->notes)
                            • {{ Str::limit($income->notes, 30) }}
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <div class="empty-icon">💰</div>
                <div class="empty-title">Belum Ada Pemasukan</div>
                <div class="empty-text">Klik tombol di atas untuk menambah pemasukan</div>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="mt-3">
        {{ $incomes->links() }}
    </div>
@endsection