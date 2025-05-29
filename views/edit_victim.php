<?php
require_once '../config/db.php';
require_once '../controllers/VictimController.php';
session_start();

$controller = new VictimController($pdo);
$victim = $controller->getById($_GET['id']);
?>
<head>
    <title>Add Victim</title>
    <?php include 'header.php'; ?>
</head>
<form action="../handlers/update_victim.php" method="POST">
    <input type="hidden" name="id" value="<?= $victim['id'] ?>">
    <input type="hidden" name="crime_id" value="<?= $victim['crime_id'] ?>">
    Name: <input type="text" name="name" value="<?= $victim['name'] ?>" required><br>
    Age: <input type="number" name="age" value="<?= $victim['age'] ?>"><br>
    Gender: 
    <select name="gender">
        <option <?= $victim['gender'] == 'Male' ? 'selected' : '' ?>>Male</option>
        <option <?= $victim['gender'] == 'Female' ? 'selected' : '' ?>>Female</option>
    </select><br>
    Contact: <input type="text" name="contact" value="<?= $victim['contact'] ?>"><br>
    Address: <input type="text" name="address" value="<?= $victim['address'] ?>"><br>
    Statement: <textarea name="statement"><?= $victim['statement'] ?></textarea><br>
    <button type="submit">Update</button>
</form>
