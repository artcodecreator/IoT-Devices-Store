<?php
session_start();
include("../includes/db.php");
error_reporting(E_ALL);
ini_set('display_errors', 1);

$message = "";

// Check if user is already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: profile.php");
    exit;
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (!empty($email) && !empty($password)) {
        $stmt = $conn->prepare("SELECT * FROM user WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                header("Location: profile.php");
                exit;
            } else {
                $message = "Invalid email or password.";
            }
        } else {
            $message = "Invalid email or password.";
        }
    } else {
        $message = "Please fill in all fields.";
    }
}

include '../includes/header.php';
?>

<div class="container mt-5">
  <h2 class="text-center mb-4">User Login</h2>

  <?php if ($message): ?>
    <div class="alert alert-danger"><?php echo $message; ?></div>
  <?php endif; ?>

  <div class="row justify-content-center">
    <div class="col-md-6">
      <form method="post" class="border p-4 bg-light rounded shadow">
        <div class="mb-3">
          <label class="form-label">Email</label>
          <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Password</label>
          <input type="password" name="password" class="form-control" required>
        </div>
        <div class="text-end">
          <button type="submit" class="btn btn-primary">Login</button>
          <a href="register.php" class="btn btn-link">Don't have an account? Register</a>
        </div>
      </form>
    </div>
  </div>
</div>

<?php include '../includes/footer.php'; ?>
