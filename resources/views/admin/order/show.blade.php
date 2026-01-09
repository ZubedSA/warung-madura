@extends('layouts.app')

@section('title', 'Detail Order')

@section('content')
    <a href="{{ route('admin.order.index') }}" class="page-back">
        ← Kembali ke Daftar Order
    </a>

    <div class="page-header flex-between">
        <div>
            <h1 class="page-title">{{ $order->order_number }}</h1>
            <p class="page-subtitle">
                Dibuat oleh {{ $order->user->name }} • {{ $order->created_at->format('d F Y, H:i') }}
            </p>
        </div>
        <span class="status-badge status-{{ $order->status }}">
            {{ $order->status_info['icon'] }} {{ $order->status_info['label'] }}
        </span>
    </div>

    {{-- Order Items --}}
    <div class="card mb-3">
        <div class="card-header">
            <h3 class="card-title">📦 Daftar Barang</h3>
            <span>{{ $order->items->count() }} item</span>
        </div>
        <div class="card-body">
            @foreach($order->items as $index => $item)
                <div class="order-item">
                    <div style="width: 24px; text-align: center; color: var(--text-muted);">
                        {{ $index + 1 }}
                    </div>
                    <div class="order-item-info">
                        <div class="order-item-name">{{ $item->product->name }}</div>
                        <div class="order-item-qty">{{ $item->quantity_text }}</div>
                    </div>
                    @if($item->stock_after_arrival)
                        <span class="status-badge status-{{ $item->stock_after_arrival }}">
                            {{ \App\Models\Product::STOCK_STATUSES[$item->stock_after_arrival]['label'] }}
                        </span>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    {{-- Notes --}}
    @if($order->notes)
        <div class="card mb-3">
            <div class="card-header">
                <h3 class="card-title">📝 Catatan</h3>
            </div>
            <div class="card-body">
                <p>{{ $order->notes }}</p>
            </div>
        </div>
    @endif

    {{-- Timeline --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">📅 Timeline</h3>
        </div>
        <div class="card-body">
            <div class="activity-list">
                <div class="activity-item">
                    <div class="activity-icon">📝</div>
                    <div class="activity-content">
                        <div class="activity-text">Order dibuat oleh {{ $order->user->name }}</div>
                        <div class="activity-meta">{{ $order->created_at->format('d/m/Y H:i') }}</div>
                    </div>
                </div>
                @if($order->sent_at)
                    <div class="activity-item">
                        <div class="activity-icon">📤</div>
                        <div class="activity-content">
                            <div class="activity-text">Order dikirim ke supplier</div>
                            <div class="activity-meta">{{ $order->sent_at->format('d/m/Y H:i') }}</div>
                        </div>
                    </div>
                @endif
                @if($order->completed_at)
                    <div class="activity-item">
                        <div class="activity-icon">✅</div>
                        <div class="activity-content">
                            <div class="activity-text">Barang diterima</div>
                            <div class="activity-meta">{{ $order->completed_at->format('d/m/Y H:i') }}</div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection