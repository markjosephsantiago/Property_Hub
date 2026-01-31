<?php
  require '../../includes/session.php';
  require '../../includes/conn.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Franciscan Reservation | Dashboard</title>

  <?php require '../../includes/link.php';?>

</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Preloader -->
  <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="../../dist/img/AdminLTELogo.png" alt="AdminLTELogo" height="60" width="60">
  </div>

  <!-- Navbar -->
  <?php require '../../includes/navbar.php';?>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <?php require '../../includes/sidebar.php';?>

  <?php
        if($_SESSION['role'] == "Admin") {
        ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" style="background-color: #f8f9fa; min-height: 100vh;">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Dashboard</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Dashboard</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid p-4">
        <div class="row g-4 mt-2">

          <!-- Book a Room -->
          <?php
          $booking_count = $conn->query("SELECT COUNT(*) AS total FROM tbl_reservations")->fetch_assoc()['total'];
          ?>
          <div class="col-lg-3 col-md-4 col-sm-6">
            <a href="../booking/booking.list.php" class="text-decoration-none">
              <div class="status-box">
                <div class="status-header">Book a Room</div>
                <div class="status-body"><?= $booking_count ?></div>
              </div>
            </a>
          </div>

          <!-- Pending Approvals -->
          <div class="col-lg-3 col-md-4 col-sm-6">
            <a href="../booking/status.list.php" class="text-decoration-none">
              <div class="status-box">
                <div class="status-header">Bookings</div>
                <div class="status-body">Pending Approvals</div>
              </div>
            </a>
          </div>

          <!-- User Registrations -->
          <div class="col-lg-3 col-md-4 col-sm-6">
            <a href="../users/add.users.php" class="text-decoration-none">
              <div class="status-box">
                <div class="status-header">User Registrations</div>
                <div class="status-body">Manage Users</div>
              </div>
            </a>
          </div>

          <!-- Available Rooms -->
          <?php
          $available_rooms = $conn->query("SELECT COUNT(*) AS total FROM tbl_rooms WHERE status = 'available'")->fetch_assoc()['total'];
          ?>
          <div class="col-lg-3 col-md-4 col-sm-6">
            <a href="../rooms/list.rooms.php?status=available" class="text-decoration-none">
              <div class="status-box">
                <div class="status-header">Available Rooms</div>
                <div class="status-body"><?= $available_rooms ?></div>
              </div>
            </a>
          </div>

          <!-- Occupied Rooms -->
          <?php
          $occupied_rooms = $conn->query("SELECT COUNT(*) AS total FROM tbl_rooms WHERE status = 'occupied'")->fetch_assoc()['total'];
          ?>
          <div class="col-lg-3 col-md-4 col-sm-6">
            <a href="../rooms/list.rooms.php?status=occupied" class="text-decoration-none">
              <div class="status-box">
                <div class="status-header">Occupied Rooms</div>
                <div class="status-body"><?= $occupied_rooms ?></div>
              </div>
            </a>
          </div>

          <!-- Maintenance -->
          <?php
          $maintenance = $conn->query("SELECT COUNT(*) AS total FROM tbl_rooms WHERE status = 'maintenance'")->fetch_assoc()['total'];
          ?>
          <div class="col-lg-3 col-md-4 col-sm-6">
            <a href="../rooms/list.rooms.php?status=maintenance" class="text-decoration-none">
              <div class="status-box">
                <div class="status-header">Under Maintenance</div>
                <div class="status-body"><?= $maintenance ?></div>
              </div>
            </a>
          </div>

          <!-- Check-in -->
          <?php
          $checkin = $conn->query("SELECT COUNT(*) AS total FROM tbl_reservations WHERE status = 'checkin'")->fetch_assoc()['total'];
          ?>
          <div class="col-lg-3 col-md-4 col-sm-6">
            <a href="../booking/status.list.php?filter=checkin" class="text-decoration-none">
              <div class="status-box">
                <div class="status-header">Check-in</div>
                <div class="status-body"><?= $checkin ?></div>
              </div>
            </a>
          </div>

          <!-- Check-out -->
          <?php
          $checkout = $conn->query("SELECT COUNT(*) AS total FROM tbl_reservations WHERE status = 'checkout'")->fetch_assoc()['total'];
          ?>
          <div class="col-lg-3 col-md-4 col-sm-6">
            <a href="../booking/status.list.php?filter=checkout" class="text-decoration-none">
              <div class="status-box">
                <div class="status-header">Check-out</div>
                <div class="status-body"><?= $checkout ?></div>
              </div>
            </a>
          </div>

          <!-- Pending Reservations -->
          <?php
          $new_reservations = $conn->query("SELECT COUNT(*) AS total FROM tbl_reservations WHERE status = 'pending'")->fetch_assoc()['total'];
          ?>
          <div class="col-lg-3 col-md-4 col-sm-6">
            <a href="../booking/status.list.php?filter=new" class="text-decoration-none">
              <div class="status-box">
                <div class="status-header">Pending Reservations</div>
                <div class="status-body"><?= $new_reservations ?></div>
              </div>
            </a>
          </div>

          <?php
          // count records per cluster
          $query = "SELECT cluster_label, COUNT(*) AS total FROM tbl_reservations GROUP BY cluster_label ORDER BY cluster_label";
          $result = $conn->query($query);
          $clusters = [];
          $counts = [];

          while ($row = $result->fetch_assoc()) {
            $label = $row['cluster_label'] == -1 ? 'Noise' : 'Cluster ' . $row['cluster_label'];
            $clusters[] = $label;
            $counts[] = $row['total'];
          }
          ?>

          <div class="col-12">
            <div class="card p-4 text-dark rounded-3" style="background-color: #f8f9fa; border: 3px solid #dc143c; box-shadow: 0 4px 12px rgba(220, 20, 60, 0.15);">
              <h5 class="mb-4" style="color: #dc143c; font-size: 22px;">
                <i class="fas fa-project-diagram me-2"></i> DBSCAN Cluster Analysis
              </h5>

              <div class="row">
                <!-- Bar Chart -->
                <div class="col-lg-6 col-md-12">
                  <div style="background: white; padding: 20px; border-radius: 8px; border-left: 4px solid #dc143c;">
                    <h6 style="color: #333; margin-bottom: 15px; font-weight: 600;">Cluster Overview</h6>
                    <canvas id="dbscanChart" style="width:100%; max-height:280px;"></canvas>
                  </div>
                </div>

                <!-- Line Chart & Donut Charts Combined -->
                <div class="col-lg-6 col-md-12">
                  <div style="background: white; padding: 20px; border-radius: 8px; border-left: 4px solid #dc143c; height: 100%;">
                    <h6 style="color: #333; margin-bottom: 15px; font-weight: 600;">Distribution Trend & Analysis</h6>
                    <canvas id="dbscanLineChart" style="width:100%; max-height:250px; margin-bottom: 20px;"></canvas>

                    <div class="d-flex justify-content-around text-center flex-wrap gap-2">
                      <div style="background: #f0f0f0; padding: 12px; border-radius: 8px; flex: 1; border: 2px solid #dc143c;">
                        <canvas id="cluster1Chart" width="70" height="70"></canvas>
                        <p class="mt-2 text-dark mb-0" style="font-weight: 600; font-size: 12px;">Cluster 1</p>
                      </div>
                      <div style="background: #f0f0f0; padding: 12px; border-radius: 8px; flex: 1; border: 2px solid #ff6b6b;">
                        <canvas id="cluster2Chart" width="70" height="70"></canvas>
                        <p class="mt-2 text-dark mb-0" style="font-weight: 600; font-size: 12px;">Cluster 2</p>
                      </div>
                      <div style="background: #f0f0f0; padding: 12px; border-radius: 8px; flex: 1; border: 2px solid #ffa500;">
                        <canvas id="cluster3Chart" width="70" height="70"></canvas>
                        <p class="mt-2 text-dark mb-0" style="font-weight: 600; font-size: 12px;">Cluster 3</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        

  <!-- Employee -->
  <?php
        } elseif ($_SESSION['role'] == "Employee") {
        ?>
        <div class="content-wrapper" style="background-color: #f8f9fa; min-height: 100vh;">
          <section class="content">
            <div class="container-fluid p-4">

              <div class="row mb-4">
                <div class="col-12">
                  <h2 class="status-bar">Reservation Overview</h2>
                </div>
              </div>

              <div class="row g-4 mt-2">
                <?php
                // Available rooms
                $available_rooms = $conn->query("SELECT COUNT(*) AS total FROM tbl_rooms WHERE status = 'available'")->fetch_assoc()['total'];
                ?>
                <div class="col-md-4 col-sm-6">
                  <a href="../rooms/list.rooms.php?status=available" class="text-decoration-none">
                    <div class="status-box">
                      <div class="status-header">Available Rooms</div>
                      <div class="status-body"><?= $available_rooms ?></div>
                    </div>
                  </a>
                </div>

                <?php
                // Occupied rooms
                $occupied_rooms = $conn->query("SELECT COUNT(*) AS total FROM tbl_rooms WHERE status = 'occupied'")->fetch_assoc()['total'];
                ?>
                <div class="col-md-4 col-sm-6">
                  <a href="../rooms/list.rooms.php?status=occupied" class="text-decoration-none">
                    <div class="status-box">
                      <div class="status-header">Occupied Rooms</div>
                      <div class="status-body"><?= $occupied_rooms ?></div>
                    </div>
                  </a>
                </div>

                <?php
                // Maintenance
                $maintenance = $conn->query("SELECT COUNT(*) AS total FROM tbl_rooms WHERE status = 'maintenance'")->fetch_assoc()['total'];
                ?>
                <div class="col-md-4 col-sm-6">
                  <a href="../rooms/list.rooms.php?status=maintenance" class="text-decoration-none">
                    <div class="status-box">
                      <div class="status-header">Under Maintenance</div>
                      <div class="status-body"><?= $maintenance ?></div>
                    </div>
                  </a>
                </div>

                <?php
                // All check-ins (confirmed)
                $checkin = $conn->query("SELECT COUNT(*) AS total FROM tbl_reservations WHERE status = 'checkin'")->fetch_assoc()['total'];
                ?>
                <div class="col-md-4 col-sm-6">
                  <a href="../booking/status.list.php?filter=checkin" class="text-decoration-none">
                    <div class="status-box">
                      <div class="status-header">Check-in</div>
                      <div class="status-body"><?= $checkin ?></div>
                    </div>
                  </a>
                </div>

                <?php
                // All checkouts (cancelled)
                $checkout = $conn->query("SELECT COUNT(*) AS total FROM tbl_reservations WHERE status = 'checkout'")->fetch_assoc()['total'];
                ?>
                <div class="col-md-4 col-sm-6">
                  <a href="../booking/status.list.php?filter=checkout" class="text-decoration-none">
                    <div class="status-box">
                      <div class="status-header">Check-out</div>
                      <div class="status-body"><?= $checkout ?></div>
                    </div>
                  </a>
                </div>

                <?php
                // Pending / new reservations
                $new_reservations = $conn->query("SELECT COUNT(*) AS total FROM tbl_reservations WHERE status = 'pending'")->fetch_assoc()['total'];
                ?>
                <div class="col-md-4 col-sm-6">
                  <a href="../booking/status.list.php?filter=new" class="text-decoration-none">
                    <div class="status-box">
                      <div class="status-header">Pending Reservations</div>
                      <div class="status-body"><?= $new_reservations ?></div>
                    </div>
                  </a>
                </div>
              </div>
            </div>
          </section>
        </div>
            <!-- Guest -->
          <?php
          $active = null;
        } elseif ($_SESSION ['role'] == "Guest") {
          $user_id = $_SESSION['user_id'];

          $res = $conn->prepare("
              SELECT reservation_id, room_id
              FROM tbl_reservations
              WHERE user_id = ?
              AND status = 'checkin'
              ORDER BY checkin DESC
              LIMIT 1
          ");
          $res->bind_param("i", $user_id);
          $res->execute();
          $active = $res->get_result()->fetch_assoc();
          ?>
            <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3>Order Food</h3>
                <p>room service</p>
              </div>
              <div class="icon">
                <i class="fas fa-utensils"></i>
              </div>
                <?php if ($active): ?>
                <a href="../food/food.menu.php?reservation_id=<?= $active['reservation_id'] ?>&room_id=<?= $active['room_id'] ?>"
                  class="small-box-footer">
                  Order Food <i class="fas fa-arrow-circle-right"></i>
                </a>
                <?php else: ?>
                    <span class="small-box-footer text-danger">
                      No active room
                    </span>
                <?php endif; ?>
            </div>
          </div>
          <?php
        }
        ?>


  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<?php require '../../includes/script.php';?>
<script>
document.addEventListener('DOMContentLoaded', function () {
  const toastEl = document.getElementById('autoCheckoutToast');
  if (toastEl) {
    const toast = new bootstrap.Toast(toastEl, { delay: 5000 });
    toast.show();
  }
});


</script>
          <!-- Chart.js -->
          <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
          <script>
          const ctx = document.getElementById('dbscanChart').getContext('2d');

          const labels = <?php echo json_encode($clusters); ?>;
          const data = <?php echo json_encode($counts); ?>;

          new Chart(ctx, {
            type: 'bar',
            data: {
              labels: labels,
              datasets: [{
                label: 'Number of Data Points',
                data: data,
                backgroundColor: [
                  '#d6c6a1',
                  '#a8c686',
                  '#f1dca7',
                  '#c9b6e4',
                  '#f2b5b5',
                  '#b4d8e7'
                ],
                borderWidth: 1,
                borderColor: '#b8a382'
              }]
            },
            options: {
              responsive: true,
              scales: {
                y: {
                  beginAtZero: true,
                  ticks: { stepSize: 1 }
                }
              },
              plugins: {
                legend: { display: false },
                title: {
                  display: true,
                  text: 'DBSCAN Cluster Distribution'
                }
              }
            }
          });
          </script>
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  // Get PHP data into JS
  const clusters = <?php echo json_encode($clusters); ?>;
  const counts = <?php echo json_encode($counts); ?>;
  const total = counts.reduce((a, b) => a + b, 0);

  // ===== BAR CHART =====
  const barCtx = document.getElementById('dbscanChart').getContext('2d');
  new Chart(barCtx, {
    type: 'bar',
    data: {
      labels: clusters,
      datasets: [{
        label: 'Number of Data Points',
        data: counts,
        backgroundColor: [
          '#d6c6a1',
          '#a8c686',
          '#f1dca7',
          '#c9b6e4',
          '#f2b5b5',
          '#b4d8e7'
        ],
        borderColor: '#b8a382',
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: { display: false },
        title: {
          display: true,
          text: 'DBSCAN Cluster Distribution'
        }
      },
      scales: {
        y: { beginAtZero: true, ticks: { stepSize: 1 } }
      }
    }
  });

  // ===== LINE CHART =====
  const lineCtx = document.getElementById('dbscanLineChart').getContext('2d');
  new Chart(lineCtx, {
    type: 'line',
    data: {
      labels: clusters,
      datasets: [{
        label: 'Cluster Size',
        data: counts,
        borderColor: '#dc143c',
        backgroundColor: 'rgba(220, 20, 60, 0.15)',
        fill: true,
        tension: 0.4,
        pointBackgroundColor: '#dc143c',
        pointBorderWidth: 2,
        pointBorderColor: '#fff',
        pointRadius: 6
      }]
    },
    options: {
      responsive: true,
      plugins: { legend: { display: false } },
      scales: {
        x: {
          ticks: { color: '#333', maxRotation: 45, minRotation: 45 }
        },
        y: {
          ticks: { color: '#333' },
          grid: { color: 'rgba(220, 20, 60, 0.1)' },
          beginAtZero: true
        }
      }
    }
  });

  // ===== DONUT CHARTS =====
  function createDonutChart(id, value, color) {
    const ctx = document.getElementById(id).getContext('2d');
    
    // Calculate percentage
    const maxValue = Math.max(...counts);
    const percentage = maxValue > 0 ? (value / maxValue) * 100 : 0;
    
    const centerPlugin = {
      id: 'centerText',
      afterDatasetsDraw(chart) {
        const { ctx: chartCtx, chartArea: { left, top, width, height } } = chart;
        chartCtx.save();
        
        const centerX = left + width / 2;
        const centerY = top + height / 2;
        
        // Draw value
        chartCtx.font = 'bold 18px Arial';
        chartCtx.fillStyle = '#dc143c';
        chartCtx.textAlign = 'center';
        chartCtx.textBaseline = 'middle';
        chartCtx.fillText(value, centerX, centerY - 5);
        
        // Draw percentage
        chartCtx.font = 'bold 12px Arial';
        chartCtx.fillStyle = '#666';
        chartCtx.fillText(Math.round(percentage) + '%', centerX, centerY + 15);
        
        chartCtx.restore();
      }
    };
    
    new Chart(ctx, {
      type: 'doughnut',
      data: {
        datasets: [{
          data: [percentage, 100 - percentage],
          backgroundColor: [color, '#e0e0e0'],
          borderWidth: 0
        }]
      },
      options: {
        cutout: '70%',
        responsive: true,
        maintainAspectRatio: true,
        plugins: { 
          legend: { display: false }
        }
      },
      plugins: [centerPlugin]
    });
  }

  // Create donuts for first 3 clusters (you can add more if needed)
  createDonutChart('cluster1Chart', counts[0] || 0, '#dc143c');
  createDonutChart('cluster2Chart', counts[1] || 0, '#b22222');
  createDonutChart('cluster3Chart', counts[2] || 0, '#ff4500');
</script>



</body>
</html>