<?php
header('Content-Type: application/json');
include 'includes/dbconnect.php';

$response = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = sanitize_input($_POST['name']);
    $roll_no = sanitize_input($_POST['roll_no']);
    $email = sanitize_input($_POST['email']);
    $department = sanitize_input($_POST['department']);
    $year = sanitize_input($_POST['year']);
    
    // Check if roll number already exists
    $check_query = "SELECT * FROM students WHERE roll_no = '$roll_no'";
    $check_result = mysqli_query($conn, $check_query);
    
    if (mysqli_num_rows($check_result) > 0) {
        $response['success'] = false;
        $response['message'] = 'Roll number already registered!';
    } else {
        // Insert new student
        $insert_query = "INSERT INTO students (name, roll_no, email, department, year) 
                        VALUES ('$name', '$roll_no', '$email', '$department', '$year')";
        
        if (mysqli_query($conn, $insert_query)) {
            $response['success'] = true;
            $response['message'] = 'Registration successful! Your Student ID is: ' . mysqli_insert_id($conn);
        } else {
            $response['success'] = false;
            $response['message'] = 'Registration failed: ' . mysqli_error($conn);
        }
    }
} else {
    $response['success'] = false;
    $response['message'] = 'Invalid request method';
}

echo json_encode($response);
mysqli_close($conn);
?>