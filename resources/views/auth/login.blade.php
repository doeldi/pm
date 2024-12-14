<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
</head>

<body>
    <div class="login-container">
        <div class="login-form">
            <h2 class="login-title">Login</h2>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" class="form-control" required autofocus>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="password-toggle">
                        <input type="password" name="password" id="password" class="form-control" required>
                        <span class="toggle-password" onclick="showPassword()" id="toggleIcon">
                            <i class="bi bi-eye-slash"></i>
                        </span>
                    </div>
                    {{-- <small class="text-muted"><a href="{{ route('password.request') }}">Lupa password?</a></small> --}}
                </div>

                <button type="submit" class="btn btn-primary btn-login">Login</button>

                <div class="text-center mt-3">
                    <p>Belum punya akun? <a href="{{ route('register') }}">Daftar sekarang</a></p>
                </div>
            </form>
        </div>
        <div class="login-content">
            <h1>Selamat Datang di Pengaduan Masyarakat</h1>
            <p>Sampaikan laporan Anda langsung kepada instansi pemerintah berwenang. Login sekarang untuk mengakses akun Anda dan membuat pengaduan.</p>
        </div>
    </div>

    <script>
        function showPassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon').querySelector('i');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.className = 'bi bi-eye';
            } else {
                passwordInput.type = 'password';
                toggleIcon.className = 'bi bi-eye-slash';
            }
        }
    </script>
</body>

</html>