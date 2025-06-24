<?php 
require '../database/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Periksa username
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Cek apakah user ditemukan dan password benar
    if ($user && password_verify($password, $user['password'])) {
        session_start();

        // Simpan data user ke session
        $_SESSION['user'] = [
            'id' => $user['id'],
            'username' => $user['username'],
            'email' => $user['email'],
            'role' => $user['role']
        ];

        // Arahkan ke halaman utama/dashboard
        header("Location: ../resource/views/dashboard.php");
        exit;
    } else {
        echo "<script>
            alert('Username atau Password Salah!');
            window.history.back();
        </script>";
    }
}
