<?php
require_once '../config/db.php';
require_once '../controllers/SuspectController.php';
session_start();

$controller = new SuspectController($pdo);
$controller->store($_POST);
