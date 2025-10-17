<?php
session_start();
include("../includes/db.php");
error_reporting(E_ALL);
ini_set('display_errors', 1);

// ✅ Protect page: user must be logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// ✅ Fetch user's orders with JOIN to get product info
$stmt = $conn->prepare("
    SELECT o.id as order_id, o.quantity, o.order_date, o.status,
           p.name as product_name, p.image, p.price
    FROM `order` o
    JOIN product p ON o.product_id = p.id
    WHERE o.user_id = ?
    ORDER BY o.order_date DESC
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

include '../includes/header.php';
?>

<div class="container py-5">
  <h2 class="text-center mb-4">My Order History</h2>

  <?php if ($result->num_rows > 0) { ?>
    <div class="table-responsive">
      <table class="table table-bordered table-striped">
        <thead class="table-dark">
          <tr>
            <th>Product</th>
            <th>Image</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Total ($)</th>
            <th>Date</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
              <td><?php echo htmlspecialchars($row['product_name']); ?></td>
              <td>
                <img src="../assets/images/<?php echo htmlspecialchars($row['image']); ?>" width="60" height="60" class="rounded">
              </td>
              <td>$<?php echo number_format($row['price'], 2); ?></td>
              <td><?php echo $row['quantity']; ?></td>
              <td>$<?php echo number_format($row['price'] * $row['quantity'], 2); ?></td>
              <td><?php echo date("Y-m-d H:i", strtotime($row['order_date'])); ?></td>
              <td>
                <span class="badge bg-<?php echo ($row['status'] == 'Pending') ? 'warning' : 'success'; ?>">
                  <?php echo htmlspecialchars($row['status']); ?>
                </span>
              </td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  <?php } else { ?>
    <div class="alert alert-info text-center">
      You have no orders yet. <a href="shop.php" class="btn btn-outline-primary btn-sm">Start Shopping</a>
    </div>
  <?php } ?>
</div>

<?php include '../includes/footer.php'; ?>
