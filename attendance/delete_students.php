<?php
session_start();
include 'includes/connection.php';

if (isset($_POST['del_student'])) {
    $stud_id = $_POST['stud_id'];

    // Delete garne laa
    $stmt = mysqli_prepare($con, "DELETE FROM students WHERE stud_id = ?");
    mysqli_stmt_bind_param($stmt, 's', $stud_id);

    if (mysqli_stmt_execute($stmt)) {
        // success message
        header('Location: add_students.php?success=deleted');
        exit();
    } else {
        // failure message
        header('Location: add_students.php?error=deletefail');
        exit();
    }
}
?>
