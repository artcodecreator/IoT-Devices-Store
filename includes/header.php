<?php
if (session_status() == PHP_SESSION_NONE) session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>IoT Devices Store</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow">
  <div class="container">
    <a class="navbar-brand" href="/iot-store/">IoT Devices Store</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <?php if (isset($_SESSION['admin_id'])) { ?>
          <li class="nav-item">
            <a class="nav-link" href="/iot-store/admin/dashboard.php">Dashboard</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/iot-store/admin/manage_products.php">Products</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/iot-store/admin/manage_orders.php">Orders</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/iot-store/admin/logout.php">Logout</a>
          </li>
        <?php } elseif (isset($_SESSION['user_id'])) { ?>
          <li class="nav-item">
            <a class="nav-link" href="/iot-store/user/shop.php">Shop</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/iot-store/user/cart.php">
              Cart
              <?php
              if (!empty($_SESSION['cart'])) {
                  echo '<span class="badge bg-success">'.array_sum($_SESSION['cart']).'</span>';
              }
              ?>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/iot-store/user/orders.php">Orders</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/iot-store/user/profile.php">Profile</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/iot-store/user/logout.php">Logout</a>
          </li>
        <?php } else { ?>
          <li class="nav-item">
            <a class="nav-link" href="/iot-store/user/shop.php">Shop</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/iot-store/user/login.php">Login</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/iot-store/user/register.php">Register</a>
          </li>
        <?php } ?>
      </ul>
    </div>
  </div>
</nav>
