<?php
header('Content-Type: application/json');
include 'includes/dbconnect.php';

$query = "SELECT * FROM rooms ORDER BY room_no";
$result = mysqli_query($conn, $query);

$response = array();

if ($result) {
    $rooms = array();
    while($row = mysqli_fetch_assoc($result)) {
        $rooms[] = $row;
    }
    $response['success'] = true;
    $response['rooms'] = $rooms;
} else {
    $response['success'] = false;
    $response['message'] = 'Failed to fetch rooms';
}

echo json_encode($response);
mysqli_close($conn);
?>