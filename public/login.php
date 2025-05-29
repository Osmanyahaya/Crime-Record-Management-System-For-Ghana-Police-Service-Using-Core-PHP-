<?php
session_start();
require_once '../config/db.php';

$email = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($password, $user['password'])) {
    $_SESSION['user'] = [
        'id' => $user['id'],
        'name' => $user['name'],
        'badge_number' => $user['badge_number'],
        'email' => $user['email'],
        'role' => $user['role']
    ];
    header("Location: ../views/dashboard.php");
    exit();
} else {
    $_SESSION['error'] = "Invalid login details!";
    header("Location: index.php");
    exit();
}
