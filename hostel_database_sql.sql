-- This creates the database. You can skip this line if you've already created it.
CREATE DATABASE IF NOT EXISTS `hostel_db`;

-- Use the database
USE `hostel_db`;

-- ---
-- CORRECTED ORDER: Create 'rooms' table first
-- ---
-- Rooms Table
CREATE TABLE rooms (
	room_no VARCHAR(10) PRIMARY KEY,
	capacity INT NOT NULL DEFAULT 4,
	occupied INT NOT NULL DEFAULT 0,
	status VARCHAR(20) NOT NULL DEFAULT 'Available',
	floor INT,
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ---
-- 'students' table is created second, so the FOREIGN KEY will work
-- ---
-- Students Table
CREATE TABLE students (
	student_id INT AUTO_INCREMENT PRIMARY KEY,
	name VARCHAR(50) NOT NULL,
	roll_no VARCHAR(20) UNIQUE NOT NULL,
	email VARCHAR(100) NOT NULL,
	department VARCHAR(30) NOT NULL,
	year INT NOT NULL,
	room_no VARCHAR(10) DEFAULT NULL,
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	FOREIGN KEY (room_no) REFERENCES rooms(room_no) ON DELETE SET NULL
);

-- Complaints Table
CREATE TABLE complaints (
	comp_id INT AUTO_INCREMENT PRIMARY KEY,
	student_id INT NOT NULL,
	complaint_text TEXT NOT NULL,
	date DATE NOT NULL,
	status VARCHAR(20) NOT NULL DEFAULT 'Pending',
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	FOREIGN KEY (student_id) REFERENCES students(student_id) ON DELETE CASCADE
);

-- Admin Table
CREATE TABLE admin (
	admin_id INT AUTO_INCREMENT PRIMARY KEY,
	username VARCHAR(30) UNIQUE NOT NULL,
	password VARCHAR(255) NOT NULL,
	email VARCHAR(100),
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ---
-- Insert Sample Data
-- ---

-- Insert Sample Admin (username: admin, password: admin123)
INSERT INTO admin (username, password, email) VALUES 
('admin', 'admin123', 'admin@hostel.com');

-- Insert Sample Rooms
INSERT INTO rooms (room_no, capacity, occupied, status, floor) VALUES
('101', 4, 0, 'Available', 1),
('102', 4, 0, 'Available', 1),
('103', 4, 0, 'Available', 1),
('104', 4, 0, 'Available', 1),
('201', 4, 0, 'Available', 2),
('202', 4, 0, 'Available', 2),
('203', 4, 0, 'Available', 2),
('204', 4, 0, 'Available', 2),
('301', 4, 0, 'Available', 3),
('302', 4, 0, 'Available', 3),
('303', 4, 0, 'Available', 3),
('304', 4, 0, 'Available', 3),
('401', 2, 0, 'Available', 4),
('402', 2, 0, 'Available', 4),
('403', 2, 0, 'Available', 4),
('404', 2, 0, 'Available', 4);

-- Insert Sample Students
INSERT INTO students (name, roll_no, email, department, year, room_no) VALUES
('John Doe', 'CS2021001', 'john@example.com', 'Computer Science', 3, '101'),
('Jane Smith', 'IT2021002', 'jane@example.com', 'Information Technology', 2, '101'),
('Mike Johnson', 'EC2021003', 'mike@example.com', 'Electronics', 4, '102'),
('Sarah Williams', 'ME2021004', 'sarah@example.com', 'Mechanical', 1, NULL),
('David Brown', 'CS2021005', 'david@example.com', 'Computer Science', 2, NULL);

-- Update room occupancy
UPDATE rooms SET occupied = 2 WHERE room_no = '101';
UPDATE rooms SET occupied = 1 WHERE room_no = '102';

-- Insert Sample Complaints
INSERT INTO complaints (student_id, complaint_text, date, status) VALUES
(1, 'AC is not working in room 101', '2024-01-15', 'Pending'),
(2, 'Water leakage in bathroom', '2024-01-16', 'Resolved'),
(3, 'Light bulb needs replacement', '2024-01-17', 'Pending');