<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Register</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .login-container {
            display: flex;
            height: 100vh;
        }

        .login-form {
            flex: 1;
            padding: 50px;
            background-color: #ffffff;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .login-content {
            flex: 1;
            padding: 50px;
            background-color: #0d6efd !important;
            color: #ffffff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .login-title {
            text-align: center;
            color: #0d6efd;
            margin-bottom: 30px;
        }

        .form-group label {
            font-weight: bold;
        }

        .btn-login {
            width: 100%;
            padding: 10px;
            font-size: 18px;
            background-color: #0d6efd !important;
        }

        .btn-register {
            width: 100%;
            padding: 10px;
            font-size: 18px;
            background-color: #f8f9fa !important;
            border: #0d6efd 2px solid;
            color: #0d6efd;
        }

        .ornament {
            font-size: 72px;
            margin-bottom: 20px;
        }

        .password-toggle {
            position: relative;
        }

        .password-toggle .toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="login-form">
            <h2 class="login-title">Login / Register</h2>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('auth.login.register') }}" method="POST" class="auth-form">
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

                <div id="confirm-password-section" class="form-group d-none">
                    <label for="password_confirmation">Confirm Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                </div>

                <button type="submit" name="action" value="login" class="btn btn-primary btn-login">Login</button>
                <button type="submit" name="action" value="register" class="btn text-primary btn-register mt-2">Register</button>
            </form>
        </div>
        <div class="login-content">
            <h1>Selamat Datang di Pengaduan Masyarakat</h1>
            <p>Sampaikan laporan Anda langsung kepada instansi pemerintah berwenang. Login sekarang untuk mengakses akun Anda dan membuat pengaduan.</p>
        </div>
    </div>

    <script>
        const registerButton = document.querySelector('button[value="register"]');
        const loginButton = document.querySelector('button[value="login"]');
        const confirmPasswordSection = document.getElementById('confirm-password-section');

        // Show Confirm Password only for Register
        registerButton.addEventListener('click', function () {
            confirmPasswordSection.classList.remove('d-none');
        });

        loginButton.addEventListener('click', function () {
            confirmPasswordSection.classList.add('d-none');
        });

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
