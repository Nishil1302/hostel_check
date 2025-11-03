<?php
session_start();
session_destroy();
header("Location: hostel_login.php");
exit;
?>
