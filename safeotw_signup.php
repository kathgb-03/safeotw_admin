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
    $client_id = trim($_POST['client_id']);

    $stmt = $conn->prepare("INSERT INTO users (user_employee_no, user_password, user_first_name, user_middle_name, user_last_name, user_contact_no, user_role_id, client_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssii", $employee_no, $password, $first_name, $middle_name, $last_name, $contact_no, $role_id, $client_id);

    if ($stmt->execute()) {
        header("Location: safeotw_login.php");
        exit();
    } else {
        echo "<script>alert('Signup failed. Try again.'); window.history.back();</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Signup</title>
    <style>
        * {
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
        }
        body {
            background-color: #f4f6f8;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        .signup-container {
            background-color: #ffffff;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }
        h2 {
            margin-bottom: 1.5rem;
            text-align: center;
            color: #333;
        }
        form input[type="text"],
        form input[type="password"],
        form input[type="number"],
        form select {
            width: 100%;
            padding: 0.75rem;
            margin-bottom: 1rem;
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: #f9f9f9;
            transition: border-color 0.3s ease;
        }
        form input:focus,
        form select:focus {
            border-color: #4a90e2;
            outline: none;
        }
        label {
            font-size: 0.9rem;
            color: #555;
            display: block;
            margin-bottom: 0.25rem;
        }
        input[type="submit"],
        .login-redirect {
            width: 100%;
            padding: 0.75rem;
            border: none;
            border-radius: 8px;
            background-color: #4a90e2;
            color: white;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease;
            text-align: center;
            text-decoration: none;
            display: block;
            margin-top: 0.75rem;
        }
        input[type="submit"]:hover,
        .login-redirect:hover {
            background-color: #357ABD;
        }
        .login-link-container {
            text-align: center;
            margin-top: 1rem;
        }
        .login-link-container a {
            color: #4a90e2;
            text-decoration: none;
            font-size: 0.9rem;
        }
        .login-link-container a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="signup-container">
        <h2>Create Account</h2>
        <form method="post">
            <input type="text" name="employee_no" placeholder="Employee No" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="text" name="first_name" placeholder="First Name" required>
            <input type="text" name="middle_name" placeholder="Middle Name">
            <input type="text" name="last_name" placeholder="Last Name" required>
            <input type="text" name="contact_no" placeholder="Contact Number" required>

            <label for="role_id">Role:</label>
            <select name="role_id" id="role_id" required>
                <option value="">-- Select Role --</option>
                <option value="1">Super Admin</option>
                <option value="2">Admin</option>
                <option value="3">Instructor</option>
            </select>
            <label for="client_id">Company:</label>
            <select name="client_id" id="client_id" required>
                <option value="">-- Select Company--</option>
                <option value="1">N/A</option>
                <option value="2">JYBG</option>
                <option value="3">St. Peter Velle</option>
            </select>
            <input type="submit" value="Register">
        </form>
        <div class="login-link-container">
            <p>Already have an account? <a href="safeotw_login.php">Log in</a></p>
        </div>
    </div>
</body>
</html>
