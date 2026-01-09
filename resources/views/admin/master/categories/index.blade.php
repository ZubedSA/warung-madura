@extends('layouts.app')

@section('title', 'Kategori')

@section('content')
    <div class="page-header flex-between">
        <div>
            <h1 class="page-title">🏷️ Kategori</h1>
            <p class="page-subtitle">{{ $categories->count() }} kategori</p>
        </div>
        <a href="{{ route('admin.kategori.create') }}" class="btn btn-primary">+ Tambah</a>
    </div>

    <div class="card">
        <div class="card-body">
            @forelse($categories as $category)
                <div class="product-item">
                    <div class="product-icon">
                        {{ $category->icon }}
                    </div>
                    <div class="product-info">
                        <div class="product-name">{{ $category->name }}</div>
                        <div class="product-meta">{{ $category->products_count }} barang</div>
                    </div>
                    <div class="flex gap-1">
                        <a href="{{ route('admin.kategori.edit', $category) }}" class="btn btn-secondary btn-icon">✏️</a>
                        <form action="{{ route('admin.kategori.destroy', $category) }}" method="POST"
                            onsubmit="return confirm('Hapus kategori ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-icon">🗑️</button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <div class="empty-icon">🏷️</div>
                    <div class="empty-title">Belum Ada Kategori</div>
                    <a href="{{ route('admin.kategori.create') }}" class="btn btn-primary mt-2">Tambah Kategori</a>
                </div>
            @endforelse
        </div>
    </div>
@endsection