<?php
session_start();
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $employee_no = trim($_POST['employee_no']);
    $password = trim($_POST['password']);

    // Join users with client to get client_name
    $stmt = $conn->prepare("
        SELECT u.*, c.client_name 
        FROM users u
        LEFT JOIN client c ON u.client_id = c.client_id
        WHERE u.user_employee_no = ?
    ");
    $stmt->bind_param("s", $employee_no);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['user_password'])) {
            // Store session variables
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['user_role_id'] = $user['user_role_id'];
            $_SESSION['user_name'] = $user['user_first_name'];
            $_SESSION['client_name'] = $user['client_name'];

            // Redirect based on role or client
            $role_id = intval($user['user_role_id']); // adjust based on your DB roles
            $client_name = strtolower(trim($user['client_name']));

            if ($role_id === 1) { // Example: role_id 1 is super admin
                header("Location: dashboard.php");
            } elseif ($client_name === 'jybg') {
                header("Location: safeotw_admin_JYBG_dashboard.php");
            } elseif ($client_name === 'st. peter velle') {
                header("Location: spv_dashboard.php");
            } else {
                header("Location: dashboard.php"); // default
            }
            exit();
        }
    }

    // Failed login
    echo "<script>alert('Invalid employee number or password.'); window.location.href='safeotw_login.php';</script>";
}
?>
