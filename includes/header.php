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
  <title>Franciscan Hoteliers</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="../../dist/css/global.css">

    <style>
    body { font-family: Arial, sans-serif; }
    
   

    /* .dropdown-menu .dropdown-item {
      color: white;  
      font-weight: bold;
    }

    .dropdown-menu .dropdown-item:hover {
      background-color: yellow;
      color: black;
    }
    section { padding: 60px 0; }
    .section-title { text-align: center; margin-bottom: 40px; font-weight: bold; }
    footer { background: #111; color: white; padding: 20px; text-align: center; } */

      .dropdown-menu {
      background-color: yellow;
      border: none;
      border-radius: 0; 
    }

    .dropdown-menu .dropdown-item {
      color: white;  
      font-weight: bold;
    }

    .dropdown-menu .dropdown-item:hover {
      background-color: yellow;
      color: black;
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
<nav class="navbar navbar-expand-lg fixed-top" style="background:red;">
  <div class="container">
    <a href="/Property_Hub/home.php"><img src="/Property_Hub/dist/img/ph6.png" class="navbar-logo" alt="Property Hub"></a>
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
