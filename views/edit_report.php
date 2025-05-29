<?php
session_start();

require_once '../config/db.php';
require_once '../controllers/ReportController.php';

// Get report ID from URL
$reportId = $_GET['report_id'] ?? null;

// Load report from database
$reportController = new ReportController($pdo);
$report = $reportController->getReportById($reportId);

// Access control
if (!$report || ($_SESSION['user']['id'] != $report['submitted_by'] && $_SESSION['user']['role'] !== 'admin')) {
    die("Access denied.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Report</title>
    <?php include 'header.php'; ?>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 2rem;
        }
        form {
            max-width: 600px;
            margin-top: 1rem;
        }
        textarea {
            width: 100%;
            padding: 1rem;
            font-size: 1rem;
            border-radius: 6px;
            border: 1px solid #ccc;
        }
        button {
            margin-top: 1rem;
            padding: 0.6rem 1.5rem;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <h2>Edit Report</h2>

    <form action="../handlers/update_report.php" method="POST">
        <input type="hidden" name="report_id" value="<?= htmlspecialchars($report['id']) ?>">

        <label for="report_text"><strong>Report Text:</strong></label><br>
        <textarea name="report_text" id="report_text" rows="6" required><?= htmlspecialchars($report['report_text']) ?></textarea>

        <button type="submit">Update Report</button>
    </form>
</body>
</html>
