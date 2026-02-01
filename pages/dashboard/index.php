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
            <a href="../booking/status.list.php" class="text-decoration-none">
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
          // Daily Sales Data
          $sales_labels = [];
          $sales_values = [];
          for ($i = 6; $i >= 0; $i--) {
              $date = date('Y-m-d', strtotime("-$i days"));
              $sales_labels[$date] = date('M d', strtotime($date));
              $sales_values[$date] = 0;
          }

          $sales_query = mysqli_query($conn, "
              SELECT DATE(payment_date) as p_date, SUM(amount) as total 
              FROM tbl_payment 
              WHERE payment_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
              GROUP BY DATE(payment_date)
              ORDER BY p_date ASC
          ");

          while ($row = mysqli_fetch_assoc($sales_query)) {
              if (isset($sales_values[$row['p_date']])) {
                  $sales_values[$row['p_date']] = (float)$row['total'];
              }
          }

          $js_labels = array_values($sales_labels);
          $js_values = array_values($sales_values);

          // Restore DBSCAN Data
          $cluster_data = [];
          $labels = [];
          $counts = [];
          $colors = ['#dc143c', '#b22222', '#ff4500', '#ffa500', '#ffd700', '#daa520'];

          $dbscan_query = "SELECT cluster_label, COUNT(*) AS total, 
                                  AVG(guest_count) as avg_guests, 
                                  AVG(DATEDIFF(checkout, checkin)) as avg_duration
                           FROM tbl_reservations 
                           GROUP BY cluster_label 
                           ORDER BY cluster_label";
          $dbscan_result = $conn->query($dbscan_query);

          $idx = 0;
          while ($row = $dbscan_result->fetch_assoc()) {
              if ($row['cluster_label'] == -1) {
                  $label_full = "Outliers (Irregular)";
              } else {
                  $label_full = "Cluster " . $row['cluster_label'] . " (Avg " . round($row['avg_guests']) . " Guests, " . round($row['avg_duration']) . " Days)";
              }
              
              $cluster_data[] = [
                  'id' => $idx,
                  'label' => $label_full,
                  'count' => $row['total'],
                  'color' => $colors[$idx % count($colors)]
              ];
              $labels[] = $label_full;
              $counts[] = $row['total'];
              $idx++;
          }
          ?>

          <div class="col-12">
            <div class="card p-4 text-dark rounded-3" style="background-color: #f8f9fa; border: 3px solid #dc143c; box-shadow: 0 4px 12px rgba(220, 20, 60, 0.15);">
              <h5 class="mb-4" style="color: #dc143c; font-size: 22px;">
                <i class="fas fa-chart-line me-2"></i> Daily Sales Report (Last 7 Days)
              </h5>

              <div class="row">
                <div class="col-12">
                  <div style="background: white; padding: 25px; border-radius: 8px; border-left: 5px solid #dc143c; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                      <h6 style="color: #333; font-weight: 700; margin: 0;">Revenue Overview</h6>
                      <span class="badge badge-danger p-2 px-3" style="border-radius: 20px; font-weight: 600;">Currency: PHP (₱)</span>
                    </div>
                    <canvas id="salesBarChart" style="width:100%; max-height:280px;"></canvas>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-12 mt-4">
            <div class="card p-4 text-dark rounded-3" style="background-color: #f8f9fa; border: 3px solid #dc143c; box-shadow: 0 4px 12px rgba(220, 20, 60, 0.15);">
              <h5 class="mb-4" style="color: #dc143c; font-size: 22px;">
                <i class="fas fa-project-diagram me-2"></i> DBSCAN Cluster Analysis
              </h5>

              <div class="row">
                <div class="col-lg-6 col-md-12">
                  <div style="background: white; padding: 20px; border-radius: 8px; border-left: 4px solid #dc143c;">
                    <h6 style="color: #333; margin-bottom: 15px; font-weight: 600;">Distribution Trend</h6>
                    <canvas id="dbscanLineChart" style="width:100%; max-height:250px;"></canvas>
                  </div>
                </div>

                <div class="col-lg-6 col-md-12">
                  <div style="background: white; padding: 20px; border-radius: 8px; border-left: 4px solid #dc143c; height: 100%;">
                    <h6 style="color: #333; margin-bottom: 15px; font-weight: 600;">Cluster Analysis</h6>
                    <div class="d-flex justify-content-around text-center flex-wrap gap-2">
                      <?php foreach ($cluster_data as $c): ?>
                          <div style="background: #f0f0f0; padding: 12px; border-radius: 8px; flex: 1; border: 2px solid <?= $c['color'] ?>;">
                            <canvas id="donutChart<?= $c['id'] ?>" width="70" height="70"></canvas>
                            <p class="mt-2 text-dark mb-0" style="font-weight: 600; font-size: 11px;"><?= $c['label'] ?></p>
                          </div>
                      <?php endforeach; ?>
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
  // Sales Chart Data
  const salesLabels = <?php echo json_encode($js_labels); ?>;
  const salesData = <?php echo json_encode($js_values); ?>;

  const salesCtx = document.getElementById('salesBarChart').getContext('2d');
  new Chart(salesCtx, {
    type: 'bar',
    data: {
      labels: salesLabels,
      datasets: [{
        label: 'Daily Revenue',
        data: salesData,
        backgroundColor: 'rgba(220, 20, 60, 0.7)',
        borderColor: '#dc143c',
        borderWidth: 2,
        borderRadius: 5,
        borderSkipped: false,
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: { display: false },
        tooltip: {
          callbacks: {
            label: function(context) {
              return 'Revenue: ₱' + context.raw.toLocaleString();
            }
          }
        }
      },
      scales: {
        y: {
          beginAtZero: true,
          ticks: {
            callback: function(value) {
              return '₱' + value.toLocaleString();
            },
            font: { weight: '600' }
          },
          grid: { color: 'rgba(0,0,0,0.05)' }
        },
        x: {
          ticks: { font: { weight: '600' } },
          grid: { display: false }
        }
      }
    }
  });

  // DBSCAN Chart Data
  const dbLabels = <?php echo json_encode($labels); ?>;
  const dbCounts = <?php echo json_encode($counts); ?>.map(Number);
  const clusterData = <?php echo json_encode($cluster_data); ?>;
  const dbTotal = dbCounts.reduce((a, b) => a + b, 0);

  // Line Chart
  const lineCtx = document.getElementById('dbscanLineChart').getContext('2d');
  new Chart(lineCtx, {
    type: 'line',
    data: {
      labels: dbLabels,
      datasets: [{
        label: 'Cluster Size',
        data: dbCounts,
        borderColor: '#dc143c',
        backgroundColor: 'rgba(220, 20, 60, 0.15)',
        fill: true,
        tension: 0.4,
        pointBackgroundColor: '#dc143c',
        pointRadius: 6
      }]
    },
    options: {
      responsive: true,
      plugins: { legend: { display: false } },
      scales: {
        x: { display: false },
        y: { beginAtZero: true, ticks: { stepSize: 1 } }
      }
    }
  });

  // Donut Charts
  function createDonutChart(id, value, color) {
    const canvas = document.getElementById(id);
    if (!canvas) return;
    const ctx = canvas.getContext('2d');
    const numericValue = parseInt(value);
    const percentage = dbTotal > 0 ? (numericValue / dbTotal) * 100 : 0;
    
    new Chart(ctx, {
      type: 'doughnut',
      data: {
        datasets: [{
          data: [numericValue, dbTotal - numericValue],
          backgroundColor: [color, '#e0e0e0'],
          borderWidth: 0
        }]
      },
      options: {
        cutout: '75%',
        plugins: { 
            legend: { display: false },
            tooltip: { enabled: false }
        }
      },
      plugins: [{
        id: 'centerText',
        afterDatasetsDraw(chart) {
          const { ctx, chartArea: { left, top, width, height } } = chart;
          ctx.save();
          const centerX = left + width / 2;
          const centerY = top + height / 2;
          ctx.font = 'bold 14px Arial';
          ctx.fillStyle = color;
          ctx.textAlign = 'center';
          ctx.textBaseline = 'middle';
          ctx.fillText(numericValue, centerX, centerY - 6);
          ctx.font = 'bold 10px Arial';
          ctx.fillStyle = '#666';
          ctx.fillText(Math.round(percentage) + '%', centerX, centerY + 8);
          ctx.restore();
        }
      }]
    });
  }

  clusterData.forEach(c => createDonutChart('donutChart' + c.id, c.count, c.color));
</script>



</body>
</html>