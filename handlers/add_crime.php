<?php
// File: actions/submit_crime.php
require_once '../controllers/CrimeController.php';

$controller = new CrimeController($pdo);
$controller->store($_POST, $_SESSION['user']);
