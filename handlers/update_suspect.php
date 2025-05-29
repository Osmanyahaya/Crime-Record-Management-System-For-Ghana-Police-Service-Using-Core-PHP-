<?php
require_once '../config/db.php';
require_once '../controllers/SuspectController.php';
session_start();

$controller = new SuspectController($pdo);

$id = $_POST['id'];
$existingPhoto = $_POST['existing_photo'];

$newPhotoName = $existingPhoto;

if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
    $ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
    $newPhotoName = uniqid('suspect_') . '.' . $ext;
    move_uploaded_file($_FILES['photo']['tmp_name'], "../uploads/suspects/$newPhotoName");

    // Optionally delete old photo
    if (!empty($existingPhoto) && file_exists("../uploads/suspects/$existingPhoto")) {
        unlink("../uploads/suspects/$existingPhoto");
    }
}

$data = [
    'name' => $_POST['name'],
    'age' => $_POST['age'],
    'gender' => $_POST['gender'],
    'contact' => $_POST['contact'],
    'address' => $_POST['address'],
    'status' => $_POST['status'],
    'photo' => $newPhotoName
];

$controller->update($id, $data);

