<?php
session_start();
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $roll = trim($_POST['roll_no']);
    $password = trim($_POST['password']);

    $sql = "SELECT * FROM students WHERE roll_no = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $roll);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if (password_verify($password, $row['password'])) {
            $_SESSION['student_name'] = $row['name'];
            $_SESSION['student_roll'] = $row['roll_no'];
            header("Location: student_dashboard.php");
            exit;
        } else {
            echo "<script>alert('Invalid password'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('No student found with this Roll No'); window.history.back();</script>";
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: hostel_login.php");
    exit;
}
?>
