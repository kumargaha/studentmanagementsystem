<?php
session_start();
if (!isset($_SESSION['id'])) {
  header('location:login.php');
  exit();
}
include 'includes/connection.php';
if ($_SESSION['user_type'] != 1) {
  header('Location: login.php');
  exit;
}

$loggedInId = $_SESSION['id'];

// Filter dates and subject
$subject = $_GET['subject'] ?? '';
$month = $_GET['month'] ?? '';
$year = $_GET['year'] ?? '';

// Calculate days in selected month
$daysInMonth = ($month && $year) ? cal_days_in_month(CAL_GREGORIAN, $month, $year) : 31;
$dateHeaders = range(1, $daysInMonth);

// Get student name (logged in)
$studentQuery = "SELECT DISTINCT stud_id, stud_name FROM att WHERE stud_id = '$loggedInId'";
if ($subject) $studentQuery .= " AND subject_name = '$subject'";
if ($month) $studentQuery .= " AND MONTH(class_date) = '$month'";
if ($year) $studentQuery .= " AND YEAR(class_date) = '$year'";
$studentResult = mysqli_query($con, $studentQuery);
$students = [];
while ($row = mysqli_fetch_assoc($studentResult)) {
  $students[$row['stud_id']] = $row['stud_name'];
}

// Get attendance records
$attendanceMap = [];
$attQuery = "SELECT stud_id, DAY(class_date) as day, status FROM att WHERE stud_id = '$loggedInId'";
if ($subject) $attQuery .= " AND subject_name = '$subject'";
if ($month) $attQuery .= " AND MONTH(class_date) = '$month'";
if ($year) $attQuery .= " AND YEAR(class_date) = '$year'";
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
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student Attendance</title>
  <link rel="stylesheet" href="styles/index.css">
  <link rel="stylesheet" href="styles/useratt.css">
</head>

<body style="background-image:url(assets/img/back.png);background-repeat:no-repeat;background-size:cover;height:80vh;width:100%;">

  <header>
    <nav>
      <a href="studentlog.php">
        <div class="logo"><img src="assets/img/merologo.png" style="width: 50px; height: 50px;"></div>
      </a>
      <div class="menu">
        <a href="studentlog.php">Home</a>
        <a href="routine.php">Subjects</a>
        <a href="useratt.php">Attendance</a>
        <a href="submit_assignment.php">Assignment</a>
      </div>
      <div class="Login">
        <?php echo "<p style='text-decoration: none;color: white;padding: 10px 20px;font-size: 20px;'>" . $_SESSION['username'];
        "</p>" ?>
        <a href="logout.php"><span>Logout</a></span>
      </div>
    </nav>

    <h1 style="color: white; text-align: center;">View Attendance</h1>

    <form method="GET" action="useratt.php" style="text-align:center; padding: 10px;">
      <select name="subject">
        <option value="">All Subjects</option>
        <?php
        $subjects = mysqli_query($con, "SELECT DISTINCT subject_name FROM att WHERE stud_id = '$loggedInId'");
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
        $years = mysqli_query($con, "SELECT DISTINCT YEAR(class_date) as year FROM att WHERE stud_id = '$loggedInId' ORDER BY year DESC");
        while ($row = mysqli_fetch_assoc($years)) {
          $sel = ($year == $row['year']) ? 'selected' : '';
          echo "<option value='{$row['year']}' $sel>{$row['year']}</option>";
        }
        ?>
      </select>

      <button type="submit">Filter</button>
    </form>

    <div class="table-container">
      <table>
        <thead>
          <tr>
            <th>Student Name</th>
            <?php foreach ($dateHeaders as $day): ?>
              <th><?= $day ?></th>
            <?php endforeach; ?>
            <th>P</th>
            <th>A</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($students): ?>
            <?php foreach ($students as $id => $name): ?>
              <tr>
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
                <td><?= $p ?></td>
                <td><?= $a ?></td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="<?= 1 + count($dateHeaders) + 2 ?>" style="color:red; text-align:center;">No records found.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </header>
</body>

</html>