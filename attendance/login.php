<?php
session_start();
require_once "./includes/connection.php";

if (isset($_SESSION['id'])) {
  
    if ($_SESSION['user_type'] == 1) {
        header('Location: studentlog.php');
    } else {
        header('Location: teacher.php');
    }
    exit();
}

if (isset($_POST['Login'])) {
    $username = mysqli_real_escape_string($con, $_POST['username']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username = '{$username}'";
    $result = mysqli_query($con, $sql);

    if (mysqli_num_rows($result) === 1) {
        $user = mysqli_fetch_assoc($result);

        if (password_verify($password, $user['password'])) {
          
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_type'] = $user['user_type'];
            $_SESSION['id'] = $user['user_id'];

            if ($user['user_type'] == 1) {
                header('Location: studentlog.php');
            } else {
                header('Location: teacher.php');
            }
            exit();
        } else {
            $error = "Invalid username or password";
        }
    } else {
        $error = "Invalid username or password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login</title>
    <link rel="stylesheet" href="styles/style.css" />
</head>
<body style="background-image:url(assets/img/back.png);background-repeat:no-repeat;background-size:cover;height:80vh;width:100%;">

<form action="" method="post">
    <div class="container">
        <h1>Login to your account</h1>
        <?php if (isset($error)) : ?>
            <p style="color:red;"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <label for="username"><b>Username</b></label>
        <input type="text" name="username" placeholder="Username" required />

        <label for="password"><b>Password</b></label>
        <input type="password" name="password" placeholder="Password" required />

        <input type="submit" name="Login" value="Login" />

        <div class="signup-link">Don't have an account? <a href="register.php">Register now</a></div>
    </div>
</form>

</body>
</html>
