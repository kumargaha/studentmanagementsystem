<?php
session_start();
if (!isset($_SESSION['id'])) {
    header('location:login.php');
    exit();
}

include 'includes/connection.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);

    //Deleting students
    $stmt = $con->prepare("DELETE FROM subject WHERE subject_id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $stmt->close();
        header("Location: add_subject.php?success=Subject deleted successfully");
        exit();
    } else {
        $stmt->close();
        header("Location: add_subject.php?error=Failed to delete subject");
        exit();
    }
} else {
    header("Location: add_subject.php?error=Invalid ID");
    exit();
}
?>
