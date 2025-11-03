<?php
// ===============================================
// FILE: db_connect.php
// PURPOSE: Handles the database connection
// ===============================================

define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', ''); 
define('DB_NAME', 'college_db'); // <<-- MUST MATCH YOUR DATABASE NAME

// Attempt to connect to MySQL database
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if ($conn->connect_error) {
    // If the connection fails, stop and show the error.
    die("CRITICAL ERROR: Connection failed: " . $conn->connect_error);
}

// Set character set
$conn->set_charset("utf8");
?>