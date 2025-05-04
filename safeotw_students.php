<?php
include('db_connection.php');

// Add Student
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_student'])) {
    $client_id = intval($_POST['client_id']);
    $last_name = trim($_POST['last_name']);
    $first_name = trim($_POST['first_name']);
    $middle_name = trim($_POST['middle_name']);
    $contact_no = trim($_POST['contact_no']);
    $address = trim($_POST['address']);
    $gender_id = intval($_POST['gender_id']);
    $age = intval($_POST['age']);

    $stmt = $conn->prepare("INSERT INTO student (client_id, student_last_name, student_first_name, student_middle_name, student_contact_no, student_address, student_gender_id, student_age) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssssii", $client_id, $last_name, $first_name, $middle_name, $contact_no, $address, $gender_id, $age);
    $stmt->execute();
    $stmt->close();

    header("Location: safeotw_students.php");
    exit;
}

// Edit Student
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_student'])) {
    $student_id = intval($_POST['student_id']);
    $client_id = intval($_POST['client_id']);
    $last_name = trim($_POST['last_name']);
    $first_name = trim($_POST['first_name']);
    $middle_name = trim($_POST['middle_name']);
    $contact_no = trim($_POST['contact_no']);
    $address = trim($_POST['address']);
    $gender_id = intval($_POST['gender_id']);
    $age = intval($_POST['age']);

    $stmt = $conn->prepare("UPDATE student SET client_id=?, student_last_name=?, student_first_name=?, student_middle_name=?, student_contact_no=?, student_address=?, student_gender_id=?, student_age=? WHERE student_id=?");
    $stmt->bind_param("isssssiii", $client_id, $last_name, $first_name, $middle_name, $contact_no, $address, $gender_id, $age, $student_id);
    $stmt->execute();
    $stmt->close();

    header("Location: safeotw_students.php");
    exit;
}

// Delete Student
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $stmt = $conn->prepare("DELETE FROM student WHERE student_id = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $stmt->close();

    header("Location: safeotw_students.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>SafeOTW Students</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    body {       font-family: Segoe UI', Tahoma, Geneva, Verdana, sans-serif';
      margin: 0;
      padding: 0;
      background-image: url('bg1.png');
      background-size: cover;
      background-position: center;
      background-attachment: fixed;
    }
    .brand-link { font-weight: 600; font-size: 1.0rem; color: rgba(255, 196, 0, 0.59); }
    .main-sidebar { background-color: #fff; border-right: 1px solid #ddd; min-height: 100vh; }
    .nav-sidebar .nav-link { color: rgba(0, 0, 0, 0.6); font-weight: 500; transition: all 0.2s ease-in-out; }
    .nav-sidebar .nav-link.active, .nav-sidebar .nav-link:hover { background-color: #fff7cc !important; color: rgb(255, 179, 0) !important; }
    .nav-sidebar .nav-link.active .nav-icon, .nav-sidebar .nav-link:hover .nav-icon { color: rgb(255, 179, 0) !important; }
    .content-wrapper { background: transparent; padding: 2rem; height: 100vh; }
    h1 { color: rgb(255, 179, 0); }
    h2 { font-weight: 600; }
    .btn-primary { background-color: rgb(255, 179, 0) !important; border: none; border-radius: 8px; }
    .btn-yellow { background-color: #ffb300; color: white; border: none; border-radius: 8px;}
    .btn-yellow:hover { background-color: #ffe066; color: black; }
    .table-card { background: #fff; border-radius: 16px; padding: 2rem; box-shadow: 0 8px 24px rgba(0,0,0,0.05); }
    table.table thead { background-color: #f1f3f5; }
    table thead th { font-weight: 700; color: #666; }
    table.table tbody tr:hover { background-color: #f8f9fc; }
    .modal .form-control { border-radius: 8px; margin-bottom: 1rem; }
  </style>
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  <aside class="main-sidebar elevation-1">
    <a href="#" class="brand-link text-center py-3">
      <span class="brand-text">SafeOTW Super Admin</span>
    </a>
    <div class="sidebar">
      <nav class="mt-3">
        <ul class="nav nav-pills nav-sidebar flex-column">
          <li class="nav-item"><a href="dashboard.php" class="nav-link"><i class="nav-icon fas fa-tachometer-alt"></i><p>Dashboard</p></a></li>
          <li class="nav-item"><a href="safeotw_users.php" class="nav-link"><i class="nav-icon fas fa-users"></i><p>Users</p></a></li>
          <li class="nav-item"><a href="safeotw_students.php" class="nav-link active"><i class="nav-icon fas fa-user-graduate"></i><p>Students</p></a></li>
          <li class="nav-item">
            <a href="cars.php" class="nav-link">
              <i class="nav-icon fas fa-car"></i>
              <p>Cars</p>
            </a>
          </li>
          <li class="nav-item"><a href="traffic_signs.php" class="nav-link"><i class="nav-icon fas fa-traffic-light"></i><p>Traffic Signs</p></a></li>
        </ul>
      </nav>
    </div>
  </aside>
  <div class="content-wrapper">
    <div class="d-flex justify-content-between mb-4 align-items-center">
      <h1 class="mb-0">Student List</h1>
      <button class="btn btn-primary" data-toggle="modal" data-target="#addStudentModal">+ Add Student</button>
    </div>
    <div class="card">
      <div class="card-body">   
      <table class="table table-bordered table-hover">
        <thead class="thead-dark"><th>ID</th><th>Last</th><th>First</th><th>Middle</th><th>Contact</th><th>Address</th><th>Gender</th><th>Age</th><th>Company</th><th>Actions</th></tr></thead>
        <tbody>
          <?php
          $query = "SELECT s.*, c.client_name, g.gender_name FROM student s LEFT JOIN client c ON s.client_id = c.client_id LEFT JOIN gender g ON s.student_gender_id = g.gender_id ORDER BY s.student_id ASC";
          $result = $conn->query($query);
          while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>{$row['student_id']}</td>";
            echo "<td>" . htmlspecialchars($row['student_last_name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['student_first_name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['student_middle_name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['student_contact_no']) . "</td>";
            echo "<td>" . htmlspecialchars($row['student_address']) . "</td>";
            echo "<td>" . htmlspecialchars($row['gender_name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['student_age']) . "</td>";
            echo "<td>" . htmlspecialchars($row['client_name']) . "</td>";
            echo "<td>
            <button class='btn btn-sm btn-warning text-white edit-btn' title='Edit'
              data-id='{$row['student_id']}'
              data-last='" . htmlspecialchars($row['student_last_name']) . "'
              data-first='" . htmlspecialchars($row['student_first_name']) . "'
              data-middle='" . htmlspecialchars($row['student_middle_name']) . "'
              data-contact='" . htmlspecialchars($row['student_contact_no']) . "'
              data-address='" . htmlspecialchars($row['student_address']) . "'
              data-gender='{$row['student_gender_id']}'
              data-age='{$row['student_age']}'
              data-client='{$row['client_id']}'>
              <i class='fas fa-edit'></i>
            </button>
            <a href='safeotw_students.php?delete_id={$row['student_id']}' class='btn btn-sm btn-secondary' title='Delete' onclick=\"return confirm('Delete this student?');\">
              <i class='fas fa-trash-alt'></i>
            </a>
          </td>";
        
            echo "</tr>";
        }        
          ?>
        </tbody>
      </table>
      </div>
    </div>
</div>
<div class="modal fade" id="addStudentModal" tabindex="-1">
  <div class="modal-dialog">
    <form method="post" class="modal-content">
      <div class="modal-header"><h5>Add Student</h5><button type="button" class="close" data-dismiss="modal">&times;</button></div>
      <div class="modal-body">
        <input type="hidden" name="add_student" value="1">
        <input type="text" name="last_name" class="form-control" placeholder="Last Name" required>
        <input type="text" name="first_name" class="form-control" placeholder="First Name" required>
        <input type="text" name="middle_name" class="form-control" placeholder="Middle Name">
        <input type="text" name="contact_no" class="form-control" placeholder="Contact No" required>
        <input type="text" name="address" class="form-control" placeholder="Address" required>
        <select name="gender_id" class="form-control" required>
          <option value="">-- Gender --</option>
          <?php $genders = $conn->query("SELECT gender_id, gender_name FROM gender"); while ($g = $genders->fetch_assoc()) echo "<option value='{$g['gender_id']}'>{$g['gender_name']}</option>"; ?>
        </select>
        <input type="number" name="age" class="form-control" placeholder="Age" required>
        <select name="client_id" class="form-control" required>
          <option value="">-- Company --</option>
          <?php $clients = $conn->query("SELECT client_id, client_name FROM client"); while ($c = $clients->fetch_assoc()) echo "<option value='{$c['client_id']}'>{$c['client_name']}</option>"; ?>
        </select>
      </div>
      <div class="modal-footer"><button type="submit" class="btn btn-yellow">Save</button></div>
    </form>
  </div>
</div>
<div class="modal fade" id="editStudentModal" tabindex="-1">
  <div class="modal-dialog">
    <form method="post" class="modal-content">
      <div class="modal-header"><h5>Edit Student</h5><button type="button" class="close" data-dismiss="modal">&times;</button></div>
      <div class="modal-body">
        <input type="hidden" name="edit_student" value="1">
        <input type="hidden" name="student_id" id="edit_student_id">
        <input type="text" name="last_name" id="edit_last_name" class="form-control" placeholder="Last Name" required>
        <input type="text" name="first_name" id="edit_first_name" class="form-control" placeholder="First Name" required>
        <input type="text" name="middle_name" id="edit_middle_name" class="form-control">
        <input type="text" name="contact_no" id="edit_contact_no" class="form-control" required>
        <input type="text" name="address" id="edit_address" class="form-control" required>
        <select name="gender_id" id="edit_gender_id" class="form-control" required>
          <option value="">-- Gender --</option>
          <?php $genders = $conn->query("SELECT gender_id, gender_name FROM gender"); while ($g = $genders->fetch_assoc()) echo "<option value='{$g['gender_id']}'>{$g['gender_name']}</option>"; ?>
        </select>
        <input type="number" name="age" id="edit_age" class="form-control" required>
        <select name="client_id" id="edit_client_id" class="form-control" required>
          <option value="">-- Company --</option>
          <?php $clients = $conn->query("SELECT client_id, client_name FROM client"); while ($c = $clients->fetch_assoc()) echo "<option value='{$c['client_id']}'>{$c['client_name']}</option>"; ?>
        </select>
      </div>
      <div class="modal-footer"><button type="submit" class="btn btn-yellow">Update</button></div>
    </form>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
<script>
$('.edit-btn').on('click', function(){
  $('#edit_student_id').val($(this).data('id'));
  $('#edit_last_name').val($(this).data('last'));
  $('#edit_first_name').val($(this).data('first'));
  $('#edit_middle_name').val($(this).data('middle'));
  $('#edit_contact_no').val($(this).data('contact'));
  $('#edit_address').val($(this).data('address'));
  $('#edit_gender_id').val($(this).data('gender'));
  $('#edit_age').val($(this).data('age'));
  $('#edit_client_id').val($(this).data('client'));
  $('#editStudentModal').modal('show');
});
</script>
</body>
</html>
