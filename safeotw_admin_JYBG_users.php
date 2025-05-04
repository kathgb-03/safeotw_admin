<?php include('db_connection.php');

// Add User
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_user'])) {
  $employee_no = trim($_POST['employee_no']);
  $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);
  $first_name = trim($_POST['first_name']);
  $middle_name = trim($_POST['middle_name']);
  $last_name = trim($_POST['last_name']);
  $contact_no = trim($_POST['contact_no']);
  $role_id = intval($_POST['role_id']);
  $client_id = intval($_POST['client_id']);

  $stmt = $conn->prepare("INSERT INTO users (user_employee_no, user_password, user_first_name, user_middle_name, user_last_name, user_contact_no, user_role_id, client_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
  $stmt->bind_param("ssssssii", $employee_no, $password, $first_name, $middle_name, $last_name, $contact_no, $role_id, $client_id);
  $stmt->execute();
  $stmt->close();

  header("Location: safeotw_admin_JYBG_users.php");
  exit;
}

// Edit User
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_user'])) {
  $user_id = intval($_POST['user_id']);
  $employee_no = trim($_POST['employee_no']);
  $first_name = trim($_POST['first_name']);
  $middle_name = trim($_POST['middle_name']);
  $last_name = trim($_POST['last_name']);
  $role_id = intval($_POST['role_id']);
  if ($role_id <= 1) {
      die("Invalid role selected. Role must be greater than 1.");
  }
  $client_id = 2;
  $status_id = intval($_POST['user_status_id']);
  
  $password = trim($_POST['password']);
  
  if (!empty($password)) {
      $hashed_password = password_hash($password, PASSWORD_DEFAULT);
      $stmt = $conn->prepare("UPDATE users SET user_employee_no = ?, user_first_name = ?, user_middle_name = ?, user_last_name = ?, user_role_id = ?, client_id = ?, user_status_id = ?, user_password = ? WHERE user_id = ?");
      $stmt->bind_param("ssssiiisi", $employee_no, $first_name, $middle_name, $last_name, $role_id, $client_id, $status_id, $hashed_password, $user_id);
  } else {
      $stmt = $conn->prepare("UPDATE users SET user_employee_no = ?, user_first_name = ?, user_middle_name = ?, user_last_name = ?, user_role_id = ?, client_id = ?, user_status_id = ? WHERE user_id = ?");
      $stmt->bind_param("ssssiiii", $employee_no, $first_name, $middle_name, $last_name, $role_id, $client_id, $status_id, $user_id);
  }
  
  $stmt->execute();
  $stmt->close();
  
  header("Location: safeotw_admin_JYBG_users.php");
  exit;
  
}

// Delete User
if (isset($_GET['delete_id'])) {
  $delete_id = intval($_GET['delete_id']);

  $stmt = $conn->prepare("DELETE FROM users WHERE user_id = ?");
  $stmt->bind_param("i", $delete_id);
  $stmt->execute();
  $stmt->close();

  header("Location: safeotw_admin_JYBG_users.php");
  exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>JYBG Users</title>

  <!-- AdminLTE & FontAwesome -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <style>
    body { font-family: 'Segoe UI', sans-serif; background-color: #f8f9fa; color: #333; }
    .content-wrapper { padding: 2rem; background: #f4f6f9; }
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
    
    h2 { font-weight: 600; color: #333; }
    .btn-primary { background-color:rgb(18, 160, 132); border: none; padding: 0.4rem 0.8rem; font-size: 0.95rem; border-radius: 8px; }
    .table-card { background: #fff; border-radius: 16px; padding: 2rem; box-shadow: 0 8px 24px rgba(0,0,0,0.05); }
    table.table { border: none; font-size: 0.95rem; }
    table.table thead { background-color: #f1f3f5; }
    table.table thead th { border-bottom: none; font-weight: 600; color: #666; padding: 0.75rem; }
    table.table tbody td { border-top: 1px solid #e9ecef; vertical-align: middle; padding: 0.75rem; }
    table.table tbody tr:hover { background-color: #f8f9fc; }
    .modal .form-control { margin-bottom: 1rem; border-radius: 8px; border: 1px solid #ced4da; }
    .modal-header, .modal-footer { border: none; }
    .btn-success { background-color: #28a745; border-radius: 6px; padding: 0.5rem 1.1rem; }
    .btn-secondary { border-radius: 6px; padding: 0.5rem 1.1rem; }
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
          <li class="nav-item">
            <a href="safeotw_admin_JYBG_dashboard.php" class="nav-link">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>Dashboard</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="safeotw_admin_JYBG_users.php" class="nav-link active">
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
  <div class="d-flex justify-content-between mb-4 align-items-center">
    <h2 class="mb-0">User List</h2>
    <button class="btn btn-primary" data-toggle="modal" data-target="#addUserModal"><i class="fas fa-user-plus"></i> Add User</button>
  </div>
  <div class="table-responsive">
    <table class="table table-hover table-sm">
      <thead>
        <tr>
          <th>ID</th><th>Employee No</th><th>Password</th><th>Last Name</th><th>First Name</th><th>Middle Name</th><th>Role</th><th>Company</th><th>Status</th><th>Actions</th>
        </tr>
      </thead>
      <tbody>
      <?php
        $query = "
                SELECT 
                u.user_id,
                u.user_employee_no,
                u.user_password,
                u.user_last_name,
                u.user_first_name,
                u.user_middle_name,
                r.role_name AS role_name,
                c.client_name,
                u.user_role_id,
                c.client_id,
                u.user_status_id,
                IFNULL(s.user_student_status_name, 'Inactive') as status_name
                FROM users u
                LEFT JOIN user_role r ON u.user_role_id = r.user_role_id
                LEFT JOIN client c ON u.client_id = c.client_id
                LEFT JOIN user_student_status s ON u.user_status_id = s.user_student_status_id
                WHERE c.client_id = 2 AND r.user_role_id > 1
              ";

              $result = $conn->query($query);

              if (!$result) {
                echo "<tr><td colspan='9'>Query error: " . $conn->error . "</td></tr>";
              } elseif ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                  echo "<tr>";
                  echo "<td>" . $row['user_id'] . "</td>";
                  echo "<td>" . htmlspecialchars($row['user_employee_no']) . "</td>";
                  echo "<td>••••••••••</td>";
                  echo "<td>" . htmlspecialchars($row['user_last_name']) . "</td>";
                  echo "<td>" . htmlspecialchars($row['user_first_name']) . "</td>";
                  echo "<td>" . htmlspecialchars($row['user_middle_name']) . "</td>";
                  echo "<td>" . htmlspecialchars($row['role_name'] ?? 'N/A') . "</td>";
                  echo "<td>" . htmlspecialchars($row['client_name'] ?? 'N/A') . "</td>";
                  echo "<td>" . htmlspecialchars($row['status_name']) . "</td>";
                  echo "<td>
                    <button class='btn btn-sm btn-primary edit-btn'
                      data-id='{$row['user_id']}'
                      data-employee='{$row['user_employee_no']}'
                      data-first='{$row['user_first_name']}'
                      data-middle='{$row['user_middle_name']}'
                      data-last='{$row['user_last_name']}'
                      data-role='{$row['user_role_id']}'
                      data-client='{$row['client_id']}'
                      data-status='{$row['user_status_id']}'
                    ><i class='fas fa-edit'></i></button>
                    <a href='safeotw_admin_JYBG_users.php?delete_id={$row['user_id']}' class='btn btn-sm btn-danger' onclick=\"return confirm('Delete this user?');\"><i class='fas fa-trash'></i></a>
                  </td>";
                  echo "</tr>";
                }
              } else {
                echo "<tr><td colspan='9'>No users found.</td></tr>";
              }
      ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1">
  <div class="modal-dialog">
    <form method="post" class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Add New User</h5><button type="button" class="close" data-dismiss="modal">&times;</button></div>
      <div class="modal-body">
        <input type="hidden" name="add_user" value="1">
        <input type="text" name="employee_no" class="form-control" placeholder="Employee No" required>
        <input type="password" name="password" class="form-control" placeholder="Password" required>
        <input type="text" name="first_name" class="form-control" placeholder="First Name" required>
        <input type="text" name="middle_name" class="form-control" placeholder="Middle Name">
        <input type="text" name="last_name" class="form-control" placeholder="Last Name" required>
        <input type="text" name="contact_no" class="form-control" placeholder="Contact Number" required>
        <select name="role_id" class="form-control" required>
          <option value="">-- Select Role --</option>
          <?php
          $roles = $conn->query("SELECT user_role_id, role_name FROM user_role WHERE user_role_id > 1");
          while ($r = $roles->fetch_assoc()) {
              echo "<option value='{$r['user_role_id']}'>{$r['role_name']}</option>";
          }
          ?>
        </select>
        <select name="client_id" class="form-control" required>
          <option value="">-- Select Company --</option>
          <?php
            $clients = $conn->query("SELECT client_id, client_name FROM client WHERE client_id = 2");
            while ($c = $clients->fetch_assoc()) {
                echo "<option value='{$c['client_id']}'>{$c['client_name']}</option>";
            }
          ?>
        </select>
      </div>
      <div class="modal-footer"><button type="submit" class="btn btn-success">Save</button><button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button></div>
    </form>
  </div>
</div>

<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1">
  <div class="modal-dialog">
    <form method="post" class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Edit User</h5><button type="button" class="close" data-dismiss="modal">&times;</button></div>
      <div class="modal-body">
        <input type="hidden" name="edit_user" value="1">
        <input type="hidden" name="user_id" id="edit_user_id">
        <input type="text" name="employee_no" id="edit_employee_no" class="form-control" placeholder="Employee No" required>
        <input type="password" name="password" id="edit_password" class="form-control" placeholder="New Password (leave blank to keep current)">

        <input type="text" name="first_name" id="edit_first_name" class="form-control" placeholder="First Name" required>
        <input type="text" name="middle_name" id="edit_middle_name" class="form-control" placeholder="Middle Name">
        <input type="text" name="last_name" id="edit_last_name" class="form-control" placeholder="Last Name" required>
        <select name="role_id" id="edit_role_id" class="form-control" required>
          <option value="">-- Select Role --</option>
          <?php
            $roles = $conn->query("SELECT user_role_id, role_name FROM user_role WHERE user_role_id > 1");
            while ($r = $roles->fetch_assoc()) {
              echo "<option value='{$r['user_role_id']}'>{$r['role_name']}</option>";
            }
          ?>
        </select>


        
        <input type="hidden" name="client_id" id="edit_client_id">
        <select name="user_status_id" id="edit_status_id" class="form-control">
          <option value="">-- Select Status --</option>
          <?php $statuses = $conn->query("SELECT user_student_status_id, user_student_status_name FROM user_student_status"); while ($s = $statuses->fetch_assoc()) echo "<option value='{$s['user_student_status_id']}'>{$s['user_student_status_name']}</option>"; ?>
        </select>
      </div>
      <div class="modal-footer"><button type="submit" class="btn btn-success">Update</button><button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button></div>
    </form>
  </div>
</div>

<!-- jQuery first -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>

<!-- Bootstrap Bundle (includes Popper) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- AdminLTE -->
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

<script>
$('.edit-btn').on('click', function(){
  $('#edit_user_id').val($(this).data('id'));
  $('#edit_employee_no').val($(this).data('employee'));
  $('#edit_first_name').val($(this).data('first'));
  $('#edit_middle_name').val($(this).data('middle'));
  $('#edit_last_name').val($(this).data('last'));
  $('#edit_role_id').val($(this).data('role')); // this sets the current role
  $('#edit_client_id').val($(this).data('client'));
  $('#edit_status_id').val($(this).data('status'));
  $('#editUserModal').modal('show');
});
  
</script>

</body>
</html>
