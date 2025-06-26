<?php
require '../../database/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../resource/views/login.php");
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

<div id="notifArea" class="container mt-3"></div>

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

                <div>
                <?php if (isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'admin') : ?>
                        <!-- Tombol Hapus File -->
                        <button type="button" class="btn btn-outline-danger me-2" data-bs-toggle="modal" data-bs-target="#deleteModal">
                            <i class="fa fa-trash"></i> Hapus File
                        </button>
                    <?php endif; ?>

                    <!-- Tombol Download File -->
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#passwordModal">
                        <i class="fa fa-download"></i> Download File
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Download -->
<div class="modal fade" id="passwordModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <form id="decryptForm" class="modal-content" method="post" action="javascript:void(0);" autocomplete="off">
      <div class="modal-header">
        <h5 class="modal-title">Masukkan Password</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">

        <!-- 🛑 Anti autofill username palsu -->
        <input type="text" name="fakeusernameremembered" style="display:none" autocomplete="username">
        <input type="password" name="fakepasswordremembered" style="display:none" autocomplete="new-password">

        <input type="hidden" name="path" value="<?= htmlspecialchars($file['path']) ?>">

        <div class="mb-3">
          <label>Password File</label>
          <input
            type="password"
            class="form-control"
            name="password"
            autocomplete="new-password"
            required
          >
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button class="btn btn-success" type="submit" id="submitDecrypt">
          <i class="fa fa-download"></i> Download
        </button>
      </div>
    </form>
  </div>
</div>


<!-- Modal Hapus File (khusus admin) -->
<?php if (isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'admin') : ?>
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="../../controllers/deleteFileController.php" method="POST" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus file <strong><?= htmlspecialchars($file['nama_file']) ?></strong>?</p>
                <input type="hidden" name="id" value="<?= $file['id'] ?>">
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button class="btn btn-danger" type="submit"><i class="fa fa-trash"></i> Hapus</button>
            </div>
        </form>
    </div>
</div>
<?php endif; ?>

<script src="../js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById('decryptForm');
    const modalEl = document.getElementById('passwordModal');
    const notifArea = document.getElementById('notifArea');

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(form);
        const startTime = performance.now();

        fetch("../../controllers/decrypt.php", {
            method: "POST",
            body: formData
        })
        .then(response => {
            if (!response.ok) throw new Error("Gagal dekripsi");

            const filename = response.headers.get("Content-Disposition")
                ?.split("filename=")[1]?.replace(/"/g, "") || "file_dekripsi";

            return response.blob().then(blob => {
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement("a");
                a.href = url;
                a.download = filename;
                document.body.appendChild(a);
                a.click();
                a.remove();
                window.URL.revokeObjectURL(url);

                const endTime = performance.now();
                const duration = ((endTime - startTime) / 1000).toFixed(2);

                // Tutup modal secara paksa dan bersihkan backdrop
                const bsModal = bootstrap.Modal.getInstance(modalEl);
                if (bsModal) bsModal.hide();

                document.body.classList.remove('modal-open');
                const backdrop = document.querySelector('.modal-backdrop');
                if (backdrop) backdrop.remove();

                // Tampilkan notifikasi
                notifArea.innerHTML = `
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        ✅ File berhasil didekripsi dan diunduh dalam ${duration} detik.
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `;
            });
        })
        .catch(error => {
            // Tutup modal jika error juga
            const bsModal = bootstrap.Modal.getInstance(modalEl);
            if (bsModal) bsModal.hide();

            document.body.classList.remove('modal-open');
            const backdrop = document.querySelector('.modal-backdrop');
            if (backdrop) backdrop.remove();

            notifArea.innerHTML = `
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    ❌ Gagal mendekripsi file: ${error.message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;
        });
    });
});
</script>


</body>
</html>
