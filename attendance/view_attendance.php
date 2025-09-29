<?php
session_start();
if (!isset($_SESSION['id'])) {
  header('location:login.php');
  exit();
}
include 'includes/connection.php';
if ($_SESSION['user_type'] != 2) {
  header('Location: login.php');
  exit;
}

// Filtering part
$subject = $_GET['subject'] ?? '';
$month = $_GET['month'] ?? '';
$year = $_GET['year'] ?? '';
$student = $_GET['student'] ?? '';

// Day headers
$daysInMonth = ($month && $year) ? cal_days_in_month(CAL_GREGORIAN, $month, $year) : 31;
$dateHeaders = range(1, $daysInMonth);

// Students bata nikaleko data
$studentQuery = "SELECT DISTINCT stud_id, stud_name FROM att WHERE 1=1";
if ($subject) $studentQuery .= " AND subject_name = '$subject'";
if ($month) $studentQuery .= " AND MONTH(class_date) = '$month'";
if ($year) $studentQuery .= " AND YEAR(class_date) = '$year'";
if ($student) $studentQuery .= " AND stud_name LIKE '%$student%'";
$studentResult = mysqli_query($con, $studentQuery);
$students = [];
while ($row = mysqli_fetch_assoc($studentResult)) {
  $students[$row['stud_id']] = $row['stud_name'];
}

// Attendance bata nikaleko data
$attendanceMap = [];
$attQuery = "SELECT stud_id, DAY(class_date) as day, status FROM att WHERE 1=1";
if ($subject) $attQuery .= " AND subject_name = '$subject'";
if ($month) $attQuery .= " AND MONTH(class_date) = '$month'";
if ($year) $attQuery .= " AND YEAR(class_date) = '$year'";
if ($student) $attQuery .= " AND stud_name LIKE '%$student%'";
$attResult = mysqli_query($con, $attQuery);
while ($row = mysqli_fetch_assoc($attResult)) {
  $status = strtoupper($row['status']);
  $attendanceMap[$row['stud_id']][$row['day']] = ($status === 'PRESENT') ? 'P' : (($status === 'ABSENT') ? 'A' : '');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1">
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="styles/admin.css">
  <link rel="stylesheet" href="styles/viewatt.css">

  <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Assistant:wght@200;300&family=Poiret+One&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Cinzel&display=swap" rel="stylesheet">
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
        <li>
          <a href="assignment.php">
            <span><img width="27" height="27" src="https://img.icons8.com/ios/50/signing-a-document.png" alt="signing-a-document" /></span>
            <!-- <span class="las la-plane"></span> -->
            <small>Assignment</small>
          </a>
        </li>
        <li>
          <a href="view_attendance.php" class="active">
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
        <small>Home / Report</small>
      </div>

      <div>
        <div style="padding: 30px; background-color: #f8f9fa; border-radius: 10px; width: 100%;">
          <h1 style="color: #6c757d; text-align: center;">View Attendance</h1>

          <form method="GET" action="view_attendance.php">
            <select name="subject">
              <option value="">All Subjects</option>
              <?php
              $subjects = mysqli_query($con, "SELECT DISTINCT subject_name FROM att");
              while ($row = mysqli_fetch_assoc($subjects)) {
                $sel = ($subject === $row['subject_name']) ? 'selected' : '';
                echo "<option value='" . htmlspecialchars($row['subject_name']) . "' $sel>" . htmlspecialchars($row['subject_name']) . "</option>";
              }
              ?>
            </select>

            <select name="month">
              <option value="">All Months</option>
              <?php
              for ($m = 1; $m <= 12; $m++) {
                $sel = ($month == $m) ? 'selected' : '';
                echo "<option value='$m' $sel>" . date('F', mktime(0, 0, 0, $m)) . "</option>";
              }
              ?>
            </select>

            <select name="year">
              <option value="">All Years</option>
              <?php
              $years = mysqli_query($con, "SELECT DISTINCT YEAR(class_date) as year FROM att ORDER BY year DESC");
              while ($row = mysqli_fetch_assoc($years)) {
                $sel = ($year == $row['year']) ? 'selected' : '';
                echo "<option value='{$row['year']}' $sel>{$row['year']}</option>";
              }
              ?>
            </select>

            <input type="text" name="student" placeholder="Search student..." value="<?= htmlspecialchars($student) ?>">
            <button type="submit">Filter</button>
            <button type="submit" formaction="download_excel.php">Download Excel</button>
          </form>
          <div class="table-container">
            <table>
              <thead>
                <tr>
                  <th>Student ID</th>
                  <th>Student Name</th>
                  <?php foreach ($dateHeaders as $day): ?>
                    <th><?= $day ?></th>
                  <?php endforeach; ?>
                  <th>P</th>
                  <th>A</th>
                  <th>Attendance %</th>
                  <th>Risk</th>

                </tr>
              </thead>
              <tbody>
                <?php if ($students): ?>
                  <?php foreach ($students as $id => $name): ?>
                    <tr>
                      <td><?= htmlspecialchars($id) ?></td>
                      <td><?= htmlspecialchars($name) ?></td>
                      <?php
                      $p = $a = 0;
                      foreach ($dateHeaders as $d) {
                        $mark = $attendanceMap[$id][$d] ?? '';
                        if ($mark === 'P') $p++;
                        if ($mark === 'A') $a++;
                        echo "<td>$mark</td>";
                      }
                      ?>
                      <?php
                      $attendance_percent = ($p + $a > 0) ? ($p / ($p + $a)) * 100 : 0;

                      // Logistic regression coefficients for 75% threshold
                      $b0 = -37.5; //-75*0.5
                      $b1 = 0.5;

                      $z = $b0 + ($b1 * $attendance_percent);
                      $prob = 1 / (1 + exp(-$z));

                      // Determine risk based on probability
                      $risk_status = ($prob >= 0.5) ? "Safe" : "At Risk";
                      ?>
                      <td><?= $p ?></td>
                      <td><?= $a ?></td>
                      <td><?= number_format($attendance_percent, 2) ?>%</td>
                      <td class="<?= $risk_status === 'At Risk' ? 'AtRisk' : 'Safe' ?>"><?= $risk_status ?></td>

                    </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                  <tr>
                    <td colspan="<?= 2 + count($dateHeaders) + 2 ?>" class="no-records">No records found.</td>
                  </tr>
                <?php endif; ?>
              </tbody>
            </table>
            <colgroup>
              <col style="width: 120px;"> <!-- Student ID -->
              <col style="width: 200px;"> <!-- Student Name -->
              <?php foreach ($dateHeaders as $day): ?>
                <col style="width: 80px;"> 
              <?php endforeach; ?>
              <col style="width: 60px;"> <!-- P column -->
              <col style="width: 60px;"> <!-- A column -->
            </colgroup>

          </div>


        </div>
</body>

</html>