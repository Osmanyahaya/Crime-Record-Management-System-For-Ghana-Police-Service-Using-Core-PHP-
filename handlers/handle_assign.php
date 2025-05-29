<?php
session_start();
require_once '../config/db.php';

if ($_SESSION['user']['role'] !== 'admin') {
    die("Access denied.");
}

$reportId = $_POST['report_id'];
$assignedTo = $_POST['assigned_to'];

// Basic validation
if (!$reportId || !$assignedTo) {
    die("Missing data.");
}

$stmt = $pdo->prepare("UPDATE crime_reports SET assigned_to = ? WHERE id = ?");
$stmt->execute([$assignedTo, $reportId]);

header("Location: crimes.php?message=Case assigned successfully");
exit;
