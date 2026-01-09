@extends('layouts.app')

@section('title', 'Tambah Barang')

@section('content')
    <a href="{{ route('admin.barang.index') }}" class="page-back">← Kembali</a>

    <div class="page-header">
        <h1 class="page-title">📦 Tambah Barang</h1>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.barang.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label class="form-label">Kategori</label>
                    <select name="category_id" class="form-select" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->icon }} {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Nama Barang</label>
                    <input type="text" name="name" class="form-input" value="{{ old('name') }}" required>
                    @error('name')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Status Stok</label>
                    <select name="stock_status" class="form-select" required>
                        @foreach(\App\Models\Product::STOCK_STATUSES as $key => $status)
                            <option value="{{ $key }}" {{ old('stock_status', 'cukup') === $key ? 'selected' : '' }}>
                                {{ $status['icon'] }} {{ $status['label'] }}
                            </option>
                        @endforeach
                    </select>
                    @error('stock_status')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Satuan</label>
                    <input type="text" name="unit" class="form-input" value="{{ old('unit', 'pcs') }}"
                        placeholder="pcs, dus, karung, dll" required>
                    @error('unit')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Nama Supplier (opsional)</label>
                    <input type="text" name="supplier_name" class="form-input" value="{{ old('supplier_name') }}">
                </div>

                <div class="form-group">
                    <label class="form-label">No. HP Supplier (opsional)</label>
                    <input type="text" name="supplier_phone" class="form-input" value="{{ old('supplier_phone') }}"
                        placeholder="08xxx">
                </div>

                <button type="submit" class="btn btn-primary btn-lg btn-block">Simpan</button>
            </form>
        </div>
    </div>
@endsection