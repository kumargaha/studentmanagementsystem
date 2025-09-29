<?php
session_start();
include 'includes/connection.php';

// Delete students 
if (isset($_POST['del_student']) && isset($_POST['stud_id'])) {
    $stud_id = trim($_POST['stud_id']);

    $stmt = mysqli_prepare($con, "DELETE FROM students WHERE stud_id = ?");
    mysqli_stmt_bind_param($stmt, "s", $stud_id);
    if (mysqli_stmt_execute($stmt)) {
        header('Location: add_students.php?success=deleted');
    } else {
        header('Location: add_students.php?error=deletefail');
    }
    mysqli_stmt_close($stmt);
    exit();
}

// Adding
if (isset($_POST['stud_but'])) {
    $stud_id = trim($_POST['stud_id']);
    $stud_name = trim($_POST['stud_name']);

    // Input Validate 
    if (empty($stud_id) || empty($stud_name)) {
        header('Location: add_students.php?error=empty');
        exit();
    }

    // Check if stud_id exists
    $stmt = mysqli_prepare($con, "SELECT stud_name FROM students WHERE stud_id = ?");
    mysqli_stmt_bind_param($stmt, "s", $stud_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) > 0) {
        mysqli_stmt_bind_result($stmt, $existing_name);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);

        if (strcasecmp($existing_name, $stud_name) === 0) {
            header('Location: add_students.php?error=duplicate'); 
        } else {
            header('Location: add_students.php?error=id_conflict'); 
        }
        exit();
    }
    mysqli_stmt_close($stmt);

    // Check if stud_name already exists (with a different stud_id)
    $stmt = mysqli_prepare($con, "SELECT stud_id FROM students WHERE stud_name = ?");
    mysqli_stmt_bind_param($stmt, "s", $stud_name);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) > 0) {
        mysqli_stmt_close($stmt);
        header('Location: add_students.php?error=name_exists');
        exit();
    }
    mysqli_stmt_close($stmt);

    // All checks passed, insert ddata
    $stmt = mysqli_prepare($con, "INSERT INTO students (stud_id, stud_name) VALUES (?, ?)");
    mysqli_stmt_bind_param($stmt, "ss", $stud_id, $stud_name);

    if (mysqli_stmt_execute($stmt)) {
        header('Location: add_students.php?success=added');
    } else {
        header('Location: add_students.php?error=insertfail');
    }
    mysqli_stmt_close($stmt);
    exit();
} else {
    echo "Invalid submission.";
    exit();
}
