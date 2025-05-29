<?php
//session_start();
require_once '../config/db.php';
require_once '../controllers/ReportController.php';

if (!isset($_SESSION['user'])) {
    header('Location: ../index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reportController = new ReportController($pdo);
    $reportController->handleStoreRequest($_POST, $_SESSION['user']);
} else {
    header("Location: ../index.php");
    exit();
}
