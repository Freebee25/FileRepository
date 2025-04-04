<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../fontawesome/css/font-awesome.min.css">
    <script src="../js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <div class="container mt-5">
        <div class="text-center">
            <!-- Welcome Message -->
            <div class="alert alert-info text-start" role="alert">
                <strong>Selamat Datang di Sistem Aplikasi</strong><br>
                Silahkan mengisi form di bawah untuk mendaftarkan akun baru.
            </div>
        </div>

        <!-- Register Form -->
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card shadow">
                    <div class="card-header text-center">
                        <h5 class="mb-0">Registrasi Akun</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="../../controllers/registerController.php">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username"
                                    placeholder="Masukkan username" required>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    placeholder="Masukkan email" required>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password"
                                    placeholder="Masukkan password" required>
                            </div>

                            <div class="mb-3">
                                <label for="verify_password" class="form-label">Konfirmasi Password</label>
                                <input type="password" class="form-control" id="verify_password" name="verify_password"
                                    placeholder="Ulangi password" required>
                            </div>

                            <button type="submit" class="btn btn-success w-100">Register</button>
                        </form>
                    </div>
                    <div class="card-footer text-center">
                        <a href="../views/login.php" class="btn btn-primary w-100">Kembali ke Login</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
