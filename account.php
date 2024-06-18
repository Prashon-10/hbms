<?php
session_start();
include 'config/connection.php';

// Redirect to login if session email is not set
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$user_email = $_SESSION['email'];

// Fetch user data based on session email
$query = "SELECT * FROM users WHERE email='$user_email'";
$result = $conn->query($query);

// If user not found, display an error message and exit
if ($result && $result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $is_admin = $user['is_admin']; // Check if user is admin
} else {
    echo "User not found.";
    exit();
}

// Check if the logged-in user is an admin
if ($is_admin != 1) {
    echo "Access denied. You are not authorized to view this page.";
    exit();
}

$message = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update'])) {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $fullname = $_POST['fullname'];
        $phone = $_POST['phone'];
        $address = $_POST['address'];
        $password = $_POST['password'];

        // Update password only if a new password is provided
        if (!empty($password)) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $query = "UPDATE users SET username='$username', email='$email', password='$hashed_password', fullname='$fullname', phone='$phone', address='$address' WHERE email='$user_email'";
        } else {
            $query = "UPDATE users SET username='$username', email='$email', fullname='$fullname', phone='$phone', address='$address' WHERE email='$user_email'";
        }

        if ($conn->query($query) === TRUE) {
            $message = "Profile updated successfully";
            $_SESSION['email'] = $email; // Update session email if email was changed
        } else {
            $message = "Error updating profile: " . $conn->error;
        }
    } elseif (isset($_POST['delete'])) {
        // Delete account logic (Optional)
        // $query = "DELETE FROM users WHERE email='$user_email'";
        // if ($conn->query($query) === TRUE) {
        //     session_destroy();
        //     header("Location: index.php");
        //     exit();
        // } else {
        //     $message = "Error deleting account: " . $conn->error;
        // }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style/account.css">
</head>

<body>
    <?php include 'includes/header.php'; ?>
    <div class="admin-dashboard">
        <h2>Admin Dashboard</h2>
        <?php if (!empty($message)) {
            echo '<p class="message">' . $message . '</p>';
        } ?>
        <p>Welcome, <?php echo htmlspecialchars($user['username']); ?>!</p>
        <p>This is the admin dashboard.</p>
        <!-- Admin-specific actions here -->
        <form method="POST" action="account.php">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>"
                required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>"
                required>

            <label for="fullname">Full Name:</label>
            <input type="text" id="fullname" name="fullname" value="<?php echo htmlspecialchars($user['fullname']); ?>"
                required>

            <label for="phone">Phone:</label>
            <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" required>

            <label for="address">Address:</label>
            <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($user['address']); ?>"
                required>

            <label for="password">New Password (leave blank to keep current):</label>
            <input type="password" id="password" name="password">

            <input type="submit" name="update" value="Update Profile">
            <!-- <input type="submit" name="delete" value="Delete Account"
                onclick="return confirm('Are you sure you want to delete your account?');"> -->
        </form>
    </div>
    <?php include 'includes/footer.php'; ?>
</body>

</html>

<?php
$conn->close(); // Close database connection
?>
