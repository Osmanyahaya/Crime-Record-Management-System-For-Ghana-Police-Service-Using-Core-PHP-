<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Report a Crime</title>
    <?php include 'header.php'; ?>
    <style>
        .form-container {
            max-width: 650px;
            margin: 40px auto;
            padding: 30px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        .back-link {
            margin-top: 20px;
            display: block;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="form-container">
        <h2 class="text-center mb-4">Report a Crime</h2>

        <form action="../handlers/add_crime.php" method="post">
            <div class="mb-3">
                <label class="form-label">Title<span class="text-danger">*</span></label>
                <input type="text" name="title" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Crime Type<span class="text-danger">*</span></label>
                <input type="text" name="crime_type" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="4"></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Location<span class="text-danger">*</span></label>
                <input type="text" name="location" class="form-control" required>
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-primary">Submit Report</button>
            </div>
        </form>

        <a class="back-link text-secondary" href="dashboard.php">← Back to Dashboard</a>
    </div>
</div>

<?php include 'footer.php'; ?>
</body>
</html>
