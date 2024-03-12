<?php
// Include the database connection file with the correct path
include_once "./config/connection.php";

// Check if the connection is successful
if ($mysqli->connect_error) {
    die('Connection failed: ' . $mysqli->connect_error);
}

// Check if the signup form is submitted
if (isset($_POST['signup'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";

    if ($mysqli->query($query) === TRUE) {
        // Set a session variable for the success message
        session_start();
        $_SESSION['registration_success'] = "User registered successfully!";

        // Redirect to login page after successful registration
        header("Location: login.php");
        exit();
    } else {
        echo "Error: " . $query . "<br>" . $mysqli->error;
    }
}

// Check if the login form is submitted
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "SELECT id, username, password FROM users WHERE email='$email'";
    $result = $mysqli->query($query);

    if ($result && $result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            echo "Login successful! Welcome, " . $row['username'];
            // Redirect to the home page or perform other actions
        } else {
            echo "Incorrect password. Please try again.";
        }
    } else {
        echo "User not found. Please check your email and try again.";
    }
}

// Close the database connection
$mysqli->close();
?>