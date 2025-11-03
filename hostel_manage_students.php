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
    <title>Manage Students - Admin</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>👥 Manage Students</h1>
            <p>Allocate and manage student rooms</p>
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
                <h2>Student List</h2>
                
                <div style="margin-bottom: 20px;">
                    <input type="text" id="searchQuery" placeholder="Search by name or roll number..." 
                           onkeyup="searchStudents()" style="width: 300px;">
                </div>
                
                <div id="searchResults"></div>
                
                <?php
                $query = "SELECT s.*, r.capacity, r.occupied 
                         FROM students s 
                         LEFT JOIN rooms r ON s.room_no = r.room_no 
                         ORDER BY s.student_id DESC";
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
                            echo '<form method="POST" action="allocate_room.php" style="display:inline;" onsubmit="return allocateRoomForm(event, ' . $row['student_id'] . ')">';
                            echo '<select name="room_no" required>';
                            echo '<option value="">Select Room</option>';
                            
                            // Get available rooms
                            $rooms_query = "SELECT * FROM rooms WHERE status = 'Available' OR occupied < capacity ORDER BY room_no";
                            $rooms_result = mysqli_query($conn, $rooms_query);
                            while($room = mysqli_fetch_assoc($rooms_result)) {
                                echo '<option value="' . $room['room_no'] . '">' . $room['room_no'] . ' (' . $room['occupied'] . '/' . $room['capacity'] . ')</option>';
                            }
                            
                            echo '</select>';
                            echo '<button type="button" onclick="allocateRoomPrompt(' . $row['student_id'] . ')" class="btn btn-success">Allocate</button>';
                            echo '</form>';
                        }
                        echo '</td>';
                        echo '</tr>';
                    }
                    echo '</table>';
                } else {
                    echo '<div class="message message-info">No students found.</div>';
                }
                ?>
            </div>
        </div>
    </div>

    <script src="assets/js/script.js"></script>
    <script>
    function allocateRoomPrompt(studentId) {
        const roomNo = prompt('Enter Room Number:');
        if (roomNo) {
            allocateRoom(studentId, roomNo);
        }
    }
    </script>
</body>
</html>