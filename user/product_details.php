<?php
session_start();
include("../includes/db.php");
error_reporting(E_ALL);
ini_set('display_errors', 1);

// ✅ Get product ID
$id = intval($_GET['id'] ?? 0);
if ($id < 1) {
    header("Location: shop.php");
    exit;
}

// ✅ Fetch product
$stmt = $conn->prepare("SELECT * FROM product WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if (!$product) {
    header("Location: shop.php");
    exit;
}

// ✅ Handle review submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rating'], $_POST['comment']) && isset($_SESSION['user_id'])) {
    $rating = intval($_POST['rating']);
    $comment = trim($_POST['comment']);
    $user_id = $_SESSION['user_id'];

    if ($rating >= 1 && $rating <= 5 && $comment !== '') {
        $insert = $conn->prepare("INSERT INTO review (user_id, product_id, rating, comment) VALUES (?, ?, ?, ?)");
        $insert->bind_param("iiis", $user_id, $id, $rating, $comment);
        $insert->execute();

        header("Location: product_details.php?id=" . $id);
        exit;
    }
}

// ✅ Fetch reviews
$review_stmt = $conn->prepare("
    SELECT r.*, u.name AS user_name 
    FROM review r
    JOIN user u ON r.user_id = u.id
    WHERE r.product_id = ?
    ORDER BY r.created_at DESC
");
$review_stmt->bind_param("i", $id);
$review_stmt->execute();
$reviews = $review_stmt->get_result();

include '../includes/header.php';
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<div class="container py-5">
  <!-- ✅ Product Info -->
  <div class="row mb-5">
    <div class="col-md-6">
      <img src="../assets/images/<?php echo htmlspecialchars($product['image']); ?>" class="img-fluid rounded shadow" alt="<?php echo htmlspecialchars($product['name']); ?>">
    </div>
    
    <div class="col-md-6">
      <h2 class="mb-3"><?php echo htmlspecialchars($product['name']); ?></h2>
      <p class="text-muted">
        <i class="bi bi-tag-fill"></i> Category: <?php echo htmlspecialchars($product['category']); ?>
      </p>
      <h4 class="text-success mb-3">$<?php echo number_format($product['price'], 2); ?></h4>

      <!-- Add to Cart -->
      <?php if ($product['stock'] > 0): ?>
        <form action="add_to_cart.php" method="post" class="mt-3">
          <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
          <input type="number" name="quantity" value="1" min="1" max="<?php echo $product['stock']; ?>" class="form-control mb-2" style="width:100px;">
          <button type="submit" class="btn btn-success">
            <i class="bi bi-cart-plus"></i> Add to Cart
          </button>
        </form>
      <?php else: ?>
        <div class="alert alert-warning mt-3">Out of stock</div>
      <?php endif; ?>

      <p class="mt-3"><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
      <p><strong>Stock:</strong> <?php echo $product['stock']; ?> available</p>
      <a href="shop.php" class="btn btn-secondary mt-3">
        <i class="bi bi-arrow-left"></i> Back to Shop
      </a>
    </div>
  </div>

  <!-- ✅ Reviews Section -->
  <div class="card mb-5">
    <div class="card-header bg-primary text-white">
      <h5 class="mb-0">Customer Reviews</h5>
    </div>
    <div class="card-body">
      <?php if ($reviews->num_rows > 0): ?>
        <?php while ($review = $reviews->fetch_assoc()): ?>
          <div class="mb-4 border-bottom pb-3">
            <div class="d-flex justify-content-between">
              <div>
                <strong><?php echo htmlspecialchars($review['user_name']); ?></strong>
                <span class="text-muted small">on <?php echo date("Y-m-d H:i", strtotime($review['created_at'])); ?></span>
              </div>
              <div>
                <?php for ($i = 1; $i <= 5; $i++): ?>
                  <?php if ($i <= $review['rating']): ?>
                    <i class="bi bi-star-fill text-warning"></i>
                  <?php else: ?>
                    <i class="bi bi-star text-muted"></i>
                  <?php endif; ?>
                <?php endfor; ?>
              </div>
            </div>
            <p class="mt-2 mb-0"><?php echo nl2br(htmlspecialchars($review['comment'])); ?></p>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p class="text-muted">No reviews yet. Be the first to review this product!</p>
      <?php endif; ?>
    </div>
  </div>

  <!-- ✅ Add Review Form -->
  <?php if (isset($_SESSION['user_id'])): ?>
    <div class="card">
      <div class="card-header bg-success text-white">
        <h5 class="mb-0">Leave a Review</h5>
      </div>
      <div class="card-body">
        <form method="post">
          <div class="mb-3">
            <label class="form-label">Rating</label>
            <select name="rating" class="form-select" required>
              <option value="">Select rating</option>
              <option value="5">⭐⭐⭐⭐⭐ - Excellent</option>
              <option value="4">⭐⭐⭐⭐ - Very Good</option>
              <option value="3">⭐⭐⭐ - Good</option>
              <option value="2">⭐⭐ - Fair</option>
              <option value="1">⭐ - Poor</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Comment</label>
            <textarea name="comment" class="form-control" rows="3" required></textarea>
          </div>
          <button type="submit" class="btn btn-success">
            <i class="bi bi-send"></i> Submit Review
          </button>
        </form>
      </div>
    </div>
  <?php else: ?>
    <div class="alert alert-info text-center">
      <a href="login.php" class="btn btn-outline-primary">Login</a> to leave a review.
    </div>
  <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>
