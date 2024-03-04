<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hotelbooking";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function redirect($location) {
    header("Location: " . $location);
    exit();
}

function showError($errorCode) {
    switch ($errorCode) {
        case 1:
            return "Invalid email or password.";
        case 2:
            return "Error during registration. Please try again.";
        // Add more error cases as needed
        default:
            return "Unknown error.";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["email"]) && isset($_POST["password"])) {
        $email = $_POST["email"];
        $password = $_POST["password"];

        // Input validation (you can add more validation as needed)
        if (empty($email) || empty($password)) {
            redirect("login.php?error=1");
        }

        $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE email=?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row["password"])) {
                $_SESSION["username"] = $row["username"];
                redirect("index.php");
            } else {
                redirect("login.php?error=1");
            }
        } else {
            redirect("login.php?error=1");
        }
    } elseif (isset($_POST["username"]) && isset($_POST["email"]) && isset($_POST["password"])) {
        $username = $_POST["username"];
        $email = $_POST["email"];
        $password = $_POST["password"];

        // Input validation (you can add more validation as needed)
        if (empty($username) || empty($email) || empty($password)) {
            redirect("signup.php?error=2");
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $hashedPassword);

        if ($stmt->execute()) {
            $_SESSION["username"] = $username;
            redirect("index.php");
        } else {
            redirect("signup.php?error=2");
        }
    }
}

$conn->close();
?>
