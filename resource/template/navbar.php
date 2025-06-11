<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../fontawesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="../css/navbar-hover.css">
    <script src="../js/bootstrap.bundle.min.js"></script>

    <style>
        .dropdown-menu.show {
            display: block;
        }
    </style>
</head>

<body>
    <header class="bg-light p-3 border-bottom">
        <div class="container d-flex justify-content-between align-items-center">
            <!-- Navigation Menu -->
            <nav>
                <ul class="nav">
                    <li class="nav-item">
                        <a href="../views/dashboard.php" class="nav-link text-black">Home</a>
                    </li>
                    <li class="nav-item">
                        <a href="../views/upload.php" class="nav-link text-black">Upload Dokumen</a>
                    </li>
                    <li class="nav-item">
                        <a href="../views/filedokumen.php" class="nav-link text-black">Dokumen File</a>
                    </li>
                </ul>
            </nav>

            <!-- User Dropdown -->
            <div class="dropdown">
                <button onclick="toggleDropdown()" id="userDropdown" class="btn btn-light dropdown-toggle" type="button">
                    <i class="fa fa-user"></i> <?php echo htmlspecialchars($_SESSION['username']); ?>
                </button>
                <div id="dropdownMenu" class="dropdown-menu dropdown-menu-end mt-2 shadow-sm">
                    <a class="dropdown-item" href="../views/manageakun.php">
                        <i class="fa fa-cog me-2"></i> Manage Akun
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item text-danger" href="../../controllers/logout.php">
                        <i class="fa fa-sign-out me-2"></i> Logout
                    </a>
                </div>
            </div>
        </div>
    </header>

    <script>
        function toggleDropdown() {
            const menu = document.getElementById('dropdownMenu');
            menu.classList.toggle('show');
        }

        // Tutup dropdown jika klik di luar
        window.addEventListener('click', function (e) {
            const button = document.getElementById('userDropdown');
            const menu = document.getElementById('dropdownMenu');

            if (!button.contains(e.target) && !menu.contains(e.target)) {
                menu.classList.remove('show');
            }
        });
    </script>
</body>

</html>
