<?php
include('db_connection.php');

// ADD Student
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_student'])) {
  $first_name = trim($_POST['first_name']);
  $middle_name = trim($_POST['middle_name']);
  $last_name = trim($_POST['last_name']);
  $contact_no = trim($_POST['contact_no']);
  $address = trim($_POST['address']);
  $gender = intval($_POST['gender']);
  $client_id = intval($_POST['client_id']);
  $age = intval($_POST['age']);

  $stmt = $conn->prepare("INSERT INTO student (student_first_name, student_middle_name, student_last_name, student_contact_no, student_address, student_gender_id, client_id, student_age) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
  $stmt->bind_param("ssssssii", $first_name, $middle_name, $last_name, $contact_no, $address, $gender, $client_id, $age);
  $stmt->execute();
  $stmt->close();

  header("Location: safeotw_admin_JYBG_students.php");
  exit;
}

// EDIT Student
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_student'])) {
  $id = intval($_POST['student_id']);
  $first_name = trim($_POST['first_name']);
  $middle_name = trim($_POST['middle_name']);
  $last_name = trim($_POST['last_name']);
  $contact_no = trim($_POST['contact_no']);
  $address = trim($_POST['address']);
  $gender = intval($_POST['gender']);
  $client_id = intval($_POST['client_id']);
  $age = intval($_POST['age']);

  $stmt = $conn->prepare("UPDATE student SET student_first_name=?, student_middle_name=?, student_last_name=?, student_contact_no=?, student_address=?, student_gender_id=?, client_id=?, student_age=? WHERE student_id=?");
  $stmt->bind_param("ssssssiii", $first_name, $middle_name, $last_name, $contact_no, $address, $gender, $client_id, $age, $id);
  $stmt->execute();
  $stmt->close();

  header("Location: safeotw_admin_JYBG_students.php");
  exit;
}

// DELETE Student
if (isset($_GET['delete_id'])) {
  $id = intval($_GET['delete_id']);
  $stmt = $conn->prepare("DELETE FROM student WHERE student_id = ?");
  $stmt->bind_param("i", $id);
  $stmt->execute();
  $stmt->close();

  header("Location: safeotw_admin_JYBG_students.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>JYBG Students</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

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
    <a href="safeotw_admin_JYBG_dashboard.php" class="brand-link text-center py-3">
      <span class="brand-text">JYBG Admin</span>
    </a>
    <div class="sidebar">
      <nav class="mt-3">
        <ul class="nav nav-pills nav-sidebar flex-column">
          <li class="nav-item"><a href="safeotw_admin_JYBG_dashboard.php" class="nav-link"><i class="nav-icon fas fa-tachometer-alt"></i><p>Dashboard</p></a></li>
          <li class="nav-item"><a href="safeotw_admin_JYBG_users.php" class="nav-link"><i class="nav-icon fas fa-users"></i><p>Users</p></a></li>
          <li class="nav-item"><a href="safeotw_admin_JYBG_students.php" class="nav-link active"><i class="nav-icon fas fa-user-graduate"></i><p>Students</p></a></li>
          <li class="nav-item"><a href="safeotw_admin_JYBG_traffic_signs.php" class="nav-link"><i class="nav-icon fas fa-traffic-light"></i><p>Traffic Signs</p></a></li>
          <li class="nav-item"><a href="safeotw_admin_JYBG_cars.php" class="nav-link"><i class="nav-icon fas fa-car"></i><p>Cars</p></a></li>
          <li class="nav-item"><a href="safeotw_admin_JYBG_schedule.php" class="nav-link"><i class="nav-icon fas fa-calendar-alt"></i><p>Schedule</p></a></li>
        </ul>
      </nav>
    </div>
  </aside>

  <!-- Content -->
  <div class="content-wrapper">
    <div class="content">
      <div class="container-fluid">
        <div class="d-flex justify-content-between mb-4 align-items-center">
          <h2 class="mb-0">Student List</h2>
          <button class="btn btn-primary" data-toggle="modal" data-target="#addStudentModal">
            <i class="fas fa-user-plus"></i> Add Student
          </button>
        </div>

        <div class="table-card">
          <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped">
              <thead>
                <tr>
                  <th>ID</th><th>Last Name</th><th>First Name</th><th>Middle Name</th>
                  <th>Contact</th><th>Address</th><th>Gender</th><th>Age</th><th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $query = "
                  SELECT 
                    s.student_id,
                    s.student_last_name,
                    s.student_first_name,
                    s.student_middle_name,
                    s.student_contact_no,
                    s.student_address,
                    g.gender_name AS gender,
                    s.student_age
                  FROM student s
                  LEFT JOIN gender g ON s.student_gender_id = g.gender_id
                  LEFT JOIN client c ON s.client_id = c.client_id 
                  WHERE c.client_id = 2
                ";
                $result = $conn->query($query);

                if ($result && $result->num_rows > 0) {
                  while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                      <td>{$row['student_id']}</td>
                      <td>".htmlspecialchars($row['student_last_name'])."</td>
                      <td>".htmlspecialchars($row['student_first_name'])."</td>
                      <td>".htmlspecialchars($row['student_middle_name'])."</td>
                      <td>".htmlspecialchars($row['student_contact_no'])."</td>
                      <td>".htmlspecialchars($row['student_address'])."</td>
                      <td>".htmlspecialchars($row['gender'])."</td>
                      <td>".htmlspecialchars($row['student_age'])."</td>
                      <td>
                        <button class='btn btn-sm btn-info' data-toggle='modal' data-target='#editModal{$row['student_id']}' title='Edit'>
                          <i class='fas fa-edit'></i>
                        </button>
                        <a href='?delete_id={$row['student_id']}' class='btn btn-sm btn-danger' onclick='return confirm(\"Delete this student?\")' title='Delete'>
                          <i class='fas fa-trash-alt'></i>
                        </a>
                      </td>
                    </tr>";

                    // Edit Modal
                    echo "
                    <div class='modal fade' id='editModal{$row['student_id']}' tabindex='-1'>
                      <div class='modal-dialog'>
                        <form method='post' class='modal-content'>
                          <div class='modal-header'><h5 class='modal-title'>Edit Student</h5>
                            <button type='button' class='close' data-dismiss='modal'>&times;</button>
                          </div>
                          <div class='modal-body'>
                            <input type='hidden' name='edit_student' value='1'>
                            <input type='hidden' name='student_id' value='{$row['student_id']}'>
                            <input type='hidden' name='client_id' value='2'>
                            <input type='text' name='first_name' class='form-control mb-2' value='".htmlspecialchars($row['student_first_name'])."' required>
                            <input type='text' name='middle_name' class='form-control mb-2' value='".htmlspecialchars($row['student_middle_name'])."'>
                            <input type='text' name='last_name' class='form-control mb-2' value='".htmlspecialchars($row['student_last_name'])."' required>
                            <input type='tel' name='contact_no' class='form-control mb-2' value='".htmlspecialchars($row['student_contact_no'])."' required>
                            <input type='text' name='address' class='form-control mb-2' value='".htmlspecialchars($row['student_address'])."' required>
                            <select name='gender' class='form-control mb-2' required>";
                              $genders = $conn->query("SELECT gender_id, gender_name FROM gender");
                              while ($g = $genders->fetch_assoc()) {
                                $selected = ($g['gender_name'] == $row['gender']) ? 'selected' : '';
                                echo "<option value='{$g['gender_id']}' $selected>{$g['gender_name']}</option>";
                              }
                            echo "</select>
                            <input type='number' name='age' class='form-control mb-2' value='".htmlspecialchars($row['student_age'])."' required>
                          </div>
                          <div class='modal-footer'>
                            <button type='submit' class='btn btn-success'>Update</button>
                            <button type='button' class='btn btn-secondary' data-dismiss='modal'>Cancel</button>
                          </div>
                        </form>
                      </div>
                    </div>";
                  }
                } else {
                  echo "<tr><td colspan='9'>No students found.</td></tr>";
                }
                ?>
              </tbody>
            </table>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>

<!-- Add Student Modal -->
<div class="modal fade" id="addStudentModal" tabindex="-1">
  <div class="modal-dialog">
    <form method="post" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add New Student</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="add_student" value="1">
        <input type="hidden" name="client_id" value="2">

        <input type="text" name="first_name" class="form-control mb-2" placeholder="First Name" required>
        <input type="text" name="middle_name" class="form-control mb-2" placeholder="Middle Name">
        <input type="text" name="last_name" class="form-control mb-2" placeholder="Last Name" required>
        <input type="tel" name="contact_no" class="form-control mb-2" placeholder="Contact Number" required>
        <input type="text" name="address" class="form-control mb-2" placeholder="Address" required>

        <select name="gender" class="form-control mb-2" required>
          <option value="">-- Select Gender --</option>
          <?php
          $genders = $conn->query("SELECT gender_id, gender_name FROM gender");
          while ($g = $genders->fetch_assoc()) {
            echo "<option value='{$g['gender_id']}'>{$g['gender_name']}</option>";
          }
          ?>
        </select>

        <input type="number" name="age" class="form-control mb-2" placeholder="Age" required>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-success">Save</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
      </div>
    </form>
  </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

</body>
</html>
