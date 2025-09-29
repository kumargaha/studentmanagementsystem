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
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_but'])) {
    $stud_id = $_POST['stud_id'];
    $stud_name = trim($_POST['stud_name']);
    if (!empty($stud_name)) {
        $stmt = mysqli_prepare($con, "UPDATE students SET stud_name=? WHERE stud_id=?");
        mysqli_stmt_bind_param($stmt, 'ss', $stud_name, $stud_id);
        mysqli_stmt_execute($stmt);
        header("Location: add_students.php?success=edited");
        exit;
    }
}
$students = mysqli_query($con, "SELECT * FROM students ORDER BY stud_id ASC");
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
                    <a href="add_subject.php">
                        <span><img width="27" height="27" src="https://img.icons8.com/ios/50/books.png" alt="books" /></span>
                        <!-- <span class="las la-home"></span> -->
                        <small>Subject</small>
                    </a>
                </li>
                <li>
                    <a href="add_students.php" class="active">
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
                <small>Home / Add Student</small>
            </div>

            <div class="bg-light form-out col-md-12">
                <form class="px-2 py-2" action="studentadd.php" method="post">
                    <h1 class="text-secondary text-center">ADD STUDENT</h1>
                    <!-- Error Check Garne -->
                    <?php
                    if (isset($_GET['error'])) {
                        switch ($_GET['error']) {
                            case 'empty':
                                echo "<p style='color:red;'>Please fill in all fields.</p>";
                                break;
                            case 'duplicate':
                                echo "<p style='color:red;'>This student already exists.</p>";
                                break;
                            case 'id_conflict':
                                echo "<p style='color:red;'>Student ID already used for another name.</p>";
                                break;
                            case 'name_exists':
                                echo "<p style='color:red;'>Student name already used with another ID.</p>";
                                break;
                            case 'insertfail':
                                echo "<p style='color:red;'>Failed to insert student. Try again.</p>";
                                break;
                            case 'deletefail':
                                echo "<p style='color:red;'>Failed to delete student. Try again.</p>";
                                break;
                        }
                    }
                    if (isset($_GET['success'])) {
                        switch ($_GET['success']) {
                            case 'added':
                                echo "<p style='color:green;'>Student added successfully.</p>";
                                break;
                            case 'deleted':
                                echo "<p style='color:red;'>Student deleted successfully.</p>";
                                break;
                        }
                    }
                    ?>


                    <div class="form-group">
                        <input type="text" class="form-control" name="stud_id" placeholder="Student ID" required>
                        <input type="text" class="form-control mt-3" name="stud_name" placeholder="Student Name" required>
                        <button type="submit" name="stud_but" class="btn btn-success w-100" style="padding:10px; border: 1px solid #ccc; border-radius: 5px; background-color:#e9edf2;">
                            Submit
                        </button>
                    </div>
                </form>
            </div>

            <?php if (isset($_SESSION['user_type'])) { ?>
                <div class="bg-light form-out col-md-12">
                    <h1 class="text-secondary text-center">STUDENT LIST</h1>
                    <table class="table table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Student ID</th>
                                <th scope="col">Student Name</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <?php $cnt = 1;
                        while ($row = mysqli_fetch_assoc($students)): ?>
                            <tr>
                                <td><?= $cnt ?></td>
                                <td><?= htmlspecialchars($row['stud_id']) ?></td>
                                <td>
                                    <?php if (isset($_GET['edit_id']) && $_GET['edit_id'] == $row['stud_id']): ?>
                                        <form action="add_students.php" method="post" style="display:inline;">
                                            <input type="hidden" name="stud_id" value="<?= htmlspecialchars($row['stud_id']) ?>">
                                            <input type="text" name="stud_name" value="<?= htmlspecialchars($row['stud_name']) ?>">
                                        <?php else: ?>
                                            <?= htmlspecialchars($row['stud_name']) ?>
                                        <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (isset($_GET['edit_id']) && $_GET['edit_id'] == $row['stud_id']): ?>
                                        <button type="submit" name="edit_but" class="save-btn">Save</button>
                                        </form>
                                        <a href="add_students.php"><button type="button" class="edit-btn">Cancel</button></a>
                                    <?php else: ?>
                                        <a href="add_students.php?edit_id=<?= $row['stud_id'] ?>"><button class="edit-btn">Edit</button></a>
                                        <form action="delete_students.php" method="post" style="display:inline;">
                                            <input type="hidden" name="stud_id" value="<?= htmlspecialchars($row['stud_id']) ?>">
                                            <button type="submit" name="del_student" class="delete-btn">Delete</button>
                                        </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php $cnt++;
                        endwhile; ?>
                    </table>
                </div>
            <?php } ?>

    </div>

    </button>
    </form>
    </div>
    </div>
    </main>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.delete-form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    if (!confirm('Are you sure you want to delete this student?')) {
                        e.preventDefault(); // Cancel form submission
                    }
                });
            });
        });
    </script>

</body>

</html>