<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tambah User</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../fontawesome/css/font-awesome.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<?php include '../template/navbar.php'; ?>

<div class="container mt-4">
    <div class="card">
        <div class="card-header bg-light text-black d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Tambah User</h5>
        </div>
        <div class="card-body">
            <form action="../../controllers/manageUserController.php" method="POST">
                <input type="hidden" name="action" value="add">

                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" name="username" id="username" class="form-control" placeholder="Masukkan username" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" class="form-control" placeholder="example@gmail.com" required>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan password" required>
                </div>

                <div class="mb-3">
                    <label for="role" class="form-label">Role</label>
                    <select name="role" id="role" class="form-select" required>
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>

                <div class="d-flex justify-content-between mt-4">
                <a href="filedokumen.php" class="btn btn-danger">
                    <i class="fa fa-arrow-left"></i> Kembali
                </a>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Notifikasi SweetAlert jika sukses -->
<?php if (isset($_SESSION['success'])): ?>
<script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: '<?= $_SESSION['success'] ?>',
        showConfirmButton: false,
        timer: 2000
    });
</script>
<?php unset($_SESSION['success']); endif; ?>

<!-- Notifikasi gagal -->
<?php if (isset($_SESSION['error'])): ?>
<script>
    Swal.fire({
        icon: 'error',
        title: 'Gagal',
        text: '<?= $_SESSION['error'] ?>',
        showConfirmButton: true
    });
</script>
<?php unset($_SESSION['error']); endif; ?>

<!-- Bootstrap Bundle JS -->
<script src="../js/bootstrap.bundle.min.js"></script>
</body>
</html>
