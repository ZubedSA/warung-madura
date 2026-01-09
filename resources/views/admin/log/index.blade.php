@extends('layouts.app')

@section('title', 'Log Aktivitas')

@section('content')
    <div class="page-header">
        <h1 class="page-title">📋 Log Aktivitas</h1>
        <p class="page-subtitle">Pantau semua aktivitas penjaga</p>
    </div>

    {{-- Filters --}}
    <div class="card mb-3">
        <div class="card-body">
            <form action="" method="GET" class="flex gap-2" style="flex-wrap: wrap;">
                <select name="action" class="form-select" style="flex: 1; min-width: 120px;">
                    <option value="">Semua Aksi</option>
                    @foreach($actions as $key => $action)
                        <option value="{{ $key }}" {{ request('action') === $key ? 'selected' : '' }}>
                            {{ $action['icon'] }} {{ $action['label'] }}
                        </option>
                    @endforeach
                </select>
                <input type="date" name="date" class="form-input" value="{{ request('date') }}"
                    style="flex: 1; min-width: 120px;">
                <button type="submit" class="btn btn-primary">Filter</button>
                @if(request()->hasAny(['action', 'date']))
                    <a href="{{ route('admin.log.index') }}" class="btn btn-secondary">Reset</a>
                @endif
            </form>
        </div>
    </div>

    {{-- Logs List --}}
    <div class="card">
        <div class="card-body">
            @forelse($logs as $log)
                <div class="activity-item">
                    <div class="activity-icon" style="background: var(--bg-card-hover);">
                        {{ $log->action_info['icon'] }}
                    </div>
                    <div class="activity-content">
                        <div class="activity-text">{{ $log->description }}</div>
                        <div class="activity-meta">
                            <strong>{{ $log->user->name }}</strong> •
                            {{ $log->created_at->format('d/m/Y H:i') }} •
                            {{ $log->created_at->diffForHumans() }}
                        </div>
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <div class="empty-icon">📋</div>
                    <div class="empty-title">Tidak Ada Log</div>
                    <div class="empty-text">Belum ada aktivitas yang tercatat</div>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Pagination --}}
    <div class="mt-3">
        {{ $logs->withQueryString()->links() }}
    </div>
@endsection