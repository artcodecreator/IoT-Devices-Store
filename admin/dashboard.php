<?php
session_start();
include("../includes/db.php");
error_reporting(E_ALL);
ini_set('display_errors', 1);

// ✅ Protect admin
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

// ✅ Fetch dashboard counts
$product_count = $conn->query("SELECT COUNT(*) as count FROM product")->fetch_assoc()['count'];
$user_count = $conn->query("SELECT COUNT(*) as count FROM user")->fetch_assoc()['count'];
$order_count = $conn->query("SELECT COUNT(*) as count FROM `order`")->fetch_assoc()['count'];
$pending_reviews = $conn->query("SELECT COUNT(*) AS total FROM review WHERE is_approved = 0")->fetch_assoc()['total'];

include '../includes/header.php';
?>

<div class="container py-5">
  <h2 class="text-center mb-4">Admin Dashboard</h2>

  <div class="row g-4">
    <div class="col-md-3">
      <div class="card text-bg-primary h-100 shadow">
        <div class="card-body">
          <h5 class="card-title">Products</h5>
          <p class="display-6 fw-bold"><?php echo $product_count; ?></p>
          <a href="manage_products.php" class="btn btn-outline-light btn-sm">
            Manage Products
          </a>
        </div>
      </div>
    </div>

    <div class="col-md-3">
      <div class="card text-bg-success h-100 shadow">
        <div class="card-body">
          <h5 class="card-title">Users</h5>
          <p class="display-6 fw-bold"><?php echo $user_count; ?></p>
          <a href="manage_users.php" class="btn btn-outline-light btn-sm">
            Manage Users
          </a>
        </div>
      </div>
    </div>

    <div class="col-md-3">
      <div class="card text-bg-warning h-100 shadow">
        <div class="card-body">
          <h5 class="card-title">Orders</h5>
          <p class="display-6 fw-bold"><?php echo $order_count; ?></p>
          <a href="manage_orders.php" class="btn btn-outline-light btn-sm">
            Manage Orders
          </a>
        </div>
      </div>
    </div>

    <div class="col-md-3">
      <div class="card text-bg-secondary h-100 shadow">
        <div class="card-body">
          <h5 class="card-title">Reviews</h5>
          <p class="display-6 fw-bold"><?php echo $pending_reviews; ?></p>
          <a href="moderate_reviews.php" class="btn btn-outline-light btn-sm">
            Manage Reviews
          </a>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include '../includes/footer.php'; ?>
