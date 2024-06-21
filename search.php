<?php
// Simulate a database call with dummy data
$query = $_GET['query'] ?? '';
$allCourses = [
    'Course 1', 'Course 2', 'Course 3', 'Course 4', 'Course 5', 'Course 6'
];

$filteredCourses = array_filter($allCourses, function($course) use ($query) {
    return stripos($course, $query) !== false;
});

echo json_encode(array_values($filteredCourses));
?>
