@extends('layouts.app')

@section('title', 'Pengguna')

@section('content')
    <div class="page-header flex-between">
        <div>
            <h1 class="page-title">👥 Pengguna</h1>
            <p class="page-subtitle">Kelola akun pengguna</p>
        </div>
        <a href="{{ route('admin.user.create') }}" class="btn btn-primary">+ Tambah</a>
    </div>

    <div class="card">
        <div class="card-body">
            @forelse($users as $user)
                <div class="product-item">
                    <div class="product-icon"
                        style="background: linear-gradient(135deg, var(--primary) 0%, var(--status-cukup) 100%); color: white; font-weight: 600;">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div class="product-info">
                        <div class="product-name">{{ $user->name }}</div>
                        <div class="product-meta">
                            {{ $user->email }}
                            <span class="status-badge"
                                style="margin-left: 0.5rem; {{ $user->isPemilik() ? 'background: rgba(139, 92, 246, 0.15); color: var(--primary); border: 1px solid rgba(139, 92, 246, 0.3);' : '' }}">
                                {{ $user->isPemilik() ? 'Pemilik' : 'Penjaga' }}
                            </span>
                        </div>
                    </div>
                    <div class="flex gap-1">
                        <a href="{{ route('admin.user.edit', $user) }}" class="btn btn-secondary btn-icon">✏️</a>
                        @if($user->id !== auth()->id())
                            <form action="{{ route('admin.user.destroy', $user) }}" method="POST"
                                onsubmit="return confirm('Hapus user ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-icon">🗑️</button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <div class="empty-icon">👥</div>
                    <div class="empty-title">Belum Ada Pengguna</div>
                </div>
            @endforelse
        </div>
    </div>

    <div class="mt-3">
        {{ $users->links() }}
    </div>
@endsection