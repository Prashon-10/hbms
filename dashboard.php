<?php
session_start();
if ($_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}
include './config/connection.php';
include_once './includes/header.php';

// Function to sanitize user inputs
function sanitize($conn, $input)
{
    return mysqli_real_escape_string($conn, htmlspecialchars(strip_tags(trim($input))));
}

// Handle various operations
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_room'])) {
        $room_id = sanitize($conn, $_POST['room_id']);
        $delete_query = "DELETE FROM rooms WHERE id = $room_id";
        if ($conn->query($delete_query)) {
            $_SESSION['message'] = "Room deleted successfully!";
            header('Location: dashboard.php');
            exit();
        } else {
            $_SESSION['error'] = "Error deleting room: " . $conn->error;
        }
    } elseif (isset($_POST['delete_user'])) {
        $user_id = sanitize($conn, $_POST['user_id']);
        $delete_query = "DELETE FROM users WHERE id = $user_id";
        if ($conn->query($delete_query)) {
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
            $_SESSION['message'] = "Booking deleted successfully!";
            header('Location: dashboard.php');
            exit();
        } else {
            $_SESSION['error'] = "Error deleting booking: " . $conn->error;
        }
    } elseif (isset($_POST['accept_booking'])) {
        $booking_id = sanitize($conn, $_POST['booking_id']);
        $accept_query = "UPDATE reservations SET status='accepted' WHERE id = $booking_id";
        if ($conn->query($accept_query)) {
            $_SESSION['message'] = "Booking accepted successfully!";
            header('Location: dashboard.php');
            exit();
        } else {
            $_SESSION['error'] = "Error accepting booking: " . $conn->error;
        }
    } elseif (isset($_POST['decline_booking'])) {
        $booking_id = sanitize($conn, $_POST['booking_id']);
        $decline_query = "UPDATE reservations SET status='declined' WHERE id = $booking_id";
        if ($conn->query($decline_query)) {
            $_SESSION['message'] = "Booking declined successfully!";
            header('Location: dashboard.php');
            exit();
        } else {
            $_SESSION['error'] = "Error declining booking: " . $conn->error;
        }
    } elseif (isset($_POST['mark_paid'])) {
        $booking_id = sanitize($conn, $_POST['booking_id']);
        $mark_paid_query = "UPDATE reservations SET payment_status='paid' WHERE id = $booking_id";
        if ($conn->query($mark_paid_query)) {
            $_SESSION['message'] = "Payment marked as paid!";
            header('Location: dashboard.php');
            exit();
        } else {
            $_SESSION['error'] = "Error marking payment: " . $conn->error;
        }
    } elseif (isset($_POST['cancel_booking'])) {
        $booking_id = sanitize($conn, $_POST['booking_id']);
        $cancel_query = "UPDATE reservations SET cancellation_status='cancelled' WHERE id = $booking_id";
        if ($conn->query($cancel_query)) {
            $_SESSION['message'] = "Booking cancelled successfully!";
            header('Location: dashboard.php');
            exit();
        } else {
            $_SESSION['error'] = "Error cancelling booking: " . $conn->error;
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

// Fetch total bookings and total amount collected
$total_bookings_query = "SELECT COUNT(*) AS total_bookings, SUM(price) AS total_amount FROM reservations";
$total_result = $conn->query($total_bookings_query);
$total_data = $total_result->fetch_assoc();

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
            max-width: 1440px;
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

        #summary {
            font-size: 27px;
            font-weight: bold;
            color: purple;
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

        .action-buttons {
            display: flex;
            gap: 10px;
        }

        .action-buttons form {
            display: inline;
        }

        .action-buttons button,
        .action-buttons a {
            padding: 5px 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            color: white;
            height: 40px;
        }

        .action-buttons .edit-btn {
            background-color: #5cb85c;
        }

        .action-buttons .delete-btn {
            background-color: #d9534f;
        }

        .action-buttons .add-btn {
            background-color: #337ab7;
        }

        .action-buttons .accept-btn {
            background-color: #5cb85c;
        }

        .action-buttons .decline-btn {
            background-color: #d9534f;
        }

        .action-buttons .paid-btn {
            background-color: #f0ad4e;
        }

        .action-buttons .cancel-btn {
            background-color: #d9534f;
        }

        .action-buttons .edit-btn:hover {
            background-color: #4cae4c;
        }

        .action-buttons .delete-btn:hover {
            background-color: #c9302c;
        }

        .action-buttons .add-btn:hover {
            background-color: #286090;
        }

        .action-buttons .accept-btn:hover {
            background-color: #4cae4c;
        }

        .action-buttons .decline-btn:hover {
            background-color: #c9302c;
        }

        .action-buttons .paid-btn:hover {
            background-color: #ec971f;
        }

        .action-buttons .cancel-btn:hover {
            background-color: #c9302c;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Admin Dashboard</h2>

        <?php if (isset($_SESSION['message'])) : ?>
            <p class="message"><?= $_SESSION['message']; ?></p>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])) : ?>
            <p class="error"><?= $_SESSION['error']; ?></p>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <!-- Display total bookings and total amount collected -->
        <div id="summary">
            <p>Total Bookings: <?= $total_data['total_bookings']; ?></p>
            <p>Total Amount Collected: <?= $total_data['total_amount']; ?></p>
        </div>

        <h3>Rooms</h3>
        <a href="add_room.php" class="action-buttons add-btn">Add Room</a>
        <table>
            <tr>
                <th>ID</th>
                <th>Type</th>
                <th>Price</th>
                <th>Actions</th>
            </tr>
            <?php $room_counter = 1; ?>
            <?php while ($room = $rooms_result->fetch_assoc()) : ?>
                <tr>
                    <td><?= $room_counter++; ?></td>
                    <td><?= $room['type']; ?></td>
                    <td><?= $room['price']; ?></td>
                    <td>
                        <div class="action-buttons">
                            <a href="edit_room.php?id=<?= $room['id']; ?>" class="edit-btn">Edit</a>
                            <form action="" method="POST">
                                <input type="hidden" name="room_id" value="<?= $room['id']; ?>">
                                <button type="submit" name="delete_room" class="delete-btn">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>

        <h3>Users</h3>
        <table>
            <tr>
                <th>ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>DOB</th>
                <th>Gender</th>
                <th>Profile Image</th>
                <th>Actions</th>
            </tr>
            <?php $user_counter = 1; ?>
            <?php while ($user = $users_result->fetch_assoc()) : ?>
                <tr>
                    <td><?= $user_counter++; ?></td>
                    <td><?= $user['firstName']; ?></td>
                    <td><?= $user['lastName']; ?></td>
                    <td><?= $user['email']; ?></td>
                    <td><?= $user['phone']; ?></td>
                    <td><?= $user['dob']; ?></td>
                    <td><?= $user['gender']; ?></td>
                    <td>
                        <?php if (!empty($user['profileImage'])) : ?>
                            <img src="uploads/<?= $user['profileImage']; ?>" alt="Profile Image" style="width: 50px; height: 50px; border-radius: 50%;">
                        <?php else : ?>
                            No Image
                        <?php endif; ?>
                    </td>
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

        <h3>Bookings</h3>
        <table>
            <tr>
                <th>ID</th>
                <th>Room Type</th>
                <th>Check-in Date</th>
                <th>Check-out Date</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Phone Number</th>
                <th>Address</th>
                <th>Status</th>
                <th>Payment Status</th>
                <th>Cancellation Status</th>
                <th>Actions</th>
            </tr>
            <?php $booking_counter = 1; ?>
            <?php while ($booking = $bookings_result->fetch_assoc()) : ?>
                <tr>
                    <td><?= $booking_counter++; ?></td>
                    <td><?= $booking['room_type']; ?></td>
                    <td><?= $booking['check_in_date']; ?></td>
                    <td><?= $booking['check_out_date']; ?></td>
                    <td><?= $booking['first_name']; ?></td>
                    <td><?= $booking['last_name']; ?></td>
                    <td><?= $booking['email']; ?></td>
                    <td><?= $booking['phone_number']; ?></td>
                    <td><?= $booking['address']; ?></td>
                    <td><?= $booking['status']; ?></td>
                    <td><?= $booking['payment_status']; ?></td>
                    <td><?= $booking['cancellation_status']; ?></td>
                    <td>
                        <div class="action-buttons">
                            <?php if ($booking['status'] == 'pending') : ?>
                                <form action="" method="POST">
                                    <input type="hidden" name="booking_id" value="<?= $booking['id']; ?>">
                                    <button type="submit" name="accept_booking" class="accept-btn">Accept</button>
                                    <button type="submit" name="decline_booking" class="decline-btn">Decline</button>
                                </form>
                            <?php endif; ?>
                            <form action="" method="POST">
                                <input type="hidden" name="booking_id" value="<?= $booking['id']; ?>">
                                <?php if ($booking['payment_status'] == 'unpaid') : ?>
                                    <button type="submit" name="mark_paid" class="paid-btn">Mark as Paid</button>
                                <?php endif; ?>
                                <?php if ($booking['cancellation_status'] == 'not_cancelled') : ?>
                                    <button type="submit" name="cancel_booking" class="cancel-btn">Cancel Booking</button>
                                <?php endif; ?>
                            </form>
                        </div>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>

</html>