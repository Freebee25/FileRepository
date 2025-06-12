<?php
require '../../database/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirect jika belum login
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

// Ambil jumlah total file
try {
    $stmt = $conn->prepare("SELECT COUNT(*) AS total_files FROM dokumen");
    $stmt->execute();
    $totalFiles = $stmt->fetch(PDO::FETCH_ASSOC)['total_files'];
} catch (PDOException $e) {
    $totalFiles = 0;
}

// Ambil 5 data terbaru
try {
    $stmt = $conn->prepare("SELECT * FROM dokumen ORDER BY tanggal_upload DESC LIMIT 5");
    $stmt->execute();
    $recentFiles = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $recentFiles = [];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../fontawesome/css/font-awesome.min.css">
    <script src="../js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <?php include '../template/navbar.php'; ?>

    <div class="container mt-4">
        <div class="alert alert-info" role="alert">
            Selamat Datang di Sistem Aplikasi
            <strong><?= htmlspecialchars($_SESSION['user']['username']) ?></strong><br>
        </div>

        <!-- Card Jumlah File -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card text-white bg-primary">
                    <div class="card-body">
                        <h5 class="card-title">Jumlah Dokumen</h5>
                        <p class="card-text">
                            <strong><?= $totalFiles ?></strong> file tersedia
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel 5 Data Terbaru -->
        <div class="row">
            <div class="col-md-12">
                <h5>Dokumen Terbaru</h5>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama File</th>
                            <th>Ukuran</th>
                            <th>Tanggal Upload</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($recentFiles) > 0): ?>
                            <?php foreach ($recentFiles as $index => $file): ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td><?= htmlspecialchars($file['nama_file']) ?></td>
                                    <td><?= number_format($file['size_file'] / (1024 * 1024), 2) ?> MB</td>
                                    <td><?= htmlspecialchars($file['tanggal_upload']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center">Tidak ada dokumen terbaru.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

                <!-- Link Lihat Selengkapnya -->
                <div class="text-end">
                    <a href="../views/filedokumen.php" class="btn btn-link">Lihat Selengkapnya &raquo;</a>
                </div>
            </div>
        </div>
    </div>

</body>

</html>
