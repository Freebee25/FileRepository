<?php
require '../database/db.php';
require '../helpers/mailer.php';
require '../helpers/sha256.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Periksa username
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Cek apakah user ditemukan
    if ($user && password_verify($password, $user['password'])) {
        // Generate OTP berdasarkan SHA-256
        $timestamp = time(); // Waktu saat ini dalam detik
        $otp_raw = $username . $timestamp;
        $otp = substr(preg_replace('/[^0-9]/', '', SHA256::make($otp_raw)), 0, 6); // Ambil 6 angka pertama
        $hashed_otp = SHA256::make($otp); // Hash OTP untuk disimpan
        $otp_expires_at = date("Y-m-d H:i:s", strtotime("+2 minutes"));

        // Simpan hash OTP ke database
        $stmt = $conn->prepare("UPDATE users SET otp = ?, otp_expires_at = ? WHERE id = ?");
        $stmt->execute([$hashed_otp, $otp_expires_at, $user['id']]);

        // Kirim OTP via email (teks biasa)
        $subject = "Kode OTP Anda";
        $message = "<h2>Kode OTP Anda adalah: $otp</h2><p>Berlaku selama 2 menit.</p>";
        sendMail($user['email'], $subject, $message);

        session_start();
        $_SESSION['otp_user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];

        header("Location: ../resource/views/verify_otp.php");
        exit;
    } else {
        echo "<script>
            alert('Username atau Password Salah!');
            window.history.back();
        </script>";
    }
}
?>
