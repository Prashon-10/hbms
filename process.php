<?php
// Establish database connection
$conn = new mysqli("localhost", "root", "", "hotelbooking");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// LOGIN PROCESS
// LOGIN PROCESS
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        // Correct login credentials
        echo "Login successful!";
        
        // Redirect to a new page (change 'new_page.php' to the desired page)
        header("Location: index.php");
        exit(); // Stop further execution
    } else {
        // Invalid email or password
        echo "Invalid email or password";
    }

    $stmt->close();
}


/// SIGNUP PROCESS
if (isset($_POST['signup'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $password);

    if ($stmt->execute()) {
        echo "Signup successful!";
        header("Refresh: 1; URL=login.php");
        exit();
    } else {
        echo "Error during signup: " . $stmt->error;
    }

    $stmt->close();
}

?>