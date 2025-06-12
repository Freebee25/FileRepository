<?php
require '../../database/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../resource/views/login.php");
    exit;
}

// Ambil semua kategori
try {
    $stmtKategori = $conn->prepare("SELECT * FROM kategori_files");
    $stmtKategori->execute();
    $kategoriList = $stmtKategori->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Kategori Error: " . $e->getMessage());
    $kategoriList = [];
}

// Ambil dokumen beserta nama kategori
try {
    $stmt = $conn->prepare("SELECT d.*, k.nama_kategori 
                            FROM dokumen d 
                            LEFT JOIN kategori_files k ON d.kategori_id = k.id");
    $stmt->execute();
    $files = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Database Error: " . $e->getMessage());
    $files = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>File Dokumen</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../fontawesome/css/font-awesome.min.css">
    <script src="../js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
</head>
<body>
<?php include '../template/navbar.php'; ?>

<div class="container mt-4">
    <div class="alert alert-info">Selamat Datang di Halaman File Dokumen</div>
</div>

<div class="container">
    <div class="card">
        <div class="card-header bg-light text-black d-flex justify-content-between align-items-center">
            <span>Surat Masuk</span>
            <div class="d-flex gap-2">
                <input type="text" id="searchInput" class="form-control" placeholder="Cari nama file / kategori...">
                <select id="filterKategori" class="form-select">
                    <option value="">Semua Kategori</option>
                    <?php foreach ($kategoriList as $kategori): ?>
                        <option value="<?= htmlspecialchars($kategori['nama_kategori']) ?>">
                            <?= htmlspecialchars($kategori['nama_kategori']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered" id="documentTable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama File</th>
                        <th>Kategori</th>
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
                                <td><?= htmlspecialchars($file['nama_file']) ?></td>
                                <td><?= htmlspecialchars($file['nama_kategori']) ?></td>
                                <td><?= number_format($file['size_file'] / (1024 * 1024), 2) ?> MB</td>
                                <td><?= htmlspecialchars($file['tanggal_upload']) ?></td>
                                <td>
                                    <form action="../views/download.php" method="GET">
                                        <input type="hidden" name="id" value="<?= htmlspecialchars($file['id']) ?>">
                                        <button type="submit" class="btn btn-primary btn-sm">Detail & Download</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="6" class="text-center">Tidak ada dokumen yang tersedia.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center" id="pagination"></ul>
            </nav>
        </div>
    </div>
</div>

<script>
const rowsPerPage = 10;
const table = document.getElementById('documentTable');
const tbody = table.querySelector('tbody');
const allRows = Array.from(tbody.querySelectorAll('tr'));
const pagination = document.getElementById('pagination');

let filteredRows = [...allRows];

function displayPage(page) {
    const start = (page - 1) * rowsPerPage;
    const end = start + rowsPerPage;

    filteredRows.forEach((row, i) => {
        row.style.display = (i >= start && i < end) ? '' : 'none';
    });
}

function createPagination() {
    const totalPages = Math.ceil(filteredRows.length / rowsPerPage);
    pagination.innerHTML = '';

    if (totalPages === 0) return;

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

function filterRows() {
    const keyword = document.getElementById('searchInput').value.toLowerCase();
    const selectedKategori = document.getElementById('filterKategori').value.toLowerCase();

    filteredRows = allRows.filter(row => {
        const namaFile = row.cells[1].textContent.toLowerCase();
        const kategori = row.cells[2].textContent.toLowerCase();
        const cocokKeyword = keyword === "" || namaFile.includes(keyword) || kategori.includes(keyword);
        const cocokKategori = selectedKategori === "" || kategori === selectedKategori;
        return cocokKeyword && cocokKategori;
    });

    allRows.forEach(row => row.style.display = 'none');

    if (filteredRows.length === 0) {
        tbody.innerHTML = `<tr><td colspan="6" class="text-center">Tidak ada hasil yang cocok.</td></tr>`;
        pagination.innerHTML = '';
    } else {
        // Kembalikan semua row ke tbody (jaga2 kalau ada innerHTML di-reset)
        tbody.innerHTML = '';
        filteredRows.forEach(row => tbody.appendChild(row));
        createPagination();
        displayPage(1);
    }
}

document.getElementById('searchInput').addEventListener('input', filterRows);
document.getElementById('filterKategori').addEventListener('change', filterRows);

// Inisialisasi
filterRows();
</script>

</body>
</html>
