<?php
require '../database/db.php';
require '../helpers/mailer.php';
require '../helpers/Sha3.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Periksa username
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Cek apakah user ditemukan
    if ($user && password_verify($password, $user['password'])) {

        $timestamp = time();
        $otp_raw = $username . $timestamp;

        // Gunakan SHA-3 untuk menghasilkan hash OTP
        $otp_hash = hash('sha3-256', $otp_raw);
        $otp = substr(preg_replace('/[^0-9]/', '', $otp_hash), 0, 6);

        $hashed_otp = hash('sha3-256', $otp);
        $otp_expires_at = date("Y-m-d H:i:s", strtotime("+2 minutes"));

        // Simpan OTP hash ke database
        $stmt = $conn->prepare("UPDATE users SET otp = ?, otp_expires_at = ? WHERE id = ?");
        $stmt->execute([$hashed_otp, $otp_expires_at, $user['id']]);

        // Kirim OTP ke email
        $subject = "Kode OTP Anda";
        $message = "<h2>Kode OTP Anda adalah: $otp</h2><p>Berlaku selama 2 menit.</p>";
        sendMail($user['email'], $subject, $message);

        session_start();

        // Simpan data user lengkap ke session
        $_SESSION['otp_user_id'] = $user['id'];
        $_SESSION['user'] = [
            'id' => $user['id'],
            'username' => $user['username'],
            'email' => $user['email'],
            'role' => $user['role']
        ];

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
