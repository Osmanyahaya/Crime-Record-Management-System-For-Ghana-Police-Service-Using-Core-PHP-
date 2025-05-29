<?php
require_once '../config/db.php';
require_once '../controllers/SuspectController.php';
session_start();

$controller = new SuspectController($pdo);
$suspect = $controller->getById($_GET['id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Suspect</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <?php include 'header.php'; ?>
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-warning text-white">
            <h4 class="mb-0">Edit Suspect Information</h4>
        </div>
        <div class="card-body">
            <form action="../handlers/update_suspect.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= htmlspecialchars($suspect['id']) ?>">
                <input type="hidden" name="existing_photo" value="<?= htmlspecialchars($suspect['photo']) ?>">

                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($suspect['name']) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Age</label>
                    <input type="number" name="age" class="form-control" value="<?= htmlspecialchars($suspect['age']) ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">Gender</label>
                    <select name="gender" class="form-select">
                        <option value="Male" <?= $suspect['gender'] === 'Male' ? 'selected' : '' ?>>Male</option>
                        <option value="Female" <?= $suspect['gender'] === 'Female' ? 'selected' : '' ?>>Female</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Contact</label>
                    <input type="text" name="contact" class="form-control" value="<?= htmlspecialchars($suspect['contact']) ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">Address</label>
                    <input type="text" name="address" class="form-control" value="<?= htmlspecialchars($suspect['address']) ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="Arrested" <?= $suspect['status'] === 'Arrested' ? 'selected' : '' ?>>Arrested</option>
                        <option value="Wanted" <?= $suspect['status'] === 'Wanted' ? 'selected' : '' ?>>Wanted</option>
                        <option value="Released" <?= $suspect['status'] === 'Released' ? 'selected' : '' ?>>Released</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Current Photo</label><br>
                    <?php if (!empty($suspect['photo'])): ?>
                        <img src="../uploads/suspects/<?= htmlspecialchars($suspect['photo']) ?>" width="80" class="img-thumbnail">
                    <?php else: ?>
                        <span class="text-muted">No photo uploaded</span>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label class="form-label">New Photo</label>
                    <input type="file" name="photo" class="form-control" accept="image/*">
                </div>

                <button type="submit" class="btn btn-success w-100">Update Suspect</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
