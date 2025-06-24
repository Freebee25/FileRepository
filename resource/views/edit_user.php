<?php
session_start();
require_once '../../database/db.php';

// Ambil data user berdasarkan ID
$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: manage_user.php");
    exit;
}

$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Jika user tidak ditemukan
if (!$user) {
    header("Location: manage_user.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit User</title>
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
            <h5 class="mb-0">Edit User</h5>
        </div>
        <div class="card-body">
            <form action="../../controllers/manageUserController.php" method="POST">
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="id" value="<?= htmlspecialchars($user['id']) ?>">

                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" name="username" id="username" value="<?= htmlspecialchars($user['username']) ?>" required class="form-control">
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" value="<?= htmlspecialchars($user['email']) ?>" required class="form-control">
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" id="password" class="form-control" placeholder="(Kosongkan jika tidak diubah)">
                </div>

                <div class="mb-3">
                    <label for="role" class="form-label">Role</label>
                    <select name="role" id="role" class="form-select" required>
                        <option value="user" <?= $user['role'] === 'user' ? 'selected' : '' ?>>User</option>
                        <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                    </select>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="manage_user.php" class="btn btn-secondary">
                        <i class="fa fa-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Notifikasi SweetAlert jika update berhasil -->
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


<script src="../js/bootstrap.bundle.min.js"></script>
</body>
</html>
