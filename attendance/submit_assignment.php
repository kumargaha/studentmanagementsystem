<!DOCTYPE html>
<html lang="en">
<?php
session_start();
if (!isset($_SESSION['id'])) {
    header('location:login.php');
}
include 'includes/connection.php';
if ($_SESSION['user_type'] != 1) {
    header('Location: login.php');
    exit;
}

$stud_id = $_SESSION['id'];
$error = '';
$success = '';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit']) && isset($_FILES['assignment_file'])) {
    $assignment_id = intval($_POST['assignment_id']);
    
    // Check if this assignment was already submitted by this student
    $stmt_check = $con->prepare("SELECT id FROM assignment_submissions WHERE assignment_id = ? AND stud_id = ?");
    $stmt_check->bind_param("ii", $assignment_id, $stud_id);
    $stmt_check->execute();
    $stmt_check->store_result();

    if ($stmt_check->num_rows > 0) {
        $error = "You have already submitted this assignment.";
    } else {
        // file upload and insert
        $upload_dir = 'uploads/assignments/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $file_name = basename($_FILES['assignment_file']['name']);
        $ext = pathinfo($file_name, PATHINFO_EXTENSION);
        $new_file_name = 'submission_' . time() . '_' . $stud_id . '.' . $ext;
        $target_path = $upload_dir . $new_file_name;

        if (move_uploaded_file($_FILES['assignment_file']['tmp_name'], $target_path)) {
            $submitted_at = date("Y-m-d H:i:s");
            $stmt = $con->prepare("INSERT INTO assignment_submissions (assignment_id, stud_id, file_path, submitted_at) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("iiss", $assignment_id, $stud_id, $target_path, $submitted_at);
            if ($stmt->execute()) {
                $success = "Assignment submitted successfully.";
            } else {
                $error = "Database error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $error = "Failed to upload file.";
        }
    }
}

// Fetch available assignments (not past due)
$assignments = [];
$query = "SELECT id, title, subject_name, due_date, description FROM assignments WHERE due_date >= CURDATE() ORDER BY due_date";
$result = $con->query($query);
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $assignments[] = $row;
    }
}
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="styles/index.css">
    <style>
        .blur-form {
            max-width: 600px;
            margin: 40px auto;
            padding: 30px 40px;

            background: rgba(255, 255, 255, 0.2);
       
            border-radius: 15px;

            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);

            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.12);


            border: 1px solid rgba(255, 255, 255, 0.25);

            color: #222;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Heading inside the container */
        .blur-form h2 {
            text-align: center;
            margin-bottom: 25px;
            font-weight: 700;
            color: #111;
        }

        /* Form elements styling */
        .blur-form form label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            color: #111;
        }

        .blur-form form select,
        .blur-form form input[type="file"],
        .blur-form form input[type="submit"] {
            width: 100%;
            padding: 12px 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            border: 1.8px solid rgba(0, 0, 0, 0.15);
            font-size: 16px;
            transition: border-color 0.3s ease;
            background: rgba(255, 255, 255, 0.6);
            backdrop-filter: blur(5px);
        }

        .blur-form form select:focus,
        .blur-form form input[type="file"]:focus {
            outline: none;
            border-color: #3a86ff;
            box-shadow: 0 0 8px #3a86ff;
            background: rgba(255, 255, 255, 0.9);
        }

        .blur-form form input[type="submit"] {
            background-color: #3a86ff;
            color: white;
            font-weight: 700;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .blur-form form input[type="submit"]:hover {
            background-color: #2a5bbf;
        }

        /* Success and error messages */
        .error,
        .success {
            padding: 12px 18px;
            margin-bottom: 20px;
            border-radius: 8px;
            font-weight: 600;
            text-align: center;
        }

        .error {
            background-color: rgba(255, 77, 79, 0.15);
            color: #ff4d4f;
            border: 1px solid #ff4d4f;
        }

        .success {
            background-color: rgba(46, 204, 113, 0.15);
            color: #2ecc71;
            border: 1px solid #2ecc71;
        }
    </style>
</head>

<body style="background-image:url(assets/img/back.png);background-repeat:no-repeat;background-size:cover;;height:80vh;width:100%;">


    <header>
        <nav>
            <div class="logo"><img src="assets/img/merologo.png " style="width: 50px; height: 50px;"></div>
            <div class="menu">
                <a href="index.php">Home</a>
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


        <div class="blur-form">
            <h2>Submit Assignment</h2>

            <?php if ($error): ?>
                <div class="error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <?php if ($success): ?>
                <div class="success"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>

            <?php if (!empty($assignments)): ?>
                <form method="POST" enctype="multipart/form-data">
                    <label for="assignment_id">Select Assignment:</label>
                    <select name="assignment_id" id="assignment_id" required>
                        <option value="">-- Select Assignment --</option>
                        <?php foreach ($assignments as $a): ?>
                            <option value="<?= $a['id'] ?>">
                                <?= htmlspecialchars($a['title']) ?> (<?= $a['subject_name'] ?>) - Due: <?= $a['due_date'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <label for="assignment_file">Upload PDF:</label>
                    <input type="file" name="assignment_file" id="assignment_file" accept=".pdf" required />

                    <input type="submit" name="submit" value="Submit Assignment" />
                </form>
            <?php else: ?>
                <p>No active assignments available right now.</p>
            <?php endif; ?>
        </div>



    </header>
</body>

</html>