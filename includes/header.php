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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link rel="stylesheet" href="../../dist/css/global.css">

    <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body { 
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f8f9fa;
    }
    
    /* Hero Section */
    .hero {
      background: url('dist/img/photo4.jpg') no-repeat center center;
      background-size: cover;
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      text-align: center;
      position: relative;
    }
    .hero::before {
      content: "";
      position: absolute;
      top: 0; left: 0;
      width: 100%; height: 100%;
      background: rgba(220, 20, 60, 0.6);
      pointer-events: none;
    }
    .hero-content {
      position: relative;
      z-index: 2;
      animation: fadeInDown 1s ease-in;
    }
    .hero h1 { 
      font-size: 4.5rem; 
      font-weight: 700;
      text-shadow: 2px 2px 8px rgba(0,0,0,0.3);
      margin-bottom: 20px;
      letter-spacing: 1px;
    }
    .hero p { 
      font-size: 1.8rem;
      font-weight: 300;
      margin-bottom: 30px;
      text-shadow: 1px 1px 4px rgba(0,0,0,0.3);
    }
    .hero .btn-book {
      background: white;
      color: #dc143c;
      padding: 15px 50px;
      font-size: 1.1rem;
      font-weight: 700;
      border-radius: 50px;
      text-decoration: none;
      display: inline-block;
      transition: all 0.3s ease;
      box-shadow: 0 4px 15px rgba(0,0,0,0.2);
      border: none;
      cursor: pointer;
    }
    .hero .btn-book:hover {
      background: #f0f0f0;
      transform: translateY(-3px);
      box-shadow: 0 6px 20px rgba(0,0,0,0.3);
      color: #dc143c;
    }

    .navbar {
      z-index: 1050;
    }

    .dropdown-menu {
      z-index: 1100;
    }

    /* Section Styling */
    section {
      padding: 80px 0;
    }

    .section-title {
      font-size: 2.8rem;
      font-weight: 700;
      color: #dc143c;
      margin-bottom: 50px;
      text-align: center;
      position: relative;
      padding-bottom: 20px;
    }

    .section-title::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 50%;
      transform: translateX(-50%);
      width: 80px;
      height: 4px;
      background: #dc143c;
      border-radius: 2px;
    }

    /* About Section */
    #about {
      background-color: white;
    }

    #about p {
      font-size: 1.1rem;
      line-height: 1.8;
      color: #333;
      text-align: justify;
    }

    /* Facilities Section */
    #facilities {
      background-color: #f8f9fa;
    }

    .facility-card {
      background: white;
      padding: 30px;
      border-radius: 15px;
      transition: all 0.3s ease;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
      text-align: center;
      margin-bottom: 30px;
    }

    .facility-card:hover {
      transform: translateY(-10px);
      box-shadow: 0 8px 25px rgba(220, 20, 60, 0.2);
    }

    .facility-card h5 {
      font-size: 1.5rem;
      color: #dc143c;
      font-weight: 700;
      margin: 20px 0 15px 0;
    }

    .facility-card p {
      color: #666;
      line-height: 1.6;
    }

    .facility-card img {
      max-height: 150px;
      object-fit: cover;
    }

    /* Gallery Section */
    #gallery {
      background-color: white;
    }

    .gallery-img {
      position: relative;
      overflow: hidden;
      border-radius: 10px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.1);
      aspect-ratio: 1;
    }

    .gallery-img img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform 0.3s ease;
    }

    .gallery-img:hover img {
      transform: scale(1.1);
    }

    /* Contact Section */
    #contact {
      background-color: #f8f9fa;
    }

    .contact-form {
      background: white;
      padding: 40px;
      border-radius: 15px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    .contact-form .form-label {
      font-weight: 600;
      color: #333;
      margin-bottom: 10px;
    }

    .contact-form .form-control {
      border: 2px solid #e0e0e0;
      border-radius: 8px;
      padding: 12px 15px;
      font-size: 1rem;
      transition: border-color 0.3s ease;
    }

    .contact-form .form-control:focus {
      border-color: #dc143c;
      box-shadow: 0 0 0 0.2rem rgba(220, 20, 60, 0.15);
    }

    .contact-form .btn-send {
      background: #dc143c;
      color: white;
      padding: 12px 40px;
      font-weight: 700;
      border-radius: 50px;
      border: none;
      transition: all 0.3s ease;
      margin-top: 15px;
      width: 100%;
    }

    .contact-form .btn-send:hover {
      background: #b91230;
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(220, 20, 60, 0.3);
    }

    .contact-info {
      background: white;
      padding: 40px;
      border-radius: 15px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.1);
      height: 100%;
      display: flex;
      align-items: center;
    }

    .contact-info p {
      font-size: 1.1rem;
      line-height: 2.5;
      color: #333;
    }

    .contact-info i {
      color: #dc143c;
      margin-right: 15px;
      font-size: 1.3rem;
      min-width: 25px;
    }

    /* Footer */
    footer {
      background: #dc143c;
      color: white;
      text-align: center;
      padding: 20px 0;
      margin-top: 50px;
      font-weight: 600;
    }

    /* Animations */
    @keyframes fadeInDown {
      from {
        opacity: 0;
        transform: translateY(-30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    /* Alert Styling */
    .alert {
      border-radius: 10px;
      border: none;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .alert-success {
      background-color: #d4edda;
      color: #155724;
    }

    .alert-danger {
      background-color: #f8d7da;
      color: #721c24;
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
