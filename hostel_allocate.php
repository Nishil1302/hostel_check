<?php
header('Content-Type: application/json');
// CORRECTION: Path changed from 'includes/dbconnect.php'
include 'hostel_dbconnect.php';

$response = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_id = sanitize_input($_POST['student_id']);
    $room_no = sanitize_input($_POST['room_no']);
    
    // Check if room is available
    $room_query = "SELECT * FROM rooms WHERE room_no = '$room_no'";
    $room_result = mysqli_query($conn, $room_query);
    $room = mysqli_fetch_assoc($room_result);
    
    if (!$room) {
        $response['success'] = false;
        $response['message'] = 'Room not found';
    } elseif ($room['occupied'] >= $room['capacity']) {
        $response['success'] = false;
        $response['message'] = 'Room is full';
    } else {
        // Check if student already has a room
        $student_query = "SELECT * FROM students WHERE student_id = '$student_id'";
        $student_result = mysqli_query($conn, $student_query);
        $student = mysqli_fetch_assoc($student_result);
        
        if ($student['room_no']) {
            $response['success'] = false;
            $response['message'] = 'Student already has a room allocated';
        } else {
            // Allocate room
            $update_student = "UPDATE students SET room_no = '$room_no' WHERE student_id = '$student_id'";
            $update_room = "UPDATE rooms SET occupied = occupied + 1 WHERE room_no = '$room_no'";
            
            if (mysqli_query($conn, $update_student) && mysqli_query($conn, $update_room)) {
                // Update room status if full
                $new_occupied = $room['occupied'] + 1;
                if ($new_occupied >= $room['capacity']) {
                    mysqli_query($conn, "UPDATE rooms SET status = 'Full' WHERE room_no = '$room_no'");
                }
                
                $response['success'] = true;
                $response['message'] = 'Room allocated successfully';
            } else {
                $response['success'] = false;
                $response['message'] = 'Failed to allocate room';
            }
        }
    }
} else {
    $response['success'] = false;
    $response['message'] = 'Invalid request method';
}

echo json_encode($response);
mysqli_close($conn);
?>