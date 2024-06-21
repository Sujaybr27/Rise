<?php
session_start();

$servername = "localhost";
$username = "hiran";
$password = "Hiran@86532409";
$dbname = "student";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get POST data
$usn = $_POST['usn'];
$dob = $_POST['dob'];

// Prepare and execute query
$stmt = $conn->prepare("SELECT * FROM student_info WHERE USN = ? AND DOB = ?");
$stmt->bind_param("ss", $usn, $dob);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Credentials are correct, fetch student details
    $student_data = $result->fetch_assoc();
    // Store student data in session
    $_SESSION['student_data'] = $student_data;
    // Redirect to student details page
    header("Location: index.html");
    exit();
} else {
    // Credentials are incorrect, redirect back to login with error
    $_SESSION['login_error'] = "Invalid USN or Date of Birth";
    header("Location: login.html");
    exit();
}

$stmt->close();
$conn->close();
?>
