<?php include '../../includes/header.php'; ?>

<!-- Contact Section -->
<section id="contact" class="bg-light" style="padding:100px 0;">
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

        <form action="../../pages/message/send.message.php" method="POST">
          <div class="mb-3">
            <label class="form-label">Your Name</label>
            <input type="text" name="name" class="form-control" placeholder="Enter your name" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Your Email</label>
            <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Message</label>
            <textarea name="message" class="form-control" rows="5" placeholder="Type your message" required></textarea>
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