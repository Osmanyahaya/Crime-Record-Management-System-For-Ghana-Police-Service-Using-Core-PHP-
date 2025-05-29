<?php
session_start();
if ($_SESSION['user']['role'] !== 'admin') {
    header("Location: ../403.php");
    exit();
}

require_once '../config/db.php';

$name = $_POST['name'] ?? '';
$badge_number = $_POST['badge_number'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$role = $_POST['role'] ?? 'officer';

if (empty($name) || empty($email) || empty($password) || empty($role)) {
    $_SESSION['error'] = "Please fill in all required fields.";
    header("Location: ../views/add_user_form.php");
    exit();
}

$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

// Insert into DB
$stmt = $pdo->prepare("INSERT INTO users (name, badge_number, email, password, role) VALUES (?, ?, ?, ?, ?)");
$stmt->execute([$name, $badge_number, $email, $hashedPassword, $role]);

$_SESSION['success'] = "User added successfully.";
header("Location: ../views/manage_users.php");
exit();
