<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="theme-color" content="#0F172A">

    <title>Login - Warung Madura</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

<body>
    <div class="login-container">
        <div class="login-card animate-slideUp">
            <div class="login-logo">
                <div class="login-logo-icon">🏪</div>
                <h1 class="login-title">Warung Madura</h1>
                <p class="login-subtitle">Sistem Manajemen Warung</p>
            </div>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group">
                    <label class="form-label" for="email">Email</label>
                    <input type="email" id="email" name="email" class="form-input form-input-lg"
                        placeholder="email@contoh.com" value="{{ old('email') }}" required autofocus>
                    @error('email')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-input form-input-lg"
                        placeholder="••••••••" required>
                    @error('password')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="flex gap-1" style="cursor: pointer;">
                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        <span style="color: var(--text-secondary);">Ingat saya</span>
                    </label>
                </div>

                <button type="submit" class="btn btn-primary btn-xl btn-block">
                    Masuk
                </button>
            </form>
        </div>
    </div>
</body>

</html>