<?php
session_start();
require_once '../database/db.php';

// Tangkap aksi hanya dari POST
$action = $_POST['action'] ?? '';

switch ($action) {
    case 'add':
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $role = $_POST['role'];
        
        $cek = $conn->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
        $cek->execute([$username]);

        if ($cek->fetchColumn() > 0) {
        $_SESSION['error'] = "Username sudah digunakan!";
        header("Location: ../resource/views/add_user.php");
        exit;
        }

        $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->execute([$username, $email, $password, $role]);

        $_SESSION['success'] = "User berhasil ditambahkan!";
        header("Location: ../resource/views/manage_user.php");
        exit;

    case 'update':
        $id = $_POST['id'];
        $username = $_POST['username'];
        $email = $_POST['email'];
        $role = $_POST['role'];
        $password = $_POST['password'];

        $cek = $conn->prepare("SELECT COUNT(*) FROM users WHERE username = ? AND id != ?");
        $cek->execute([$username, $id]);

        if ($cek->fetchColumn() > 0) {
        $_SESSION['error'] = "Username sudah digunakan oleh user lain!";
        header("Location: ../resource/views/edit_user.php?id=$id");
        exit;
        }

        if (!empty($password)) {
            $password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET username=?, email=?, password=?, role=? WHERE id=?");
            $stmt->execute([$username, $email, $password, $role, $id]);
        } else {
            $stmt = $conn->prepare("UPDATE users SET username=?, email=?, role=? WHERE id=?");
            $stmt->execute([$username, $email, $role, $id]);
        }

        $_SESSION['success'] = "Data user berhasil diperbarui!";
        header("Location: ../resource/views/manage_user.php?id=$id");
        exit;

    case 'delete':
        $id = $_POST['id'];

        if ($_SESSION['user']['role'] === 'admin') {
        $stmt = $conn->prepare("DELETE FROM users WHERE id=?");
        $stmt->execute([$id]);
        $_SESSION['success'] = "User berhasil dihapus!";
        } else {
        $_SESSION['error'] = "Anda tidak memiliki izin.";
       }
        
        header("Location: ../resource/views/manage_user.php");
        exit;
        

}


