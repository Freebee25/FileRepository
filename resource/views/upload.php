<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../resource/views/login.php");
    exit;
}

// Notifikasi jika ada error atau success
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
        default:
            $notification = "";
            break;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

    <!-- Notifikasi -->
    <?php echo $notification; ?>

    <div class="container mt-4">
        <div class="card">
            <div class="card-header">Upload Dokumen</div>
            <div class="card-body">
                <form action="../../controllers/uploadController.php" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="tanggal_upload" class="form-label">Tanggal Upload:</label>
                        <input type="text" class="form-control" name="tanggal_upload" id="tanggal_upload" value="<?php echo date('Y-m-d H:i:s'); ?>" disabled>
                    </div>
                    <div class="mb-3">
                        <label for="file" class="form-label">Pilih Dokumen:</label>
                        <input type="file" class="form-control" name="file" id="file" required>
                        <small class="form-text text-muted">Hanya file PDF, DOCX, XLSX, dan XLS yang diperbolehkan. Ukuran file maksimal 10 MB.</small>
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
</body>
</html>
