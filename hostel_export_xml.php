<?php
include 'hostel_dbconnect.php'; // Corrected

// Create XML document
$xml = new DOMDocument('1.0', 'UTF-8');
$xml->formatOutput = true;

// Root element
$root = $xml->createElement('hostel_data');
$root->setAttribute('export_date', date('Y-m-d H:i:s'));
$xml->appendChild($root);

// Students
$students_element = $xml->createElement('students');
$root->appendChild($students_element);

$students = fetch_all("SELECT * FROM students");
foreach ($students as $student) {
    $student_element = $xml->createElement('student');
    
    foreach ($student as $key => $value) {
        $child = $xml->createElement($key, htmlspecialchars($value));
        $student_element->appendChild($child);
    }
    
    $students_element->appendChild($student_element);
}

// Rooms
$rooms_element = $xml->createElement('rooms');
$root->appendChild($rooms_element);

$rooms = fetch_all("SELECT * FROM rooms");
foreach ($rooms as $room) {
    $room_element = $xml->createElement('room');
    
    foreach ($room as $key => $value) {
        $child = $xml->createElement($key, htmlspecialchars($value));
        $room_element->appendChild($child);
    }
    
    $rooms_element->appendChild($room_element);
}

// Complaints
$complaints_element = $xml->createElement('complaints');
$root->appendChild($complaints_element);

$complaints = fetch_all("SELECT * FROM complaints");
foreach ($complaints as $complaint) {
    $complaint_element = $xml->createElement('complaint');
    
    foreach ($complaint as $key => $value) {
        $child = $xml->createElement($key, htmlspecialchars($value));
        $complaint_element->appendChild($child);
    }
    
    $complaints_element->appendChild($complaint_element);
}

// Generate XML string
$xml_string = $xml->saveXML();

// Set headers for download
header('Content-Type: application/xml');
header('Content-Disposition: attachment; filename="hostel_data_' . date('Y-m-d') . '.xml"');
header('Content-Length: ' . strlen($xml_string));

echo $xml_string;

mysqli_close($conn);
?>