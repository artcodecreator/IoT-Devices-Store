<?php
session_start();
include("../includes/db.php");
error_reporting(E_ALL);
ini_set('display_errors', 1);

// ✅ Ensure logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$message = "";

// ✅ Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $address = trim($_POST['address']);
    $phone = trim($_POST['phone']);
    $new_password = $_POST['password'];

    if (!empty($name) && !empty($email)) {
        if (!empty($new_password)) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE user SET name=?, email=?, address=?, phone=?, password=? WHERE id=?");
            $stmt->bind_param("sssssi", $name, $email, $address, $phone, $hashed_password, $user_id);
        } else {
            $stmt = $conn->prepare("UPDATE user SET name=?, email=?, address=?, phone=? WHERE id=?");
            $stmt->bind_param("ssssi", $name, $email, $address, $phone, $user_id);
        }

        if ($stmt->execute()) {
            $_SESSION['user_name'] = $name;
            $message = "<div class='alert alert-success'>Profile updated successfully.</div>";
        } else {
            $message = "<div class='alert alert-danger'>Error updating profile.</div>";
        }
    } else {
        $message = "<div class='alert alert-danger'>Name and Email are required.</div>";
    }
}

// ✅ Load current user data
$stmt = $conn->prepare("SELECT * FROM user WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

include '../includes/header.php';
?>

<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-lg-8">
      <div class="card shadow-lg border-0">
        <div class="card-header bg-gradient text-white text-center" style="background: linear-gradient(45deg, #007bff, #00c6ff);">
          <h3 class="mb-0">Edit Your Profile</h3>
          <small class="text-light">Update your information below</small>
        </div>
        <div class="card-body">
          <?php if ($message) echo $message; ?>

          <form method="post" class="mt-3">
            <div class="mb-3">
              <label class="form-label">Full Name</label>
              <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" class="form-control" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Email</label>
              <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" class="form-control" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Address</label>
              <input type="text" name="address" value="<?php echo htmlspecialchars($user['address']); ?>" class="form-control">
            </div>
            <div class="mb-3">
              <label class="form-label">Phone</label>
              <input type="text" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" class="form-control">
            </div>
            <div class="mb-3">
              <label class="form-label">New Password (optional)</label>
              <input type="password" name="password" class="form-control" placeholder="Leave blank to keep current password">
            </div>
            <div class="text-end">
              <button type="submit" class="btn btn-success">Save Changes</button>
              <a href="profile.php" class="btn btn-secondary">Cancel</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include '../includes/footer.php'; ?>
