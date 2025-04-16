<?php
require '../database/db.php';
require '../helpers/mailer.php';
require '../helpers/Sha3.php'; 

session_start();

// Pastikan user ID dari sesi ada
if (!isset($_SESSION['otp_user_id'])) {
    die("Session expired. Please log in again.");
}

$user_id = $_SESSION['otp_user_id'];

// Ambil data user
$stmt = $conn->prepare("SELECT username, email FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    // Generate OTP baru menggunakan SHA-3
    $timestamp = time();
    $otp_raw = $user['username'] . $timestamp;
    
    $otp_hash = hash('sha3-256', $otp_raw);
    $otp = substr(preg_replace('/[^0-9]/', '', $otp_hash), 0, 6); // Ambil 6 angka pertama

    $hashed_otp = hash('sha3-256', $otp);
    $otp_expires_at = date("Y-m-d H:i:s", strtotime("+2 minutes"));

    // Simpan hash OTP baru ke database
    $stmt = $conn->prepare("UPDATE users SET otp = ?, otp_expires_at = ? WHERE id = ?");
    $stmt->execute([$hashed_otp, $otp_expires_at, $user_id]);

    // Kirim OTP via email
    $subject = "Kode OTP Baru Anda";
    $message = "<h2>Kode OTP Anda adalah: $otp</h2><p>Berlaku selama 2 menit.</p>";
    sendMail($user['email'], $subject, $message);

    $_SESSION['success_message'] = "Kode OTP baru telah dikirim.";
    header("Location: ../resource/views/verify_otp.php");
    exit;
} else {
    $_SESSION['error_message'] = "User tidak ditemukan!";
    header("Location: ../resource/views/verify_otp.php");
    exit;
}
?>
