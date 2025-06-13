<?php
require_once '../../database/db.php';
$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit User</title>
    <link rel="stylesheet" href="../auth/css/bootstrap.min.css">
    <link rel="stylesheet" href="../fontawesome/css/font-awesome.min.css">
</head>
<body class="m-0 p-0">
<?php include '../template/navbar.php'; ?>
<div class="container mt-4">
    <h2>Edit User</h2>
    <form action="../../controllers/manageUserController.php?action=update" method="POST">
        <input type="hidden" name="id" value="<?= $user['id'] ?>">
        <input type="text" name="username" value="<?= $user['username'] ?>" required class="form-control mb-2">
        <input type="email" name="email" value="<?= $user['email'] ?>" required class="form-control mb-2">
        <input type="password" name="password" placeholder="(Kosongkan jika tidak diubah)" class="form-control mb-2">
        <select name="role" class="form-control mb-3">
            <option <?= $user['role'] == 'user' ? 'selected' : '' ?> value="user">User</option>
            <option <?= $user['role'] == 'admin' ? 'selected' : '' ?> value="admin">Admin</option>
        </select>
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="manage_user.php" class="btn btn-secondary">Back</a>
    </form>
</div>
</body>
</html>
