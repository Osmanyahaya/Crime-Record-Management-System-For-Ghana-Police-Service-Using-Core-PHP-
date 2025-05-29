<?php
session_start();
if ($_SESSION['user']['role'] !== 'admin') {
    header("Location: ../403.php");
    exit();
}

require_once '../config/db.php';

$id = $_POST['id'] ?? null;

if (!$id) {
    $_SESSION['error'] = "Invalid user ID.";
    header("Location: ../views/manage_users.php");
    exit();
}

// Prevent admin from deleting themselves
if ($_SESSION['user']['id'] == $id) {
    $_SESSION['error'] = "You cannot delete your own account.";
    header("Location: ../views/manage_users.php");
    exit();
}

// Delete user
$stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
$stmt->execute([$id]);

$_SESSION['success'] = "User deleted successfully.";
header("Location: ../views/manage_users.php");
exit();
