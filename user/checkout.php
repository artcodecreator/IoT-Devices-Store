<?php
session_start();
include("../includes/db.php");
error_reporting(E_ALL);
ini_set('display_errors', 1);

// ✅ Protect: user must be logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// ✅ Get cart from session
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
if (empty($cart)) {
    header("Location: cart.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$message = "";

// ✅ Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!empty($cart)) {
        foreach ($cart as $product_id => $quantity) {
            $stmt = $conn->prepare("INSERT INTO `order` (user_id, product_id, quantity, status) VALUES (?, ?, ?, 'Pending')");
            $stmt->bind_param("iii", $user_id, $product_id, $quantity);
            $stmt->execute();
        }
        // ✅ Clear cart
        unset($_SESSION['cart']);
        $message = "<div class='alert alert-success'>Your order has been placed successfully!</div>";
    }
}

include '../includes/header.php';
?>

<div class="container py-5">
  <h2 class="text-center mb-4">Checkout</h2>

  <?php
  if ($message) {
      echo $message;
  } else if (!empty($cart)) { 
  ?>

  <div class="row">
    <div class="col-md-7">
      <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-primary text-white">
          <h5 class="mb-0">Your Cart Summary</h5>
        </div>
        <div class="card-body">
          <ul class="list-group">
            <?php
            $total = 0;
            foreach ($cart as $product_id => $quantity) {
                $stmt = $conn->prepare("SELECT * FROM product WHERE id = ?");
                $stmt->bind_param("i", $product_id);
                $stmt->execute();
                $product = $stmt->get_result()->fetch_assoc();
                if (!$product) continue;

                $subtotal = $product['price'] * $quantity;
                $total += $subtotal;
            ?>
              <li class="list-group-item d-flex justify-content-between align-items-center">
                <span>
                  <?php echo htmlspecialchars($product['name']); ?> (x<?php echo $quantity; ?>)
                </span>
                <span>$<?php echo number_format($subtotal, 2); ?></span>
              </li>
            <?php } ?>
            <li class="list-group-item d-flex justify-content-between fw-bold">
              <span>Total:</span>
              <span>$<?php echo number_format($total, 2); ?></span>
            </li>
          </ul>
        </div>
      </div>
    </div>

    <div class="col-md-5">
      <div class="card shadow-sm border-0">
        <div class="card-header bg-success text-white">
          <h5 class="mb-0">Confirm Your Order</h5>
        </div>
        <div class="card-body">
          <form method="post">
            <p class="text-muted">Shipping details are already on file from your profile. Click Place Order to confirm.</p>
            <button type="submit" class="btn btn-success w-100">Place Order</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <?php 
  } else { 
  ?>
    <div class="alert alert-info text-center">
      Your cart is empty. <a href="shop.php" class="btn btn-outline-primary btn-sm">Shop Now</a>
    </div>
  <?php } ?>
</div>

<?php include '../includes/footer.php'; ?>
