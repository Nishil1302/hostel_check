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
    <title>Admin Dashboard - Hostel System</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>🏢 Admin Dashboard</h1>
            <p>Welcome, <?php echo $_SESSION['admin_username']; ?></p>
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

        <div class="dashboard-stats">
            <div class="stat-card">
                <h3 id="totalRooms">0</h3>
                <p>Total Rooms</p>
            </div>
            <div class="stat-card">
                <h3 id="occupiedRooms">0</h3>
                <p>Occupied Rooms</p>
            </div>
            <div class="stat-card">
                <h3 id="availableRooms">0</h3>
                <p>Available Rooms</p>
            </div>
            <div class="stat-card">
                <h3 id="totalStudents">0</h3>
                <p>Total Students</p>
            </div>
        </div>

        <div class="main-content">
            <div class="card full-width">
                <h2>Quick Actions</h2>
                <button onclick="location.href='manage_students.php'" class="btn btn-primary">Allocate Rooms</button>
                <button onclick="location.href='view_complaints.php'" class="btn btn-info">View Complaints</button>
                <button onclick="exportData('json')" class="btn btn-success">Export JSON</button>
                <button onclick="exportData('xml')" class="btn btn-success">Export XML</button>
            </div>

            <div class="card full-width">
                <h2>Recent Students</h2>
                <?php
                $query = "SELECT s.*, r.status as room_status FROM students s 
                         LEFT JOIN rooms r ON s.room_no = r.room_no 
                         ORDER BY s.student_id DESC LIMIT 10";
                $result = mysqli_query($conn, $query);
                
                if (mysqli_num_rows($result) > 0) {
                    echo '<table>';
                    echo '<tr><th>ID</th><th>Name</th><th>Roll No</th><th>Department</th><th>Year</th><th>Room</th><th>Action</th></tr>';
                    while($row = mysqli_fetch_assoc($result)) {
                        echo '<tr>';
                        echo '<td>' . $row['student_id'] . '</td>';
                        echo '<td>' . $row['name'] . '</td>';
                        echo '<td>' . $row['roll_no'] . '</td>';
                        echo '<td>' . $row['department'] . '</td>';
                        echo '<td>' . $row['year'] . '</td>';
                        echo '<td>' . ($row['room_no'] ? $row['room_no'] : 'Not Allocated') . '</td>';
                        echo '<td>';
                        if ($row['room_no']) {
                            echo '<button onclick="deallocateRoom(' . $row['student_id'] . ')" class="btn btn-danger">Deallocate</button>';
                        } else {
                            echo '<button onclick="location.href=\'allocate.php?id=' . $row['student_id'] . '\'" class="btn btn-success">Allocate</button>';
                        }
                        echo '</td>';
                        echo '</tr>';
                    }
                    echo '</table>';
                } else {
                    echo '<p>No students registered yet.</p>';
                }
                ?>
            </div>
        </div>
    </div>

    <script src="assets/js/script.js"></script>
</body>
</html>