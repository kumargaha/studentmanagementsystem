<?php
session_start();
include 'includes/connection.php';

if (isset($_POST['submit_attendance'])) {
    $subject_id = $_POST['subject_id'];
    $class_date = $_POST['class_date'];
    $stud_ids = $_POST['stud_id'];
    $stud_names = $_POST['stud_name'];
    $statuses = $_POST['status'];

    // Prevent future dates
    $today = date('Y-m-d');
    if ($class_date > $today) {
        echo "<script>alert('Error: You cannot select a future date for attendance.'); window.location.href='attendance.php';</script>";
        exit();
    }

    // Get subject name
    $subject_query = mysqli_query($con, "SELECT subject_name FROM subject WHERE subject_id = '$subject_id'");
    if (!$subject_query || mysqli_num_rows($subject_query) === 0) {
        die("Invalid subject selected.");
    }
    $subject_data = mysqli_fetch_assoc($subject_query);
    $subject_name = $subject_data['subject_name'];

    // Insert/update attendance
    for ($i = 0; $i < count($stud_ids); $i++) {
        $stud_id = (int)$stud_ids[$i];
        $stud_name = mysqli_real_escape_string($con, $stud_names[$i]);
        $status = mysqli_real_escape_string($con, $statuses[$stud_id]); // Use stud_id key

        // Check if attendance already exists
        $check_query = mysqli_query($con, "SELECT id FROM att WHERE stud_id = '$stud_id' AND subject_name = '$subject_name' AND class_date = '$class_date'");
        
        if (mysqli_num_rows($check_query) > 0) {
            // Update existing record
            mysqli_query($con, "UPDATE att SET status = '$status' WHERE stud_id = '$stud_id' AND subject_name = '$subject_name' AND class_date = '$class_date'");
        } else {
            // Insert new record
            mysqli_query($con, "INSERT INTO att (stud_id, subject_name, stud_name, class_date, status) 
                                VALUES ('$stud_id', '$subject_name', '$stud_name', '$class_date', '$status')");
        }
    }

    echo "<script>alert('Attendance saved successfully!'); window.location.href='attendance.php';</script>";
} else {
    echo "Invalid request.";
}
