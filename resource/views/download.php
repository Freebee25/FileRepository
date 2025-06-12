<?php
require '../../database/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../resource/login.php");
    exit;
}

if (!isset($_GET['id'])) {
    die("ID dokumen tidak ditemukan.");
}

$id = $_GET['id'];

try {
    $stmt = $conn->prepare("SELECT d.*, k.nama_kategori FROM dokumen d LEFT JOIN kategori_files k ON d.kategori_id = k.id WHERE d.id = ?");
    $stmt->execute([$id]);
    $file = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$file) {
        die("Dokumen tidak ditemukan.");
    }
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Detail Dokumen</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../fontawesome/css/font-awesome.min.css">
</head>
<body>
<?php include '../template/navbar.php'; ?>
<div class="container mt-5">
    <h2>Detail File</h2>
    <p class="text-muted">Informasi lengkap file terkait</p>
    <div class="card">
        <div class="card-header bg-light"><strong>Download</strong></div>
        <div class="card-body">
            <p><strong>Nama File Sumber:</strong> <?= htmlspecialchars($file['nama_file']) ?></p>
            <p><strong>Ukuran File:</strong> <?= $file['size_file'] ?> bytes</p>
            <p><strong>Kategori:</strong> <?= htmlspecialchars($file['nama_kategori']) ?></p>
            <p><strong>Tanggal Upload:</strong> <?= htmlspecialchars($file['tanggal_upload']) ?></p>
            <p><strong>Keterangan:</strong> <?= htmlspecialchars($file['deskripsi']) ?></p>

            <div class="d-flex justify-content-between mt-4">
                <a href="filedokumen.php" class="btn btn-danger">
                    <i class="fa fa-arrow-left"></i> Kembali
                </a>

                <!-- Modal Password -->
                <div class="modal fade" id="passwordModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <form action="../../controllers/decrypt.php" method="POST" class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Masukkan Password</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="path" value="<?= htmlspecialchars($file['path']) ?>">
                                <div class="mb-3">
                                    <label>Password File</label>
                                    <input type="password" class="form-control" name="password" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button class="btn btn-success" type="submit"><i class="fa fa-download"></i> Download</button>
                            </div>
                        </form>
                    </div>
                </div>

                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#passwordModal">
                    <i class="fa fa-download"></i> Download File
                </button>
            </div>
        </div>
    </div>
</div>
<script src="../js/bootstrap.bundle.min.js"></script>
</body>
</html>
