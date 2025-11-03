<?php
header('Content-Type: application/json');
include 'includes/dbconnect.php';

$response = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_id = sanitize_input($_POST['student_id']);
    
    // Get student's current room
    $student_query = "SELECT * FROM students WHERE student_id = '$student_id'";
    $student_result = mysqli_query($conn, $student_query);
    $student = mysqli_fetch_assoc($student_result);
    
    if (!$student['room_no']) {
        $response['success'] = false;
        $response['message'] = 'Student has no room allocated';
    } else {
        $room_no = $student['room_no'];
        
        // Deallocate room
        $update_student = "UPDATE students SET room_no = NULL WHERE student_id = '$student_id'";
        $update_room = "UPDATE rooms SET occupied = occupied - 1, status = 'Available' WHERE room_no = '$room_no'";
        
        if (mysqli_query($conn, $update_student) && mysqli_query($conn, $update_room)) {
            $response['success'] = true;
            $response['message'] = 'Room deallocated successfully';
        } else {
            $response['success'] = false;
            $response['message'] = 'Failed to deallocate room';
        }
    }
} else {
    $response['success'] = false;
    $response['message'] = 'Invalid request method';
}

echo json_encode($response);
mysqli_close($conn);
?>