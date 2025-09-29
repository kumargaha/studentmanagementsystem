<?php
session_start();
require_once "./includes/connection.php";

if (isset($_SESSION['username'])) {
    header('Location: index.php');
    exit();
}

if (isset($_POST['submit'])) {
    $username = mysqli_real_escape_string($con, $_POST['username']);
    $password = $_POST['password'];
    $cpassword = $_POST['confirmpassword'];

    $type = 1; // default user_type

    $uppercase = preg_match('@[A-Z]@', $password);
    $lowercase = preg_match('@[a-z]@', $password);
    $number    = preg_match('@[0-9]@', $password);

    if (strlen($username) < 4) {
        $error = 'Username must be at least 4 characters';
    } elseif (!preg_match("/^[a-zA-Z]*$/", $username)) {
        $error = "Only letters with no white space are allowed in username";
    } elseif (strlen($password) < 5) {
        $error = 'Password must be at least 5 characters';
    } elseif (!$uppercase || !$lowercase || !$number) {
        $error = "Password must contain at least one number, one uppercase and one lowercase letter";
    } elseif ($cpassword !== $password) {
        $error = "Password does not match";
    } else {
        // Check if username already exists
        $checkUserQuery = "SELECT * FROM users WHERE username = '{$username}'";
        $result = mysqli_query($con, $checkUserQuery);
        if (mysqli_num_rows($result) > 0) {
            $error = "Username already exists";
        } else {
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $insertQuery = "INSERT INTO users (username, password, user_type) VALUES ('{$username}', '{$hashed_password}', {$type})";
            if (mysqli_query($con, $insertQuery)) {
                header('Location: login.php');
                exit();
            } else {
                $error = "Registration failed, please try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Register</title>
    <link rel="stylesheet" href="styles/style.css" />
</head>
<body style="background-image:url(assets/img/back.png);background-repeat:no-repeat;background-size:cover;height:80vh;width:100%;">

<form action="" method="post">
    <div class="container">
        <h1>Create an Account</h1>
        <?php if (isset($error)) : ?>
            <p style="color:red;"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <label for="username"><b>Username</b></label>
        <input type="text" name="username" placeholder="Username" required value="<?php echo isset($username) ? htmlspecialchars($username) : ''; ?>" />

        <label for="password"><b>Password</b></label>
        <input type="password" name="password" placeholder="Password" required />

        <label for="confirmpassword"><b>Confirm Password</b></label>
        <input type="password" name="confirmpassword" placeholder="Confirm Password" required />

        <input type="submit" name="submit" value="Sign Up" />

        <div class="signup-link">Already have an account? <a href="login.php">Login now</a></div>
    </div>
</form>

</body>
</html>
