<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: index.html');
    exit();
}
include 'includes/dbconnect.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Complaints - Admin</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>📋 Student Complaints</h1>
            <p>Manage and resolve student complaints</p>
        </header>

        <nav>
            <div>
                <a href="admin_dashboard.php">Dashboard</a>
                <a href="manage_rooms.php">Manage Rooms</a>
                <a href="manage_students.php">Manage Students</a>
                <a href="view_complaints.php">View Complaints</a>
            </div>
            <a href="logout.php" class="logout-btn">Logout</a>
        </nav>

        <div class="main-content">
            <div class="card full-width">
                <h2>All Complaints</h2>
                <?php
                $query = "SELECT c.*, s.name, s.roll_no, s.room_no 
                         FROM complaints c 
                         JOIN students s ON c.student_id = s.student_id 
                         ORDER BY c.date DESC";
                $result = mysqli_query($conn, $query);
                
                if (mysqli_num_rows($result) > 0) {
                    echo '<table>';
                    echo '<tr><th>ID</th><th>Student</th><th>Roll No</th><th>Room</th><th>Complaint</th><th>Date</th><th>Status</th><th>Action</th></tr>';
                    while($row = mysqli_fetch_assoc($result)) {
                        $statusClass = $row['status'] == 'Pending' ? 'status-pending' : 'status-resolved';
                        echo '<tr>';
                        echo '<td>' . $row['comp_id'] . '</td>';
                        echo '<td>' . $row['name'] . '</td>';
                        echo '<td>' . $row['roll_no'] . '</td>';
                        echo '<td>' . ($row['room_no'] ? $row['room_no'] : 'N/A') . '</td>';
                        echo '<td>' . substr($row['complaint_text'], 0, 50) . '...</td>';
                        echo '<td>' . $row['date'] . '</td>';
                        echo '<td><span class="status-badge ' . $statusClass . '">' . $row['status'] . '</span></td>';
                        echo '<td>';
                        if ($row['status'] == 'Pending') {
                            echo '<button onclick="updateComplaintStatus(' . $row['comp_id'] . ', \'Resolved\')" class="btn btn-success">Resolve</button>';
                        } else {
                            echo '<button onclick="updateComplaintStatus(' . $row['comp_id'] . ', \'Pending\')" class="btn btn-info">Reopen</button>';
                        }
                        echo '</td>';
                        echo '</tr>';
                    }
                    echo '</table>';
                } else {
                    echo '<div class="message message-info">No complaints found.</div>';
                }
                ?>
            </div>
        </div>
    </div>

    <script src="assets/js/script.js"></script>
</body>
</html>