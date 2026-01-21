<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Faktur</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="auth-body">
    <form class="login-card" action="{{ route('login.attempt') }}" method="POST">
        @csrf
        <h1>Masuk</h1>
        <p>Gunakan akun pengguna untuk melanjutkan.</p>

        <div class="form-group">
            <label for="email">Email</label>
            <input class="input-field" type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
            @error('email')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input class="input-field" type="password" id="password" name="password" required>
            @error('password')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="auth-actions">
            <input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
            <label for="remember">Ingat saya</label>
        </div>

        <button class="btn" type="submit">Masuk</button>
    </form>
</body>
</html>
