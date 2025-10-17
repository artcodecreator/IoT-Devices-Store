<?php
session_start();
include("../includes/db.php");
include '../includes/header.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

// ✅ Constants
$limit = 6;

// ✅ Get current page
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

// ✅ Get search keyword
$keyword = isset($_GET['search']) ? trim($_GET['search']) : "";
$like = '%' . $keyword . '%';

// ✅ Search or Normal Query
if ($keyword !== "") {
    // Count total products with search
    $count_stmt = $conn->prepare("SELECT COUNT(*) FROM product WHERE name LIKE ? OR category LIKE ? OR description LIKE ?");
    $count_stmt->bind_param("sss", $like, $like, $like);
    $count_stmt->execute();
    $count_stmt->bind_result($total_products);
    $count_stmt->fetch();
    $count_stmt->close();

    // Fetch products with search, pagination
    $stmt = $conn->prepare("SELECT * FROM product WHERE name LIKE ? OR category LIKE ? OR description LIKE ? ORDER BY id DESC LIMIT ? OFFSET ?");
    $stmt->bind_param("sssii", $like, $like, $like, $limit, $offset);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    // Count all products
    $row = $conn->query("SELECT COUNT(*) as count FROM product")->fetch_assoc();
    $total_products = $row['count'];

    // Fetch all products with pagination
    $stmt = $conn->prepare("SELECT * FROM product ORDER BY id DESC LIMIT ? OFFSET ?");
    $stmt->bind_param("ii", $limit, $offset);
    $stmt->execute();
    $result = $stmt->get_result();
}

// ✅ Calculate total pages
$total_pages = ceil($total_products / $limit);
?>

<!-- ✅ Search Form -->
<div class="row justify-content-center mt-5 mb-4">
    <div class="col-md-6">
      <form method="get" action="shop.php" class="d-flex">
        <input type="text" name="search" value="<?php echo htmlspecialchars($keyword); ?>" class="form-control me-2" placeholder="Search products...">
        <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> Search</button>
      </form>
    </div>
  </div>

<div class="container py-5">
  <h2 class="text-center mb-4">Our Products</h2>

  

  <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
    <?php if ($result->num_rows > 0): ?>
      <?php while ($product = $result->fetch_assoc()): ?>
        <div class="col">
          <div class="card h-100 shadow-sm border-0">
            <img src="../assets/images/<?php echo htmlspecialchars($product['image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($product['name']); ?>" style="height:250px; object-fit:cover;">
            <div class="card-body d-flex flex-column">
              <h5 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h5>
              <p class="card-text text-muted mb-1">
                <small><i class="bi bi-tag-fill"></i> <?php echo htmlspecialchars($product['category']); ?></small>
              </p>
              <p class="card-text mb-2">
                <?php echo nl2br(htmlspecialchars(substr($product['description'], 0, 100))); ?>...
              </p>
              <div class="mt-auto">
                <div class="d-flex justify-content-between align-items-center">
                  <span class="fw-bold text-success">$<?php echo number_format($product['price'], 2); ?></span>
                  <a href="product_details.php?id=<?php echo $product['id']; ?>" class="btn btn-outline-primary btn-sm">
                    View Details
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <div class="col-12 text-center">
        <p class="text-muted">No products found.</p>
        <a href="shop.php" class="btn btn-outline-secondary btn-sm mt-2">Clear Search</a>
      </div>
    <?php endif; ?>
  </div>

  <!-- ✅ Pagination -->
  <?php if ($total_pages > 1): ?>
    <nav class="mt-4">
      <ul class="pagination justify-content-center">
        <?php if ($page > 1): ?>
          <li class="page-item">
            <a class="page-link" href="?<?php echo http_build_query(['search' => $keyword, 'page' => $page - 1]); ?>">Previous</a>
          </li>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
          <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
            <a class="page-link" href="?<?php echo http_build_query(['search' => $keyword, 'page' => $i]); ?>"><?php echo $i; ?></a>
          </li>
        <?php endfor; ?>

        <?php if ($page < $total_pages): ?>
          <li class="page-item">
            <a class="page-link" href="?<?php echo http_build_query(['search' => $keyword, 'page' => $page + 1]); ?>">Next</a>
          </li>
        <?php endif; ?>
      </ul>
    </nav>
  <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>
