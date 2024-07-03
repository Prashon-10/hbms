<?php
session_start();

if ($_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

include './config/connection.php';
include_once './includes/header.php';

function sanitize($conn, $input)
{
    return mysqli_real_escape_string($conn, htmlspecialchars(strip_tags(trim($input))));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_user'])) {
        $first_name = sanitize($conn, $_POST['first_name']);
        $last_name = sanitize($conn, $_POST['last_name']);
        $email = sanitize($conn, $_POST['email']);
        $phone = sanitize($conn, $_POST['phone']);
        $dob = sanitize($conn, $_POST['dob']);
        $gender = sanitize($conn, $_POST['gender']);
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        // Handle profile image upload
        $profile_image = $_FILES['profile_image']['name'];
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($profile_image);
        move_uploaded_file($_FILES['profile_image']['tmp_name'], $target_file);

        $insert_query = "INSERT INTO users (first_name, last_name, email, phone, dob, gender, password, profile_image) 
                         VALUES ('$first_name', '$last_name', '$email', '$phone', '$dob', '$gender', '$password', '$profile_image')";

        if ($conn->query($insert_query)) {
            $_SESSION['message'] = "User added successfully!";
            header('Location: dashboard.php');
            exit();
        } else {
            $_SESSION['error'] = "Error adding user: " . $conn->error;
        }
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register User</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>Register User</h2>
        <?php if (isset($_SESSION['message'])): ?>
            <p class="message"><?= $_SESSION['message']; ?></p>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <p class="error"><?= $_SESSION['error']; ?></p>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <form action="" method="POST" enctype="multipart/form-data">
            <label for="first_name">First Name:</label>
            <input type="text" id="first_name" name="first_name" required>
            
            <label for="last_name">Last Name:</label>
            <input type="text" id="last_name" name="last_name" required>
            
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            
            <label for="phone">Phone:</label>
            <input type="text" id="phone" name="phone" required>
            
            <label for="dob"></label>
            <input type="date" id="dob" name="dob" required>
            
            <label for="gender">Gender:</label>
            <select id="gender" name="gender" required>
                <option value="male">Male</option>
                <option value="female">Female</option>
                <option value="other">Other</option>
            </select>
            
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            
            <label for="profile_image">Profile Image:</label>
            <input type="file" id="profile_image" name="profile_image" required>
            
            <button type="submit" name="add_user">Register</button>
        </form>
    </div>
</body>
</html>
