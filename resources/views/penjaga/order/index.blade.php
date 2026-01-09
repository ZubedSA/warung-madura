@extends('layouts.app')

@section('title', 'Order Barang')

@section('content')
    <div class="page-header">
        <h1 class="page-title">🛒 Order Barang</h1>
        <p class="page-subtitle">Daftar order ke supplier</p>
    </div>

    {{-- Actions --}}
    <div class="flex gap-2 mb-3">
        <a href="{{ route('penjaga.order.draft') }}" class="btn btn-primary">
            📝 Draft Order
        </a>
    </div>

    {{-- Tabs --}}
    <div class="flex gap-1 mb-3" style="overflow-x: auto;">
        <a href="{{ route('penjaga.order.index') }}" class="btn {{ !request('status') ? 'btn-primary' : 'btn-secondary' }}">
            Semua
        </a>
        <a href="{{ route('penjaga.order.index', ['status' => 'draft']) }}"
            class="btn {{ request('status') === 'draft' ? 'btn-primary' : 'btn-secondary' }}">
            Draft
        </a>
        <a href="{{ route('penjaga.order.index', ['status' => 'dikirim']) }}"
            class="btn {{ request('status') === 'dikirim' ? 'btn-primary' : 'btn-secondary' }}">
            Dikirim
        </a>
        <a href="{{ route('penjaga.order.index', ['status' => 'selesai']) }}"
            class="btn {{ request('status') === 'selesai' ? 'btn-primary' : 'btn-secondary' }}">
            Selesai
        </a>
    </div>

    {{-- Orders List --}}
    <div class="product-list">
        @forelse($orders as $order)
            <a href="{{ route('penjaga.order.show', $order) }}" class="product-item">
                <div class="product-icon">
                    {{ $order->status_info['icon'] }}
                </div>
                <div class="product-info">
                    <div class="product-name">{{ $order->order_number }}</div>
                    <div class="product-meta">
                        {{ $order->items->count() }} item • {{ $order->created_at->format('d/m/Y H:i') }}
                    </div>
                </div>
                <span class="status-badge status-{{ $order->status }}">
                    {{ $order->status_info['label'] }}
                </span>
            </a>
        @empty
            <div class="empty-state">
                <div class="empty-icon">🛒</div>
                <div class="empty-title">Belum Ada Order</div>
                <div class="empty-text">Buat order pertama untuk memulai</div>
                <a href="{{ route('penjaga.order.create') }}" class="btn btn-primary mt-2">
                    Buat Order Baru
                </a>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="mt-3">
        {{ $orders->links() }}
    </div>
@endsection