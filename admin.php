<?php
session_start();
include './config/connection.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['login'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Validate username (optional: add more robust validation)
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
            $error = 'Invalid username format';
        } else {
            $query = "SELECT * FROM admins WHERE username=?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 1) {
                $admin = $result->fetch_assoc();
                if (password_verify($password, $admin['password'])) {
                    $_SESSION['admin'] = $username;
                    header('Location: dashboard.php');
                    exit();
                } else {
                    $error = 'Invalid username or password';
                }
            } else {
                $error = 'Invalid username or password';
            }
        }
    } elseif (isset($_POST['signup'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Validate username (optional: add more robust validation)
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
            $error = 'Invalid username format';
        } else {
            // Check if username already exists
            $query = "SELECT * FROM admins WHERE username=?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $error = 'Username already taken';
            } else {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $insert_query = "INSERT INTO admins (username, password) VALUES (?, ?)";
                $insert_stmt = $conn->prepare($insert_query);
                $insert_stmt->bind_param("ss", $username, $hashedPassword);
                if ($insert_stmt->execute()) {
                    $_SESSION['admin'] = $username;
                    header('Location: dashboard.php');
                    exit();
                } else {
                    $error = 'Signup failed';
                }
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
    <title>Admin Login/Signup</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <div class="form-container">
        <h2>Admin Login/Signup</h2>
        <?php if ($error): ?>
            <p class="error"><?= $error ?></p>
        <?php endif; ?>
        <form action="admin.php" method="POST">
            <label for="username">Username</label>
            <input type="text" name="username" id="username" required>
            <label for="password">Password</label>
            <input type="password" name="password" id="password" required>
            <button type="submit" name="login">Login</button>
            <button type="submit" name="signup">Signup</button>
        </form>
    </div>
</body>

</html>