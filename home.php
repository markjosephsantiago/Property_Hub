
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Property Hub | Home</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="dist/css/global.css">
  
  <style>
    body { 
      font-family: Arial, sans-serif;
     }
    
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
      background: rgba(0,0,0,0.5);
    }
    .hero-content {
      position: relative;
      z-index: 2;
    }
    .hero h1 { font-size: 4rem; font-weight: bold; }
    .hero p { font-size: 1.5rem; }
    .hero {
        position: relative;
        z-index: 1;
      }

      .hero::before {
        content: "";
        position: absolute;
        inset: 0;
        background: rgba(0,0,0,0.5);
        pointer-events: none;   /* 🔥 THIS LINE FIXES IT */
      }
      .navbar {
        z-index: 1050;
      }

      .dropdown-menu {
        z-index: 1100;
      }

  </style>
</head>
<body>
<!-- Navbar --> 
<?php include 'includes/header.php'; ?>

<!-- Hero Section -->
<section id="home" class="hero">
  <div class="hero-content">
    <h1>Welcome to Property Hub</h1>
    <p>Book and manage rooms with ease</p>
    <a href="pages/booking/booking.form.php" class="btn-login">BOOK NOW</a>
  </div>
</section>

<!-- About Section -->
<section id="about">
  <div class="container">
    <h2 class="section-title">About Us</h2>
    <div class="row">
      <div class="col-md-6">
        <img src="dist/img/fh.png" class="img-fluid rounded shadow" alt="About Franciscan Hoteliers">
      </div>
      <div class="col-md-6 d-flex align-items-center">
        <p>
          Franciscan Hoteliers is your one-stop platform for room booking and property management.  
          We provide seamless reservations, secure transactions, and a user-friendly interface 
          designed to make managing your stay more convenient and enjoyable.
        </p>
      </div>
    </div>
  </div>
</section>

<!-- Facilities Section -->
<section id="facilities" class="bg-light">
  <div class="container">
    <h2 class="section-title">Our Facilities</h2>
    <div class="row text-center">
      <div class="col-md-4">
        <img src="dist/img/photo2.png" class="img-fluid rounded mb-3" alt="Room">
        <h5>Comfortable Rooms</h5>
        <p>Enjoy well-maintained rooms designed for your comfort and relaxation.</p>
      </div>
      <div class="col-md-4">
        <img src="dist/img/wifi.png" class="img-fluid rounded mb-3" alt="WiFi">
        <h5>Free WiFi</h5>
        <p>Stay connected with fast and reliable internet throughout your stay.</p>
      </div>
      <div class="col-md-4">
        <img src="dist/img/service.jpg" class="img-fluid rounded mb-3" alt="Service">
        <h5>24/7 Service</h5>
        <p>Our team is always available to provide you with the best experience.</p>
      </div>
    </div>
  </div>
</section>

<!-- Gallery Section -->
<section id="gallery">
  <div class="container">
    <h2 class="section-title">Gallery</h2>
    <div class="row g-3">
      <div class="col-md-3"><img src="dist/img/room1.jpg" class="img-fluid rounded shadow" alt=""></div>
      <div class="col-md-3"><img src="dist/img/room2.jpg" class="img-fluid rounded shadow" alt=""></div>
      <div class="col-md-3"><img src="dist/img/room3.jpg" class="img-fluid rounded shadow" alt=""></div>
      <div class="col-md-3"><img src="dist/img/room4.jpg" class="img-fluid rounded shadow" alt=""></div>
      <div class="col-md-3"><img src="dist/img/room5.jpg" class="img-fluid rounded shadow" alt=""></div>
      <div class="col-md-3"><img src="dist/img/room6.jpg" class="img-fluid rounded shadow" alt=""></div>
    </div>
  </div>
</section>

<!-- Contact Section -->
<section id="contact" class="bg-light">
  <div class="container">
    <h2 class="section-title">Contact Us</h2>
    <div class="row">
      <div class="col-md-6">
        <?php if (isset($_SESSION['success'])): ?>
          <div class="alert alert-success alert-dismissible fade show" role="alert">
              <?= $_SESSION['success']; ?>
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
          <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= $_SESSION['error']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <form action="pages/message/send.message.php" method="POST">
          <div class="mb-3">
            <label class="form-label">Your Name</label>
            <input type="text" name="name" class="form-control" placeholder="Enter your name">
          </div>
          <div class="mb-3">
            <label class="form-label">Your Email</label>
            <input type="email" name="email" class="form-control" placeholder="Enter your email">
          </div>
          <div class="mb-3">
            <label class="form-label">Message</label>
            <textarea name="message" class="form-control" rows="5" placeholder="Type your message"></textarea>
          </div>
          <button type="submit" class="btn btn-danger">Send Message</button>
        </form>
      </div>
      <div class="col-md-6 d-flex align-items-center">
        <p>
          📍 Address: Bacoor City, Cavite <br>
          ☎ Phone: +63 912 345 6789 <br>
          📧 Email: info@propertyhub.com
        </p>
      </div>
    </div>
  </div>
</section>

<!-- Footer -->
<footer>
  &copy; <?php echo date('Y'); ?> Property Hub. All rights reserved.
</footer>

<script>
  // Auto-hide alerts after 5 seconds
  setTimeout(() => {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
      alert.classList.remove('show');
      alert.classList.add('fade');
    });
  }, 5000);
</script>

</body>
</html>
