@extends('layouts.app')

@section('title', 'Tambah Pemasukan')

@section('content')
    <a href="{{ route('penjaga.pemasukan.index') }}" class="page-back">
        ← Kembali
    </a>

    <div class="page-header">
        <h1 class="page-title">💰 Tambah Pemasukan</h1>
        <p class="page-subtitle">Input total pemasukan harian</p>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('penjaga.pemasukan.store') }}" method="POST">
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
                    <label class="form-label">Jumlah (Rp)</label>
                    <input type="number" name="amount" class="form-input form-input-lg" placeholder="0"
                        value="{{ old('amount') }}" min="0" step="1000" required>
                    @error('amount')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                    <p class="form-hint">Masukkan total pemasukan hari ini</p>
                </div>

                <div class="form-group">
                    <label class="form-label">Catatan (opsional)</label>
                    <textarea name="notes" class="form-input" rows="3"
                        placeholder="Catatan tambahan...">{{ old('notes') }}</textarea>
                </div>

                <button type="submit" class="btn btn-success btn-xl btn-block">
                    Simpan Pemasukan
                </button>
            </form>
        </div>
    </div>
@endsection