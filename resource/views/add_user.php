<!DOCTYPE html>
<html>
<head>
    <title>Add User</title>
    <link rel="stylesheet" href="../auth/css/bootstrap.min.css">
    <link rel="stylesheet" href="../fontawesome/css/font-awesome.min.css">
</head>
<body class="m-0 p-0">
<?php include '../template/navbar.php'; ?>
<div class="container mt-4">
    <h2>Tambah User</h2>
    <form action="../../controllers/manageUserController.php?action=add" method="POST">
        <table class="table borderless">
            <tr>
                <td><input type="text" name="username" required placeholder="Username" class="form-control"></td>
            </tr>
            <tr>
                <td><input type="email" name="email" required placeholder="Example@gmail.com" class="form-control"></td>
            </tr>
            <tr>
                <td><input type="password" name="password" required placeholder="Password" class="form-control"></td>
            </tr>
            <tr>
                <td>
                    <select name="role" class="form-control">
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="manage_user.php" class="btn btn-secondary">Back</a>
                </td>
            </tr>
        </table>
    </form>
</div>
</body>
</html>
