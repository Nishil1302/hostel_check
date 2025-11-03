<?php
header('Content-Type: application/json');
include 'hostel_dbconnect.php'; // Corrected

$response = array();

// Total rooms
$total_rooms_query = "SELECT COUNT(*) as total FROM rooms";
$total_rooms_result = mysqli_query($conn, $total_rooms_query);
$total_rooms = mysqli_fetch_assoc($total_rooms_result)['total'];

// Occupied rooms
$occupied_query = "SELECT COUNT(*) as occupied FROM rooms WHERE occupied > 0";
$occupied_result = mysqli_query($conn, $occupied_query);
$occupied_rooms = mysqli_fetch_assoc($occupied_result)['occupied'];

// Available rooms
$available_query = "SELECT COUNT(*) as available FROM rooms WHERE status = 'Available'";
$available_result = mysqli_query($conn, $available_query);
$available_rooms = mysqli_fetch_assoc($available_result)['available'];

// Total students
$total_students_query = "SELECT COUNT(*) as total FROM students";
$total_students_result = mysqli_query($conn, $total_students_query);
$total_students = mysqli_fetch_assoc($total_students_result)['total'];

$response['success'] = true;
$response['stats'] = array(
    'total_rooms' => $total_rooms,
    'occupied_rooms' => $occupied_rooms,
    'available_rooms' => $available_rooms,
    'total_students' => $total_students
);

echo json_encode($response);
mysqli_close($conn);
?>