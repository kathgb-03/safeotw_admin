<?php include('db_connection.php'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Traffic Signs</title>

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css">

  <style>
    body {
      background-color: #fffdf7;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .brand-link {
      font-weight: 600;
      font-size: 2rem;
      color: teal !important;
    }


    .main-sidebar {
      background-color: #fff;
      border-right: 1px solid #ddd;
    }

    .nav-sidebar .nav-link {
      font-weight: 500;
    }

    .nav-sidebar .nav-link.active, .nav-sidebar .nav-link:hover {
      background-color: #f0f0f5;
      color: teal;
    }

    .traffic-card {
  background: #fffef9;
  border: 1px solid #ffe680;
  border-radius: 16px;
  padding: 16px;
  text-align: center;
  box-shadow: 0 3px 12px rgba(0,0,0,0.04);
  transition: 0.2s ease-in-out;
  height: 300px; /* fixed height for uniformity */
  display: flex;
  flex-direction: column;
  justify-content: flex-start;
  overflow: hidden;
}

.traffic-card:hover {
  transform: translateY(-3px);
  box-shadow: 0 6px 16px rgba(255, 204, 0, 0.25);
}

.traffic-card img {
  max-width: 100%;
  height: 120px;
  object-fit: contain;
  margin-bottom: 12px;
  border-radius: 8px;
  background: #fffce6;
  padding: 8px;
}

.traffic-card h5 {
  margin: 8px 0 6px;
  font-weight: 600;
  font-size: 1rem;
  color: #333;
}

.traffic-card p {
  font-size: 0.9rem;
  color: #666;
  flex-grow: 1;
  overflow: hidden;
  text-overflow: ellipsis;
  display: -webkit-box;
  -webkit-line-clamp: 3; /* show max 3 lines */
  -webkit-box-orient: vertical;
}

  </style>
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

  <!-- Sidebar -->
  <aside class="main-sidebar elevation-1">
    <a href="#" class="brand-link text-center py-3">
      <span class="brand-text">JYBG Admin</span>
    </a>

    <div class="sidebar">
      <nav class="mt-3">
        <ul class="nav nav-pills nav-sidebar flex-column">
          <li class="nav-item">
            <a href="safeotw_admin_JYBG_dashboard.php" class="nav-link">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>Dashboard</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="safeotw_admin_JYBG_users.php" class="nav-link">
              <i class="nav-icon fas fa-users"></i>
              <p>Users</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="safeotw_admin_JYBG_students.php" class="nav-link">
              <i class="nav-icon fas fa-user-graduate"></i>
              <p>Students</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="safeotw_admin_JYBG_traffic_signs.php" class="nav-link active">
              <i class="nav-icon fas fa-traffic-light"></i>
              <p>Traffic Signs</p>
            </a>
          </li>
          <li class="nav-item"><a href="safeotw_admin_JYBG_cars.php" class="nav-link"><i class="nav-icon fas fa-car"></i><p>Cars</p></a></li>
          <li class="nav-item">
            <a href="safeotw_admin_JYBG_schedule.php" class="nav-link">
              <i class="nav-icon fas fa-calendar-alt"></i>
              <p>Schedule</p>
            </a>
          </li>
        </ul>
      </nav>
    </div>
  </aside>

    <!-- Content -->
    <div class="content-wrapper p-4">
    <h2 class="page-title">Traffic Signs</h2>

    <div class="row">
      <?php
      $result = $conn->query("SELECT * FROM traffic_signs ORDER BY traffic_sign_name ASC");
      if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
          $imageData = base64_encode($row['traffic_sign_image']);
          $imageSrc = 'data:image/jpeg;base64,' . $imageData;
          ?>
          <div class="col-sm-6 col-md-4 col-lg-3 mb-4">
            <div class="traffic-card">
              <img src="<?= $imageSrc ?>" alt="<?= htmlspecialchars($row['traffic_sign_name']) ?>">
              <h5><?= htmlspecialchars($row['traffic_sign_name']) ?></h5>
              <p><?= htmlspecialchars($row['traffic_sign_description']) ?></p>
            </div>
          </div>
          <?php
        }
      } else {
        echo '<p>No traffic signs found.</p>';
      }
      ?>
    </div>
  </div>
</div>

    <!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/plugins/jquery/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
</body>
</html>