<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

$BASE = '/Property_Hub/pages/';
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Franciscan Reservation</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="../../dist/css/global.css">

    <style>
    body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
    
    .navbar .btn-login {
      background: white;
      color: #dc143c !important;
      padding: 8px 20px;
      font-weight: 700;
      border-radius: 30px;
      text-decoration: none;
      transition: all 0.3s ease;
    }

    .navbar .btn-login:hover {
      background-color: #f0f0f0;
      box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    }
    
    .dropdown-menu {
      background-color: white;
      border: 1px solid #ddd;
      border-radius: 8px;
      box-shadow: 0 4px 12px rgba(220, 20, 60, 0.15);
    }

    .dropdown-menu .dropdown-item {
      color: #333;
      font-weight: 500;
      transition: all 0.3s ease;
    }

    .dropdown-menu .dropdown-item:hover {
      background-color: #ffe6e6;
      color: #dc143c;
      font-weight: 600;
    }
    
    section { padding: 60px 0; }
    .section-title { text-align: center; margin-bottom: 40px; font-weight: bold; }
    footer { background: #111; color: white; padding: 20px; text-align: center; }
    .navbar { 
      padding: 16px 0 !important;
    }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg fixed-top" style="background:red; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
  <div class="container">
    <a href="/Property_Hub/home.php"><img src="/Property_Hub/dist/img/FHSubIcon1.png" class="navbar-logo" alt="Franciscan Reservation"></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        
        <li class="nav-item"><a class="nav-link <?= $currentPage == 'aboutus.php' ? 'active' : '' ?>" href="<?= $BASE ?>home/aboutus.php">ABOUT US</a></li>
        <li class="nav-item"><a class="nav-link <?= $currentPage == 'facilities.php' ? 'active' : '' ?>" href="<?= $BASE ?>home/facilities.php">FACILITIES</a></li>
        <li class="nav-item"><a class="nav-link <?= $currentPage == 'gallery.php' ? 'active' : '' ?>" href="<?= $BASE ?>home/gallery.php">GALLERY</a></li>
        <li class="nav-item"><a class="nav-link <?= $currentPage == 'contact.php' ? 'active' : '' ?>" href="<?= $BASE ?>home/contact.php">CONTACT</a></li>

        

        <?php if (isset($_SESSION['role'])): ?>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle btn-login" href="#" id="profileDropdown" role="button" data-bs-toggle="dropdown">
              <?= htmlspecialchars($_SESSION['fullname']) ?>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
              <li><a class="dropdown-item" href="<?= $BASE ?>dashboard/index.php">Dashboard</a></li>
              <li><a class="dropdown-item" href="<?= $BASE ?>login/usersData/ctrl.logout.php">Logout</a></li>
            </ul>
          </li>
        <?php else: ?>
          <li class="nav-item"><a class="nav-link btn-login" href="<?= $BASE ?>login/login.php">LOGIN</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
