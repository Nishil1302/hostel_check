// Check Room Availability using AJAX
function checkAvailability() {
    fetch('fetch_rooms.php')
        .then(response => response.json())
        .then(data => {
            let list = document.getElementById("roomList");
            if (!list) return;
            
            if (data.success) {
                list.innerHTML = "<h3>Available Rooms</h3>";
                data.rooms.forEach(room => {
                    let statusClass = room.status === 'Available' ? 'room-available' : 'room-full';
                    let badgeClass = room.status === 'Available' ? 'status-available' : 'status-full';
                    
                    list.innerHTML += `
                        <div class="room-item ${statusClass}">
                            <div>
                                <strong>Room ${room.room_no}</strong><br>
                                <small>Capacity: ${room.capacity} | Occupied: ${room.occupied}</small>
                            </div>
                            <span class="status-badge ${badgeClass}">${room.status}</span>
                        </div>
                    `;
                });
            } else {
                list.innerHTML = `<div class="message message-error">${data.message}</div>`;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            let list = document.getElementById("roomList");
            if (list) {
                list.innerHTML = '<div class="message message-error">Failed to fetch room data</div>';
            }
        });
}

// Admin Login
if (document.getElementById('adminLoginForm')) {
    document.getElementById('adminLoginForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const username = document.getElementById('username').value;
        const password = document.getElementById('password').value;
        
        const formData = new FormData();
        formData.append('username', username);
        formData.append('password', password);
        
        fetch('admin_login.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            const msgDiv = document.getElementById('loginMessage');
            if (data.success) {
                msgDiv.innerHTML = '<div class="message message-success">Login successful! Redirecting...</div>';
                setTimeout(() => {
                    window.location.href = 'admin_dashboard.php';
                }, 1500);
            } else {
                msgDiv.innerHTML = `<div class="message message-error">${data.message}</div>`;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('loginMessage').innerHTML = 
                '<div class="message message-error">Login failed. Please try again.</div>';
        });
    });
}

// Student Registration
if (document.getElementById('registerForm')) {
    document.getElementById('registerForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        fetch('register_student.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            const msgDiv = document.getElementById('registerMessage');
            if (data.success) {
                msgDiv.innerHTML = `<div class="message message-success">${data.message}</div>`;
                this.reset();
            } else {
                msgDiv.innerHTML = `<div class="message message-error">${data.message}</div>`;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('registerMessage').innerHTML = 
                '<div class="message message-error">Registration failed. Please try again.</div>';
        });
    });
}

// Submit Complaint
if (document.getElementById('complaintForm')) {
    document.getElementById('complaintForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        fetch('submit_complaint.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            const msgDiv = document.getElementById('complaintMessage');
            if (data.success) {
                msgDiv.innerHTML = `<div class="message message-success">${data.message}</div>`;
                this.reset();
            } else {
                msgDiv.innerHTML = `<div class="message message-error">${data.message}</div>`;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('complaintMessage').innerHTML = 
                '<div class="message message-error">Failed to submit complaint.</div>';
        });
    });
}

// Allocate Room (Admin)
function allocateRoom(studentId, roomNo) {
    if (!confirm('Are you sure you want to allocate this room?')) return;
    
    const formData = new FormData();
    formData.append('student_id', studentId);
    formData.append('room_no', roomNo);
    
    fetch('allocate_room.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to allocate room');
    });
}

// Deallocate Room (Admin)
function deallocateRoom(studentId) {
    if (!confirm('Are you sure you want to deallocate this room?')) return;
    
    const formData = new FormData();
    formData.append('student_id', studentId);
    
    fetch('deallocate_room.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to deallocate room');
    });
}

// Update Complaint Status (Admin)
function updateComplaintStatus(compId, status) {
    const formData = new FormData();
    formData.append('comp_id', compId);
    formData.append('status', status);
    
    fetch('update_complaint.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to update complaint');
    });
}

// Search functionality
function searchStudents() {
    const query = document.getElementById('searchQuery').value;
    
    fetch(`search_students.php?q=${encodeURIComponent(query)}`)
        .then(response => response.json())
        .then(data => {
            displaySearchResults(data);
        })
        .catch(error => console.error('Error:', error));
}

function displaySearchResults(data) {
    const resultsDiv = document.getElementById('searchResults');
    if (!resultsDiv) return;
    
    if (data.success && data.students.length > 0) {
        let html = '<h3>Search Results</h3>';
        data.students.forEach(student => {
            html += `
                <div class="student-item">
                    <div>
                        <strong>${student.name}</strong> (${student.roll_no})<br>
                        <small>${student.department} - Year ${student.year}</small><br>
                        <small>Room: ${student.room_no || 'Not Allocated'}</small>
                    </div>
                </div>
            `;
        });
        resultsDiv.innerHTML = html;
    } else {
        resultsDiv.innerHTML = '<div class="message message-info">No students found</div>';
    }
}

// Export Data
function exportData(format) {
    window.location.href = `export_${format}.php`;
}

// Load dashboard stats
function loadDashboardStats() {
    fetch('get_stats.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('totalRooms').textContent = data.stats.total_rooms;
                document.getElementById('occupiedRooms').textContent = data.stats.occupied_rooms;
                document.getElementById('availableRooms').textContent = data.stats.available_rooms;
                document.getElementById('totalStudents').textContent = data.stats.total_students;
            }
        })
        .catch(error => console.error('Error:', error));
}

// Initialize dashboard if on admin page
if (document.getElementById('totalRooms')) {
    loadDashboardStats();
}