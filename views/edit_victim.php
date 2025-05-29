<?php
require_once '../config/db.php';
require_once '../controllers/VictimController.php';
session_start();

$controller = new VictimController($pdo);
$victim = $controller->getById($_GET['id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Victim Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <?php include 'header.php'; ?>
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Edit Victim Information</h4>
        </div>
        <div class="card-body">
            <form action="../handlers/update_victim.php" method="POST">
                <input type="hidden" name="id" value="<?= htmlspecialchars($victim['id']) ?>">
                <input type="hidden" name="crime_id" value="<?= htmlspecialchars($victim['crime_id']) ?>">

                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($victim['name']) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Age</label>
                    <input type="number" name="age" class="form-control" value="<?= htmlspecialchars($victim['age']) ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">Gender</label>
                    <select name="gender" class="form-select">
                        <option value="Male" <?= $victim['gender'] == 'Male' ? 'selected' : '' ?>>Male</option>
                        <option value="Female" <?= $victim['gender'] == 'Female' ? 'selected' : '' ?>>Female</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Contact</label>
                    <input type="text" name="contact" class="form-control" value="<?= htmlspecialchars($victim['contact']) ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">Address</label>
                    <input type="text" name="address" class="form-control" value="<?= htmlspecialchars($victim['address']) ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">Statement</label>
                    <textarea name="statement" class="form-control" rows="4"><?= htmlspecialchars($victim['statement']) ?></textarea>
                </div>

                <button type="submit" class="btn btn-success w-100">Update Victim</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
