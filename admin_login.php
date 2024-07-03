<?php
session_start();
include './config/connection.php';


// admin123@gmail.com          admin123

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Query to get user data
    $query = "SELECT * FROM admins WHERE email = '$email' and password='" . md5($password) . "';";
    $result = $conn->query($query);
    //  echo $query; die;
    if ($result->num_rows > 0) {
        // Verify the password
        $user = $result->fetch_assoc();
        $_SESSION['admin_id'] = $user['adid'];
        $_SESSION['admin_email'] = $user['email'];
        $_SESSION['role'] = 'admin';
        header('Location: dashboard.php');
        exit();
    } else {
        $_SESSION['error'] = "Invalid email or password!";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f0f0f0;
            font-family: Arial, sans-serif;
        }

        .login-container {
            background-color: #fff;
            padding: 21px 43px 39px 35px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }

        .login-container h2 {
            margin-bottom: 20px;
            text-align: center;
        }

        .login-container input[type="email"],
        .login-container input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .login-container button {
            width: 108%;
            padding: 10px;
            background-color: #337ab7;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            position: relative;
        }

        .login-container button:hover {
            background-color: #286090;
        }

        .error {
            background-color: #f2dede;
            color: #a94442;
            border: 1px solid #ebccd1;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 4px;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <h2>Admin Login</h2>
        <?php if (isset($_SESSION['error'])): ?>
            <p class="error"><?= $_SESSION['error']; ?></p>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
        <form action="" method="POST">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
    </div>
</body>

</html>