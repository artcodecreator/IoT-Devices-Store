<?php
session_start();
include("../includes/db.php");
error_reporting(E_ALL);
ini_set('display_errors', 1);

// ✅ Admin check
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

// ✅ Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'], $_POST['status'])) {
    $user_id = intval($_POST['user_id']);
    $status = ($_POST['status'] == 'Blocked') ? 'Blocked' : 'Active';

    $stmt = $conn->prepare("UPDATE user SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $user_id);
    $stmt->execute();

    header("Location: manage_users.php");
    exit;
}

// ✅ Fetch users
$result = $conn->query("SELECT * FROM user ORDER BY id DESC");

include '../includes/header.php';
?>

<div class="container py-5">
  <h2 class="text-center mb-4">Manage Users</h2>

  <?php if ($result->num_rows > 0): ?>
    <div class="table-responsive">
      <table class="table table-bordered table-striped">
        <thead class="table-dark">
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($user = $result->fetch_assoc()): ?>
            <tr>
              <td><?php echo $user['id']; ?></td>
              <td><?php echo htmlspecialchars($user['name']); ?></td>
              <td><?php echo htmlspecialchars($user['email']); ?></td>
              <td>
                <span class="badge bg-<?php echo $user['status'] == 'Active' ? 'success' : 'secondary'; ?>">
                  <?php echo $user['status']; ?>
                </span>
              </td>
              <td>
                <form method="post" class="d-flex">
                  <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                  <select name="status" class="form-select form-select-sm me-2">
                    <option value="Active" <?php if ($user['status'] == 'Active') echo 'selected'; ?>>Active</option>
                    <option value="Blocked" <?php if ($user['status'] == 'Blocked') echo 'selected'; ?>>Blocked</option>
                  </select>
                  <button type="submit" class="btn btn-primary btn-sm">Update</button>
                </form>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  <?php else: ?>
    <div class="alert alert-info text-center">
      No users found.
    </div>
  <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>
