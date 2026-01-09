@extends('layouts.app')

@section('title', 'Edit Kategori')

@section('content')
    <a href="{{ route('admin.kategori.index') }}" class="page-back">← Kembali</a>

    <div class="page-header">
        <h1 class="page-title">✏️ Edit Kategori</h1>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.kategori.update', $category) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label class="form-label">Nama Kategori</label>
                    <input type="text" name="name" class="form-input" value="{{ old('name', $category->name) }}" required>
                    @error('name')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Icon (Emoji)</label>
                    <input type="text" name="icon" class="form-input" value="{{ old('icon', $category->icon) }}" required
                        maxlength="10">
                    @error('icon')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Urutan</label>
                    <input type="number" name="sort_order" class="form-input"
                        value="{{ old('sort_order', $category->sort_order) }}" min="0" required>
                    @error('sort_order')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <button type="submit" class="btn btn-primary btn-lg btn-block">Update</button>
            </form>
        </div>
    </div>
@endsection