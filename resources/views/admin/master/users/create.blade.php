@extends('layouts.app')

@section('title', 'Tambah Pengguna')

@section('content')
    <a href="{{ route('admin.user.index') }}" class="page-back">← Kembali</a>

    <div class="page-header">
        <h1 class="page-title">👥 Tambah Pengguna</h1>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.user.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label class="form-label">Nama</label>
                    <input type="text" name="name" class="form-input" value="{{ old('name') }}" required>
                    @error('name')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-input" value="{{ old('email') }}" required>
                    @error('email')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">No. HP (opsional)</label>
                    <input type="text" name="phone" class="form-input" value="{{ old('phone') }}" placeholder="08xxx">
                </div>

                <div class="form-group">
                    <label class="form-label">Role</label>
                    <select name="role" class="form-select" required>
                        <option value="penjaga" {{ old('role') === 'penjaga' ? 'selected' : '' }}>Penjaga Warung</option>
                        <option value="pemilik" {{ old('role') === 'pemilik' ? 'selected' : '' }}>Pemilik / Admin</option>
                    </select>
                    @error('role')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-input" required>
                    @error('password')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="form-input" required>
                </div>

                <button type="submit" class="btn btn-primary btn-lg btn-block">Simpan</button>
            </form>
        </div>
    </div>
@endsection