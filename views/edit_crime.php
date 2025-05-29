<?php
require_once '../controllers/CrimeController.php';
$controller = new CrimeController($pdo);

$id = $_GET['id'] ?? null;
$crime = $controller->show($id);

if (!$crime) {
    echo "Crime not found.";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Crime</title>
    <?php include 'header.php'; ?>

</head>
<body class="container">
    <h2>Edit Crime Record</h2>
    <form action="../handlers/update_crime.php" method="post">
        <input type="hidden" name="id" value="<?= $crime['id'] ?>">

        <div class="mb-3">
            <label>Case Number</label>
            <input type="text" name="case_number" value="<?= htmlspecialchars($crime['case_number']) ?>" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Title</label>
            <input type="text" name="title" value="<?= htmlspecialchars($crime['title']) ?>" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control"><?= htmlspecialchars($crime['description']) ?></textarea>
        </div>

        <div class="mb-3">
            <label>Type</label>
            <input type="text" name="crime_type" value="<?= htmlspecialchars($crime['crime_type']) ?>" class="form-control">
        </div>

        <div class="mb-3">
            <label>Location</label>
            <input type="text" name="location" value="<?= htmlspecialchars($crime['location']) ?>" class="form-control">
        </div>

        <div class="mb-3">
            <label>Status</label>
            <select name="status" class="form-control">
                <option value="open" <?= $crime['status'] == 'open' ? 'selected' : '' ?>>Open</option>
                <option value="under investigation" <?= $crime['status'] == 'under investigation' ? 'selected' : '' ?>>Under Investigation</option>
                <option value="closed" <?= $crime['status'] == 'closed' ? 'selected' : '' ?>>Closed</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Update Crime</button>
        <a href="crime_list.php" class="btn btn-secondary">Cancel</a>
    </form>
</body>
</html>
