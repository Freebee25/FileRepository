<?php
require '../database/db.php';
require '../helpers/mailer.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $verify_password = $_POST['verify_password'];

    // Validasi password
    if ($password !== $verify_password) {
        die("<script>
            alert('Password dan Verify Password tidak cocok!');
            window.history.back();
        </script>");
    }

    // Cek apakah username sudah ada
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $usernameExists = $stmt->fetch();

    if ($usernameExists) {
        die("<script>
            alert('Username sudah digunakan. Silakan gunakan yang lain.');
            window.history.back();
        </script>");
    }

    // Cek apakah email sudah ada
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $emailExists = $stmt->fetch();

    if ($emailExists) {
        die("<script>
            alert('Email sudah digunakan. Silakan gunakan yang lain.');
            window.history.back();
        </script>");
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Simpan data ke database
    $stmt = $conn->prepare("INSERT INTO users (username, email, password, created_at) VALUES (?, ?, ?, NOW())");
    $stmt->execute([$username, $email, $hashed_password]);

    // Kirim notifikasi email
    $subject = "Pendaftaran Berhasil";
    $message = "
        <h2>Selamat, Anda telah berhasil mendaftar!</h2>
        <p>Berikut adalah detail akun Anda:</p>
        <ul>
            <li><strong>Username:</strong> $username</li>
            <li><strong>Email:</strong> $email</li>
            <li><strong>Password:</strong> $password</li>
        </ul>
        <p>Harap simpan informasi ini dengan baik.</p>
    ";
    sendMail($email, $subject, $message);

    // Notifikasi sukses dan pengalihan ke halaman login
    echo "<script>
        alert('Pendaftaran berhasil! Silakan login.');
        window.location.href = '../resource/views/login.php';
    </script>";
}
?>
