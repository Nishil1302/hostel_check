<?php
include 'includes/dbconnect.php';

// Fetch all data
$students = fetch_all("SELECT * FROM students");
$rooms = fetch_all("SELECT * FROM rooms");
$complaints = fetch_all("SELECT * FROM complaints");

// Create data array
$data = array(
    'export_date' => date('Y-m-d H:i:s'),
    'students' => $students,
    'rooms' => $rooms,
    'complaints' => $complaints
);

// Convert to JSON
$json_data = json_encode($data, JSON_PRETTY_PRINT);

// Set headers for download
header('Content-Type: application/json');
header('Content-Disposition: attachment; filename="hostel_data_' . date('Y-m-d') . '.json"');
header('Content-Length: ' . strlen($json_data));

echo $json_data;

mysqli_close($conn);
?>