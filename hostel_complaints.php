<?php
header('Content-Type: application/json');
// CORRECTION: Path changed from 'includes/dbconnect.php'
include 'hostel_dbconnect.php';

$response = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_id = sanitize_input($_POST['student_id']);
    $complaint_text = sanitize_input($_POST['complaint_text']);
    $date = date('Y-m-d');
    
    // Check if student exists
    $check_query = "SELECT * FROM students WHERE student_id = '$student_id'";
    $check_result = mysqli_query($conn, $check_query);
    
    if (mysqli_num_rows($check_result) == 0) {
        $response['success'] = false;
        $response['message'] = 'Student ID not found';
    } else {
        // Insert complaint
        $insert_query = "INSERT INTO complaints (student_id, complaint_text, date, status) 
                        VALUES ('$student_id', '$complaint_text', '$date', 'Pending')";
        
        if (mysqli_query($conn, $insert_query)) {
            $response['success'] = true;
            $response['message'] = 'Complaint submitted successfully. Complaint ID: ' . mysqli_insert_id($conn);
        } else {
            $response['success'] = false;
            $response['message'] = 'Failed to submit complaint';
        }
    }
} else {
    $response['success'] = false;
    $response['message'] = 'Invalid request method';
}

echo json_encode($response);
mysqli_close($conn);
?>