<?php
session_start();

include("../includes/db.php");
error_reporting(E_ALL);
ini_set('display_errors', 1);

// ✅ Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

// ✅ Handle Delete
if (isset($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    $conn->query("DELETE FROM product WHERE id=$delete_id");
    header("Location: manage_products.php");
    exit;
}

// ✅ Handle Add Product
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_product'])) {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $category = trim($_POST['category']);
    $price = floatval($_POST['price']);
    $stock = intval($_POST['stock']);
    $image = '';

if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] == 0) {
    $target_dir = "../assets/images/";
    $image = basename($_FILES["image_file"]["name"]);
    $target_file = $target_dir . $image;

    // Optionally add file type validation
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($imageFileType, $allowed)) {
        die("Sorry, only JPG, JPEG, PNG & GIF files are allowed.");
    }

    // Move uploaded file
    if (!move_uploaded_file($_FILES["image_file"]["tmp_name"], $target_file)) {
        die("Sorry, there was an error uploading your file.");
    }
}


    $stmt = $conn->prepare("INSERT INTO product (name, description, category, price, stock, image) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssdis", $name, $description, $category, $price, $stock, $image);
    $stmt->execute();

    header("Location: manage_products.php");
    exit;
}

// ✅ Include header
include '../includes/header.php';
?>
<!-- bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<div class="container mt-5">
  <h2 class="text-center mb-4">Manage Products</h2>

  <!-- Add Product Button -->
  <div class="text-end mb-3">
    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addProductModal">
      Add New Product
    </button>
  </div>

  <!-- Products Table -->
  <div class="table-responsive">
    <table class="table table-bordered table-striped">
      <thead class="table-dark">
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Category</th>
          <th>Price ($)</th>
          <th>Stock</th>
          <th>Image</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $result = $conn->query("SELECT * FROM product ORDER BY id DESC");
        if ($result->num_rows > 0):
          while ($row = $result->fetch_assoc()):
        ?>
        <tr>
          <td><?php echo $row['id']; ?></td>
          <td><?php echo htmlspecialchars($row['name']); ?></td>
          <td><?php echo htmlspecialchars($row['category']); ?></td>
          <td><?php echo number_format($row['price'], 2); ?></td>
          <td><?php echo $row['stock']; ?></td>
          <td>
            <img src="../assets/images/<?php echo htmlspecialchars($row['image']); ?>" width="50" height="50" alt="Product">
          </td>
          <td>
            <a href="edit_product.php?id=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm">Edit</a>
            <a href="manage_products.php?delete=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
          </td>
        </tr>
        <?php
          endwhile;
        else:
        ?>
        <tr>
          <td colspan="7" class="text-center">No products found.</td>
        </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Add Product Modal -->
<div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="post" action="" enctype="multipart/form-data" class="modal-content">
      <input type="hidden" name="add_product" value="1">

      <div class="modal-header">
        <h5 class="modal-title" id="addProductModalLabel">Add New Product</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label">Product Name</label>
          <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Description</label>
          <textarea name="description" class="form-control" required></textarea>
        </div>
        <div class="mb-3">
          <label class="form-label">Category</label>
          <select name="category" class="form-select" required>
            <option value="">Select Category</option>
           <option value="Sensors">Sensors</option>
           <option value="Actuators">Actuators</option>
           <option value="Controllers">Controllers</option>
           <option value="Accessories">Accessories</option>
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">Price ($)</label>
          <input type="number" step="0.01" name="price" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Stock</label>
          <input type="number" name="stock" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Image Filename</label>
         <input type="file" name="image_file" class="form-control" required>
        </div>
      </div>

      <div class="modal-footer">
        <button type="submit" class="btn btn-success">Add Product</button>
      </div>
    </form>
  </div>
</div>

<?php include '../includes/footer.php'; ?>
