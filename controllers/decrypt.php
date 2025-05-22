<?php
session_start();
require '../database/db.php';
require '../helpers/aes.php';
require '../helpers/ctr.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['path'], $_POST['password'])) {
        die("Data tidak lengkap.");
    }

    $encryptedFilePath = $_POST['path'];
    $inputPassword = $_POST['password'];

    // Ambil informasi file dari database
    $stmt = $conn->prepare("SELECT * FROM dokumen WHERE path = :path");
    $stmt->execute([':path' => $encryptedFilePath]);
    $fileData = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$fileData) {
        die("Data file tidak ditemukan.");
    }

    // Verifikasi password
    if (!password_verify($inputPassword, $fileData['password'])) {
        echo "<script>alert('Password salah'); window.history.back();</script>";
        exit;
    }

    if (!file_exists($encryptedFilePath)) {
        die("File tidak ditemukan.");
    }

    try {
        // Ambil data terenkripsi dari file
        $encryptedData = file_get_contents($encryptedFilePath);

        // Ambil nonce (8 byte pertama) dan ciphertext (sisanya)
        $nonce = substr($encryptedData, 0, 8);
        $ciphertext = substr($encryptedData, 8);

        // Buat key dari password pengguna (harus sama seperti saat enkripsi)
        $key = substr(hash('sha256', $inputPassword, true), 0, 16);

        // Dekripsi menggunakan AES CTR
        $aes = new AES($key);
        $ctr = new CTR($aes);
        $decryptedData = $ctr->decrypt($ciphertext, $nonce);

        // Ambil nama asli file
        $originalName = $fileData['nama_file'];

        // Set header untuk proses download
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $originalName . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . strlen($decryptedData));

        // Output file ke browser
        echo $decryptedData;
        exit;

    } catch (Exception $e) {
        die("Terjadi kesalahan saat dekripsi: " . $e->getMessage());
    }
} else {
    echo "Akses tidak valid.";
}
