<?php
session_start();
if ($_SESSION['user']['role'] !== 'admin') {
    header("Location: ../403.php");
    exit();
}

require_once '../config/db.php';

$id = $_POST['id'];
$name = $_POST['name'];
$badge_number = $_POST['badge_number'] ?? '';
$email = $_POST['email'];
$role = $_POST['role'];
$password = $_POST['password'];

// Prepare SQL based on whether password was provided
if (!empty($password)) {
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    $stmt = $pdo->prepare("UPDATE users SET name = ?, badge_number = ?, email = ?, role = ?, password = ? WHERE id = ?");
    $stmt->execute([$name, $badge_number, $email, $role, $hashedPassword, $id]);
} else {
    $stmt = $pdo->prepare("UPDATE users SET name = ?, badge_number = ?, email = ?, role = ? WHERE id = ?");
    $stmt->execute([$name, $badge_number, $email, $role, $id]);
}

$_SESSION['success'] = "User updated successfully.";
header("Location: ../views/manage_users.php");
exit();
