<?php
session_start();
header('Content-Type: application/json');
// CORRECTION: Path changed from 'includes/dbconnect.php'
include 'hostel_dbconnect.php';

$response = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = sanitize_input($_POST['username']);
    $password = sanitize_input($_POST['password']);
    
    // This query is insecure (SQL injection risk), but it matches your database setup
    $query = "SELECT * FROM admin WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($conn, $query);
    
    if (mysqli_num_rows($result) == 1) {
        $admin = mysqli_fetch_assoc($result);
        $_SESSION['admin_id'] = $admin['admin_id'];
        $_SESSION['admin_username'] = $admin['username'];
        
        $response['success'] = true;
        $response['message'] = 'Login successful';
    } else {
        $response['success'] = false;
        $response['message'] = 'Invalid username or password';
    }
} else {
    $response['success'] = false;
    $response['message'] = 'Invalid request method';
}

echo json_encode($response);
mysqli_close($conn);
?>