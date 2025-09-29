<!DOCTYPE html>
<html lang="en">
<?php
session_start();
if (!isset($_SESSION['id'])) {
  header('location:login.php');
}
include 'includes/connection.php';
if ($_SESSION['user_type'] != 2) {

  header('Location: login.php');
  exit;
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
          <a href="teacher.php" class="active">
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
        <small>Home / Dashboard</small>
      </div>
    </main>

    <section>
      <div class="container">
        <div class="main-section">
          <div class="dashbord">
            <div class="icon-section">
              <span class="la las-users"></span><br>
              Subjects
              <p>
                <?php
                $checksubject = "SELECT * FROM subject ";
                $query = mysqli_query($con, $checksubject);
                $row = mysqli_num_rows($query);
                echo $row;
                ?>
              </p>
            </div>
          </div>

          <div class="dashbord dashbord-red">
            <div class="icon-section">
              <span class="la las-plane"></span><br>
              Students
              <p>
                <?php
                $checkstudents = "SELECT * FROM students";
                $query = mysqli_query($con, $checkstudents);
                $row = mysqli_num_rows($query);
                echo $row;
                ?>
              </p>
            </div>
          </div>

          <div class="dashbord dashbord-red">
            <div class="icon-section">
              <span class="la las-plane"></span><br>
              Teachers
              <p>
                <?php
                $countTeachers = "SELECT COUNT(DISTINCT teacher_name) as teacher_count FROM subject";
                $query = mysqli_query($con, $countTeachers);
                $result = mysqli_fetch_assoc($query);
                echo $result['teacher_count'];
                ?>
              </p>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</body>

</html>