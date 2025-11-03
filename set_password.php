<?php
include 'db_connect.php';
if ($_SERVER['REQUEST_METHOD']!=='POST') { http_response_code(405); exit; }
$roll = trim($_POST['roll_no'] ?? '');
$pwd  = $_POST['password'] ?? '';
if ($roll === '' || $pwd === '') { echo "MISSING"; exit; }
$hash = password_hash($pwd, PASSWORD_DEFAULT);
$stmt = $conn->prepare("UPDATE students SET password=? WHERE roll_no=?");
$stmt->bind_param("ss",$hash,$roll);
$stmt->execute();
if ($stmt->affected_rows) echo "OK"; else echo "NO";
$stmt->close();
$conn->close();
?>
