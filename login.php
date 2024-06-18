<?php
session_start();
include ("./config/connection.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['signIn'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Check if the user is an admin
        $admin_query = "SELECT * FROM admins WHERE username = ?";
        $admin_stmt = $conn->prepare($admin_query);
        $admin_stmt->bind_param("s", $username);
        $admin_stmt->execute();
        $admin_result = $admin_stmt->get_result();
        $admin = $admin_result->fetch_assoc();

        if ($admin && password_verify($password, $admin['password'])) {
            // Admin login successful
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $username;
            header("Location: dashboard.php");
            exit();
        } else {
            // Check if the user is a regular user
            $user_query = "SELECT * FROM users WHERE email = ?";
            $user_stmt = $conn->prepare($user_query);
            $user_stmt->bind_param("s", $username);
            $user_stmt->execute();
            $user_result = $user_stmt->get_result();
            $user = $user_result->fetch_assoc();

            if ($user && password_verify($password, $user['password'])) {
                // Regular user login successful
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['email'] = $username; // Store email in session for regular user
                header("Location: index.php");
                exit();
            } else {
                $message = "Invalid username or password.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register & Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="./style/register.css">
</head>

<body>
    <div class="container" id="signup" style="display:none;">
        <h1 class="form-title">Register</h1>
        <form method="post" action="register.php">
            <div class="input-group">
                <i class="fas fa-user"></i>
                <input type="text" name="fName" id="fName" placeholder="First Name" required>
                <label for="fName">First Name</label>
            </div>
            <div class="input-group">
                <i class="fas fa-user"></i>
                <input type="text" name="lName" id="lName" placeholder="Last Name" required>
                <label for="lName">Last Name</label>
            </div>
            <div class="input-group">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" id="email" placeholder="Email" required>
                <label for="email">Email</label>
            </div>
            <div class="input-group">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" id="password" placeholder="Password" required>
                <label for="password">Password</label>
            </div>
            <input type="submit" class="btn" value="Sign Up" name="signUp">
        </form>
        <p class="or">
            ----------or--------
        </p>
        <div class="icons">
            <i class="fab fa-google"></i>
            <i class="fab fa-facebook"></i>
        </div>
        <div class="links">
            <p>Already Have Account ?</p>
            <button id="signInButton">Sign In</button>
        </div>
    </div>

    <div class="container" id="signIn">
        <h1 class="form-title">Sign In</h1>
        <?php if (!empty($message)) { echo '<p class="message">' . $message . '</p>'; } ?>
        <form method="post" action="">
            <div class="input-group">
                <i class="fas fa-user"></i>
                <input type="text" name="username" id="username" placeholder="Username" required>
                <label for="username">Username</label>
            </div>
            <div class="input-group">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" id="password" placeholder="Password" required>
                <label for="password">Password</label>
            </div>
            <p class="recover">
                <a href="#">Recover Password</a>
            </p>
            <input type="submit" class="btn" value="Sign In" name="signIn">
        </form>
        <p class="or">
            ----------or--------
        </p>
        <div class="icons">
            <i class="fab fa-google"></i>
            <i class="fab fa-facebook"></i>
        </div>
        <div class="links">
            <p>Don't have account yet?</p>
            <button id="signUpButton">Sign Up</button>
        </div>
    </div>
    <script src="./js/login-reg.js"></script>
</body>

</html>
