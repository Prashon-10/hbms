<?php
session_start();
include './config/connection.php';

// Check if admin is logged in
if (!isset($_SESSION['admin'])) {
    header('Location: admin.php');
    exit();
}

// Function to sanitize user inputs
function sanitize($conn, $input)
{
    return mysqli_real_escape_string($conn, htmlspecialchars(strip_tags(trim($input))));
}

// Handle delete operations for rooms, users, and bookings
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_room'])) {
        $room_id = sanitize($conn, $_POST['room_id']);
        $delete_query = "DELETE FROM rooms WHERE id = $room_id";
        if ($conn->query($delete_query)) {
            // Redirect with success message
            $_SESSION['message'] = "Room deleted successfully!";
            header('Location: dashboard.php');
            exit();
        } else {
            $_SESSION['error'] = "Error deleting room: " . $conn->error;
        }
    } elseif (isset($_POST['delete_user'])) {
        $user_id = sanitize($conn, $_POST['user_id']);
        // Before deleting user, delete associated reservations (handled by ON DELETE CASCADE)
        $delete_query = "DELETE FROM users WHERE id = $user_id";
        if ($conn->query($delete_query)) {
            // Redirect with success message
            $_SESSION['message'] = "User deleted successfully!";
            header('Location: dashboard.php');
            exit();
        } else {
            $_SESSION['error'] = "Error deleting user: " . $conn->error;
        }
    } elseif (isset($_POST['delete_booking'])) {
        $booking_id = sanitize($conn, $_POST['booking_id']);
        $delete_query = "DELETE FROM reservations WHERE id = $booking_id";
        if ($conn->query($delete_query)) {
            // Redirect with success message
            $_SESSION['message'] = "Booking deleted successfully!";
            header('Location: dashboard.php');
            exit();
        } else {
            $_SESSION['error'] = "Error deleting booking: " . $conn->error;
        }
    }
}

// Fetch data for display
$rooms_query = "SELECT * FROM rooms";
$users_query = "SELECT * FROM users";
$bookings_query = "SELECT * FROM reservations";

$rooms_result = $conn->query($rooms_query);
$users_result = $conn->query($users_query);
$bookings_result = $conn->query($bookings_query);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1200px;
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

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table th,
        table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        table th {
            background-color: #f2f2f2;
        }

        .message {
            background-color: #dff0d8;
            color: #3c763d;
            border: 1px solid #d6e9c6;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 4px;
        }

        .error {
            background-color: #f2dede;
            color: #a94442;
            border: 1px solid #ebccd1;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 4px;
        }

        form {
            display: inline-block;
        }

        form button {
            background-color: #d9534f;
            color: #fff;
            border: none;
            padding: 6px 12px;
            cursor: pointer;
            border-radius: 4px;
        }

        form button:hover {
            background-color: #c9302c;
        }

        .add-button {
            background-color: #5cb85c;
            color: #fff;
            border: none;
            padding: 10px 15px;
            cursor: pointer;
            border-radius: 4px;
            text-decoration: none;
            display: inline-block;
            margin-bottom: 10px;
        }

        .add-button:hover {
            background-color: #4cae4c;
        }

        @media only screen and (max-width: 768px) {

            /* Responsive design adjustments */
            .container {
                padding: 10px;
            }

            table {
                font-size: 14px;
            }

            table th,
            table td {
                padding: 6px;
            }

            .add-button,
            form button {
                padding: 8px 12px;
                font-size: 14px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Dashboard</h2>

        <?php if (isset($_SESSION['message'])): ?>
            <p class="message"><?= $_SESSION['message']; ?></p>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <p class="error"><?= $_SESSION['error']; ?></p>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <h3>Manage Rooms</h3>
        <a href="add_room.php" class="add-button">Add Room</a>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Type</th>
                    <th>Price</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($room = $rooms_result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $room['id']; ?></td>
                        <td><?= $room['type']; ?></td>
                        <td>$<?= $room['price']; ?></td>
                        <td>
                            <form action="" method="POST">
                                <input type="hidden" name="room_id" value="<?= $room['id']; ?>">
                                <button type="submit" name="delete_room">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <h3>Manage Users</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Email</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($user = $users_result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $user['id']; ?></td>
                        <td><?= $user['email']; ?></td>
                        <td>
                            <form action="" method="POST">
                                <input type="hidden" name="user_id" value="<?= $user['id']; ?>">
                                <button type="submit" name="delete_user">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <h3>Manage Bookings</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User ID</th>
                    <th>Room Type</th>
                    <th>Check-in Date</th>
                    <th>Check-out Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($booking = $bookings_result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $booking['id']; ?></td>
                        <td><?= $booking['user_id']; ?></td>
                        <td><?= $booking['room_type']; ?></td>
                        <td><?= $booking['check_in_date']; ?></td>
                        <td><?= $booking['check_out_date']; ?></td>
                        <td>
                            <form action="" method="POST">
                                <input type="hidden" name="booking_id" value="<?= $booking['id']; ?>">
                                <button type="submit" name="delete_booking">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>

</html>