<html>
    <head>
    <!DOCTYPE html>
<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../fontawesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="../css/logo.css">
    <script src="../js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <div class="header">
        <img src="../img/apn.png" alt="logo" class="logo">
    </div>
    <div class="container mt-5 clearfix" >
        <div class="text-center">
            <!-- Welcome Message -->
            <div class="alert alert-info text-start " role="alert">
                <strong>Selamat Datang di Sistem Aplikasi</strong><br>
                Silahkan menggunakan akun yang anda miliki untuk login.
            </div>
        </div>

        <!-- Login Form -->
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card shadow">
                    <div class="card-header text-center">
                        <h5 class="mb-0">Masuk aplikasi</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="../../controllers/loginController.php">
                            <input type="hidden" name="action" value="login">

                            <div class="mb-3">
                                <label for="username" class="form-label">User name</label>
                                <input type="text" class="form-control" id="username" name="username"
                                    placeholder="Masukkan username" required>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password"
                                    placeholder="Masukkan password" required>
                            </div>

                            <button type="submit" class="btn btn-success w-100">Login</button>
                        </form>
                    </div>
                    <div class="card-footer text-center">
                        <a href="../views/register.php" class="btn btn-primary w-100">Register</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
