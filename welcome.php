<?php
header('Content-Type: application/json');
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

// Check if student data is stored in session
if (isset($_SESSION['student_data'])) {
    // Fetch USN from session data
    $USN = $_SESSION['student_data']['USN'];

    // Fetch student details from database
    $student_sql = "SELECT * FROM student_info WHERE USN = '$USN'";
    $student_result = $conn->query($student_sql);

    if ($student_result->num_rows > 0) {
        $student_data = $student_result->fetch_assoc();

        // Prepare response for student details
        $response = [
            'name' => $student_data['Name'],
            'email' => $student_data['Email'],
            'enrollment' => $student_data['USN'],
            'semester' => $student_data['Current Sem'],
            'backlogs' => $student_data['Backlogs'],
            'phone' => $student_data['Phone Number'],
            'cgpa' => $student_data['CGPA']
        ];

        // Fetch placement status from database
        $placement_sql = "SELECT * FROM current_recruitment WHERE USN = '$USN'";
        $placement_result = $conn->query($placement_sql);

        if ($placement_result->num_rows > 0) {
            $placement_data = $placement_result->fetch_assoc();

            // Add placement status to response
            $response['status'] = $placement_data['Recruitment_status'];
            $response['company'] = $placement_data['company'];
            $response['position'] = $placement_data['Role'];
            $response['package'] = $placement_data['Package'];
            $response['recruitedDate'] = $placement_data['Recruited_date'];
        } else {
            $response['status'] = 'Not placed';
            $response['company'] = '';
            $response['position'] = '';
            $response['package'] = '';
            $response['recruitedDate'] = '';
        }

        // Fetch job history from database
        $jobHistory_sql = "SELECT Company, Role, Term_in_months FROM job_history WHERE USN = '$USN' ORDER BY Term_in_months DESC";
        $jobHistory_result = $conn->query($jobHistory_sql);

        if ($jobHistory_result->num_rows > 0) {
            $jobHistory = [];
            while ($row = $jobHistory_result->fetch_assoc()) {
                $jobHistory[] = [
                    'company' => $row['Company'],
                    'role' => $row['Role'],
                    'term' => $row['Term_in_months']
                ];
            }
            $response['jobHistory'] = $jobHistory;
        } else {
            $response['jobHistory'] = [];
        }

        echo json_encode($response);
    } else {
        echo json_encode(['error' => 'Student details not found']);
    }
} else {
    echo json_encode(['error' => 'Session data not found']);
}

$conn->close();
?>
