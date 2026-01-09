@extends('layouts.app')

@section('title', 'Barang Datang')

@section('content')
    <a href="{{ route('penjaga.order.show', $order) }}" class="page-back">
        ← Kembali ke Detail Order
    </a>

    <div class="page-header">
        <h1 class="page-title">📥 Barang Datang</h1>
        <p class="page-subtitle">{{ $order->order_number }}</p>
    </div>

    <div class="card mb-3" style="border-color: var(--info); background: rgba(6, 182, 212, 0.1);">
        <div class="card-body">
            <p style="font-weight: 600; margin-bottom: 0.5rem;">ℹ️ Petunjuk</p>
            <p class="text-muted" style="font-size: 0.875rem;">
                Update status stok untuk setiap barang yang datang.
                Jika tidak ingin mengubah status, biarkan saja (tidak perlu pilih).
            </p>
        </div>
    </div>

    <form action="{{ route('penjaga.order.complete', $order) }}" method="POST">
        @csrf

        @foreach($order->items as $item)
            <div class="card mb-2">
                <div class="card-body">
                    <div class="flex-between mb-2">
                        <div>
                            <div style="font-weight: 600;">{{ $item->product->name }}</div>
                            <div class="text-muted" style="font-size: 0.875rem;">
                                Jumlah: {{ $item->quantity_text }}
                            </div>
                        </div>
                        <span class="status-badge status-{{ $item->product->stock_status }}">
                            {{ $item->product->status_info['label'] }}
                        </span>
                    </div>

                    <div style="font-size: 0.75rem; color: var(--text-muted); margin-bottom: 0.5rem;">
                        UPDATE STATUS STOK:
                    </div>

                    <div class="status-selector">
                        <label class="status-option">
                            <input type="radio" name="stock_status[{{ $item->id }}]" value="banyak">
                            <span class="status-option-icon">🟢</span>
                            <span class="status-option-label">Banyak</span>
                        </label>
                        <label class="status-option">
                            <input type="radio" name="stock_status[{{ $item->id }}]" value="cukup">
                            <span class="status-option-icon">🔵</span>
                            <span class="status-option-label">Cukup</span>
                        </label>
                        <label class="status-option">
                            <input type="radio" name="stock_status[{{ $item->id }}]" value="sedikit">
                            <span class="status-option-icon">🟠</span>
                            <span class="status-option-label">Sedikit</span>
                        </label>
                        <label class="status-option">
                            <input type="radio" name="stock_status[{{ $item->id }}]" value="kosong">
                            <span class="status-option-icon">🔴</span>
                            <span class="status-option-label">Kosong</span>
                        </label>
                    </div>
                </div>
            </div>
        @endforeach

        <button type="submit" class="btn btn-success btn-xl btn-block mt-3">
            ✅ Konfirmasi Barang Diterima
        </button>
    </form>

    @push('scripts')
        <script>
            document.querySelectorAll('.status-option').forEach(option => {
                option.addEventListener('click', function () {
                    // Remove selected from siblings only
                    const parent = this.closest('.status-selector');
                    parent.querySelectorAll('.status-option').forEach(opt => opt.classList.remove('selected'));
                    this.classList.add('selected');
                    this.querySelector('input').checked = true;
                });
            });
        </script>
    @endpush
@endsection