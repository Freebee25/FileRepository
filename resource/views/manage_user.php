<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: dashboard.php");
    exit;
}

require_once '../../database/db.php';

$stmt = $conn->query("SELECT * FROM users ORDER BY id DESC");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage User</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../fontawesome/css/font-awesome.min.css">
</head>
<body class="m-0 p-0">
<?php include '../template/navbar.php'; ?>

<div class="container mt-4">
    <h2>Manage User</h2>
    <a href="add_user.php" class="btn btn-success mb-3">+ Tambah User</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Username</th><th>Email</th><th>Role</th><th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $u): ?>
            <tr>
                <td><?= htmlspecialchars($u['username']) ?></td>
                <td><?= htmlspecialchars($u['email']) ?></td>
                <td><?= htmlspecialchars($u['role']) ?></td>
                <td>
                    <a href="edit_user.php?id=<?= $u['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                    <button 
                        class="btn btn-danger btn-sm" 
                        data-bs-toggle="modal" 
                        data-bs-target="#deleteModal" 
                        data-user-id="<?= $u['id'] ?>">
                        Hapus
                    </button>
                </td>
            </tr>
            <?php endforeach ?>
        </tbody>
    </table>

    <!-- Modal Konfirmasi Hapus -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered"> <!-- Tambahan class di sini -->
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>
        <div class="modal-body">
            Apakah kamu yakin ingin menghapus user ini?
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <a href="#" class="btn btn-danger" id="confirmDeleteBtn">Hapus</a>
        </div>
        </div>
    </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        var deleteModal = document.getElementById('deleteModal');
        var confirmDeleteBtn = document.getElementById('confirmDeleteBtn');

        deleteModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var userId = button.getAttribute('data-user-id');
            confirmDeleteBtn.href = "../../controllers/manageUserController.php?action=delete&id=" + userId;
        });
    </script>
</body>
</html>
