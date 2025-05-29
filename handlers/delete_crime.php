<?php
require_once '../controllers/CrimeController.php';


if (!isset($_SESSION['user'])) {
    header("Location: ../index.php");
    exit;
}


if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../views/403.php");
    exit;
}

$controller = new CrimeController();

if (isset($_GET['id'])) {
    $controller->deleteCrime($_GET['id']);
    header("Location: ../views/crime_list.php?msg=deleted");
    exit;
} else {
    echo "Crime ID not specified.";
}
