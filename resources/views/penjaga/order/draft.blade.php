@extends('layouts.app')

@section('title', 'Draft Order')

@section('content')
    <a href="{{ route('penjaga.order.index') }}" class="page-back">
        ← Kembali ke Daftar Order
    </a>

    <div class="page-header">
        <h1 class="page-title">📝 Draft Order</h1>
        <p class="page-subtitle">
            @if($order)
                {{ $order->order_number }}
            @else
                Buat order baru
            @endif
        </p>
    </div>

    @if($order)
        {{-- Order Items --}}
        <div class="card mb-3">
            <div class="card-header">
                <h3 class="card-title">Daftar Barang</h3>
                <span class="status-badge status-draft">{{ $order->items->count() }} item</span>
            </div>
            <div class="card-body">
                @forelse($order->items as $item)
                    <div class="order-item">
                        <div class="order-item-info">
                            <div class="order-item-name">{{ $item->product->name }}</div>
                            <div class="order-item-qty">{{ $item->quantity_text }}</div>
                        </div>
                        <div class="flex gap-1">
                            <button type="button" class="btn btn-secondary btn-icon"
                                onclick="openEditModal({{ $item->id }}, '{{ $item->product->name }}', '{{ $item->quantity_text }}')">
                                ✏️
                            </button>
                            <form action="{{ route('penjaga.order.remove-item', [$order, $item]) }}" method="POST"
                                onsubmit="return confirm('Hapus barang ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-icon">🗑️</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <div class="empty-icon">📦</div>
                        <div class="empty-text">Belum ada barang di order ini</div>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Add Item --}}
        <div class="card mb-3">
            <div class="card-header">
                <h3 class="card-title">Tambah Barang</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('penjaga.order.add-item', $order) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Pilih Barang</label>
                        <select name="product_id" class="form-select" required>
                            <option value="">-- Pilih Barang --</option>
                            @foreach($products->groupBy('category.name') as $categoryName => $categoryProducts)
                                <optgroup label="{{ $categoryName }}">
                                    @foreach($categoryProducts as $product)
                                        <option value="{{ $product->id }}">
                                            {{ $product->name }} ({{ $product->status_info['label'] }})
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Jumlah</label>
                        <input type="text" name="quantity_text" class="form-input" placeholder="contoh: 2 dus" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Tambah Barang</button>
                </form>
            </div>
        </div>

        {{-- Notes & Send --}}
        @if($order->items->count() > 0)
            <form action="{{ route('penjaga.order.send', $order) }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label">Catatan (opsional)</label>
                    <textarea name="notes" class="form-input" rows="3"
                        placeholder="Catatan tambahan untuk supplier...">{{ $order->notes }}</textarea>
                </div>
                <button type="submit" class="btn btn-success btn-xl btn-block">
                    📤 Kirim Order
                </button>
            </form>
        @endif

    @else
        {{-- No Draft - Create New --}}
        <div class="empty-state">
            <div class="empty-icon">📝</div>
            <div class="empty-title">Belum Ada Draft Order</div>
            <div class="empty-text">Buat order baru atau tambahkan barang dari halaman stok</div>
            <form action="{{ route('penjaga.order.create') }}" method="POST" class="mt-2">
                @csrf
                <button type="submit" class="btn btn-primary btn-lg">
                    Buat Draft Order Baru
                </button>
            </form>
        </div>
    @endif

    {{-- Edit Item Modal --}}
    <div class="modal-overlay" id="editModal">
        <div class="modal">
            <div class="modal-header">
                <h3 class="modal-title">Edit Jumlah</h3>
                <button type="button" class="modal-close" onclick="closeModal('editModal')">&times;</button>
            </div>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <p class="mb-3" id="editProductName" style="font-weight: 600;"></p>
                    <div class="form-group">
                        <label class="form-label">Jumlah</label>
                        <input type="text" name="quantity_text" id="editQuantity" class="form-input form-input-lg" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('editModal')">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            function openEditModal(itemId, productName, quantity) {
                document.getElementById('editProductName').textContent = productName;
                document.getElementById('editQuantity').value = quantity;
                document.getElementById('editForm').action = '/penjaga/order/{{ $order?->id }}/item/' + itemId;
                document.getElementById('editModal').classList.add('active');
            }

            function closeModal(modalId) {
                document.getElementById(modalId).classList.remove('active');
            }

            document.querySelectorAll('.modal-overlay').forEach(overlay => {
                overlay.addEventListener('click', function (e) {
                    if (e.target === this) {
                        this.classList.remove('active');
                    }
                });
            });
        </script>
    @endpush
@endsection