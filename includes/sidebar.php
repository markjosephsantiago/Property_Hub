<aside class="main-sidebar sidebar-dark-beige elevation-4">
  <!-- Brand Logo -->
  <a href="../dashboard/index.php" class="brand-link">
    <img src="../../dist/img/FHLoginIcon.png" alt="Franciscan Reservation Logo" class="brand-image elevation-3" style="opacity: .8; max-width: 100%; height: auto;">
    <span class="brand-text font-weight-light">Franciscan Reservation</span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- User Panel -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <?php
        $info = mysqli_query($conn, "SELECT * FROM tbl_user WHERE user_id = '$_SESSION[user_id]'");
        $row = mysqli_fetch_array($info);
        if (!empty($row['img'])) {
          echo '<img src="data:image/jpeg;base64,' . base64_encode($row['img']) . '" class="img-circle elevation-2 mt-2" style="width: 40px; height: 40px;" alt="User Image">';
        } else {
          echo '<img src="../../docs/assets/img/user.png" class="img-circle elevation-2 mt-2" style="width: 40px; height: 40px;" alt="User Image">';
        }
        ?>
      </div>
      <div class="info">
        <a href="#" class="d-block"><?= $_SESSION['fullname']; ?></a>
        <a href="#" class="d-block"><b><?= $_SESSION['role']; ?></b></a>
      </div>
    </div>

    <!-- Sidebar Search -->

    <!-- Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        
        <!-- Dashboard -->
        <li class="nav-item">
          <a href="../dashboard/index.php" class="nav-link">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>Dashboard</p>
          </a>
        </li>

        <!-- Admin sidebar -->
        <?php
        switch ($_SESSION['role']) {
          case 'Admin':
        ?>
        <!-- Admin Settings -->
        <li class="nav-item has-treeview">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-cogs"></i><p>Settings<i class="fas fa-angle-left right"></i></p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item"><a href="../role/add.roles.php" class="nav-link"><i class="fas fa-plus-circle nav-icon"></i><p>Add Role</p></a></li>
            <li class="nav-item"><a href="../role/list.roles.php" class="nav-link"><i class="fas fa-list nav-icon"></i><p>Role List</p></a></li>
            <li class="nav-item"><a href="../rooms/list.rooms.php" class="nav-link"><i class="fas fa-list nav-icon" ></i><p>Room List</p></a></li>
          </ul>
        </li>

        <!-- Admin Users -->
        <li class="nav-item has-treeview">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-users"></i><p>Users<i class="fas fa-angle-left right"></i></p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item"><a href="../users/add.users.php" class="nav-link"><i class="fas fa-user-plus nav-icon"></i><p>Add User</p></a></li>
            <li class="nav-item"><a href="../employee/add.employee.php" class="nav-link"><i class="fas fa-user-plus nav-icon"></i><p>Add Employee</p></a></li>
            <li class="nav-item"><a href="../users/list.users.php" class="nav-link"><i class="fas fa-list nav-icon"></i><p>Users List</p></a></li>
            <li class="nav-item"><a href="../guest/guest.list.php" class="nav-link"><i class="fas fa-list nav-icon"></i><p>Guest List</p></a></li>
            <li class="nav-item"><a href="../employee/list.employee.php" class="nav-link"><i class="fas fa-list nav-icon"></i><p>Employee List</p></a></li>
          </ul>
        </li>

        <!-- Admin Search -->


            <!-- Employee sidebar -->
        <?php break; case 'Employee': ?>
        <!-- Employee Room Management -->
        <li class="nav-item">
          <a href="../rooms/list.rooms.php" class="nav-link">
            <i class="nav-icon fas fa-list"></i><p>Room List</p>
          </a>
        </li>
         
          <!-- Guest sidebar -->
        <?php break; case 'Guest': ?>
        <!-- Guest Booking -->
        <li class="nav-item">
          <a href="../booking/booking.form.php" class="nav-link">
            <i class="nav-icon fas fa-door-open"></i><p>Book a Room</p>
          </a>
        </li>
        <?php break; } ?>

        <!-- All Users (except Admin): My Bookings -->
        <?php if ($_SESSION['role'] != "Admin"): ?>
        <li class="nav-item">
          <a href="../booking/status.list.php" class="nav-link">
            <i class="nav-icon fas fa-calendar-check"></i><p>My Bookings</p>
          </a>
        </li>
        <?php endif; ?>

        <!-- Logout -->
        <li class="nav-item logout-btn">
          <a class="nav-link" href="#" onclick="confirmLogout(event)">
            <i class="fas fa-sign-out-alt"></i> Logout
          </a>
        </li>
      </ul>
    </nav>
  </div>
</aside>
