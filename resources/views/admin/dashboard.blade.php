@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
    <div class="page-header mb-4">
        <div>
            <h1 class="page-title text-2xl font-bold mb-1">Dashboard Pemilik <span class="text-xl">📊</span></h1>
            <p class="text-muted">{{ now()->translatedFormat('l, d F Y') }}</p>
        </div>
        {{-- Optional: Add Action Button here --}}
    </div>

    {{-- Hero Stats Section --}}
    <div class="stat-grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        {{-- Profit Card (Hero) --}}
        <div class="stat-card-premium {{ $monthlyProfit >= 0 ? 'success' : 'danger' }} md:col-span-2">
            <div class="flex justify-between items-start">
                <div>
                    <div class="stat-label mb-2">Net Profit Bulan Ini</div>
                    <div class="stat-value">Rp {{ number_format(abs($monthlyProfit), 0, ',', '.') }}</div>
                    <div class="text-sm opacity-90 mt-1">
                        {{ $monthlyProfit >= 0 ? '📈 Untung' : '📉 Rugi' }}
                    </div>
                </div>
                <div class="icon-wrapper bg-white/20">
                    {{ $monthlyProfit >= 0 ? '💰' : '💸' }}
                </div>
            </div>
        </div>

        {{-- Income Card --}}
        <div class="stat-card-premium bg-card">
            <div class="flex justify-between items-start">
                <div>
                    <div class="stat-label text-muted mb-2">Pemasukan Hari Ini</div>
                    <div class="stat-value text-success">Rp {{ number_format($todayIncome, 0, ',', '.') }}</div>
                </div>
                <div class="icon-wrapper text-success bg-success/10">
                    📥
                </div>
            </div>
        </div>

        {{-- Expense Card --}}
        <div class="stat-card-premium bg-card">
            <div class="flex justify-between items-start">
                <div>
                    <div class="stat-label text-muted mb-2">Pengeluaran Hari Ini</div>
                    <div class="stat-value text-danger">Rp {{ number_format($todayExpense, 0, ',', '.') }}</div>
                </div>
                <div class="icon-wrapper text-danger bg-danger/10">
                    📤
                </div>
            </div>
        </div>
    </div>

    {{-- Stock Status Grid (4 Columns) --}}
    <div class="mb-8">
        <h3 class="text-lg font-semibold mb-4 flex items-center gap-2">
            <span class="bg-primary/20 p-2 rounded-lg text-primary text-sm">📦</span>
            Status Stok Barang
        </h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="stat-card-premium bg-card hover:border-danger group cursor-pointer"
                onclick="window.location='{{ route('penjaga.stok.status', 'kosong') }}'">
                <div class="text-center">
                    <div class="text-3xl font-bold text-danger mb-1">{{ $stockStats['kosong'] }}</div>
                    <div
                        class="text-xs font-bold text-muted uppercase tracking-wider group-hover:text-danger transition-colors">
                        Kosong</div>
                </div>
            </div>
            <div class="stat-card-premium bg-card hover:border-warning group cursor-pointer"
                onclick="window.location='{{ route('penjaga.stok.status', 'sedikit') }}'">
                <div class="text-center">
                    <div class="text-3xl font-bold text-warning mb-1">{{ $stockStats['sedikit'] }}</div>
                    <div
                        class="text-xs font-bold text-muted uppercase tracking-wider group-hover:text-warning transition-colors">
                        Sedikit</div>
                </div>
            </div>
            <div class="stat-card-premium bg-card hover:border-info group cursor-pointer"
                onclick="window.location='{{ route('penjaga.stok.status', 'cukup') }}'">
                <div class="text-center">
                    <div class="text-3xl font-bold text-info mb-1">{{ $stockStats['cukup'] }}</div>
                    <div
                        class="text-xs font-bold text-muted uppercase tracking-wider group-hover:text-info transition-colors">
                        Cukup</div>
                </div>
            </div>
            <div class="stat-card-premium bg-card hover:border-success group cursor-pointer"
                onclick="window.location='{{ route('penjaga.stok.status', 'banyak') }}'">
                <div class="text-center">
                    <div class="text-3xl font-bold text-success mb-1">{{ $stockStats['banyak'] }}</div>
                    <div
                        class="text-xs font-bold text-muted uppercase tracking-wider group-hover:text-success transition-colors">
                        Banyak</div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Monthly Summary --}}
        <div class="col-span-1 lg:col-span-2">
            <div class="card h-full">
                <div class="card-header border-0 pb-0">
                    <h3 class="card-title flex items-center gap-2">
                        <span class="bg-primary/20 p-2 rounded-lg text-primary text-sm">💵</span>
                        Ikhtisar Keuangan Bulan Ini
                    </h3>
                </div>
                <div class="card-body">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                        <div class="p-4 rounded-xl bg-success/5 border border-success/20">
                            <div class="text-sm text-muted mb-1">Total Pemasukan</div>
                            <div class="text-xl font-bold text-success">Rp {{ number_format($monthlyIncome, 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="p-4 rounded-xl bg-danger/5 border border-danger/20">
                            <div class="text-sm text-muted mb-1">Total Pengeluaran</div>
                            <div class="text-xl font-bold text-danger">Rp {{ number_format($monthlyExpense, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 pt-6 border-t border-dashed border-gray-700">
                        <div class="flex justify-between items-end">
                            <div>
                                <div class="text-sm text-muted mb-1">Estimasi Profit Bersih</div>
                                <div class="text-xs text-muted">Pendapatan - Pengeluaran</div>
                            </div>
                            <div class="text-2xl font-bold {{ $monthlyProfit >= 0 ? 'text-success' : 'text-danger' }}">
                                {{ $monthlyProfit >= 0 ? '+' : '-' }} Rp
                                {{ number_format(abs($monthlyProfit), 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Active Orders / Recent Activity --}}
        <div class="col-span-1">
            <div class="card h-full">
                <div class="card-header border-0 pb-2">
                    <h3 class="card-title flex items-center gap-2">
                        <span class="bg-warning/20 p-2 rounded-lg text-warning text-sm">⚡</span>
                        Aktivitas Terbaru
                    </h3>
                    <a href="{{ route('admin.log.index') }}" class="text-xs text-primary hover:underline">Lihat Semua</a>
                </div>
                <div class="card-body p-0">
                    @if($recentLogs->count() > 0)
                        <div class="activity-list space-y-4 px-6 pb-6">
                            @foreach($recentLogs->take(5) as $log)
                                <div class="flex gap-3 relative pb-4 border-l border-gray-700 last:border-0 pl-4 last:pb-0">
                                    <div class="absolute -left-[5px] top-1 w-2.5 h-2.5 rounded-full bg-primary ring-4 ring-bg-card">
                                    </div>
                                    <div>
                                        <div class="text-sm text-gray-300 font-medium">{{ $log->description }}</div>
                                        <div class="text-xs text-muted mt-0.5">
                                            {{ $log->user->name }} • {{ $log->created_at->diffForHumans() }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-muted">
                            <div class="text-2xl mb-2">💤</div>
                            <div>Belum ada aktivitas</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection