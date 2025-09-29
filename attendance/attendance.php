<?php
session_start();
if (!isset($_SESSION['id'])) {
    header('location:login.php');
}
include 'includes/connection.php';
if ($_SESSION['user_type'] != 2) {
    // Logged in as admin
    header('Location: login.php');
    exit;
}

// Sabai students fetch garne
$students = [];
$student_result = mysqli_query($con, "SELECT stud_id, stud_name FROM students ORDER BY stud_name ASC");
if ($student_result) {
    while ($row = mysqli_fetch_assoc($student_result)) {
        $students[] = $row;
    }
}

// Yo chai subject bata fetch garne
$subjects = [];
$subject_result = mysqli_query($con, "SELECT subject_id, subject_name FROM subject ORDER BY subject_name ASC");
if ($subject_result) {
    while ($row = mysqli_fetch_assoc($subject_result)) {
        $subjects[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="Styles/admin.css">
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Assistant:wght@200;300&family=Poiret+One&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cinzel&display=swap" rel="stylesheet">
    <style>
        /* Container & Layout */
        body {
            font-family: 'Assistant', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }

        .main-content {
            padding: 20px;
        }

        /* Header */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .page-header h1 {
            font-size: 2rem;
            margin: 0;
        }

        .page-header small {
            font-size: 1rem;
            color: #777;
        }

        /* User Display */
        .user {
            display: flex;
            align-items: center;
        }

        .user p {
            margin: 0;
            padding: 0 10px;
            font-size: 15px;
            color: skyblue;
        }

        .user a {
            color: red;
            text-decoration: none;
            font-weight: bold;
            margin-left: 10px;
        }

        .user a:hover {
            text-decoration: underline;
        }

        /* Form Container */
        .form-container {
            margin-bottom: 20px;
        }

        form {
            background-color: #fff;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        /* Form Inputs */
        label {
            font-weight: bold;
            margin-right: 10px;
        }

        select,
        input[type="date"] {
            padding: 8px 12px;
            border-radius: 5px;
            border: 1px solid #ccc;
            margin-top: 10px;
            font-size: 16px;
        }

        select:focus,
        input[type="date"]:focus {
            outline: none;
            border-color: #007bff;
        }

        /* Attendance Table */
        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
            background-color: white;
        }

        th,
        td {
            padding: 12px 10px;
            border: 1px solid #ddd;
            text-align: center;
        }

        th {
            background-color: #f4f4f4;
            font-size: 18px;
        }

        td {
            font-size: 16px;
        }

        /* Buttons */
        button[type="submit"] {
            background-color: #28a745;
            color: white;
            padding: 10px 30px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        button[type="submit"]:hover {
            background-color: #218838;
        }
    </style>

</head>

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
                    <a href="attendance.php" class="active">
                        <span><img width="27" height="27" src="https://img.icons8.com/ios/50/attendance-mark.png" alt="attendance-mark" /></span>
                        <!-- <span class="las la-plane"></span> -->
                        <small>Attendance</small>
                    </a>
                </li>
                <li>
                    <a href="assignment.php">
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
                <label for="menu-toggle">
                    <span class="las la-bars"></span>
                </label>

                <div class="user">
                    <?php echo "<p style='text-decoration: none;color: skyblue;padding: 10px 20px;font-size: 15px;'>" . $_SESSION['username'];
                    "</p>" ?>
                    <a href="logout.php"><span style="color: red;">Logout</a></span>
                </div>

            </div>
        </header>
        <main>
            <div class="page-header">
                <h1>Dashboard</h1>
                <small>Home / Attendance</small>
            </div>

            <div class="form-container">
                <h2>Attendance</h2>
                <form action="save_attendance.php" method="post">
                    <label>Select Subject:</label>
                    <select name="subject_id" required>
                        <option value="">--Select Subject--</option>
                        <?php foreach ($subjects as $subject): ?>
                            <option value="<?= $subject['subject_id'] ?>"><?= htmlspecialchars($subject['subject_name']) ?></option>
                        <?php endforeach; ?>
                    </select>

                    <label style="margin-left: 20px;">Select Date:</label>
                    <input type="date" name="class_date" required min="<?= date('Y-m-d') ?>" max="<?= date('Y-m-d') ?>" value="<?= date('Y-m-d') ?>">



                    <?php if (isset($_GET['error']) && $_GET['error'] == 'future_date'): ?>
                        <p style='color:red; font-weight:bold;'>You cannot select a future date for attendance.</p>
                    <?php endif; ?>
                    <style>
                        table {
                            width: 100%;
                            margin-top: 20px;
                            border-collapse: separate;
                            border-spacing: 0;
                            border-radius: 10px;
                            overflow: hidden;
                            background-color: #fff;
                            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
                        }

                        th,
                        td {
                            padding: 14px 12px;
                            border-bottom: 1px solid #e0e0e0;
                            text-align: center;
                            font-size: 16px;
                        }

                        th {
                            background-color: #f1f1f1;
                            font-weight: 600;
                            text-transform: uppercase;
                            letter-spacing: 0.5px;
                        }

                        tr:hover {
                            background-color: #f9f9f9;
                        }

                        tr:last-child td {
                            border-bottom: none;
                        }
                    </style>

                    <table>
                        <thead>
                            <tr>
                                <th style="text-align: center;">SN</th>
                                <th style="text-align: center;">Student Name</th>
                                <th style="text-align: center;">Status</th>
                            </tr>

                        </thead>
                        <tbody>
                            <?php if (count($students) > 0): ?>
                                <?php $i = 1;
                                foreach ($students as $student): ?>
                                    <tr>
                                        <td><?= $i++ ?></td>
                                        <td>
                                            <?= htmlspecialchars($student['stud_name']) ?>
                                            <input type="hidden" name="stud_id[]" value="<?= $student['stud_id'] ?>">
                                            <input type="hidden" name="stud_name[]" value="<?= htmlspecialchars($student['stud_name']) ?>">
                                        </td>
                                        <td>
                                            <label>
                                                <input type="radio" name="status[<?= $student['stud_id'] ?>]" value="Present" required>
                                                Present
                                            </label>
                                            &nbsp;&nbsp;
                                            <label>
                                                <input type="radio" name="status[<?= $student['stud_id'] ?>]" value="Absent">
                                                Absent
                                            </label>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="3">No students found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>

                    </table>

                    <button type="submit" name="submit_attendance" style="margin-top: 20px;">Save Attendance</button>
                </form>
            </div>
        </main>

</body>

</html>