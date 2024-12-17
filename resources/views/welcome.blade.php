<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Pengaduan Masyarakat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-Zenh87qX5J7h4jtBWg3LG3Ym3bjdGY1iVruvfCzD8c7xhGibzQwLndFH4ad8icnDbC" crossorigin="anonymous" />
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f2f5;
        }

        .container {
            display: flex;
            min-height: 100vh;
        }

        .left-section {
            background-color: #0d6efd !important;
            color: white;
            padding: 50px;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: flex-start;
        }

        .left-section h1 {
            font-size: 3em;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .left-section p {
            font-size: 1.2em;
            line-height: 1.6;
            margin-bottom: 30px;
        }

        .left-section a {
            background-color: white;
            color: #0d6efd;
            padding: 12px 30px;
            font-size: 1.1em;
            border-radius: 30px;
            text-decoration: none;
            font-weight: bold;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
        }

        .left-section a:hover {
            background-color: #f0f2f5;
            color: #003d99;
        }

        .right-section {
            flex: 1;
            position: relative;
            overflow: hidden;
        }

        .right-section img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            filter: brightness(70%);
        }

        .right-section .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.4);
        }

        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }

            .left-section,
            .right-section {
                width: 100%;
                height: 50vh;
            }

            .left-section {
                padding: 30px;
                text-align: center;
                align-items: center;
            }

            .left-section a {
                padding: 10px 20px;
                font-size: 1em;
            }

            .right-section img {
                height: 50vh;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="left-section">
            <h1>Pengaduan Masyarakat</h1>
            <p>
                Sampaikan laporan Anda langsung kepada instansi pemerintah berwenang. 
                Login sekarang untuk mengakses akun Anda atau bergabung sebagai pengguna baru.
            </p>
            <a href="{{ route('auth.login.register.form') }}">Bergabung Sekarang</a>
        </div>
        <div class="right-section">
            <img alt="Aerial view of a city street with cars and trees"
                src="https://storage.googleapis.com/a1aa/image/ZtWrahp2neQ8QC1Zd7Gto183X7oAgvArJJFCL56B5MvU4h9JA.jpg" />
            <div class="overlay"></div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
</body>

</html>
