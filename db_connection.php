<?php

$host = 'localhost'; 
$username = 'root';  
$password = 'Keypass_2003';      
$database = 'safeotw_admin'; 


$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
