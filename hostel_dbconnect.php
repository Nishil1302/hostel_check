<?php
// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'hostel_db';

// Create connection
$conn = mysqli_connect($host, $username, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Set charset to utf8
mysqli_set_charset($conn, "utf8");

// Function to sanitize input
function sanitize_input($data) {
    global $conn;
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    $data = mysqli_real_escape_string($conn, $data);
    return $data;
}

// Function to execute query and return result
function execute_query($query) {
    global $conn;
    return mysqli_query($conn, $query);
}

// Function to fetch all results
function fetch_all($query) {
    $result = execute_query($query);
    if ($result) {
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    return [];
}

// Function to fetch single result
function fetch_one($query) {
    $result = execute_query($query);
    if ($result && mysqli_num_rows($result) > 0) {
        return mysqli_fetch_assoc($result);
    }
    return null;
}
?>