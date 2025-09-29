<?php
session_start();
if (!isset($_SESSION['id'])) {
    header('location:login.php');
}
include 'includes/connection.php';
if ($_SESSION['user_type'] != 1) {
    // Logged in as student
    header('Location: login.php'); 
    exit;
  }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="styles/index.css">
    <link rel="stylesheet" href="styles/routine.css">

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
        <main>
            <h2 style="color: white;">Subject List</h2>

            <?php
            $query = "SELECT * FROM subject";
            $result = mysqli_query($con, $query);
            ?>
            <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Subject Name</th>
                        <th>Subject Code</th>
                        <th>Teacher Name</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['subject_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['subject_code']); ?></td>
                            <td><?php echo htmlspecialchars($row['teacher_name']); ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            </div>
        </main>
    </header>

</body>

</html>