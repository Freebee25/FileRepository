<?php
require '../database/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['nama_kategori'])) {
    $nama = trim($_POST['nama_kategori']);

    $stmt = $conn->prepare("SELECT id FROM kategori_files WHERE nama_kategori = ?");
    $stmt->execute([$nama]);
    if ($stmt->fetch()) {
        echo json_encode(['status' => 'error', 'message' => 'Kategori sudah ada.']);
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO kategori_files (nama_kategori) VALUES (?)");
    $stmt->execute([$nama]);

    echo json_encode([
        'status' => 'success',
        'id' => $conn->lastInsertId(),
        'nama' => htmlspecialchars($nama)
    ]);
    exit;
}

echo json_encode(['status' => 'error', 'message' => 'Permintaan tidak valid.']);
