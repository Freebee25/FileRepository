<?php
require_once '../database/db.php';

$action = $_GET['action'] ?? '';
switch ($action) {
    case 'add':
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $role = $_POST['role'];
        $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->execute([$username, $email, $password, $role]);
        break;

    case 'update':
        $id = $_POST['id'];
        $username = $_POST['username'];
        $email = $_POST['email'];
        $role = $_POST['role'];
        $password = $_POST['password'];

        if (!empty($password)) {
            $password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET username=?, email=?, password=?, role=? WHERE id=?");
            $stmt->execute([$username, $email, $password, $role, $id]);
        } else {
            $stmt = $conn->prepare("UPDATE users SET username=?, email=?, role=? WHERE id=?");
            $stmt->execute([$username, $email, $role, $id]);
        }
        break;

    case 'delete':
        $id = $_GET['id'];
        $stmt = $conn->prepare("DELETE FROM users WHERE id=?");
        $stmt->execute([$id]);
        break;
}

header("Location: ../resource/views/manage_user.php");
exit;
