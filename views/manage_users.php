<?php
session_start();
include 'header.php';

if ($_SESSION['user']['role'] !== 'admin') {
    header("Location: 403.php");
    exit();
}

require_once '../controllers/UserController.php';

$search = $_GET['search'] ?? '';
$role = $_GET['role'] ?? '';
$roleFilter = $_GET['role'] ?? '';
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

$userController = new UserController($pdo);
$users = $userController->fetchUsers($search, $role, $limit, $offset);
$totalUsers = $userController->countUsers($search, $role);
$totalPages = ceil($totalUsers / $limit);
?>

<div class="container mt-4">
  <h2>Manage Users</h2>
  <a href="add_user_form.php" class="btn btn-success mb-3">+ Add New User</a>
  <form method="GET" class="mb-3 row g-2">
  <div class="col-md-3">
    <input type="text" name="search" class="form-control" placeholder="Search name or email" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
  </div>
  <div class="col-md-3">
    <select name="role" class="form-select">
      <option value="">All Roles</option>
      <option value="admin" <?= ($_GET['role'] ?? '') === 'admin' ? 'selected' : '' ?>>Admin</option>
      <option value="officer" <?= ($_GET['role'] ?? '') === 'officer' ? 'selected' : '' ?>>Officer</option>
      <option value="cid" <?= ($_GET['role'] ?? '') === 'cid' ? 'selected' : '' ?>>CID</option>
    </select>
  </div>
  <div class="col-md-3">
    <button type="submit" class="btn btn-primary">Search</button>
    <a href="manage_users.php" class="btn btn-secondary">Reset</a>
  </div>
</form>

  <table class="table table-bordered">
    <thead class="thead-dark">
      <tr>
        <th>Name</th>
        <th>Email</th>
        <th>Badge Number</th>
        <th>Role</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
    <?php foreach ($users as $user): ?>
      <tr>
        <td><?= htmlspecialchars($user['name']) ?></td>
        <td><?= htmlspecialchars($user['email']) ?></td>
        <td><?= htmlspecialchars($user['badge_number']) ?></td>
        <td><?= htmlspecialchars($user['role']) ?></td>
        <td>
          <a href="edit_user_form.php?id=<?= $user['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
          <form method="POST" action="../handlers/delete_user.php" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this user?');">
            <input type="hidden" name="id" value="<?= $user['id'] ?>">
            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
  <!-- Pagination -->
<nav>
    <ul class="pagination">
        <?php if ($page > 1): ?>
            <li class="page-item"><a class="page-link" href="?search=<?= urlencode($search) ?>&role=<?= urlencode($roleFilter) ?>&page=<?= $page - 1 ?>">Previous</a></li>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <li class="page-item <?= ($i === $page) ? 'active' : '' ?>">
                <a class="page-link" href="?search=<?= urlencode($search) ?>&role=<?= urlencode($roleFilter) ?>&page=<?= $i ?>"><?= $i ?></a>
            </li>
        <?php endfor; ?>

        <?php if ($page < $totalPages): ?>
            <li class="page-item"><a class="page-link" href="?search=<?= urlencode($search) ?>&role=<?= urlencode($roleFilter) ?>&page=<?= $page + 1 ?>">Next</a></li>
        <?php endif; ?>
    </ul>
</nav>
</div>

<?php include 'footer.php'; ?>
