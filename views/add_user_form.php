<?php
session_start();
if ($_SESSION['user']['role'] !== 'admin') {
    header("Location: 403.php");
    exit();
}
include 'header.php';
?>

<div class="container mt-4">
  <h2>Add New User</h2>
  <form method="POST" action="../handlers/add_user.php">
    <div class="form-group">
      <label for="name">Full Name</label>
      <input type="text" name="name" class="form-control" required>
    </div>

    <div class="form-group">
      <label for="badge_number">Badge Number</label>
      <input type="text" name="badge_number" class="form-control">
    </div>

    <div class="form-group">
      <label for="email">Email Address</label>
      <input type="email" name="email" class="form-control" required>
    </div>

    <div class="form-group">
      <label for="role">Role</label>
      <select name="role" class="form-control" required>
        <option value="officer">Officer</option>
        <option value="cid">CID</option>
        <option value="admin">Admin</option>
      </select>
    </div>

    <div class="form-group">
      <label for="password">Password (default)</label>
      <input type="password" name="password" class="form-control" required>
    </div>

    <button type="submit" class="btn btn-success">Create User</button>
    <a href="manage_users.php" class="btn btn-secondary">Cancel</a>
  </form>
</div>

<?php include 'footer.php'; ?>
