<?php
session_start();
include("../includes/db.php");
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../includes/header.php';

$cart = $_SESSION['cart'] ?? [];
$total = 0;
?>

<div class="container py-5">
  <h2 class="text-center mb-4">Your Cart</h2>

  <?php if (!empty($cart)): ?>
    <form method="post" action="update_cart.php">
      <div class="table-responsive">
        <table class="table table-bordered">
          <thead class="table-dark">
            <tr>
              <th>Image</th>
              <th>Product</th>
              <th>Price</th>
              <th>Quantity</th>
              <th>Subtotal</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($cart as $product_id => $quantity): ?>
              <?php
                $stmt = $conn->prepare("SELECT * FROM product WHERE id = ?");
                $stmt->bind_param("i", $product_id);
                $stmt->execute();
                $product = $stmt->get_result()->fetch_assoc();
                if (!$product) continue;

                $subtotal = $product['price'] * $quantity;
                $total += $subtotal;
              ?>
              <tr>
                <td><img src="../assets/images/<?php echo htmlspecialchars($product['image']); ?>" width="60" height="60" class="rounded"></td>
                <td><?php echo htmlspecialchars($product['name']); ?></td>
                <td>$<?php echo number_format($product['price'], 2); ?></td>
                <td>
                  <input type="number" name="quantities[<?php echo $product_id; ?>]" value="<?php echo $quantity; ?>" min="1" max="<?php echo $product['stock']; ?>" class="form-control" style="width:80px;">
                </td>
                <td>$<?php echo number_format($subtotal, 2); ?></td>
                <td>
                  <a href="remove_from_cart.php?id=<?php echo $product_id; ?>" class="btn btn-danger btn-sm">Remove</a>
                </td>
              </tr>
            <?php endforeach; ?>
            <tr class="table-secondary">
              <td colspan="4" class="text-end fw-bold">Total:</td>
              <td colspan="2" class="fw-bold">$<?php echo number_format($total, 2); ?></td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="text-end">
        <button type="submit" class="btn btn-success">Update Cart</button>
        <a href="checkout.php" class="btn btn-primary">Checkout</a>
      </div>
    </form>
  <?php else: ?>
    <div class="alert alert-info text-center">
      Your cart is empty. <a href="shop.php" class="btn btn-outline-primary btn-sm">Shop Now</a>
    </div>
  <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>
