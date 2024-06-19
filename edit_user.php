<?php
session_start();
include './config/connection.php';
include_once './includes/header.php';

// Function to sanitize user inputs
function sanitize($conn, $input)
{
    return mysqli_real_escape_string($conn, htmlspecialchars(strip_tags(trim($input))));
}

// Fetch user details if ID is provided
$user_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($user_id > 0) {
    $query = "SELECT * FROM users WHERE id = $user_id";
    $result = $conn->query($query);
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
    } else {
        $_SESSION['error'] = "User not found!";
        header('Location: dashboard.php');
        exit();
    }
} else {
    $_SESSION['error'] = "Invalid user ID!";
    header('Location: dashboard.php');
    exit();
}

// Handle form submission for updating user
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = sanitize($conn, $_POST['user_id']);
    $firstName = sanitize($conn, $_POST['firstName']);
    $lastName = sanitize($conn, $_POST['lastName']);
    $email = sanitize($conn, $_POST['email']);

    $update_query = "UPDATE users SET firstName = '$firstName', lastName = '$lastName', email = '$email' WHERE id = $user_id";
    if ($conn->query($update_query)) {
        $_SESSION['message'] = "User updated successfully!";
        header('Location: dashboard.php');
        exit();
    } else {
        $_SESSION['error'] = "Error updating user: " . $conn->error;
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <!-- <link rel="stylesheet" href="styles.css"> -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        form {
            margin-bottom: 20px;
        }

        form label {
            display: block;
            margin-bottom: 8px;
        }

        form input[type="text"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 10px;
        }

        form button {
            background-color: #5cb85c;
            color: #fff;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 4px;
        }

        form button:hover {
            background-color: #4cae4c;
        }

        .error-message {
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
    <div class="container">
        <h2>Edit User</h2>

        <?php if (isset($_SESSION['error'])): ?>
            <p class="error-message"><?= $_SESSION['error']; ?></p>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <form action="" method="POST">
            <input type="hidden" name="user_id" value="<?= $user['id']; ?>">
            <label for="firstName">First Name:</label>
            <input type="text" id="firstName" name="firstName" value="<?= isset($user['firstName']) ? $user['firstName'] : ''; ?>" required>

            <label for="lastName">Last Name:</label>
            <input type="text" id="lastName" name="lastName" value="<?= isset($user['lastName']) ? $user['lastName'] : ''; ?>" required>

            <label for="email">Email:</label>
            <input type="text" id="email" name="email" value="<?= $user['email']; ?>" required>

            <button type="submit">Update User</button>
        </form>
    </div>
</body>

</html>
