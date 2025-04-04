<?php
require '../../database/db.php'; // Pastikan path ini benar
session_start();

// Periksa apakah pengguna telah login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../resource/views/login.php");
    exit;
}

// Query untuk mengambil data dokumen
try {
    $stmt = $conn->prepare("SELECT * FROM dokumen");
    $stmt->execute();
    $files = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Jika terjadi kesalahan pada query, log error dan set files sebagai array kosong
    error_log("Database Error: " . $e->getMessage());
    $files = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Dokumen</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../fontawesome/css/font-awesome.min.css">
    <script src="../js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
</head>

<body>
    <?php include '../template/navbar.php'; ?>
    
    <div class="container mt-4">
        <div class="alert alert-info" role="alert">
            Selamat Datang di Halaman File Dokumen
        </div>
    </div>

    <div class="container">
        <div class="card">
            <div class="card-header bg-light text-black d-flex justify-content-between align-items-center">
                <span>Surat Masuk</span>
                <input type="text" id="searchInput" class="form-control w-25" placeholder="Cari dokumen...">
            </div>
            <div class="card-body">
                <table class="table table-bordered" id="documentTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama File</th>
                            <th>Ukuran</th>
                            <th>Tanggal Upload</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($files) > 0): ?>
                            <?php foreach ($files as $index => $file): ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td><?= htmlspecialchars($file['nama_file'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><?= number_format(htmlspecialchars($file['size_file'], ENT_QUOTES, 'UTF-8') / (1024 * 1024), 2) ?> MB</td>
                                    <td><?= htmlspecialchars($file['tanggal_upload'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td>
                                        <form action="../views/download.php" method="GET">
                                            <input type="hidden" name="id" value="<?= htmlspecialchars($file['id'], ENT_QUOTES, 'UTF-8') ?>">
                                            <button type="submit" class="btn btn-primary btn-sm">Detail & Download</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center">Tidak ada dokumen yang tersedia.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

                <!-- Pagination Controls -->
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center" id="pagination">
                        <!-- Pagination links will be dynamically generated -->
                    </ul>
                </nav>
            </div>
        </div>
    </div>

    <script>
    // Pagination functionality
    const rowsPerPage = 10; // Number of rows per page
    const table = document.getElementById('documentTable');
    const tbody = table.querySelector('tbody');
    const rows = Array.from(tbody.querySelectorAll('tr'));
    const pagination = document.getElementById('pagination');

    function displayPage(page) {
        const start = (page - 1) * rowsPerPage;
        const end = start + rowsPerPage;

        rows.forEach((row, index) => {
            row.style.display = index >= start && index < end ? '' : 'none';
        });
    }

    function createPagination() {
        const totalPages = Math.ceil(rows.length / rowsPerPage);
        pagination.innerHTML = '';

        for (let i = 1; i <= totalPages; i++) {
            const li = document.createElement('li');
            li.className = 'page-item';
            li.innerHTML = `<a class="page-link" href="#">${i}</a>`;
            pagination.appendChild(li);

            li.addEventListener('click', (e) => {
                e.preventDefault();
                displayPage(i);
                document.querySelectorAll('.page-item').forEach(item => item.classList.remove('active'));
                li.classList.add('active');
            });
        }

        pagination.querySelector('li')?.classList.add('active');
    }

    createPagination();
    displayPage(1);

    // Search functionality
    document.getElementById('searchInput').addEventListener('input', function () {
        const searchTerm = this.value.toLowerCase();
        rows.forEach(row => {
            const cells = Array.from(row.querySelectorAll('td'));
            const matches = cells.some(cell => cell.textContent.toLowerCase().includes(searchTerm));
            row.style.display = matches ? '' : 'none';
        });

        // Update pagination after search
        const visibleRows = rows.filter(row => row.style.display === '');
        const visibleRowCount = visibleRows.length;

        if (visibleRowCount <= rowsPerPage) {
            pagination.innerHTML = '';
        } else {
            createPagination();
        }
    });
</script>

</body>
</html>
