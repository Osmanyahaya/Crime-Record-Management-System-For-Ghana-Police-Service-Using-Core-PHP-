<?php
session_start();
$crimeId = $_GET['crime_id'] ?? null;
if (!$crimeId) {
    echo "Invalid crime ID.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Add Victim</title>
    <?php include 'header.php'; ?>
    <style>
        .form-container {
            max-width: 600px;
            margin: 30px auto;
            padding: 25px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        .error-message {
            color: red;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="form-container">
        <h3 class="text-center mb-4">Add Victim</h3>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="error-message"><?= $_SESSION['error'] ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <form action="../handlers/victim_create_action.php" method="POST">
            <input type="hidden" name="crime_id" value="<?= htmlspecialchars($crimeId) ?>">

            <div class="mb-3">
                <label class="form-label">Name<span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Age</label>
                <input type="number" name="age" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">Gender</label>
                <select name="gender" class="form-select">
                    <option value="">Select</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Contact</label>
                <input type="text" name="contact" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">Address</label>
                <input type="text" name="address" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">Statement</label>
                <textarea name="statement" class="form-control" rows="4"></textarea>
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-success">Submit Victim</button>
            </div>
        </form>
    </div>
</div>
</body>
</html>
