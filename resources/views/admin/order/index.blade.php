@extends('layouts.app')

@section('title', 'Monitoring Order')

@section('content')
    <div class="page-header">
        <h1 class="page-title">📦 Monitoring Order</h1>
        <p class="page-subtitle">Pantau semua order penjaga</p>
    </div>

    {{-- Stats --}}
    <div class="stat-grid mb-3">
        <div class="stat-card">
            <div class="stat-value">{{ $stats['draft'] }}</div>
            <div class="stat-label">Draft</div>
        </div>
        <div class="stat-card stat-info">
            <div class="stat-value">{{ $stats['dikirim'] }}</div>
            <div class="stat-label">Dikirim</div>
        </div>
        <div class="stat-card stat-success" style="grid-column: span 2;">
            <div class="stat-value">{{ $stats['selesai'] }}</div>
            <div class="stat-label">Selesai</div>
        </div>
    </div>

    {{-- Orders List --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Order</h3>
        </div>
        <div class="card-body">
            @forelse($orders as $order)
                <a href="{{ route('admin.order.show', $order) }}" class="product-item" style="text-decoration: none;">
                    <div class="product-icon">
                        {{ $order->status_info['icon'] }}
                    </div>
                    <div class="product-info">
                        <div class="product-name">{{ $order->order_number }}</div>
                        <div class="product-meta">
                            {{ $order->user->name }} • {{ $order->items->count() }} item •
                            {{ $order->created_at->format('d/m/Y') }}
                        </div>
                    </div>
                    <span class="status-badge status-{{ $order->status }}">
                        {{ $order->status_info['label'] }}
                    </span>
                </a>
            @empty
                <div class="empty-state">
                    <div class="empty-icon">📦</div>
                    <div class="empty-title">Belum Ada Order</div>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Pagination --}}
    <div class="mt-3">
        {{ $orders->links() }}
    </div>
@endsection