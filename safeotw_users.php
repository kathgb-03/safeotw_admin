<?php
include('db_connection.php');

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

    header("Location: safeotw_users.php");
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
    $client_id = intval($_POST['client_id']);
    $status_id = intval($_POST['user_status_id']);

    $stmt = $conn->prepare("UPDATE users SET user_employee_no = ?, user_first_name = ?, user_middle_name = ?, user_last_name = ?, user_role_id = ?, client_id = ?, user_status_id = ? WHERE user_id = ?");
    $stmt->bind_param("ssssiiii", $employee_no, $first_name, $middle_name, $last_name, $role_id, $client_id, $status_id, $user_id);
    $stmt->execute();
    $stmt->close();

    header("Location: safeotw_users.php");
    exit;
}

// Delete User
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);

    $stmt = $conn->prepare("DELETE FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $stmt->close();

    header("Location: safeotw_users.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>SafeOTW Users</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <style>
    body {
      font-family: Segoe UI', Tahoma, Geneva, Verdana, sans-serif';
      margin: 0;
      padding: 0;
      background-image: url('bg1.png');
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
      color: rgb(255, 179, 0) !important;
    }

    .nav-sidebar .nav-link.active .nav-icon,
    .nav-sidebar .nav-link:hover .nav-icon {
      color: rgb(255, 179, 0) !important;
    }

    .content-wrapper {
      padding: 2rem;
      background-color:rgba(245, 246, 250, 0);
      height: 100vh;
    }

    .btn-primary {
      background-color: rgb(255, 179, 0) !important;
      border: none;
      padding: 0.4rem 0.8rem;
      font-size: 0.95rem;
      border-radius: 8px;
    }
    .btn-secondary {
      background-color: #6c757d !important;
      border: none;
      padding: 0.4rem 0.8rem;
      font-size: 0.95rem;
      border-radius: 8px;
    }

    .btn-danger {
      background-color: #6c757d !important;
      border-color: #6c757d !important;
    }
    .btn-yellow {
      background-color: #ffb300;
      color: white;
      border: none;
      padding: 0.5rem 1.1rem;
      border-radius: 6px;
      font-weight: 500;
    }

    .btn-yellow:hover {
      background-color: #ffe680;
      color: #000;
    }
    

    .table-card {
      background: #fff;
      border-radius: 16px;
      padding: 2rem;
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05);
    }

    table.table {
      font-size: 0.95rem;
    }

    table thead {
      background-color: #f1f3f5;
    }

    table thead th {
      font-weight: 700;
      color: #666;
    }

    table tbody tr:hover {
      background-color: #f8f9fc;
    }

    h1 {
      color: rgb(255, 179, 0);
    }

    .modal .form-control {
      border-radius: 8px;
      margin-bottom: 1rem;
    }
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
            <a href="dashboard.php" class="nav-link">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>Dashboard</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="safeotw_users.php" class="nav-link active">
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

  <div class="content-wrapper">
    <div class="d-flex justify-content-between mb-4 align-items-center">
      <h1 class="mb-0">User List</h1>
      <button class="btn btn-primary" data-toggle="modal" data-target="#addUserModal">
        <i class="fas fa-user-plus"></i> Add User
      </button>
    </div>

    <div class="card">
      <div class="card-body">
        <table class="table table-bordered table-hover">
          <thead class="thead-dark">
              <th>ID</th><th>Employee No</th><th>Password</th><th>Last</th><th>First</th><th>Middle</th><th>Role</th><th>Company</th><th>Status</th><th>Actions</th>
          </thead>
          <tbody>
            <?php
              $query = "
                SELECT u.user_id, u.user_employee_no, u.user_last_name,
                  u.user_first_name, u.user_middle_name, r.role_name, c.client_name,
                  COALESCE(s.user_student_status_name, 'Inactive') AS status_name,
                  u.user_role_id, u.client_id, u.user_status_id
                FROM users u
                LEFT JOIN user_role r ON u.user_role_id = r.user_role_id
                LEFT JOIN client c ON u.client_id = c.client_id
                LEFT JOIN user_student_status s ON u.user_status_id = s.user_student_status_id
                ORDER BY u.user_id ASC
              ";
              $result = $conn->query($query);
              while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>{$row['user_id']}</td>";
                echo "<td>" . htmlspecialchars($row['user_employee_no']) . "</td>";
                echo "<td>••••••••••</td>";
                echo "<td>" . htmlspecialchars($row['user_last_name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['user_first_name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['user_middle_name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['role_name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['client_name']) . "</td>";
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
                  <a href='safeotw_users.php?delete_id={$row['user_id']}' class='btn btn-sm btn-danger' onclick=\"return confirm('Delete this user?');\">
                    <i class='fas fa-trash'></i>
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
          <?php $roles = $conn->query("SELECT user_role_id, role_name FROM user_role"); while ($r = $roles->fetch_assoc()) echo "<option value='{$r['user_role_id']}'>{$r['role_name']}</option>"; ?>
        </select>
        <select name="client_id" class="form-control" required>
          <option value="">-- Select Company --</option>
          <?php $clients = $conn->query("SELECT client_id, client_name FROM client"); while ($c = $clients->fetch_assoc()) echo "<option value='{$c['client_id']}'>{$c['client_name']}</option>"; ?>
        </select>
      </div>
      <div class="modal-footer"><button type="submit" class="btn btn-yellow">Save</button><button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button></div>
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
        <input type="text" name="first_name" id="edit_first_name" class="form-control" placeholder="First Name" required>
        <input type="text" name="middle_name" id="edit_middle_name" class="form-control" placeholder="Middle Name">
        <input type="text" name="last_name" id="edit_last_name" class="form-control" placeholder="Last Name" required>
        <select name="role_id" id="edit_role_id" class="form-control" required>
          <option value="">-- Select Role --</option>
          <?php $roles = $conn->query("SELECT user_role_id, role_name FROM user_role"); while ($r = $roles->fetch_assoc()) echo "<option value='{$r['user_role_id']}'>{$r['role_name']}</option>"; ?>
        </select>
        <select name="client_id" id="edit_client_id" class="form-control" required>
          <option value="">-- Select Company --</option>
          <?php $clients = $conn->query("SELECT client_id, client_name FROM client"); while ($c = $clients->fetch_assoc()) echo "<option value='{$c['client_id']}'>{$c['client_name']}</option>"; ?>
        </select>
        <select name="user_status_id" id="edit_status_id" class="form-control">
          <option value="">-- Select Status --</option>
          <?php $statuses = $conn->query("SELECT user_student_status_id, user_student_status_name FROM user_student_status"); while ($s = $statuses->fetch_assoc()) echo "<option value='{$s['user_student_status_id']}'>{$s['user_student_status_name']}</option>"; ?>
        </select>
      </div>
      <div class="modal-footer"><button type="submit" class="btn btn-yellow">Update</button><button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button></div>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
$('.edit-btn').on('click', function(){
  $('#edit_user_id').val($(this).data('id'));
  $('#edit_employee_no').val($(this).data('employee'));
  $('#edit_first_name').val($(this).data('first'));
  $('#edit_middle_name').val($(this).data('middle'));
  $('#edit_last_name').val($(this).data('last'));
  $('#edit_role_id').val($(this).data('role'));
  $('#edit_client_id').val($(this).data('client'));
  $('#edit_status_id').val($(this).data('status'));
  $('#editUserModal').modal('show');
});
</script>
</body>
</html>
