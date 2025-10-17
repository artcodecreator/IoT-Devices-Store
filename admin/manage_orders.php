<?php
session_start();
include("../includes/db.php");
error_reporting(E_ALL);
ini_set('display_errors', 1);

// ✅ Check if admin
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

// ✅ Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['status'])) {
    $order_id = intval($_POST['order_id']);
    $status = trim($_POST['status']);

    $update = $conn->prepare("UPDATE `order` SET status = ? WHERE id = ?");
    $update->bind_param("si", $status, $order_id);
    $update->execute();

    header("Location: manage_orders.php");
    exit;
}

// ✅ Fetch all orders
$query = "
    SELECT o.id, o.quantity, o.order_date, o.status,
           u.name AS user_name, u.email AS user_email,
           p.name AS product_name
    FROM `order` o
    JOIN user u ON o.user_id = u.id
    JOIN product p ON o.product_id = p.id
    ORDER BY o.order_date DESC
";
$result = $conn->query($query);

include '../includes/header.php';
?>

<div class="container py-5">
  <h2 class="text-center mb-4">Manage Orders</h2>

  <?php if ($result->num_rows > 0): ?>
    <div class="table-responsive">
      <table class="table table-bordered table-striped">
        <thead class="table-dark">
          <tr>
            <th>ID</th>
            <th>User</th>
            <th>Product</th>
            <th>Quantity</th>
            <th>Order Date</th>
            <th>Status</th>
            <th>Update Status</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?php echo $row['id']; ?></td>
              <td>
                <?php echo htmlspecialchars($row['user_name']); ?><br>
                <small class="text-muted"><?php echo htmlspecialchars($row['user_email']); ?></small>
              </td>
              <td><?php echo htmlspecialchars($row['product_name']); ?></td>
              <td><?php echo $row['quantity']; ?></td>
              <td><?php echo date("Y-m-d H:i", strtotime($row['order_date'])); ?></td>
              <td>
                <span class="badge bg-<?php echo ($row['status'] == 'Pending' ? 'warning' : ($row['status'] == 'Shipped' ? 'info' : ($row['status'] == 'Delivered' ? 'success' : 'secondary'))); ?>">
                  <?php echo htmlspecialchars($row['status']); ?>
                </span>
              </td>
              <td>
                <form method="post" class="d-flex">
                  <input type="hidden" name="order_id" value="<?php echo $row['id']; ?>">
                  <select name="status" class="form-select form-select-sm me-2">
                    <option value="Pending" <?php if ($row['status'] == 'Pending') echo 'selected'; ?>>Pending</option>
                    <option value="Shipped" <?php if ($row['status'] == 'Shipped') echo 'selected'; ?>>Shipped</option>
                    <option value="Delivered" <?php if ($row['status'] == 'Delivered') echo 'selected'; ?>>Delivered</option>
                    <option value="Canceled" <?php if ($row['status'] == 'Canceled') echo 'selected'; ?>>Canceled</option>
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
      No orders found.
    </div>
  <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>
