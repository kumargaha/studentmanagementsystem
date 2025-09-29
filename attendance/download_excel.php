<?php
session_start();
if (!isset($_SESSION['id']) || $_SESSION['user_type'] != 2) {
    header('location:login.php');
    exit();
}

include 'includes/connection.php';

// Get filters
$subject = $_GET['subject'] ?? '';
$month   = $_GET['month'] ?? '';
$year    = $_GET['year'] ?? '';
$student = $_GET['student'] ?? '';

// Query student data
$studentQuery = "SELECT DISTINCT stud_id, stud_name FROM att WHERE 1=1";
if ($subject) $studentQuery .= " AND subject_name = '$subject'";
if ($month)   $studentQuery .= " AND MONTH(class_date) = '$month'";
if ($year)    $studentQuery .= " AND YEAR(class_date) = '$year'";
if ($student) $studentQuery .= " AND stud_name LIKE '%$student%'";

$studentResult = mysqli_query($con, $studentQuery);
$students = [];
while ($row = mysqli_fetch_assoc($studentResult)) {
    $students[$row['stud_id']] = $row['stud_name'];
}

// Days in month
$daysInMonth = ($month && $year) ? cal_days_in_month(CAL_GREGORIAN, $month, $year) : 31;
$dateHeaders = range(1, $daysInMonth);

// Attendance data
$attendanceMap = [];
$attQuery = "SELECT stud_id, DAY(class_date) as day, status FROM att WHERE 1=1";
if ($subject) $attQuery .= " AND subject_name = '$subject'";
if ($month)   $attQuery .= " AND MONTH(class_date) = '$month'";
if ($year)    $attQuery .= " AND YEAR(class_date) = '$year'";
if ($student) $attQuery .= " AND stud_name LIKE '%$student%'";
$attResult = mysqli_query($con, $attQuery);
while ($row = mysqli_fetch_assoc($attResult)) {
    $status = strtoupper($row['status']);
    $attendanceMap[$row['stud_id']][$row['day']] = ($status === 'PRESENT') ? 'P' : (($status === 'ABSENT') ? 'A' : '');
}

// Send headers for Excel download
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=attendance_report.xls");

// Start output buffer for Excel table
echo "<table border='1'>";
echo "<tr>
        <th>Student ID</th>
        <th>Student Name</th>";
foreach ($dateHeaders as $day) {
    echo "<th>$day</th>";
}
echo "<th>P</th><th>A</th><th>Attendance %</th><th>Risk</th></tr>";

foreach ($students as $id => $name) {
    echo "<tr>";
    echo "<td>$id</td>";
    echo "<td>$name</td>";

    $p = $a = 0;
    foreach ($dateHeaders as $d) {
        $mark = $attendanceMap[$id][$d] ?? '';
        if ($mark === 'P') $p++;
        if ($mark === 'A') $a++;
        echo "<td>$mark</td>";
    }
    //Algorithm
    $attendance_percent = ($p + $a > 0) ? ($p / ($p + $a)) * 100 : 0;
    $b0 = -37.5;
    $b1 = 0.5;
    $z = $b0 + ($b1 * $attendance_percent);
    $prob = 1 / (1 + exp(-$z));
    $risk_status = ($prob >= 0.5) ? "Safe" : "At Risk";

    echo "<td>$p</td><td>$a</td><td>" . number_format($attendance_percent, 2) . "%</td><td>$risk_status</td>";
    echo "</tr>";
}

echo "</table>";
exit;
?>
