{{-- resources/views/auth/login.blade.php --}}
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | SIAP BAPER RRI!</title>

    {{-- CSS & Icons --}}
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body class="login-body">

    <div class="login-container">
        <div class="login-branding">
            <img src="{{ asset('assets/images/rripthbc.png') }}" alt="Logo RRI" class="login-logo">
            <h2>SIAP BAPER RRI!</h2>
            <p>Sistem Informasi Permintaan Barang Persediaan
                Radio Republik Indonesia</p>
        </div>

        <div class="login-form">
            <form method="POST" action="{{ route('authenticate') }}">
                @csrf
                <h3>Login ke Akun Anda</h3>
                <p class="subtitle">Selamat datang kembali! Silakan masukkan alamat login anda.</p>

                <div class="form-group">
                    <label for="email">NIP atau Username</label>
                    <div class="input-wrapper">
                        <i class='bx bx-user'></i>
                        <input id="email" type="text" name="email" value="{{ old('email') }}" required
                            autofocus placeholder="Masukkan NIP atau Username">
                    </div>
                    @error('email')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>


                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-wrapper">
                        <i class='bx bx-lock-alt'></i>
                        <input id="password" type="password" name="password" required autocomplete="current-password"
                            placeholder="Masukkan password">
                    </div>
                    @error('password')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-options">
                    <div class="remember-me">
                        <input type="checkbox" name="remember" id="remember">
                        <label for="remember">Ingat Saya</label>
                    </div>
                    <a href="#" class="forgot-password">Lupa Password?</a>
                </div>

                <button type="submit" class="btn-login">Login</button>
            </form>
        </div>
    </div>

</body>

</html>
