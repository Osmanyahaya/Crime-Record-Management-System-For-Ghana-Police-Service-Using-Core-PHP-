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
    <title>Add Suspect</title>
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
        #preview {
            max-width: 150px;
            margin-top: 10px;
            display: none;
            border: 1px solid #ccc;
            border-radius: 8px;
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
        <h3 class="text-center mb-4">Add Suspect</h3>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="error-message"><?= $_SESSION['error'] ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <form action="../handlers/suspect_create_action.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="crime_id" value="<?= htmlspecialchars($crimeId) ?>">

            <div class="mb-3">
                <label for="name" class="form-label">Name<span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" id="name" required>
            </div>

            <div class="mb-3">
                <label for="age" class="form-label">Age</label>
                <input type="number" name="age" class="form-control" id="age">
            </div>

            <div class="mb-3">
                <label class="form-label">Gender</label>
                <select name="gender" class="form-select">
                    <option value="">Select</option>
                    <option>Male</option>
                    <option>Female</option>
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
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option>Arrested</option>
                    <option>Wanted</option>
                    <option>Released</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="photo" class="form-label">Suspect Photo</label>
                <input type="file" name="photo" id="photo" class="form-control" accept="image/*" onchange="validateAndPreview(event)">
                <div id="error" class="error-message"></div>
                <img id="preview" alt="Image Preview">
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-primary">Add Suspect</button>
            </div>
        </form>
    </div>
</div>

<script>
function validateAndPreview(event) {
    const file = event.target.files[0];
    const preview = document.getElementById('preview');
    const errorDiv = document.getElementById('error');

    errorDiv.textContent = '';
    preview.style.display = 'none';
    preview.src = '';

    if (!file) return;

    const validTypes = ['image/jpeg', 'image/png', 'image/webp'];
    const maxSize = 2 * 1024 * 1024; // 2MB

    if (!validTypes.includes(file.type)) {
        errorDiv.textContent = 'Invalid file type. Please upload JPG, PNG, or WebP.';
        event.target.value = '';
        return;
    }

    if (file.size > maxSize) {
        errorDiv.textContent = 'File is too large. Maximum size allowed is 2MB.';
        event.target.value = '';
        return;
    }

    const reader = new FileReader();
    reader.onload = function(e) {
        preview.src = e.target.result;
        preview.style.display = 'block';
    };
    reader.readAsDataURL(file);
}
</script>
</body>
</html>
