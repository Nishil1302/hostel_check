<?php
header('Content-Type: application/json');
include 'includes/dbconnect.php';

$response = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $comp_id = sanitize_input($_POST['comp_id']);
    $status = sanitize_input($_POST['status']);
    
    $update_query = "UPDATE complaints SET status = '$status' WHERE comp_id = '$comp_id'";
    
    if (mysqli_query($conn, $update_query)) {
        $response['success'] = true;
        $response['message'] = 'Complaint status updated successfully';
    } else {
        $response['success'] = false;
        $response['message'] = 'Failed to update complaint status';
    }
} else {
    $response['success'] = false;
    $response['message'] = 'Invalid request method';
}

echo json_encode($response);
mysqli_close($conn);
?>