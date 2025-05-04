<?php include('db_connection.php'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dashboard</title>

  <!-- AdminLTE & FontAwesome -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css">

  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      margin: 0;
      padding: 0;
      background-image: url('bg.png');
      background-size: cover;
      background-position: center;
      background-attachment: fixed;
    }

    .brand-link {
      font-weight: 600;
      font-size: 1.0rem;
      color: rgba(255, 196, 0, 0.59);
    }

    .main-sidebar {
  background-color: #fff;
  border-right: 1px solid #ddd;
  min-height: 100vh;
}

.nav-sidebar .nav-link {
  color: rgba(0, 0, 0, 0.6);
  font-weight: 500;
  transition: all 0.2s ease-in-out;
}

.nav-sidebar .nav-link.active,
.nav-sidebar .nav-link:hover {
  background-color: #fff7cc !important;
  color:rgb(255, 179, 0) !important;            
}

.nav-sidebar .nav-link.active .nav-icon,
.nav-sidebar .nav-link:hover .nav-icon {
  color:rgb(255, 179, 0) !important;
}

.content-wrapper {
  background: transparent;
  padding: 2rem;
  height: 100vh;
}


    h1 {
      color: rgb(255, 179, 0);
    }

    .dashboard-card {
      display: flex;
      align-items: center;
      justify-content: space-between;
      background: #fff;
      border-left: 6px solid;
      border-radius: 14px;
      padding: 20px 24px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
      transition: all 0.2s ease-in-out;
    }

    .dashboard-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 8px 18px rgba(0, 0, 0, 0.08);
    }

    .dashboard-card .info {
      flex: 1;
    }

    .dashboard-card h3 {
      font-size: 2rem;
      font-weight: 700;
      margin: 0;
      color: #222;
    }

    .dashboard-card p {
      margin: 0;
      font-size: 1rem;
      color: #777;
    }

    .dashboard-card .icon {
      font-size: 2.5rem;
      opacity: 0.3;
      margin-left: 20px;
    }

    .border-info { border-color: #17a2b8; }
    .border-danger { border-color: #dc3545; }
    .border-warning { border-color: #ffc107; }
    .border-success { border-color: #28a745; }
    .border-primary { border-color: #007bff; }
    .border-dark { border-color: #343a40; }
  </style>
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

  <!-- Sidebar -->
  <aside class="main-sidebar elevation-1">
    <a href="#" class="brand-link text-center py-3">
      <span class="brand-text">SafeOTW Super Admin</span>
    </a>

    <div class="sidebar">
      <nav class="mt-3">
        <ul class="nav nav-pills nav-sidebar flex-column">
          <li class="nav-item">
            <a href="dashboard.php" class="nav-link active">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>Dashboard</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="safeotw_users.php" class="nav-link">
              <i class="nav-icon fas fa-users"></i>
              <p>Users</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="safeotw_students.php" class="nav-link">
              <i class="nav-icon fas fa-user-graduate"></i>
              <p>Students</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="cars.php" class="nav-link">
              <i class="nav-icon fas fa-car"></i>
              <p>Cars</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="traffic_signs.php" class="nav-link">
              <i class="nav-icon fas fa-traffic-light"></i>
              <p>Traffic Signs</p>
            </a>
          </li>
        </ul>
      </nav>
    </div>
  </aside>

  <!-- Content -->
  <div class="content-wrapper">
    <h1>Dashboard</h1>

    <div class="row mt-5">
      <?php
        $total_users = $conn->query("SELECT COUNT(*) as count FROM users")->fetch_assoc()['count'];
        $total_superadmins = $conn->query("SELECT COUNT(*) as count FROM users WHERE user_role_id = 1")->fetch_assoc()['count'];
        $total_admins = $conn->query("SELECT COUNT(*) as count FROM users WHERE user_role_id = 2")->fetch_assoc()['count'];
        $total_instructors = $conn->query("SELECT COUNT(*) as count FROM users WHERE user_role_id = 3")->fetch_assoc()['count'];
        $total_students = $conn->query("SELECT COUNT(*) as count FROM student")->fetch_assoc()['count'];
        $total_signs = $conn->query("SELECT COUNT(*) as count FROM traffic_signs")->fetch_assoc()['count'];
        $total_cars = $conn->query("SELECT COUNT(*) as count FROM car")->fetch_assoc()['count'];
      ?>

      <div class="col-md-6 col-lg-4 mb-4">
        <div class="dashboard-card border-info">
          <div class="info">
            <h3><?= $total_users ?></h3>
            <p>Total Users</p>
          </div>
          <div class="icon text-info"><i class="fas fa-users"></i></div>
        </div>
      </div>

      <div class="col-md-6 col-lg-4 mb-4">
        <div class="dashboard-card border-dark">
          <div class="info">
            <h3><?= $total_superadmins ?></h3>
            <p>Super Admins</p>
          </div>
          <div class="icon text-dark"><i class="fas fa-user-shield"></i></div>
        </div>
      </div>

      <div class="col-md-6 col-lg-4 mb-4">
        <div class="dashboard-card border-danger">
          <div class="info">
            <h3><?= $total_admins ?></h3>
            <p>Admins</p>
          </div>
          <div class="icon text-danger"><i class="fas fa-user-tie"></i></div>
        </div>
      </div>

      <div class="col-md-6 col-lg-4 mb-4">
        <div class="dashboard-card border-warning">
          <div class="info">
            <h3><?= $total_instructors ?></h3>
            <p>Instructors</p>
          </div>
          <div class="icon text-warning"><i class="fas fa-chalkboard-teacher"></i></div>
        </div>
      </div>

      <div class="col-md-6 col-lg-4 mb-4">
        <div class="dashboard-card border-success">
          <div class="info">
            <h3><?= $total_students ?></h3>
            <p>Students</p>
          </div>
          <div class="icon text-success"><i class="fas fa-user-graduate"></i></div>
        </div>
      </div>

      <div class="col-md-6 col-lg-4 mb-4">
        <div class="dashboard-card border-primary">
          <div class="info">
            <h3><?= $total_signs ?></h3>
            <p>Traffic Signs</p>
          </div>
          <div class="icon text-primary"><i class="fas fa-traffic-light"></i></div>
        </div>
      </div>
      <div class="col-md-6 col-lg-4 mb-4">
        <div class="dashboard-card border-secondary">
          <div class="info">
            <h3><?= $total_cars ?></h3>
            <p>Cars</p>
          </div>
          <div class="icon text-secondary"><i class="fas fa-car"></i></div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/plugins/jquery/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
</body>
</html>
