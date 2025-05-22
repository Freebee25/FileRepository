<?php
session_start();
require '../database/db.php';
require '../helpers/aes.php';
require '../helpers/ctr.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../resource/views/login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tanggal_upload = date('Y-m-d H:i:s');
    $deskripsi = $_POST['deskripsi'] ?? '';
    $password = $_POST['password'] ?? '';

    if (isset($_FILES['file'])) {
        $allowedTypes = [
            'application/pdf',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/vnd.ms-excel'
        ];
        $maxSize = 10 * 1024 * 1024; // 10MB

        $file = $_FILES['file'];

        if ($file['error'] !== UPLOAD_ERR_OK) {
            header("Location: ../resource/views/upload.php?status=error");
            exit;
        }

        if (!in_array($file['type'], $allowedTypes)) {
            header("Location: ../resource/views/upload.php?status=invalid_format");
            exit;
        }

        if ($file['size'] > $maxSize) {
            header("Location: ../resource/views/upload.php?status=file_too_large");
            exit;
        }

        $originalName = basename($file['name']);
        $sizeFile = $file['size'];
        $uploadDir = "../uploads/";
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

        $encryptedName = uniqid('enc_') . '.enc';
        $encryptedPath = $uploadDir . $encryptedName;

        // Cek apakah file original sudah pernah ada
        if (file_exists($uploadDir . $originalName)) {
            header("Location: ../resource/views/upload.php?status=file_exists");
            exit;
        }

        // Enkripsi
        $plaintext = file_get_contents($file['tmp_name']);
        $key = substr(hash('sha256', $password, true), 0, 16);
        $nonce = substr(md5(uniqid('', true)), 0, 8);

        $aes = new AES($key);
        $ctr = new CTR($aes);
        $ciphertext = $ctr->encrypt($plaintext, $nonce);

        $output = $nonce . $ciphertext;
        file_put_contents($encryptedPath, $output);

       // Simpan ke DB (tabel sesuai struktur yang kamu kirim)
        $stmt = $conn->prepare("INSERT INTO dokumen (nama_file, size_file, tanggal_upload, deskripsi, path, password)
        VALUES (:nama_file, :size_file, :tanggal_upload, :deskripsi, :path, :password)");

        $success = $stmt->execute([
        ':nama_file'      => $originalName,
        ':size_file'      => $sizeFile,
        ':tanggal_upload' => $tanggal_upload,
        ':deskripsi'      => $deskripsi,
        ':path'           => $encryptedPath,
        ':password'       => password_hash($password, PASSWORD_BCRYPT)
        ]);

        if ($success) {
        header("Location: ../resource/views/upload.php?status=success");
        } else {
        header("Location: ../resource/views/upload.php?status=db_error");
        }
        exit;

    }
}
?>
