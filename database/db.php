<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "direksi";

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Koneksi gagal: " . $e->getMessage());
    }
    // echo "koneksi berhasil";
?>