<!DOCTYPE html>
<html lang="en">
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
// Handle Edit Submission
if (isset($_POST['edit_but'])) {
    $id = intval($_POST['subject_id']);
    $name = mysqli_real_escape_string($con, $_POST['subject_name']);
    $code = mysqli_real_escape_string($con, $_POST['subject_code']);
    $teacher = mysqli_real_escape_string($con, $_POST['teacher_name']);

    $update = "UPDATE subject SET subject_name='$name', subject_code='$code', teacher_name='$teacher' WHERE subject_id=$id";
    mysqli_query($con, $update);
    header("Location: add_subject.php?success=updated");
    exit;
}

// Fetch all subjects
$subjects = [];
$res = mysqli_query($con, "SELECT * FROM subject ORDER BY subject_name ASC");
if ($res) {
    while ($row = mysqli_fetch_assoc($res)) {
        $subjects[] = $row;
    }
}
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="Styles/admin.css">
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Assistant:wght@200;300&family=Poiret+One&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cinzel&display=swap" rel="stylesheet">
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
            margin-top: 10px;
            width: 85%;
        }

        a:hover {
            text-decoration: none;
        }

        th {
            font-size: 22px;
        }

        td {
            margin-top: 10px !important;
            font-size: 16px;
            font-weight: bold;
            font-family: 'Assistant', sans-serif !important;
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
                    <a href="add_subject.php" class="active">
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
                <li>
                    <a href="assignment.php">
                        <span><img width="27" height="27" src="https://img.icons8.com/ios/50/signing-a-document.png" alt="signing-a-document"/></span>
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
                <small>Home / Add Subject</small>
            </div>

            <div>
                <div style="padding: 30px; background-color: #f8f9fa; border-radius: 10px; width: 100%;">
                    <h1 style="color: #6c757d; text-align: center;">ADD SUBJECT DETAILS</h1>
                    <?php
                    if (isset($_GET['error'])) {
                        $error = htmlspecialchars($_GET['error']);
                        if ($error === "duplicate") {
                            echo '<p style="color: red; text-align: center; font-weight: bold;">Error: This subject already exists with the same name, code, and teacher.</p>';
                        } elseif ($error === "emptyfields") {
                            echo '<p style="color: red; text-align: center; font-weight: bold;">Error: Please fill in all fields.</p>';
                        } elseif ($error === "failed") {
                            echo '<p style="color: red; text-align: center; font-weight: bold;">Error: Failed to add subject. Please try again later.</p>';
                        }
                    }

                    if (isset($_GET['success']) && $_GET['success'] === "subjectadded") {
                        echo '<p style="color: green; text-align: center; font-weight: bold;">Success: Subject added successfully!</p>';
                    }
                    ?>


                    <form method="POST" action="subjectadd.php" style="text-align: center;">
                        <div style="display: flex; justify-content: space-between; flex-wrap: wrap; margin-bottom: 20px;">
                            <div style="flex: 1; min-width: 200px; margin-right: 10px;">
                                <div style="margin-bottom: 10px;">
                                    <label for="subject_name" style="display: block; font-weight: bold; margin-bottom: 5px;">SUBJECT NAME</label>
                                    <input type="text" name="subject_name" id="subject_name" required style="width: 100%; padding: 8px; border: 1px solid #ced4da; border-radius: 5px;" />
                                </div>
                            </div>
                            <div style="flex: 1; min-width: 200px; margin-right: 10px;">
                                <div style="margin-bottom: 10px;">
                                    <label for="subject_code" style="display: block; font-weight: bold; margin-bottom: 5px;">SUBJECT CODE</label>
                                    <input type="text" name="subject_code" id="subject_code" required style="width: 100%; padding: 8px; border: 1px solid #ced4da; border-radius: 5px;" />
                                </div>
                            </div>
                            <div style="flex: 1; min-width: 200px;">
                                <div style="margin-bottom: 10px;">
                                    <label for="teacher_name" style="display: block; font-weight: bold; margin-bottom: 5px;">TEACHER NAME</label>
                                    <input type="text" name="teacher_name" id="teacher_name" required style="width: 100%; padding: 8px; border: 1px solid #ced4da; border-radius: 5px;" />
                                </div>
                            </div>
                        </div>

                        <button name="subject_but" type="submit" style="margin-top: 40px; padding: 10px 20px; font-size: 1.5rem; background-color: #28a745; color: white; border: none; border-radius: 5px; cursor: pointer;">
                            <i class="fa fa-lg fa-arrow-right"></i> Add Subject
                        </button>
                    </form>
                </div>

                </button>
                </form>
                <?php
                // Fetch subjects from the database
                $query = "SELECT * FROM subject"; 
                $result = mysqli_query($con, $query);
                ?>

                <h2 style="text-align: center; margin-top: 40px;">Existing Subjects</h2>
                <table border="1" cellpadding="10" cellspacing="0" align="center" style="margin-top: 20px; width: 100%;">
                    <thead>
                        <tr>
                            <th>Subject Name</th>
                            <th>Subject Code</th>
                            <th>Teacher Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
                    $edit_id = $_GET['edit_id'] ?? '';
                    foreach ($subjects as $row): ?>
                        <tr>
                        <?php if ($edit_id == $row['subject_id']): ?>
                            <!-- Edit Mode -->
                            <form method="POST">
                                <td><input type="text" name="subject_name" value="<?= htmlspecialchars($row['subject_name']) ?>" required></td>
                                <td><input type="text" name="subject_code" value="<?= htmlspecialchars($row['subject_code']) ?>" required></td>
                                <td><input type="text" name="teacher_name" value="<?= htmlspecialchars($row['teacher_name']) ?>" required></td>
                                <td>
                                    <input type="hidden" name="subject_id" value="<?= $row['subject_id'] ?>">
                                    <button type="submit" name="edit_but" class="btn-edit">Save</button>
                                    <a href="add_subject.php"><button type="button" class="btn-cancel">Cancel</button></a>
                                </td>
                            </form>
                        <?php else: ?>
                            <!-- View Mode -->
                            <td><?= htmlspecialchars($row['subject_name']) ?></td>
                            <td><?= htmlspecialchars($row['subject_code']) ?></td>
                            <td><?= htmlspecialchars($row['teacher_name']) ?></td>
                            <td>
                                <a href="add_subject.php?edit_id=<?= $row['subject_id'] ?>"><button class="btn-edit">Edit</button></a>
                                <a href="delete_subject.php?id=<?= $row['subject_id'] ?>" onclick="return confirm('Are you sure?');">
                                    <button class="btn-delete">Delete</button>
                                </a>
                            </td>
                        <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                </table>

            </div>
    </div>
    </main>
    </div>
</body>

</html>