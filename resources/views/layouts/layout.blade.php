<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Manajemen Siswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0f2f5;
        }

        .navbar {
            background: linear-gradient(135deg, #1a73e8, #0052cc);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.6rem;
            letter-spacing: 0.5px;
        }

        .navbar .nav-link {
            color: rgba(255, 255, 255, 0.9) !important;
            font-weight: 500;
            padding: 0.7rem 1.2rem;
            transition: all 0.3s ease;
            border-radius: 8px;
        }

        .navbar .nav-link:hover {
            color: #ffffff !important;
            background-color: rgba(255, 255, 255, 0.1);
            transform: translateY(-2px);
        }

        .navbar .nav-link.active {
            color: #ffffff !important;
            background-color: rgba(255, 255, 255, 0.2);
        }

        .dropdown-menu {
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
            border: none;
            padding: 0.8rem;
        }

        .dropdown-item {
            padding: 0.8rem 1.5rem;
            border-radius: 8px;
            transition: all 0.2s ease;
            font-weight: 500;
        }

        .dropdown-item:hover {
            background-color: #f0f7ff;
            transform: translateX(5px);
            color: #1a73e8;
        }

        .container {
            background-color: #ffffff;
            border-radius: 20px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
            padding: 2.5rem;
            margin-top: 2.5rem;
            margin-bottom: 2.5rem;
        }

        h2 {
            color: #1a73e8;
            font-weight: 700;
            margin-bottom: 1.8rem;
            border-bottom: 3px solid #1a73e8;
            padding-bottom: 0.8rem;
            font-size: 2rem;
        }

        .fas {
            margin-right: 0.7rem;
        }

        .nav-item.dropdown.bg-white {
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .nav-item.dropdown.bg-white:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .navbar-toggler {
            border: none;
            padding: 0.5rem;
        }

        .navbar-toggler:focus {
            box-shadow: none;
            outline: none;
        }

        @media (max-width: 768px) {
            .navbar-nav {
                padding: 1.2rem 0;
            }

            .dropdown-menu {
                margin-top: 0.8rem;
            }

            .container {
                padding: 1.5rem;
                margin-top: 1.5rem;
                margin-bottom: 1.5rem;
            }

            h2 {
                font-size: 1.8rem;
            }
        }

        /* Animasi hover untuk semua elemen interaktif */
        a,
        button {
            transition: all 0.3s ease;
        }
    </style>

    @stack('styles')
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="fa-solid fa-message"></i> Pengaduan
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 gap-2">
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('home') ? 'active' : '' }}" href="{{ route('home') }}">
                            <i class="fa-solid fa-house"></i> Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('report.data-report') ? 'active' : '' }}"
                            href="{{ route('report.data-report') }}">
                            <i class="fa-solid fa-newspaper"></i> Article
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('report.myReports') }}"
                            class="nav-link {{ Route::is('report.myReports') ? 'active' : '' }}"><i
                                class="fa-solid fa-user"></i> Me
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('responses.index') }}"
                            class="nav-link {{ Route::is('responses.index') ? 'active' : '' }}"><i
                                class="fa-solid fa-message"></i> Data-Report
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('staff.index') }}"
                            class="nav-link {{ Route::is('staff.index') ? 'active' : '' }}"><i
                                class="fa-solid fa-users"></i> Staff
                        </a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown"
                            role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="text-white px-2 py-1 rounded"
                                style="background: linear-gradient(135deg, #3498db, #2980b9);">
                                <i class="fa-solid fa-circle-user me-1"></i>
                                {{ Auth::user()->email }}
                            </span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0" aria-labelledby="userDropdown">
                            <li>
                                <a class="dropdown-item py-2 px-4 d-flex align-items-center"
                                    href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">
                                    <i class="fa-solid fa-right-from-bracket me-2"></i>
                                    <span>Logout</span>
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container">
        <h2>@yield('title')</h2>
        <div>
            @yield('content')
        </div>
    </div>

    @stack('script')

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQ+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script>
</body>

</html>
