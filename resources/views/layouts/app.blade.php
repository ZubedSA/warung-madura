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
</body>

</html>