<?php
session_start();
include("../includes/db.php");
error_reporting(E_ALL);
ini_set('display_errors', 1);

$message = "";

// If form submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $address = trim($_POST['address']);
    $phone = trim($_POST['phone']);

    if (!empty($name) && !empty($email) && !empty($password)) {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert into DB
        $stmt = $conn->prepare("INSERT INTO user (name, email, password, address, phone) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $name, $email, $hashed_password, $address, $phone);

        if ($stmt->execute()) {
            $message = "<div class='alert alert-success'>Registration successful! <a href='login.php'>Click here to login</a>.</div>";
        } else {
            $message = "<div class='alert alert-danger'>Error: Email already exists or invalid data.</div>";
        }
    } else {
        $message = "<div class='alert alert-danger'>Please fill in all required fields.</div>";
    }
}

include '../includes/header.php';
?>

<div class="container mt-5">
  <h2 class="text-center mb-4">User Registration</h2>

  <?php
  if ($message) {
      echo $message;
  }
  ?>

  <div class="row justify-content-center">
    <div class="col-md-6">
      <form method="post" class="border p-4 bg-light rounded shadow">
        <div class="mb-3">
          <label class="form-label">Full Name</label>
          <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Email</label>
          <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Password</label>
          <input type="password" name="password" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Address</label>
          <input type="text" name="address" class="form-control">
        </div>
        <div class="mb-3">
          <label class="form-label">Phone</label>
          <input type="text" name="phone" class="form-control">
        </div>
        <div class="text-end">
          <button type="submit" class="btn btn-success">Register</button>
          <a href="login.php" class="btn btn-link">Already have an account? Login</a>
        </div>
      </form>
    </div>
  </div>
</div>

<?php include '../includes/footer.php'; ?>
