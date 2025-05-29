<?php
session_start();
require_once '../config/db.php';
require_once '../controllers/ReportController.php';

$reportId = $_POST['report_id'];
$reportController = new ReportController($pdo);
$report = $reportController->getReportById($reportId);

if ($_SESSION['user']['id'] != $report['submitted_by'] && $_SESSION['user']['role'] != 'admin') {
    die("Unauthorized");
}

$reportController->deleteReport($reportId);
header("Location: ../views/crime_details.php?id=" . $report['crime_id']);
