<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['path'])) {
    $path = $_POST['path'];

    // Cek apakah file ada di server
    if (file_exists($path)) {
        // Header untuk mengatur proses download file
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($path) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($path));

        // Membaca file dan mengirimkannya ke output buffer
        readfile($path);
        exit;
    } else {
        echo "File tidak ditemukan.";
    }
} else {
    echo "Akses tidak valid.";
}
?>
