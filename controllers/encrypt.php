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
    $tanggal_upload = $_POST['tanggal_upload'] ?? date('Y-m-d H:i:s');
    $deskripsi = $_POST['deskripsi'] ?? '';
    $password = $_POST['password'] ?? '';
    $kategori_id = $_POST['kategori_id'] ?? '';

    // Pastikan kategori_id hanya berisi angka
    if (!ctype_digit($kategori_id)) {
        header("Location: ../resource/views/upload.php?status=invalid_kategori");
        exit;
    }

    if (isset($_FILES['file'])) {
        $allowedTypes = [
            'application/pdf',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/vnd.ms-excel'
        ];
        $allowedExtensions = ['pdf', 'docx', 'xlsx', 'xls'];
        $maxSize = 10 * 1024 * 1024;

        $file = $_FILES['file'];
        $originalName = basename($file['name']);
        $sizeFile = $file['size'];
        $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

        if ($file['error'] !== UPLOAD_ERR_OK) {
            header("Location: ../resource/views/upload.php?status=error");
            exit;
        }

        if (!in_array($file['type'], $allowedTypes) || !in_array($ext, $allowedExtensions)) {
            header("Location: ../resource/views/upload.php?status=invalid_format");
            exit;
        }

        if ($sizeFile > $maxSize) {
            header("Location: ../resource/views/upload.php?status=file_too_large");
            exit;
        }

        $uploadDir = "../uploads/";
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

        if (file_exists($uploadDir . $originalName)) {
            header("Location: ../resource/views/upload.php?status=file_exists");
            exit;
        }

        $encryptedName = uniqid('enc_') . '.enc';
        $encryptedPath = $uploadDir . $encryptedName;

        // Enkripsi
        $plaintext = file_get_contents($file['tmp_name']);
        $key = substr(hash('sha256', $password, true), 0, 16);
        $nonce = substr(md5(uniqid('', true)), 0, 8);

        $aes = new AES($key);
        $ctr = new CTR($aes);
        $ciphertext = $ctr->encrypt($plaintext, $nonce);

        $output = base64_encode($nonce . $ciphertext);
        file_put_contents($encryptedPath, $output);

        // Simpan metadata ke database
        $stmt = $conn->prepare("INSERT INTO dokumen 
            (nama_file, size_file, tanggal_upload, deskripsi, path, password, kategori_id)
            VALUES (:nama_file, :size_file, :tanggal_upload, :deskripsi, :path, :password, :kategori_id)");

        $success = $stmt->execute([
            ':nama_file'      => $originalName,
            ':size_file'      => $sizeFile,
            ':tanggal_upload' => $tanggal_upload,
            ':deskripsi'      => $deskripsi,
            ':path'           => $encryptedPath,
            ':password'       => password_hash($password, PASSWORD_BCRYPT),
            ':kategori_id'    => $kategori_id
        ]);

        if ($success) {
            header("Location: ../resource/views/upload.php?status=success");
        } else {
            header("Location: ../resource/views/upload.php?status=db_error");
        }
        exit;
    }
}
