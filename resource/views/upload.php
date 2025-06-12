<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../resource/views/login.php");
    exit;
}

$notification = '';
if (isset($_GET['status'])) {
    switch ($_GET['status']) {
        case 'file_exists':
            $notification = "<div class='notification alert alert-danger'>File dengan nama yang sama sudah ada!</div>";
            break;
        case 'invalid_format':
            $notification = "<div class='notification alert alert-danger'>Hanya format PDF, DOCX, XLSX, dan XLS yang diperbolehkan.</div>";
            break;
        case 'file_too_large':
            $notification = "<div class='notification alert alert-danger'>Ukuran file terlalu besar. Maksimal 10MB.</div>";
            break;
        case 'error':
            $notification = "<div class='notification alert alert-danger'>Terjadi kesalahan saat mengupload file. Coba lagi.</div>";
            break;
        case 'success':
            $notification = "<div class='notification alert alert-success'>Dokumen berhasil diupload!</div>";
            break;
        case 'db_error':
            $notification = "<div class='notification alert alert-danger'>Gagal menyimpan data ke database.</div>";
            break;
        case 'invalid_kategori':
            $notification = "<div class='notification alert alert-danger'>Kategori tidak valid.</div>";
            break;
        default:
            $notification = "";
            break;
    }
}

require_once '../../database/db.php';

$kategoriOptions = '';
$stmt = $conn->query("SELECT * FROM kategori_files ORDER BY nama_kategori ASC");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $kategoriOptions .= "<option value='{$row['id']}'>{$row['nama_kategori']}</option>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Upload Dokumen</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../fontawesome/css/font-awesome.min.css">
    <style>
        .notification {
            margin-top: 20px;
            padding: 15px;
            font-size: 16px;
            border-radius: 5px;
            width: 100%;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <?php include '../template/navbar.php'; ?>
    <?php echo $notification; ?>

    <div class="container mt-4">
        <div class="card">
            <div class="card-header">Upload Dokumen</div>
            <div class="card-body">
                <form action="../../controllers/encrypt.php" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label">Tanggal Upload:</label>
                        <input type="text" class="form-control" value="<?php echo date('Y-m-d H:i:s'); ?>" readonly>
                        <input type="hidden" name="tanggal_upload" value="<?php echo date('Y-m-d H:i:s'); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="file" class="form-label">Pilih Dokumen:</label>
                        <input type="file" class="form-control" name="file" id="file" required>
                        <small class="form-text text-muted">Hanya file PDF, DOCX, XLSX, dan XLS yang diperbolehkan. Ukuran maksimal 10 MB.</small>
                    </div>
                    <div class="mb-3">
                        <label for="kategori_id" class="form-label">Kategori Dokumen:</label>
                        <select name="kategori_id" id="kategori_id" class="form-control" required>
                            <option value="">-- Pilih Kategori --</option>
                            <?php echo $kategoriOptions; ?>
                            <option value="tambah">+ Tambah Kategori Baru</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password:</label>
                        <input type="password" class="form-control" name="password" id="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi Dokumen:</label>
                        <textarea class="form-control" name="deskripsi" id="deskripsi" rows="3" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Upload</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Kategori -->
    <div class="modal fade" id="kategoriModal" tabindex="-1" aria-labelledby="kategoriModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <form id="formTambahKategori">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="kategoriModalLabel">Tambah Kategori Baru</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
              <div class="mb-3">
                <label for="nama_kategori" class="form-label">Nama Kategori</label>
                <input type="text" class="form-control" id="nama_kategori" name="nama_kategori" required>
              </div>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary">Simpan</button>
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            </div>
          </div>
        </form>
      </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('kategori_id').addEventListener('change', function () {
            if (this.value === 'tambah') {
                var modal = new bootstrap.Modal(document.getElementById('kategoriModal'));
                modal.show();
                this.value = "";
            }
        });

        $('#formTambahKategori').on('submit', function (e) {
            e.preventDefault();
            var namaKategori = $('#nama_kategori').val();

            $.post('../../controllers/tambah_kategori_ajax.php', { nama_kategori: namaKategori }, function (res) {
                if (res.status === 'success') {
                    $('#kategori_id').append(
                        `<option value="${res.id}" selected>${res.nama}</option>`
                    );
                    $('#kategoriModal').modal('hide');
                    $('#nama_kategori').val('');
                } else {
                    alert(res.message || 'Gagal menambah kategori.');
                }
            }, 'json');
        });
    </script>
</body>
</html>
