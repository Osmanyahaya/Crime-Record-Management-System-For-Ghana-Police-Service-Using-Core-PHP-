
<?php
session_start();
require_once '../config/db.php';
require_once '../controllers/ReportController.php';

$reportId = $_POST['report_id'];
$text = $_POST['report_text'];

$reportController = new ReportController($pdo);
$report = $reportController->getReportById($reportId);

// Only original author or admin can update
if ($_SESSION['user']['id'] != $report['submitted_by'] && $_SESSION['user']['role'] != 'admin') {
    die("Unauthorized");
}

$reportController->updateReport($reportId, $text);
header("Location: ../views/crime_details.php?id=" . $report['crime_id']);
