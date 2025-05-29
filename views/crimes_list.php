<?php
require_once '../config/db.php';
require_once '../controllers/CrimeController.php';

$controller = new CrimeController($pdo);

// Get filter values from GET request
$status = $_GET['status'] ?? '';
$officer_id = $_GET['officer_id'] ?? '';
$date_from = $_GET['date_from'] ?? '';
$date_to = $_GET['date_to'] ?? '';

// Fetch list of officers for the dropdown
$officers = $controller->getAllOfficers();
$crimes = $controller->filterCrimes($status, $officer_id, $date_from, $date_to);
?>

<h2>All Reported Crimes</h2>

<form method="GET" style="margin-bottom: 20px;">
    <label>Status:
        <select name="status">
            <option value="">--All--</option>
            <option value="open" <?= $status == 'open' ? 'selected' : '' ?>>Open</option>
            <option value="under investigation" <?= $status == 'under investigation' ? 'selected' : '' ?>>Under Investigation</option>
            <option value="closed" <?= $status == 'closed' ? 'selected' : '' ?>>Closed</option>
        </select>
    </label>

    <label>Officer:
        <select name="officer_id">
            <option value="">--All--</option>
            <?php foreach ($officers as $officer): ?>
                <option value="<?= $officer['id'] ?>" <?= $officer_id == $officer['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($officer['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </label>

    <label>Date From:
        <input type="date" name="date_from" value="<?= htmlspecialchars($date_from) ?>">
    </label>

    <label>Date To:
        <input type="date" name="date_to" value="<?= htmlspecialchars($date_to) ?>">
    </label>

    <button type="submit">Filter</button>
</form>
