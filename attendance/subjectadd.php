<?php
session_start();
include 'includes/connection.php'; 

// Check if the form is submitted
if (isset($_POST['subject_but']) && isset($_SESSION['user_type'])) {
    // Retrieve form data
    $subject_name = trim($_POST['subject_name']);
    $subject_code = trim($_POST['subject_code']);
    $teacher_name = trim($_POST['teacher_name']);

    // Validate input
    if (empty($subject_name) || empty($subject_code) || empty($teacher_name)) {
        header('Location: add_subject.php?error=emptyfields');     
        exit();
    }

    // Check for duplicate entry
    $check_sql = "SELECT * FROM subject WHERE subject_name = ? AND subject_code = ? AND teacher_name = ?";
    $check_stmt = mysqli_prepare($con, $check_sql);
    mysqli_stmt_bind_param($check_stmt, 'sss', $subject_name, $subject_code, $teacher_name);
    mysqli_stmt_execute($check_stmt);
    mysqli_stmt_store_result($check_stmt);

    if (mysqli_stmt_num_rows($check_stmt) > 0) {
        // Duplicate found
        header('Location: add_subject.php?error=duplicate');
        exit();
    }

    // Insert new subject
    $insert_sql = "INSERT INTO subject (subject_name, subject_code, teacher_name) VALUES (?, ?, ?)";
    $insert_stmt = mysqli_prepare($con, $insert_sql);
    mysqli_stmt_bind_param($insert_stmt, 'sss', $subject_name, $subject_code, $teacher_name);

    if (mysqli_stmt_execute($insert_stmt)) {
        header('Location: add_subject.php?success=subjectadded');
        exit();
    } else {
        header('Location: add_subject.php?error=failed');
        exit();
    }
}
?>
