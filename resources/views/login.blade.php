<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Pangkalan LPG Modern</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-bootstrap-4/bootstrap-4.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            /* Menggunakan Flexbox untuk menengahkan konten */
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            /* Latar belakang gradien yang elegan */
            /* background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); */
            padding: 1rem;
        }

        .login-box {
            width: 100%;
            max-width: 450px;
            background: #ffffff;
            padding: 2.5rem;
            border-radius: 1rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .btn-primary {
            padding: 0.75rem;
            font-weight: 600;
        }
    </style>
</head>

<body>

    <div class="login-box">
        <div class="text-center mb-5">
            <h2 class="fw-bold mt-3">Selamat Datang</h2>
            <p class="text-muted">Silakan masukkan akun Anda.</p>
        </div>

        <form method="POST" action="{{ url('login') }}">
            @csrf

            <div class="form-floating mb-3">
                <input type="text" class="form-control" name="username" id="username" placeholder="Username"
                    required value="{{ old('username') }}" autofocus>
                <label for="username"><i class="bi bi-person me-2"></i>Username</label>
            </div>

            <div class="form-floating mb-3">
                <input type="password" class="form-control" name="password" id="password" placeholder="Password"
                    required>
                <label for="password"><i class="bi bi-lock me-2"></i>Password</label>
            </div>

            

            <button type="submit" class="btn btn-primary w-100 btn-lg">MASUK</button>

        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                timer: 2000,
                showConfirmButton: false
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: '{{ session('error') }}',
                timer: 2500,
                showConfirmButton: false
            });
        </script>
    @endif

</body>

</html>
