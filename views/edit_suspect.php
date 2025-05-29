<?php
require_once '../config/db.php';
require_once '../controllers/SuspectController.php';
session_start();

$controller = new SuspectController($pdo);
$suspect = $controller->getById($_GET['id']);
?>
<head>
    <title>Update Victim</title>
    <?php include 'header.php'; ?>
</head>
<h3>Edit Suspect</h3>
<form action="../handlers/update_suspect.php" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?= $suspect['id'] ?>">
    <input type="hidden" name="existing_photo" value="<?= $suspect['photo'] ?>">

    Name: <input type="text" name="name" value="<?= htmlspecialchars($suspect['name']) ?>" required><br>
    Age: <input type="number" name="age" value="<?= htmlspecialchars($suspect['age']) ?>"><br>
    Gender: 
    <select name="gender">
        <option <?= $suspect['gender'] === 'Male' ? 'selected' : '' ?>>Male</option>
        <option <?= $suspect['gender'] === 'Female' ? 'selected' : '' ?>>Female</option>
    </select><br>
    Contact: <input type="text" name="contact" value="<?= htmlspecialchars($suspect['contact']) ?>"><br>
    Address: <input type="text" name="address" value="<?= htmlspecialchars($suspect['address']) ?>"><br>
    Status:
    <select name="status">
        <option <?= $suspect['status'] === 'Arrested' ? 'selected' : '' ?>>Arrested</option>
        <option <?= $suspect['status'] === 'Wanted' ? 'selected' : '' ?>>Wanted</option>
        <option <?= $suspect['status'] === 'Released' ? 'selected' : '' ?>>Released</option>
    </select><br>

    Current Photo:
    <?php if (!empty($suspect['photo'])): ?>
        <img src="../uploads/suspects/<?= $suspect['photo'] ?>" width="60"><br>
    <?php else: ?>
        No photo uploaded<br>
    <?php endif; ?>

    New Photo: <input type="file" name="photo" accept="image/*"><br>

    <button type="submit">Update Suspect</button>
</form>

