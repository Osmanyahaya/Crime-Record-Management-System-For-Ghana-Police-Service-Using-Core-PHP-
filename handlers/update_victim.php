<?php
require_once '../config/db.php';
require_once '../controllers/VictimController.php';
session_start();

$controller = new VictimController($pdo);
$controller->update($_POST['id'], $_POST);
