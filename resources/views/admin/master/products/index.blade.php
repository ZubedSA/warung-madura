@extends('layouts.app')

@section('title', 'Barang')

@section('content')
    <div class="page-header flex-between">
        <div>
            <h1 class="page-title">📦 Barang</h1>
            <p class="page-subtitle">Kelola daftar barang</p>
        </div>
        <a href="{{ route('admin.barang.create') }}" class="btn btn-primary">+ Tambah</a>
    </div>

    <div class="card">
        <div class="card-body">
            @forelse($products as $product)
                <div class="product-item">
                    <div class="product-icon">
                        {{ $product->status_info['icon'] }}
                    </div>
                    <div class="product-info">
                        <div class="product-name">{{ $product->name }}</div>
                        <div class="product-meta">
                            {{ $product->category->icon }} {{ $product->category->name }} • {{ $product->unit }}
                        </div>
                    </div>
                    <div class="flex gap-1">
                        <a href="{{ route('admin.barang.edit', $product) }}" class="btn btn-secondary btn-icon">✏️</a>
                        <form action="{{ route('admin.barang.destroy', $product) }}" method="POST"
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
                    <div class="empty-title">Belum Ada Barang</div>
                    <a href="{{ route('admin.barang.create') }}" class="btn btn-primary mt-2">Tambah Barang</a>
                </div>
            @endforelse
        </div>
    </div>

    <div class="mt-3">
        {{ $products->links() }}
    </div>
@endsection