<?php
session_start();
include("includes/header.php");
?>

<!-- ✅ HERO SECTION -->
<div class="bg-dark text-white text-center py-5 mb-4">
  <div class="container">
    <h1 class="display-4 fw-bold">Welcome to the IoT Store</h1>
    <p class="lead mb-4">Your one-stop shop for smart, connected devices.</p>
    <a href="user/shop.php" class="btn btn-primary btn-lg">
      <i class="bi bi-shop"></i> Shop Now
    </a>
  </div>
</div>




<!-- ✅ FEATURES SECTION -->
<div class="container py-4">
  <div class="row text-center">
    <div class="col-md-4 mb-4">
      <i class="bi bi-lightning-charge-fill display-4 text-primary"></i>
      <h4 class="fw-bold mt-3">Cutting-Edge Tech</h4>
      <p>Browse the latest in IoT technology and smart devices for your home and business.</p>
    </div>
    <div class="col-md-4 mb-4">
      <i class="bi bi-truck display-4 text-success"></i>
      <h4 class="fw-bold mt-3">Fast Delivery</h4>
      <p>Get your orders delivered quickly and reliably, straight to your door.</p>
    </div>
    <div class="col-md-4 mb-4">
      <i class="bi bi-shield-lock-fill display-4 text-warning"></i>
      <h4 class="fw-bold mt-3">Secure Shopping</h4>
      <p>Shop with confidence thanks to our secure checkout and payment systems.</p>
    </div>
  </div>
</div>

<!-- ✅ CALL TO ACTION -->
<div class="bg-primary text-white text-center py-4">
  <div class="container">
    <h3 class="mb-3">Ready to upgrade your world with smart tech?</h3>
    <a href="user/shop.php" class="btn btn-light btn-lg">
      <i class="bi bi-bag-fill"></i> Start Shopping
    </a>
  </div>
</div>

<?php include("includes/footer.php"); ?>
