<?php
include('db_connection.php');

// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Add Car
  if (isset($_POST['add_car'])) {
    $plate_no = $_POST['plate_no'];
    $model = $_POST['model'];
    $client_id = $_POST['client_id'];
    $transmission_id = $_POST['mode_of_transmission_id'];
    $status_id = $_POST['car_status_id'];

    $stmt = $conn->prepare("INSERT INTO car (plate_no, model, client_id, mode_of_transmission_id, car_status_id) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssiii", $plate_no, $model, $client_id, $transmission_id, $status_id);
    $stmt->execute();
  }

  // Update Car
  if (isset($_POST['update_car'])) {
    $car_id = $_POST['car_id'];
    $plate_no = $_POST['plate_no'];
    $model = $_POST['model'];
    $client_id = $_POST['client_id'];
    $transmission_id = $_POST['mode_of_transmission_id'];
    $status_id = $_POST['car_status_id'];

    $stmt = $conn->prepare("UPDATE car SET plate_no=?, model=?, client_id=?, mode_of_transmission_id=?, car_status_id=? WHERE car_id=?");
    $stmt->bind_param("ssiiii", $plate_no, $model, $client_id, $transmission_id, $status_id, $car_id);
    $stmt->execute();
  }

  // Delete Car
  if (isset($_POST['delete_car_id'])) {
    $delete_id = $_POST['delete_car_id'];
    $stmt = $conn->prepare("DELETE FROM car WHERE car_id = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
  }
}

// Fetch dropdown options
$clients = $conn->query("SELECT * FROM client WHERE client_name = 'JYBG'");
$transmissions = $conn->query("SELECT * FROM mode_of_transmission");
$statuses = $conn->query("SELECT * FROM car_status");

// Fetch joined data for display (JYBG client only)
$result = $conn->query("
  SELECT car.*, 
         client.client_name, 
         mode_of_transmission.mode_of_transmission_name, 
         car_status.car_status_name 
  FROM car 
  LEFT JOIN client ON client.client_id = car.client_id 
  LEFT JOIN mode_of_transmission ON mode_of_transmission.mode_of_transmission_id = car.mode_of_transmission_id 
  LEFT JOIN car_status ON car_status.car_status_id = car.car_status_id
  WHERE client.client_name = 'JYBG'
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Cars</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css">
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
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
    .content-wrapper {
      padding: 2rem;
      background: #f8f9fa;
    }
    .table-card {
      background: #fff;
      border-radius: 14px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.05);
      padding: 1.5rem;
    }
    table th {
      background-color: #f0f0f5;
      color: #333;
      font-weight: 600;
    }
    table th, table td {
      vertical-align: middle !important;
      text-align: center;
    }
    .btn-primary {
      background-color:rgb(18, 160, 132) !important;
      border: none;
      padding: 0.4rem 0.8rem;
      font-size: 0.95rem;
      border-radius: 8px;
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
            <a href="safeotw_admin_JYBG_traffic_signs.php" class="nav-link">
              <i class="nav-icon fas fa-traffic-light"></i>
              <p>Traffic Signs</p>
            </a>
          </li>
          <li class="nav-item"><a href="safeotw_admin_JYBG_cars.php" class="nav-link active"><i class="nav-icon fas fa-car"></i><p>Cars</p></a></li>
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

  <div class="content-wrapper">
    <div class="d-flex justify-content-between mb-4 align-items-center">
    <h1 class="mb-0">Cars</h1>
      <button class="btn btn-primary" data-toggle="modal" data-target="#addCarModal"><i class="fas fa-plus"></i> Add Car</button>
    </div>

    <div class="table-card">
      <div class="table-responsive">
        <table class="table table-bordered table-hover table-striped">
          <thead>
            <tr>
              <th>ID</th>
              <th>Plate No</th>
              <th>Model</th>
              <th>Transmission</th>
              <th>Client</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
          <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?= $row['car_id'] ?></td>
              <td><?= $row['plate_no'] ?></td>
              <td><?= $row['model'] ?></td>
              <td><?= $row['mode_of_transmission_name'] ?></td>
              <td><?= $row['client_name'] ?></td>
              <td><?= $row['car_status_name'] ?></td>
              <td>
                <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editCarModal<?= $row['car_id'] ?>">Edit</button>
                <form method="POST" style="display:inline;">
                  <input type="hidden" name="delete_car_id" value="<?= $row['car_id'] ?>">
                  <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this car?')">Delete</button>
                </form>
              </td>
            </tr>

            <!-- Edit Modal -->
            <div class="modal fade" id="editCarModal<?= $row['car_id'] ?>" tabindex="-1">
              <div class="modal-dialog">
                <form method="POST" class="modal-content">
                  <input type="hidden" name="car_id" value="<?= $row['car_id'] ?>">
                  <input type="hidden" name="update_car" value="1">
                  <div class="modal-header">
                    <h5 class="modal-title">Edit Car</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                  </div>
                  <div class="modal-body">
                    <div class="form-group">
                      <label>Plate No</label>
                      <input type="text" name="plate_no" class="form-control" value="<?= $row['plate_no'] ?>" required>
                    </div>
                    <div class="form-group">
                      <label>Model</label>
                      <input type="text" name="model" class="form-control" value="<?= $row['model'] ?>" required>
                    </div>
                    <div class="form-group">
                      <label>Client</label>
                      <select name="client_id" class="form-control">
                        <?php while ($client = $clients->fetch_assoc()): ?>
                          <option value="<?= $client['client_id'] ?>" <?= $client['client_id'] == $row['client_id'] ? 'selected' : '' ?>><?= $client['client_name'] ?></option>
                        <?php endwhile; ?>
                      </select>
                    </div>
                    <div class="form-group">
                      <label>Transmission</label>
                      <select name="mode_of_transmission_id" class="form-control">
                        <?php while ($trans = $transmissions->fetch_assoc()): ?>
                          <option value="<?= $trans['mode_of_transmission_id'] ?>" <?= $trans['mode_of_transmission_id'] == $row['mode_of_transmission_id'] ? 'selected' : '' ?>><?= $trans['mode_of_transmission_name'] ?></option>
                        <?php endwhile; ?>
                      </select>
                    </div>
                    <div class="form-group">
                      <label>Status</label>
                      <select name="car_status_id" class="form-control">
                        <?php while ($status = $statuses->fetch_assoc()): ?>
                          <option value="<?= $status['car_status_id'] ?>" <?= $status['car_status_id'] == $row['car_status_id'] ? 'selected' : '' ?>><?= $status['car_status_name'] ?></option>
                        <?php endwhile; ?>
                      </select>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                  </div>
                </form>
              </div>
            </div>
          <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Add Car Modal -->
<div class="modal fade" id="addCarModal" tabindex="-1">
  <div class="modal-dialog">
    <form method="POST" class="modal-content">
      <input type="hidden" name="add_car" value="1">
      <div class="modal-header">
        <h5 class="modal-title">Add New Car</h5>
        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label>Plate No</label>
          <input type="text" name="plate_no" class="form-control" required>
        </div>
        <div class="form-group">
          <label>Model</label>
          <input type="text" name="model" class="form-control" required>
        </div>
        <div class="form-group">
          <label>Client</label>
          <select name="client_id" class="form-control" required>
            <?php while ($client = $clients->fetch_assoc()): ?>
              <option value="<?= $client['client_id'] ?>"><?= $client['client_name'] ?></option>
            <?php endwhile; ?>
          </select>
        </div>
        <div class="form-group">
          <label>Transmission</label>
          <select name="mode_of_transmission_id" class="form-control" required>
            <?php while ($trans = $transmissions->fetch_assoc()): ?>
              <option value="<?= $trans['mode_of_transmission_id'] ?>"><?= $trans['mode_of_transmission_name'] ?></option>
            <?php endwhile; ?>
          </select>
        </div>
        <div class="form-group">
          <label>Status</label>
          <select name="car_status_id" class="form-control" required>
            <?php while ($status = $statuses->fetch_assoc()): ?>
              <option value="<?= $status['car_status_id'] ?>"><?= $status['car_status_name'] ?></option>
            <?php endwhile; ?>
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Add Car</button>
      </div>
    </form>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
</body>
</html>
