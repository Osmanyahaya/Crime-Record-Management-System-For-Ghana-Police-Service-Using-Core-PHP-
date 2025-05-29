<?php
require_once '../config/db.php';
require_once '../controllers/SuspectController.php';
session_start();

$id = $_GET['id'] ?? null;
$photo = $_GET['photo'] ?? '';
$controller = new SuspectController($pdo);
$crimeID=$controller->getCrimeIdForSuspect($id);
if ($id) {
    // Delete suspect from DB
    $stmt = $pdo->prepare("DELETE FROM suspects WHERE id = ?");
    $stmt->execute([$id]);

    // Delete photo from filesystem
    if ($photo && file_exists('../uploads/' . $photo)) {
        unlink('../uploads/' . $photo);
    }

    $_SESSION['message'] = "Suspect and photo deleted.";
} else {
    $_SESSION['error'] = "Invalid suspect ID.";
}
$controller = new SuspectController($pdo);

header("Location: ../views/crime_details.php?id=" . $crimeID);

exit();
?>
