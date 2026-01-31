<?php include 'includes/header.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Franciscan Reservation | Premium Hotel Booking</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="dist/css/global.css">
  
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

<!-- Hero Section -->
<section id="home" class="hero">
  <div class="hero-content">
    <h1>Welcome to Franciscan Reservation</h1>
    <p>Your Premier Hotel Booking Experience</p>
    <a href="pages/booking/booking.form.php" class="btn-book">BOOK YOUR STAY</a>
  </div>
</section>

<!-- About Section -->
<section id="about">
  <div class="container">
    <h2 class="section-title">About Franciscan Reservation</h2>
    <div class="row align-items-center">
      <div class="col-md-6">
        <img src="dist/img/fh.png" class="img-fluid rounded shadow" alt="About Franciscan Reservation">
      </div>
      <div class="col-md-6">
        <p>
          <strong>Franciscan Reservation</strong> stands as a beacon of hospitality excellence, offering world-class accommodation and services. With our state-of-the-art Property Management System, we've revolutionized room booking and property management. Whether you're a business traveler, family vacationer, or leisure seeker, our platform ensures a seamless reservation experience with premium comfort and exceptional service at every touchpoint.
        </p>
      </div>
    </div>
  </div>
</section>

<!-- Facilities Section -->
<section id="facilities" class="bg-light">
  <div class="container">
    <h2 class="section-title">Our Premium Facilities</h2>
    <div class="row">
      <div class="col-md-4">
        <div class="facility-card">
          <i class="fas fa-bed" style="font-size: 3rem; color: #dc143c;"></i>
          <h5>Luxurious Rooms</h5>
          <p>Experience comfort in our elegantly designed rooms featuring modern amenities, plush bedding, and stunning views.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="facility-card">
          <i class="fas fa-wifi" style="font-size: 3rem; color: #dc143c;"></i>
          <h5>High-Speed WiFi</h5>
          <p>Stay connected with complimentary high-speed internet throughout the property, perfect for work or leisure.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="facility-card">
          <i class="fas fa-headset" style="font-size: 3rem; color: #dc143c;"></i>
          <h5>24/7 Concierge</h5>
          <p>Our dedicated team is always available to assist with reservations, recommendations, and special requests.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Gallery Section -->
<section id="gallery">
  <div class="container">
    <h2 class="section-title">Our Gallery</h2>
    <div class="row g-4">
      <div class="col-md-3 col-sm-6">
        <div class="gallery-img">
          <img src="dist/img/room1.jpg" alt="Room 1">
        </div>
      </div>
      <div class="col-md-3 col-sm-6">
        <div class="gallery-img">
          <img src="dist/img/room2.jpg" alt="Room 2">
        </div>
      </div>
      <div class="col-md-3 col-sm-6">
        <div class="gallery-img">
          <img src="dist/img/room3.jpg" alt="Room 3">
        </div>
      </div>
      <div class="col-md-3 col-sm-6">
        <div class="gallery-img">
          <img src="dist/img/room4.jpg" alt="Room 4">
        </div>
      </div>
      <div class="col-md-3 col-sm-6">
        <div class="gallery-img">
          <img src="dist/img/room5.jpg" alt="Room 5">
        </div>
      </div>
      <div class="col-md-3 col-sm-6">
        <div class="gallery-img">
          <img src="dist/img/room6.jpg" alt="Room 6">
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Contact Section -->
<section id="contact" class="bg-light">
  <div class="container">
    <h2 class="section-title">Get In Touch</h2>
    <div class="row g-4">
      <div class="col-md-6">
        <div class="contact-form">
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

          <h5 style="color: #dc143c; margin-bottom: 30px; font-weight: 700;">Send us your message</h5>
          <form action="pages/message/send.message.php" method="POST">
            <div class="mb-3">
              <label class="form-label"><i class="fas fa-user"></i> Your Name</label>
              <input type="text" name="name" class="form-control" placeholder="Enter your full name" required>
            </div>
            <div class="mb-3">
              <label class="form-label"><i class="fas fa-envelope"></i> Your Email</label>
              <input type="email" name="email" class="form-control" placeholder="Enter your email address" required>
            </div>
            <div class="mb-3">
              <label class="form-label"><i class="fas fa-comment"></i> Message</label>
              <textarea name="message" class="form-control" rows="5" placeholder="Type your message here..." required></textarea>
            </div>
            <button type="submit" class="btn-send"><i class="fas fa-paper-plane"></i> Send Message</button>
          </form>
        </div>
      </div>
      <div class="col-md-6">
        <div class="contact-info">
          <div>
            <p><i class="fas fa-map-marker-alt"></i> <strong>Address:</strong> Bacoor City, Cavite</p>
            <p><i class="fas fa-phone"></i> <strong>Phone:</strong> +63 912 345 6789</p>
            <p><i class="fas fa-envelope"></i> <strong>Email:</strong> info@propertyhub.com</p>
            <p><i class="fas fa-clock"></i> <strong>Hours:</strong> 24/7 Service Available</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Footer -->
<footer>
  &copy; <?php echo date('Y'); ?> Franciscan Reservation. All rights reserved.
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
