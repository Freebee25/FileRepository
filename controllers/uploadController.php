<?php
session_start();
require '../database/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../resource/views/login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Tanggal upload (otomatis di-generate)
    $tanggal_upload = date('Y-m-d H:i:s');
    $deskripsi = $_POST['deskripsi'];

    // Proses file
    if (isset($_FILES['file'])) {
        $file = $_FILES['file'];
        $file_name = $file['name']; // Nama asli file
        $file_tmp = $file['tmp_name'];
        $file_size = $file['size'];
        $file_error = $file['error'];

        // Validasi ekstensi file
        $valid_extensions = ['pdf', 'docx', 'xlsx', 'xls'];
        $file_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        if (!in_array($file_extension, $valid_extensions)) {
            header("Location: ../resource/views/upload.php?status=invalid_format");
            exit;
        }

        // Validasi ukuran file (maksimal 10MB)
        if ($file_size > 10 * 1024 * 1024) { // 10MB dalam bytes
            header("Location: ../resource/views/upload.php?status=file_too_large");
            exit;
        }

        // Cek apakah file dengan nama yang sama sudah ada
        $upload_dir = "../uploads/";
        $path = $upload_dir . basename($file_name);

        if (file_exists($path)) {
            header("Location: ../resource/views/upload.php?status=file_exists");
            exit;
        }

        // Pindahkan file ke folder "uploads"
        if (move_uploaded_file($file_tmp, $path)) {
            try {
                // Simpan data ke database, termasuk `nama_file`
                $stmt = $conn->prepare("INSERT INTO dokumen (nama_file, size_file, tanggal_upload, deskripsi, path) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$file_name, $file_size, $tanggal_upload, $deskripsi, $path]);

                // Redirect dengan status sukses
                header("Location: ../resource/views/upload.php?status=success");
                exit;
            } catch (PDOException $e) {
                header("Location: ../resource/views/upload.php?status=error");
                exit;
            }
        } else {
            header("Location: ../resource/views/upload.php?status=error");
            exit;
        }
    } else {
        header("Location: ../resource/views/upload.php?status=error");
        exit;
    }
}
?>
