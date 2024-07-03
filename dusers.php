<?php
session_start();
include './config/connection.php';
include_once './includes/header.php';

// Function to sanitize user inputs
function sanitize($conn, $input)
{
    return mysqli_real_escape_string($conn, htmlspecialchars(strip_tags(trim($input))));
}

// Handle delete operation for users
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_user'])) {
    $user_id = sanitize($conn, $_POST['user_id']);
    $delete_query = "DELETE FROM users WHERE id = $user_id";
    if ($conn->query($delete_query)) {
        $_SESSION['message'] = "User deleted successfully!";
        header('Location: users.php');
        exit();
    } else {
        $_SESSION['error'] = "Error deleting user: " . $conn->error;
    }
}

// Fetch data for display
$users_query = "SELECT * FROM users";
$users_result = $conn->query($users_query);
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>Users</h2>

        <?php if (isset($_SESSION['message'])): ?>
            <p class="message"><?= $_SESSION['message']; ?></p>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <p class="error"><?= $_SESSION['error']; ?></p>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <a href="add_user.php" class="action-buttons add-btn">Add User</a>
        <table>
            <tr>
                <th>ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
            <?php $user_counter = 1; ?>
            <?php while ($user = $users_result->fetch_assoc()): ?>
                <tr>
                    <td><?= $user_counter++; ?></td>
                    <td><?= $user['firstName']; ?></td>
                    <td><?= $user['lastName']; ?></td>
                    <td><?= $user['email']; ?></td>
                    <td>
                        <div class="action-buttons">
                            <a href="edit_user.php?id=<?= $user['id']; ?>" class="edit-btn">Edit</a>
                            <form action="" method="POST">
                                <input type="hidden" name="user_id" value="<?= $user['id']; ?>">
                                <button type="submit" name="delete_user" class="delete-btn">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>
