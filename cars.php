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
$clients = $conn->query("SELECT * FROM client");
$transmissions = $conn->query("SELECT * FROM mode_of_transmission");
$statuses = $conn->query("SELECT * FROM car_status");

// Fetch joined data for display
$result = $conn->query("
  SELECT car.*, 
         client.client_name, 
         mode_of_transmission.mode_of_transmission_name, 
         car_status.car_status_name 
  FROM car 
  LEFT JOIN client ON client.client_id = car.client_id 
  LEFT JOIN mode_of_transmission ON mode_of_transmission.mode_of_transmission_id = car.mode_of_transmission_id 
  LEFT JOIN car_status ON car_status.car_status_id = car.car_status_id
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
      background-image: url('bg1.png');
      background-size: cover;
      background-attachment: fixed;
    }
    .brand-link { font-weight: 600; font-size: 1.0rem; color: rgba(255, 196, 0, 0.59); }
    .main-sidebar { background-color: #fff; border-right: 1px solid #ddd; min-height: 100vh; }
    .nav-sidebar .nav-link { color: rgba(0, 0, 0, 0.6); font-weight: 500; }
    .nav-sidebar .nav-link.active,
    .nav-sidebar .nav-link:hover { background-color: #fff7cc !important; color: rgb(255, 179, 0) !important; }
    .content-wrapper { background: transparent; padding: 2rem; min-height: 100vh; }
    table { background: white; }
    h1 { color: rgb(255, 179, 0); }
    .btn-primary { background-color: rgb(255, 179, 0) !important; border: none; }
    .btn-yellow { background-color: #ffb300; color: white; border: none; padding: 0.5rem 1.1rem; border-radius: 6px; font-weight: 500; }
  </style>
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  <aside class="main-sidebar elevation-1">
    <a href="#" class="brand-link text-center py-3"><span class="brand-text">SafeOTW Super Admin</span></a>
    <div class="sidebar">
      <nav class="mt-3">
        <ul class="nav nav-pills nav-sidebar flex-column">
          <li class="nav-item"><a href="dashboard.php" class="nav-link"><i class="nav-icon fas fa-tachometer-alt"></i><p>Dashboard</p></a></li>
          <li class="nav-item"><a href="safeotw_users.php" class="nav-link"><i class="nav-icon fas fa-users"></i><p>Users</p></a></li>
          <li class="nav-item"><a href="safeotw_students.php" class="nav-link"><i class="nav-icon fas fa-user-graduate"></i><p>Students</p></a></li>
          <li class="nav-item"><a href="cars.php" class="nav-link active"><i class="nav-icon fas fa-car"></i><p>Cars</p></a></li>
          <li class="nav-item"><a href="traffic_signs.php" class="nav-link"><i class="nav-icon fas fa-traffic-light"></i><p>Traffic Signs</p></a></li>
        </ul>
      </nav>
    </div>
  </aside>

  <div class="content-wrapper">
    <div class="d-flex justify-content-between mb-3 align-items-center">
      <h1>Cars</h1>
      <button class="btn btn-primary" data-toggle="modal" data-target="#addCarModal"><i class="fas fa-plus"></i> Add Car</button>
    </div>

    <div class="card">
      <div class="card-body">
        <table class="table table-bordered table-hover">
          <thead class="thead-dark">
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
          <?php
          $clients_for_modal = $conn->query("SELECT * FROM client");
          $transmissions_for_modal = $conn->query("SELECT * FROM mode_of_transmission");
          $statuses_for_modal = $conn->query("SELECT * FROM car_status");
          ?>
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
                <div class="modal-header"><h5>Edit Car</h5><button type="button" class="close" data-dismiss="modal">&times;</button></div>
                <div class="modal-body">
                  <input type="hidden" name="car_id" value="<?= $row['car_id'] ?>">
                  <input type="hidden" name="update_car" value="1">
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
                      <select name="client_id" class="form-control" required>
                        <?php mysqli_data_seek($clients_for_modal, 0); while ($c = $clients_for_modal->fetch_assoc()): ?>
                          <option value="<?= $c['client_id'] ?>" <?= $c['client_id'] == $row['client_id'] ? 'selected' : '' ?>><?= $c['client_name'] ?></option>
                        <?php endwhile; ?>
                      </select>
                    </div>
                    <div class="form-group">
                      <label>Transmission</label>
                      <select name="mode_of_transmission_id" class="form-control" required>
                        <?php mysqli_data_seek($transmissions_for_modal, 0); while ($t = $transmissions_for_modal->fetch_assoc()): ?>
                          <option value="<?= $t['mode_of_transmission_id'] ?>" <?= $t['mode_of_transmission_id'] == $row['mode_of_transmission_id'] ? 'selected' : '' ?>><?= $t['mode_of_transmission_name'] ?></option>
                        <?php endwhile; ?>
                      </select>
                    </div>
                    <div class="form-group">
                      <label>Status</label>
                      <select name="car_status_id" class="form-control" required>
                        <?php mysqli_data_seek($statuses_for_modal, 0); while ($s = $statuses_for_modal->fetch_assoc()): ?>
                          <option value="<?= $s['car_status_id'] ?>" <?= $s['car_status_id'] == $row['car_status_id'] ? 'selected' : '' ?>><?= $s['car_status_name'] ?></option>
                        <?php endwhile; ?>
                      </select>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="submit" class="btn btn-yellow">Update</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
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
            <?php mysqli_data_seek($clients, 0); while ($c = $clients->fetch_assoc()): ?>
              <option value="<?= $c['client_id'] ?>"><?= $c['client_name'] ?></option>
            <?php endwhile; ?>
          </select>
        </div>
        <div class="form-group">
          <label>Transmission</label>
          <select name="mode_of_transmission_id" class="form-control" required>
            <?php mysqli_data_seek($transmissions, 0); while ($t = $transmissions->fetch_assoc()): ?>
              <option value="<?= $t['mode_of_transmission_id'] ?>"><?= $t['mode_of_transmission_name'] ?></option>
            <?php endwhile; ?>
          </select>
        </div>
        <div class="form-group">
          <label>Status</label>
          <select name="car_status_id" class="form-control" required>
            <?php mysqli_data_seek($statuses, 0); while ($s = $statuses->fetch_assoc()): ?>
              <option value="<?= $s['car_status_id'] ?>"><?= $s['car_status_name'] ?></option>
            <?php endwhile; ?>
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-yellow">Add Car</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
      </div>
    </form>
  </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/plugins/jquery/jquery.min.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

</body>
</html>
