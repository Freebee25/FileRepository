<?php
require '../database/db.php';
require '../helpers/Sha3.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    session_start();

    // Pastikan user ID dari sesi ada
    if (!isset($_SESSION['otp_user_id'])) {
        die("Session expired. Please log in again.");
    }

    $user_id = $_SESSION['otp_user_id'];
    $otp = $_POST['otp'];

    // Ambil data OTP dan waktu kedaluwarsa dari database
    $stmt = $conn->prepare("SELECT otp, otp_expires_at FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $current_time = (new DateTime())->format('Y-m-d H:i:s');

        // Hash OTP input menggunakan SHA256 sebelum validasi
        $hashed_input_otp = hash('sha3-256', (string)$otp);

        // Validasi OTP dan waktu kedaluwarsa
        if ($hashed_input_otp === $user['otp'] && $current_time <= $user['otp_expires_at']) {
            // Login berhasil
            unset($_SESSION['otp_user_id']); 

            $stmt = $conn->prepare("UPDATE users SET otp = NULL, otp_expires_at = NULL WHERE id = ?");
            $stmt->execute([$user_id]);

            // Set session login
            $_SESSION['logged_in'] = true;
            $_SESSION['user_id'] = $user_id;

            // Arahkan ke dashboard.php
            header("Location: ../resource/views/dashboard.php");
            exit;
        } else {
            // OTP salah atau telah kedaluwarsa
            $_SESSION['error_message'] = "OTP tidak valid atau telah kedaluwarsa!";
            header("Location: ../resource/views/verify_otp.php");
            exit;
        }
    } else {
        $_SESSION['error_message'] = "User tidak ditemukan!";
        header("Location: ../resource/views/verify_otp.php");
        exit;
    }
}
?>
