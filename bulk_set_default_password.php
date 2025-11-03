<?php
include 'db_connect.php';
$default = 'ChangeMe@123';
$hash = password_hash($default, PASSWORD_DEFAULT);
$stmt = $conn->prepare("UPDATE students SET password=?");
$stmt->bind_param("s",$hash);
$stmt->execute();
echo "Updated: ".$stmt->affected_rows;
$stmt->close();
$conn->close();
?>
