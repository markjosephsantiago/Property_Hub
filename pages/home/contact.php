<?php include '../../includes/header.php'; ?>

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
            <p><i class="fas fa-envelope"></i> <strong>Email:</strong> info@franciscanreservation.com</p>
            <p><i class="fas fa-clock"></i> <strong>Hours:</strong> 24/7 Service Available</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<footer>
  &copy; <?php echo date('Y'); ?> Franciscan Hoteliers. All rights reserved.
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