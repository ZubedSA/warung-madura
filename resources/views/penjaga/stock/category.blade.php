@extends('layouts.app')

@section('title', $category->name)

@section('content')
    <a href="{{ route('penjaga.stok.index') }}" class="page-back">
        ← Kembali ke Kategori
    </a>

    <div class="page-header">
        <div>
            <h1 class="page-title">{{ $category->icon }} {{ $category->name }}</h1>
            <p class="page-subtitle">{{ $products->count() }} barang</p>
        </div>
        <button type="button" class="btn btn-primary" onclick="openAddProductModal()">
            + Tambah Barang
        </button>
    </div>

    {{-- Products List --}}
    <div class="product-list">
        @forelse($products as $product)
            <div class="product-item {{ in_array($product->id, $draftProductIds) ? 'in-order' : '' }}"
                id="product-{{ $product->id }}">
                <div class="product-icon">
                    {{ $product->status_info['icon'] }}
                </div>
                <div class="product-info">
                    <div class="product-name">{{ $product->name }}</div>
                    <div class="product-meta">
                        <span class="status-badge status-{{ $product->stock_status }}">
                            {{ $product->status_info['label'] }}
                        </span>
                    </div>
                </div>
                <div class="product-actions">
                    <button type="button" class="btn btn-secondary btn-icon"
                        onclick="openStatusModal({{ $product->id }}, '{{ $product->name }}', '{{ $product->stock_status }}')">
                        ✏️
                    </button>
                    <button type="button" class="btn btn-primary btn-icon"
                        onclick="openOrderModal({{ $product->id }}, '{{ $product->name }}')">
                        🛒
                    </button>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <div class="empty-icon">📦</div>
                <div class="empty-title">Belum Ada Barang</div>
                <div class="empty-text">Kategori ini belum memiliki barang</div>
            </div>
        @endforelse
    </div>

    {{-- Status Modal --}}
    <div class="modal-overlay" id="statusModal">
        <div class="modal">
            <div class="modal-header">
                <h3 class="modal-title">Ubah Status Stok</h3>
                <button type="button" class="modal-close" onclick="closeModal('statusModal')">&times;</button>
            </div>
            <form id="statusForm" method="POST">
                @csrf
                <div class="modal-body">
                    <p class="mb-3" id="statusProductName" style="font-weight: 600;"></p>

                    <div class="status-selector">
                        <label class="status-option" data-status="banyak">
                            <input type="radio" name="stock_status" value="banyak">
                            <span class="status-option-icon">🟢</span>
                            <span class="status-option-label">Banyak</span>
                        </label>
                        <label class="status-option" data-status="cukup">
                            <input type="radio" name="stock_status" value="cukup">
                            <span class="status-option-icon">🔵</span>
                            <span class="status-option-label">Cukup</span>
                        </label>
                        <label class="status-option" data-status="sedikit">
                            <input type="radio" name="stock_status" value="sedikit">
                            <span class="status-option-icon">🟠</span>
                            <span class="status-option-label">Sedikit</span>
                        </label>
                        <label class="status-option" data-status="kosong">
                            <input type="radio" name="stock_status" value="kosong">
                            <span class="status-option-icon">🔴</span>
                            <span class="status-option-label">Kosong</span>
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('statusModal')">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Order Modal --}}
    <div class="modal-overlay" id="orderModal">
        <div class="modal">
            <div class="modal-header">
                <h3 class="modal-title">Tambah ke Order</h3>
                <button type="button" class="modal-close" onclick="closeModal('orderModal')">&times;</button>
            </div>
            <form id="orderForm" method="POST">
                @csrf
                <div class="modal-body">
                    <p class="mb-3" id="orderProductName" style="font-weight: 600;"></p>

                    <div class="form-group">
                        <label class="form-label">Jumlah</label>
                        <input type="text" name="quantity_text" class="form-input form-input-lg"
                            placeholder="contoh: 2 dus, 5 karung, 10 pcs" required>
                        <p class="form-hint">Tulis jumlah sesuai kebutuhan. Bisa ditulis bebas.</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('orderModal')">Batal</button>
                    <button type="submit" class="btn btn-primary">Tambah ke Order</button>
                </div>
            </form>
        </div>
    </div>
    {{-- Add Product Modal --}}
    <div class="modal-overlay" id="addProductModal">
        <div class="modal">
            <div class="modal-header">
                <h3 class="modal-title">Tambah Barang Baru</h3>
                <button type="button" class="modal-close" onclick="closeModal('addProductModal')">&times;</button>
            </div>
            <form action="{{ route('penjaga.stok.store') }}" method="POST">
                @csrf
                <input type="hidden" name="category_id" value="{{ $category->id }}">

                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Nama Barang</label>
                        <input type="text" name="name" class="form-input" placeholder="Contoh: Kopi Kapal Api Mix" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Satuan</label>
                        <select name="unit" class="form-select" required>
                            <option value="Pcs">Pcs</option>
                            <option value="Sachet">Sachet</option>
                            <option value="Renteng">Renteng</option>
                            <option value="Bungkus">Bungkus</option>
                            <option value="Pack">Pack</option>
                            <option value="Botol">Botol</option>
                            <option value="Kaleng">Kaleng</option>
                            <option value="Dus">Dus</option>
                            <option value="Kg">Kg</option>
                            <option value="Liter">Liter</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('addProductModal')">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Barang</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            function openAddProductModal() {
                document.getElementById('addProductModal').classList.add('active');
            }

            function openStatusModal(productId, productName, currentStatus) {
                document.getElementById('statusProductName').textContent = productName;
                const form = document.getElementById('statusForm');
                form.action = '/penjaga/stok/' + productId + '/update-status';
                form.dataset.productId = productId; // Store ID for AJAX reference

                // Reset and select current status
                document.querySelectorAll('.status-option').forEach(opt => {
                    opt.classList.remove('selected');
                    const radio = opt.querySelector('input');
                    if (radio.value === currentStatus) {
                        opt.classList.add('selected');
                        radio.checked = true;
                    }
                });

                document.getElementById('statusModal').classList.add('active');
            }

            function openOrderModal(productId, productName) {
                document.getElementById('orderProductName').textContent = productName;
                document.getElementById('orderForm').action = '/penjaga/stok/' + productId + '/add-to-order';
                document.querySelector('#orderForm input[name="quantity_text"]').value = '';

                document.getElementById('orderModal').classList.add('active');
            }

            function closeModal(modalId) {
                document.getElementById(modalId).classList.remove('active');
            }

            // Status option click handler
            document.querySelectorAll('.status-option').forEach(option => {
                option.addEventListener('click', function () {
                    document.querySelectorAll('.status-option').forEach(opt => opt.classList.remove('selected'));
                    this.classList.add('selected');
                    this.querySelector('input').checked = true;
                });
            });

            // Close modal on overlay click
            document.querySelectorAll('.modal-overlay').forEach(overlay => {
                overlay.addEventListener('click', function (e) {
                    if (e.target === this) {
                        this.classList.remove('active');
                    }
                });
            });

            // AJAX Form Handling
            document.getElementById('statusForm').addEventListener('submit', function (e) {
                e.preventDefault();
                const formData = new FormData(this);
                const action = this.action;
                const productId = this.dataset.productId;

                fetch(action, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    },
                    body: formData
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            closeModal('statusModal');
                            // Update UI interactively
                            const productItem = document.getElementById('product-' + productId);
                            const badge = productItem.querySelector('.status-badge');
                            badge.className = 'status-badge status-' + data.new_status;

                            // Map status to label - simple mapping
                            const labels = {
                                'banyak': 'Banyak', 'cukup': 'Cukup',
                                'sedikit': 'Sedikit', 'kosong': 'Kosong'
                            };
                            badge.textContent = labels[data.new_status];

                            // Update updating button parameters so if clicked again it shows correct status
                            const btn = productItem.querySelector('.btn-secondary');
                            // We need to update the onclick attribute or listener. 
                            // Simpler to just update the attribute for this inline style.
                            const productName = productItem.querySelector('.product-name').textContent;
                            btn.setAttribute('onclick', `openStatusModal(${productId}, '${productName}', '${data.new_status}')`);

                            showToast(data.message, 'success');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan');
                    });
            });

            document.getElementById('orderForm').addEventListener('submit', function (e) {
                e.preventDefault();
                const formData = new FormData(this);
                const action = this.action;

                fetch(action, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    },
                    body: formData
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            closeModal('orderModal');
                            showToast(data.message, 'success');

                            // Add visual indicator
                            // Let's robustly get ID from form action or add hidden input
                            // We do not have dataset on orderForm easily. Let's parse url.
                            const urlParts = action.split('/');
                            const cmdIndex = urlParts.indexOf('add-to-order');
                            if (cmdIndex > 0) {
                                const pId = urlParts[cmdIndex - 1];
                                const pItem = document.getElementById('product-' + pId);
                                if (pItem) {
                                    pItem.classList.add('in-order');
                                }
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan');
                    });
            });

            // Toast Notification Helper
            function showToast(message, type = 'success') {
                const toast = document.createElement('div');
                toast.className = `toast toast-${type}`;
                toast.style.cssText = `
                                    position: fixed;
                                    bottom: 20px;
                                    left: 50%;
                                    transform: translateX(-50%);
                                    background: var(--bg-card);
                                    border: 1px solid var(--primary);
                                    color: var(--text-primary);
                                    padding: 12px 24px;
                                    border-radius: 50px;
                                    box-shadow: 0 4px 12px rgba(0,0,0,0.3);
                                    z-index: 9999;
                                    font-weight: 600;
                                    animation: slideUp 0.3s ease-out;
                                `;
                toast.textContent = message;
                document.body.appendChild(toast);

                setTimeout(() => {
                    toast.style.opacity = '0';
                    toast.style.transform = 'translateX(-50%) translateY(20px)';
                    toast.style.transition = 'all 0.3s ease-out';
                    setTimeout(() => toast.remove(), 300);
                }, 2000);
            }

            // Add keyframes for toast
            const style = document.createElement('style');
            style.textContent = `
                                @keyframes slideUp {
                                    from { transform: translateX(-50%) translateY(20px); opacity: 0; }
                                    to { transform: translateX(-50%) translateY(0); opacity: 1; }
                                }
                            `;
            document.head.appendChild(style);
        </script>
    @endpush
@endsection