<form action="../../controllers/manageUserController.php?action=add" method="POST">
    <input type="text" name="username" required placeholder="Username" class="form-control mb-2">
    <input type="email" name="email" required placeholder="Email" class="form-control mb-2">
    <input type="password" name="password" required placeholder="Password" class="form-control mb-2">
    <select name="role" class="form-control mb-2">
        <option value="user">User</option>
        <option value="admin">Admin</option>
    </select>
    <button type="submit" class="btn btn-primary">Simpan</button>
</form>
