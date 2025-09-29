<!DOCTYPE html>
<html lang="en">
<?php
session_start();
if (!isset($_SESSION['id'])) {
  header('location:login.php');
}
include 'includes/connection.php';
if ($_SESSION['user_type'] != 1) {
    header('Location: login.php'); 
    exit;
  }
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="styles/index.css">

</head>

<body style="background-image:url(assets/img/back.png);background-repeat:no-repeat;background-size:cover;;height:80vh;width:100%;">


    <header>
        <nav>
            <a href="studentlog.php">
                <div class="logo"><img src="assets/img/merologo.png " style="width: 50px; height: 50px;"></div>
            </a>
            <div class="menu">
                <a href="studentlog.php">Home</a>
                <a href="routine.php">Subjects</a>
                <a href="useratt.php">Attendance</a>
                <a href="submit_assignment.php">Assignment </a>
            </div>
            <div class="Login">
                <?php echo "<p style='text-decoration: none;color: white;padding: 10px 20px;font-size: 20px;'>" . $_SESSION['username'];
                "</p>" ?>
                <a href="logout.php"><span>Logout</a></span>
            </div>
        </nav>
        <section class="h-txt">

            <h1>Learner's Management System</h1>

            <br>
            <a href="useratt.php">View your Attendance</a><br><br><br>

            <a href="submit_assignment.php">Submit your Assignment</a>
        </section>
    </header>
</body>

</html>