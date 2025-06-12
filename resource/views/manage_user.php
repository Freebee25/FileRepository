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
    <link rel="stylesheet" href="../auth/css/bootstrap.min.css">
</head>
<body class="p-4">
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
                    <a href="../../controllers/manageUserController.php?action=delete&id=<?= $u['id'] ?>" 
                       class="btn btn-danger btn-sm" onclick="return confirm('Hapus user ini?')">Hapus</a>
                </td>
            </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</body>
</html>
