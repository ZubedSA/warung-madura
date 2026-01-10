<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#0F172A">

    <title>@yield('title', 'Warung Madura') - Sistem Manajemen</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    @stack('styles')
</head>

<body>
    <div class="app-container">
        @auth
            <!-- Mobile Header (Visible only on mobile) -->
            <div class="mobile-header">
                <div class="flex items-center gap-3">
                    <button id="sidebarToggle"
                        class="p-2 -ml-2 rounded-lg text-gray-400 hover:text-white hover:bg-white/10">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                    <div class="flex items-center gap-2">
                        <div
                            class="w-8 h-8 rounded-lg bg-gradient-to-br from-primary to-primary-dark flex items-center justify-center text-lg">
                            🏪</div>
                        <span class="font-bold text-lg">Warung</span>
                    </div>
                </div>
                <div class="p-1 rounded-full bg-surface border border-white/10">
                    <img src="https://ui-avatars.com/api/?name={{ auth()->user()->name }}&background=8B5CF6&color=fff&size=32"
                        class="w-8 h-8 rounded-full" alt="User">
                </div>
            </div>

            <!-- Mobile Sidebar Overlay -->
            <div id="sidebarOverlay" class="sidebar-overlay"></div>

            @if(auth()->user()->isPemilik())
                @include('layouts.partials.sidebar-admin')
            @else
                @include('layouts.partials.header')
            @endif
        @endauth

        <main class="main-content">
            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="alert alert-success animate-slideUp">
                    <span class="alert-icon">✅</span>
                    <span class="alert-content">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-error animate-slideUp">
                    <span class="alert-icon">❌</span>
                    <span class="alert-content">{{ session('error') }}</span>
                </div>
            @endif

            @if(session('info'))
                <div class="alert alert-info animate-slideUp">
                    <span class="alert-icon">ℹ️</span>
                    <span class="alert-content">{{ session('info') }}</span>
                </div>
            @endif

            @if(session('warning'))
                <div class="alert alert-warning animate-slideUp">
                    <span class="alert-icon">⚠️</span>
                    <span class="alert-content">{{ session('warning') }}</span>
                </div>
            @endif

            @yield('content')
        </main>

        @auth
            @if(auth()->user()->isPenjaga())
                @include('layouts.partials.bottom-nav')
            @endif
        @endauth
    </div>

    @stack('scripts')

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const toggle = document.getElementById('sidebarToggle');
            const closeBtn = document.getElementById('sidebarClose');
            const sidebar = document.querySelector('.sidebar');
            const overlay = document.getElementById('sidebarOverlay');

            if (sidebar && overlay) {
                // Function to toggle sidebar
                const toggleSidebar = (show) => {
                    if (show === undefined) {
                        sidebar.classList.toggle('active');
                        overlay.classList.toggle('active');
                    } else if (show) {
                        sidebar.classList.add('active');
                        overlay.classList.add('active');
                    } else {
                        sidebar.classList.remove('active');
                        if (sidebar.scrollTop) sidebar.scrollTop = 0; // Reset scroll
                        overlay.classList.remove('active');
                    }

                    // Prevent body scroll when sidebar is open
                    document.body.style.overflow = sidebar.classList.contains('active') ? 'hidden' : '';
                };

                if (toggle) {
                    toggle.addEventListener('click', (e) => {
                        e.stopPropagation();
                        toggleSidebar(true);
                    });
                }

                if (closeBtn) {
                    closeBtn.addEventListener('click', (e) => {
                        e.stopPropagation();
                        toggleSidebar(false);
                    });
                }

                overlay.addEventListener('click', () => {
                    toggleSidebar(false);
                });

                // Close on escape key
                document.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape' && sidebar.classList.contains('active')) {
                        toggleSidebar(false);
                    }
                });
            }
        });
    </script>
</body>

</html>