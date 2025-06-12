<?php
require_once '../../database/db.php';
$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<form action="../../controllers/manageUserController.php?action=update" method="POST">
    <input type="hidden" name="id" value="<?= $user['id'] ?>">
    <input type="text" name="username" value="<?= $user['username'] ?>" required class="form-control mb-2">
    <input type="email" name="email" value="<?= $user['email'] ?>" required class="form-control mb-2">
    <input type="password" name="password" placeholder="(Kosongkan jika tidak diubah)" class="form-control mb-2">
    <select name="role" class="form-control mb-2">
        <option <?= $user['role'] == 'user' ? 'selected' : '' ?> value="user">User</option>
        <option <?= $user['role'] == 'admin' ? 'selected' : '' ?> value="admin">Admin</option>
    </select>
    <button type="submit" class="btn btn-primary">Update</button>
</form>
