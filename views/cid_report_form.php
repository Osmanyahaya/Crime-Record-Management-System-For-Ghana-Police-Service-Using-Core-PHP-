<?php
session_start();
require_once '../config/db.php';
include 'header.php';

if ($_SESSION['user']['role'] !== 'cid' && $_SESSION['user']['role'] !== 'admin') {
    header("Location: 403.php");
    exit();
}

// Fetch open/under-investigation crimes
$stmt = $pdo->query("SELECT id, case_number, title FROM crimes WHERE status != 'closed'");
$crimes = $stmt->fetchAll();
?>

<div class="container mt-4">
    <h2>Submit CID Report</h2>
    <form action="../handlers/add_report.php" method="POST">
        <input type="hidden" name="action" value="submit_cid_report">

        <div class="mb-3">
            <label for="crime_id" class="form-label">Select Crime</label>
            <select name="crime_id" id="crime_id" class="form-control" required>
                <option value="">-- Select --</option>
                <?php foreach ($crimes as $crime): ?>
                    <option value="<?= $crime['id'] ?>">
                        <?= htmlspecialchars($crime['case_number'] . ' - ' . $crime['title']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Findings</label>
            <textarea name="report_text" class="form-control" rows="4" required></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Submit Report</button>
    </form>
</div>

<?php include 'footer.php'; ?>
