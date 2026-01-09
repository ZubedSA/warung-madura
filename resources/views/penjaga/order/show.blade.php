@extends('layouts.app')

@section('title', 'Detail Order')

@section('content')
    <a href="{{ route('penjaga.order.index') }}" class="page-back">
        ← Kembali ke Daftar Order
    </a>

    <div class="page-header flex-between">
        <div>
            <h1 class="page-title">{{ $order->order_number }}</h1>
            <p class="page-subtitle">{{ $order->created_at->format('d F Y, H:i') }}</p>
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

    {{-- Actions based on status --}}
    @if($order->isDraft())
        <div class="flex gap-2">
            <a href="{{ route('penjaga.order.draft') }}" class="btn btn-primary btn-lg" style="flex: 1;">
                ✏️ Edit Order
            </a>
        </div>
    @elseif($order->isSent())
        <div class="card mb-3" style="border-color: var(--primary); background: rgba(139, 92, 246, 0.1);">
            <div class="card-body text-center">
                <p class="mb-2">Order sudah dikirim ke supplier</p>
                <p class="text-muted mb-3">Klik tombol di bawah saat barang sudah datang</p>
                <a href="{{ route('penjaga.order.arrived', $order) }}" class="btn btn-success btn-xl btn-block">
                    📥 Barang Sudah Datang
                </a>
            </div>
        </div>
        <a href="{{ route('penjaga.order.whatsapp', $order) }}" class="btn btn-whatsapp btn-lg btn-block">
            Lihat Pesan WhatsApp
        </a>
    @elseif($order->isCompleted())
        <div class="card mb-3" style="border-color: var(--success); background: rgba(34, 197, 94, 0.1);">
            <div class="card-body text-center">
                <p class="mb-1" style="font-size: 2rem;">✅</p>
                <p class="mb-1" style="font-weight: 600;">Order Selesai</p>
                <p class="text-muted">Barang diterima {{ $order->completed_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>
    @endif

    {{-- Timeline --}}
    <div class="card mt-3">
        <div class="card-header">
            <h3 class="card-title">📅 Timeline</h3>
        </div>
        <div class="card-body">
            <div class="activity-list">
                <div class="activity-item">
                    <div class="activity-icon">📝</div>
                    <div class="activity-content">
                        <div class="activity-text">Order dibuat</div>
                        <div class="activity-meta">{{ $order->created_at->format('d/m/Y H:i') }}</div>
                    </div>
                </div>
                @if($order->sent_at)
                    <div class="activity-item">
                        <div class="activity-icon">📤</div>
                        <div class="activity-content">
                            <div class="activity-text">Order dikirim</div>
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