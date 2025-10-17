<?php include '../includes/header.php'; ?>

<?php

include("../includes/db.php");
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check admin logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

// Check product ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid Product ID.");
}

$product_id = intval($_GET['id']);

// Fetch existing product
$stmt = $conn->prepare("SELECT * FROM product WHERE id=?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows != 1) {
    die("Product not found.");
}
$product = $result->fetch_assoc();

// Handle Update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $category = trim($_POST['category']);
    $price = floatval($_POST['price']);
    $stock = intval($_POST['stock']);
    $image = $product['image']; // Default to existing

    // Check if new image uploaded
    if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] == 0) {
        $target_dir = "../assets/images/";
        $new_image = basename($_FILES["image_file"]["name"]);
        $target_file = $target_dir . $new_image;

        // Optional: Validate type
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($imageFileType, $allowed)) {
            die("Error: Only JPG, JPEG, PNG & GIF allowed.");
        }

        if (move_uploaded_file($_FILES["image_file"]["tmp_name"], $target_file)) {
            $image = $new_image;
        } else {
            die("Error uploading new image.");
        }
    }

    // Update in DB
    $update = $conn->prepare("UPDATE product SET name=?, description=?, category=?, price=?, stock=?, image=? WHERE id=?");
    $update->bind_param("sssdisi", $name, $description, $category, $price, $stock, $image, $product_id);
    if ($update->execute()) {
        header("Location: manage_products.php");
        exit;
    } else {
        echo "Error updating product: " . $conn->error;
    }
}
?>

<div class="container mt-5">
  <h2 class="text-center mb-4">Edit Product</h2>
  <div class="row justify-content-center">
    <div class="col-md-8">
      <form method="post" enctype="multipart/form-data" class="border p-4 rounded bg-light shadow">

        <div class="mb-3">
          <label class="form-label">Product Name</label>
          <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($product['name']); ?>" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Description</label>
          <textarea name="description" class="form-control" required><?php echo htmlspecialchars($product['description']); ?></textarea>
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
          <input type="number" step="0.01" name="price" class="form-control" value="<?php echo htmlspecialchars($product['price']); ?>" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Stock</label>
          <input type="number" name="stock" class="form-control" value="<?php echo htmlspecialchars($product['stock']); ?>" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Current Image</label><br>
          <img src="../assets/images/<?php echo htmlspecialchars($product['image']); ?>" width="100" alt="Current Image">
        </div>

        <div class="mb-3">
          <label class="form-label">Change Image (optional)</label>
          <input type="file" name="image_file" class="form-control">
        </div>

        <div class="text-end">
          <button type="submit" class="btn btn-primary">Save Changes</button>
          <a href="manage_products.php" class="btn btn-secondary">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>


<?php include '../includes/footer.php'; ?>
