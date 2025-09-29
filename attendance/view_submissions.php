<?php
session_start();
include 'includes/connection.php';

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 2) {
    header('Location: login.php');
    exit;
}

$query = "
SELECT
    asub.id AS submission_id,
    asub.submitted_at,
    asub.file_path,
    stud.stud_name,
    ass.title,
    ass.subject_name,
    ass.due_date
FROM assignment_submissions AS asub
LEFT JOIN students AS stud ON asub.stud_id = stud.stud_id
LEFT JOIN assignments AS ass ON asub.assignment_id = ass.id
ORDER BY asub.submitted_at DESC

";

$result = $con->query($query);


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>View Assignment Submissions</title>
    <link rel="stylesheet" href="Styles/admin.css">
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Assistant:wght@200;300&family=Poiret+One&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cinzel&display=swap" rel="stylesheet">

    <style>
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

        body {
            font-family: Arial, sans-serif;
            padding: 30px;
            background: #f4f7f8;
            color: #333;
        }

        h1 {
            text-align: center;
            margin-bottom: 25px;
            color: #2980b9;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            background: white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border-radius: 6px;
            overflow: hidden;
        }

        th,
        td {
            padding: 14px 18px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #2980b9;
            color: white;
        }

        tr:hover {
            background-color: #f1f9ff;
        }

        a.download-link {
            color: #2980b9;
            text-decoration: none;
            font-weight: 600;
        }

        a.download-link:hover {
            text-decoration: underline;
        }

        .no-data {
            text-align: center;
            padding: 40px;
            font-size: 18px;
            color: #666;
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
        <h1>Assignment Submissions</h1>

        <?php if ($result && $result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Student Name</th>
                        <th>Assignment Title</th>
                        <th>Subject</th>
                        <th>Due Date</th>
                        <th>Submitted At</th>
                        <th>File</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['stud_name']) ?></td>
                            <td><?= htmlspecialchars($row['title']) ?></td>
                            <td><?= htmlspecialchars($row['subject_name']) ?></td>
                            <td><?= htmlspecialchars($row['due_date']) ?></td>
                            <td><?= htmlspecialchars($row['submitted_at']) ?></td>
                            <td>
                                <a class="download-link" href="<?= htmlspecialchars($row['file_path']) ?>" target="_blank" download>Download PDF</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="no-data">No submissions found.</p>
        <?php endif; ?>
</body>

</html>