<?php
require_once '../controllers/CrimeController.php';
$controller = new CrimeController($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->updateCrime($_POST);
    header("Location: ../views/crimes.php?msg=updated");
    exit;
}

