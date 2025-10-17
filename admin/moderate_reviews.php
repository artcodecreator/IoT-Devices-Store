<?php
session_start();
include("../includes/db.php");
error_reporting(E_ALL);
ini_set('display_errors', 1);

// ✅ Secure admin-only page
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

// ✅ Handle Approve/Unapprove
if (isset($_GET['toggle'])) {
    $review_id = intval($_GET['toggle']);
    $result = $conn->query("SELECT is_approved FROM review WHERE id=$review_id");
    if ($result && $result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $new_status = $row['is_approved'] ? 0 : 1;
        $conn->query("UPDATE review SET is_approved=$new_status WHERE id=$review_id");
    }
    header("Location: moderate_reviews.php");
    exit;
}

// ✅ Handle Delete
if (isset($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    $conn->query("DELETE FROM review WHERE id=$delete_id");
    header("Location: moderate_reviews.php");
    exit;
}

// ✅ Fetch all reviews with user and product details
$sql = "
  SELECT r.id, r.comment, r.rating, r.is_approved, r.created_at,
         u.name AS user_name,
         p.name AS product_name
  FROM review r
  JOIN user u ON r.user_id = u.id
  JOIN product p ON r.product_id = p.id
  ORDER BY r.id DESC
";
$result = $conn->query($sql);
?>

<?php include '../includes/header.php'; ?>
<div class="container mt-5">
  <h2 class="text-center mb-4">Moderate Product Reviews</h2>

  <div class="table-responsive">
    <table class="table table-bordered table-striped">
      <thead class="table-dark">
        <tr>
          <th>ID</th>
          <th>User</th>
          <th>Product</th>
          <th>Rating</th>
          <th>Comment</th>
          <th>Status</th>
          <th>Created At</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($result->num_rows > 0): ?>
          <?php while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo htmlspecialchars($row['user_name']); ?></td>
            <td><?php echo htmlspecialchars($row['product_name']); ?></td>
            <td><?php echo $row['rating']; ?></td>
            <td><?php echo nl2br(htmlspecialchars($row['comment'])); ?></td>
            <td>
              <?php echo $row['is_approved'] ? '<span class="badge bg-success">Approved</span>' : '<span class="badge bg-warning text-dark">Pending</span>'; ?>
            </td>
            <td><?php echo $row['created_at']; ?></td>
            <td>
              <a href="moderate_reviews.php?toggle=<?php echo $row['id']; ?>" 
                 class="btn btn-sm <?php echo $row['is_approved'] ? 'btn-warning' : 'btn-success'; ?>">
                 <?php echo $row['is_approved'] ? 'Unapprove' : 'Approve'; ?>
              </a>
              <a href="moderate_reviews.php?delete=<?php echo $row['id']; ?>" 
                 class="btn btn-danger btn-sm"
                 onclick="return confirm('Are you sure you want to delete this review?');">
                 Delete
              </a>
            </td>
          </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr>
            <td colspan="8" class="text-center">No reviews found.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
<?php include '../includes/footer.php'; ?>
