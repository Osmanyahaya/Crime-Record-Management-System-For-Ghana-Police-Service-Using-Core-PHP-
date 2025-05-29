<?php
session_start();
if ($_SESSION['user']['role'] !== 'admin') {
    header("Location: 403.php");
    exit();
}
require_once '../config/db.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    $_SESSION['error'] = "User not specified.";
    header("Location: manage_users.php");
    exit();
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    $_SESSION['error'] = "User not found.";
    header("Location: manage_users.php");
    exit();
}

include 'header.php';
?>

<div class="container mt-4">
  <h2>Edit User</h2>
  <form method="POST" action="../handlers/update_user.php">
    <input type="hidden" name="id" value="<?= $user['id'] ?>">

    <div class="form-group">
      <label for="name">Full Name</label>
      <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($user['name']) ?>" required>
    </div>

    <div class="form-group">
      <label for="badge_number">Badge Number</label>
      <input type="text" name="badge_number" class="form-control" value="<?= htmlspecialchars($user['badge_number']) ?>">
    </div>

    <div class="form-group">
      <label for="email">Email Address</label>
      <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
    </div>

    <div class="form-group">
      <label for="role">Role</label>
      <select name="role" class="form-control" required>
        <option value="officer" <?= $user['role'] === 'officer' ? 'selected' : '' ?>>Officer</option>
        <option value="cid" <?= $user['role'] === 'cid' ? 'selected' : '' ?>>CID</option>
        <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
      </select>
    </div>

    <div class="form-group">
      <label for="password">New Password (leave blank to keep current)</label>
      <input type="password" name="password" class="form-control">
    </div>

    <button type="submit" class="btn btn-primary">Update User</button>
    <a href="manage_users.php" class="btn btn-secondary">Cancel</a>
  </form>
</div>

<?php include 'footer.php'; ?>
