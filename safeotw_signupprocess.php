<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include 'db_connection.php';

    $employee_no = trim($_POST['employee_no']);
    $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);
    $first_name = trim($_POST['first_name']);
    $middle_name = trim($_POST['middle_name']);
    $last_name = trim($_POST['last_name']);
    $contact_no = trim($_POST['contact_no']);
    $role_id = trim($_POST['role_id']);

    $stmt = $conn->prepare("INSERT INTO users (user_employee_no, user_password, user_first_name, user_middle_name, user_last_name, user_contact_no, user_role_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssi", $employee_no, $password, $first_name, $middle_name, $last_name, $contact_no, $role_id);

    if ($stmt->execute()) {
        header("Location: safeotw_login.php");
        exit();
    } else {
        echo "<script>alert('Signup failed. Try again.'); window.history.back();</script>";
    }
}
?>
