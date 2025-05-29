<?php
require_once '../config/db.php';
require_once '../controllers/ReportController.php';

session_start();

$controller = new ReportController($pdo);
$cidReports = $controller->getCIDReports();
?>

<!DOCTYPE html>
<html>
<head>
    <title>CID Reports</title>
    <?php include 'header.php'; ?>
    <style>
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>

</head>
<body>
    <h1>CID Reports</h1>

    <?php if (isset($_SESSION['success'])): ?>
        <p style="color: green;"><?= $_SESSION['success']; unset($_SESSION['success']); ?></p>
    <?php endif; ?>

    <table>
        <thead>
            <tr><th>View Crime</th>
                <th>Crime Title</th>
                <th>Findings</th>
                <th>Submitted By</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cidReports as $report): ?>
                <tr>
                    <td><a href="crime_details.php?id=<?= htmlspecialchars($report['crime_identity']) ?>" 
                                   class="btn btn-sm btn-primary">
                                    View
                                </a></td>
                    <td><?= htmlspecialchars($report['title']) ?></td>
                    <td><?= nl2br(htmlspecialchars($report['report_text'])) ?></td>
                  </td>
                    <td><?= htmlspecialchars($report['officer_name']) ?></td>
                    <td><?= htmlspecialchars($report['created_at']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
