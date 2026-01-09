@extends('layouts.app')

@section('title', 'Tambah Pengeluaran')

@section('content')
    <a href="{{ route('penjaga.pengeluaran.index') }}" class="page-back">
        ← Kembali
    </a>

    <div class="page-header">
        <h1 class="page-title">💸 Tambah Pengeluaran</h1>
        <p class="page-subtitle">Catat pengeluaran warung</p>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('penjaga.pengeluaran.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label class="form-label">Tanggal</label>
                    <input type="date" name="date" class="form-input form-input-lg" value="{{ old('date', date('Y-m-d')) }}"
                        required>
                    @error('date')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Kategori</label>
                    <div class="category-grid" style="grid-template-columns: repeat(2, 1fr);">
                        @foreach($categories as $key => $cat)
                            <label class="category-card" style="cursor: pointer; padding: 1rem;">
                                <input type="radio" name="category" value="{{ $key }}" {{ old('category') === $key ? 'checked' : '' }} style="display: none;" required>
                                <span style="font-size: 1.5rem;">{{ $cat['icon'] }}</span>
                                <span style="font-size: 0.875rem; font-weight: 500;">{{ $cat['label'] }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('category')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Jumlah (Rp)</label>
                    <input type="number" name="amount" class="form-input form-input-lg" placeholder="0"
                        value="{{ old('amount') }}" min="0" step="1000" required>
                    @error('amount')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Catatan (opsional)</label>
                    <textarea name="notes" class="form-input" rows="3"
                        placeholder="Catatan tambahan...">{{ old('notes') }}</textarea>
                </div>

                <button type="submit" class="btn btn-danger btn-xl btn-block">
                    Simpan Pengeluaran
                </button>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            document.querySelectorAll('.category-card').forEach(card => {
                const input = card.querySelector('input');
                if (input.checked) {
                    card.style.borderColor = 'var(--primary)';
                    card.style.background = 'rgba(139, 92, 246, 0.1)';
                }

                card.addEventListener('click', function () {
                    document.querySelectorAll('.category-card').forEach(c => {
                        c.style.borderColor = 'var(--border-color)';
                        c.style.background = 'var(--bg-card)';
                    });
                    this.style.borderColor = 'var(--primary)';
                    this.style.background = 'rgba(139, 92, 246, 0.1)';
                });
            });
        </script>
    @endpush
@endsection