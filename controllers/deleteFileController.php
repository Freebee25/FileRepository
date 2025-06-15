<?php
require '../database/db.php';
session_start();

$user = $_SESSION['user'] ?? null;

if (!isset($_SESSION['user_id']) || !$user || strtolower($user['role'] ?? '') !== 'admin') {
    die("Akses ditolak.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];

    try {
        $stmt = $conn->prepare("SELECT path FROM dokumen WHERE id = ?");
        $stmt->execute([$id]);
        $file = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($file && file_exists($file['path'])) {
            unlink($file['path']);
        }

        $stmt = $conn->prepare("DELETE FROM dokumen WHERE id = ?");
        $stmt->execute([$id]);

        header("Location: ../resource/views/filedokumen.php?status=deleted");
        exit;
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
} else {
    die("Permintaan tidak valid.");
}
