<?php
session_start();
include 'includes/connection.php';

// Admin session check
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 2) {
    header('Location: login.php');
    exit;
}

$error = '';
$success = '';

// Fetch subjects for dropdown
$subjects = [];
$result = $con->query("SELECT subject_id, subject_name FROM subject ORDER BY subject_name");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $subjects[] = $row;
    }
}
//Validation and insertion of assignments
if (isset($_POST['submit'])) {
    $title = trim(mysqli_real_escape_string($con, $_POST['title']));
    $subject_name = trim(mysqli_real_escape_string($con, $_POST['subject_name']));
    $due_date = trim(mysqli_real_escape_string($con, $_POST['due_date']));
    $description = trim(mysqli_real_escape_string($con, $_POST['description']));

    if (empty($title) || empty($subject_name) || empty($due_date) || empty($description)) {
        $error = "Please fill in all fields.";
    } elseif (strtotime($due_date) < strtotime(date('Y-m-d'))) {
        $error = "Due date cannot be in the past.";
    } else {
        $stmt = $con->prepare("INSERT INTO assignments (title, subject_name, due_date, description) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $title, $subject_name, $due_date, $description);
        if ($stmt->execute()) {
            $success = "Assignment created successfully.";
        } else {
            $error = "Error: " . $stmt->error;
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="Styles/admin.css">
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Assistant:wght@200;300&family=Poiret+One&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cinzel&display=swap" rel="stylesheet">
</head>
<style>
    .main-content {
        padding: 20px;
    }

    .user {
        display: flex;
        align-items: center;
    }

    .bg-img {
        width: 40px;
        height: 40px;
        background-size: cover;
        background-position: center;
        border-radius: 50%;
        margin-right: 10px;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .page-header h1 {
        font-size: 2rem;
        margin: 0;
    }

    .page-header small {
        font-size: 1rem;
        color: #777;
        margin: 0;
    }

    .bg-light.form-out {
        padding: 30px;
        margin: 20px 0;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    table {
        background-color: white;
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        font-family: 'Assistant', sans-serif;
        font-size: 18px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    th {
        background-color: #007BFF;
        color: white;
        padding: 12px 16px;
        text-align: left;
        font-size: 20px;
    }

    td {
        padding: 12px 16px;
        border-bottom: 1px solid #ddd;
        font-weight: 500;
    }

    tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    tr:hover {
        background-color: #f1f1f1;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
    }


    h2 {
        text-align: center;
        color: #2c3e50;
        margin-bottom: 30px;
    }

    form {
        background-color: #ffffff;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        padding: 25px;
    }

    label {
        display: block;
        margin-top: 20px;
        font-weight: 600;
        color: #2c3e50;
    }

    input[type="text"],
    input[type="date"],
    select,
    textarea {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #ccc;
        border-radius: 6px;
        margin-top: 5px;
        font-size: 16px;
        box-sizing: border-box;
        transition: border-color 0.3s;
    }

    input[type="text"]:focus,
    input[type="date"]:focus,
    select:focus,
    textarea:focus {
        border-color: #2980b9;
        outline: none;
    }

    textarea {
        resize: vertical;
    }

    input[type="submit"] {
        background-color: #2980b9;
        color: #fff;
        border: none;
        padding: 12px 25px;
        margin-top: 25px;
        border-radius: 6px;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    input[type="submit"]:hover {
        background-color: #1f618d;
    }

    .error {
        color: #e74c3c;
        background-color: #fceae9;
        padding: 10px 15px;
        border: 1px solid #e0b4b4;
        border-radius: 5px;
        margin-bottom: 15px;
    }

    .success {
        color: #2ecc71;
        background-color: #e9f7ef;
        padding: 10px 15px;
        border: 1px solid #b4e0c8;
        border-radius: 5px;
        margin-bottom: 15px;
    }
</style>

<body>
    <input type="checkbox" id="menu-toggle">
    <div class="sidebar">
        <div class="side-header">
            <h3><img src="assets/img/merologo.png " style="width: 50px; height: 50px;"></h3>
        </div>
        <div class="side-menu">
            <ul>
                <li>
                    <a href="teacher.php">
                        <span><img width="27" height="27" src="https://img.icons8.com/material-rounded/24/teacher.png" alt="teacher" /></span>
                        <!-- <span class="las la-clipboard-list"></span> -->
                        <small>Dashboard</small>
                    </a>
                </li>
                <li>
                    <a href="add_subject.php">
                        <span><img width="27" height="27" src="https://img.icons8.com/ios/50/books.png" alt="books" /></span>
                        <!-- <span class="las la-home"></span> -->
                        <small>Subject</small>
                    </a>
                </li>
                <li>
                    <a href="add_students.php">
                        <span><img width="27" height="27" src="https://img.icons8.com/wired/64/students.png" alt="students" /></span>
                        <!-- <span class="las la-plane-departure"></span> -->
                        <small>Students</small>
                    </a>
                </li>
                <li>
                    <a href="attendance.php">
                        <span><img width="27" height="27" src="https://img.icons8.com/ios/50/attendance-mark.png" alt="attendance-mark" /></span>
                        <!-- <span class="las la-plane"></span> -->
                        <small>Attendance</small>
                    </a>
                </li>
                </li>
                <li>
                    <a href="assignment.php" class="active">
                        <span><img width="27" height="27" src="https://img.icons8.com/ios/50/signing-a-document.png" alt="signing-a-document" /></span>
                        <!-- <span class="las la-plane"></span> -->
                        <small>Assignment</small>
                    </a>
                </li>
                <li>
                    <a href="view_attendance.php">
                        <span><img width="27" height="27" src="https://img.icons8.com/ios/50/clipboard.png" alt="clipboard" /></span>
                        <!-- <span class="las la-clipboard-list"></span> -->
                        <small>Report</small>
                    </a>
                </li>



            </ul>
        </div>
    </div>
    </div>

    <div class="main-content">
        <header>
            <div class="header-content">
                <label for="menu-toggle"><span class="las la-bars"></span></label>
                <div class="user">
                    <?php echo "<p style='text-decoration: none;color: skyblue;padding: 10px 20px;font-size: 15px;'>" . $_SESSION['username'] . "</p>"; ?>
                    <a href="logout.php"><span style="color: red;">Logout</a></span>
                </div>
            </div>
        </header>

        <main>
            <div class="page-header">
                <h1>Dashboard</h1>
                <small>Home / Assignment</small>
            </div>
        </main>
        <div style="margin-top: 30px; text-align: right;">
            <a href="view_submissions.php"
                style="background-color: #2980b9; color: white; padding: 10px 20px; border-radius: 6px; text-decoration: none; font-weight: 600;transition: background-color 0.3s ease;"
                onmouseover="this.style.backgroundColor='#1f618d'"
                onmouseout="this.style.backgroundColor='#2980b9'">
                View Submissions
            </a>
        </div>


        <h2 style="text-align: left;">Create New Assignment</h2>

        <?php if ($error): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <?php if ($success): ?>
            <p class="success"><?= htmlspecialchars($success) ?></p>
        <?php endif; ?>

        <form method="POST" action="">
            <label for="title">Assignment Title:</label>
            <input type="text" id="title" name="title" required />

            <label for="subject_name">Subject:</label>
            <select id="subject_name" name="subject_name" required>
                <option value="">-- Select Subject --</option>
                <?php foreach ($subjects as $sub): ?>
                    <option value="<?= htmlspecialchars($sub['subject_name']) ?>">
                        <?= htmlspecialchars($sub['subject_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="due_date">Due Date:</label>
            <input type="date" id="due_date" name="due_date" required min="<?= date('Y-m-d') ?>" />

            <label for="description">Description:</label>
            <textarea id="description" name="description" rows="5" required></textarea>

            <input type="submit" name="submit" value="Create Assignment" />
        </form>

</body>

</html>