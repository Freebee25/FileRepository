<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../fontawesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="../css/navbar-hover.css">
    <script src="../js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <header class="bg-light text-white p-3">
        <div class="container d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <!-- <img src="logo.png" alt="Pertamina Logo" style="width: 150px; height: auto;"> -->
            </div>
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
            <a href="../../controllers/logout.php" class="btn btn-light">
                <i class="fa fa-sign-out"></i> Logout</a>
        </div>
    </header>
</body>
