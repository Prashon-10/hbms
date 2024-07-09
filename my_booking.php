<?php
session_start();
if (!isset($_SESSION['user_id'])) {
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

// Handle booking cancellation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['cancel_booking'])) {
        $booking_id = sanitize($conn, $_POST['booking_id']);
        $cancel_query = "UPDATE reservations SET cancellation_status='cancelled' WHERE id = $booking_id";
        if ($conn->query($cancel_query)) {
            $_SESSION['message'] = "Booking cancelled successfully!";
        } else {
            $_SESSION['error'] = "Error cancelling booking: " . $conn->error;
        }
    } elseif (isset($_POST['delete_booking'])) {
        $booking_id = sanitize($conn, $_POST['booking_id']);
        $delete_query = "DELETE FROM reservations WHERE id = $booking_id";
        if ($conn->query($delete_query)) {
            $_SESSION['message'] = "Booking deleted successfully!";
        } else {
            $_SESSION['error'] = "Error deleting booking: " . $conn->error;
        }
    }
    header('Location: my_booking.php');
    exit();
}

// Fetch bookings for the logged-in user
$user_id = $_SESSION['user_id'];
$bookings_query = "SELECT * FROM reservations WHERE user_id = $user_id";
$bookings_result = $conn->query($bookings_query);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 1600px;
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
        table th, table td {
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
        .action-buttons {
            display: flex;
            gap: 10px;
        }
        .action-buttons form {
            display: inline;
        }
        .action-buttons button {
            padding: 5px 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            color: white;
            background-color: #d9534f;
        }
        .action-buttons button:hover {
            background-color: #c9302c;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>My Bookings</h2>

        <?php if (isset($_SESSION['message'])) : ?>
            <p class="message"><?= $_SESSION['message']; ?></p>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])) : ?>
            <p class="error"><?= $_SESSION['error']; ?></p>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <table>
            <tr>
                <th>ID</th>
                <th>Room Type</th>
                <th>Check-in Date</th>
                <th>Check-out Date</th>
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
                    <td><?= $booking['status']; ?></td>
                    <td><?= $booking['payment_status']; ?></td>
                    <td><?= $booking['cancellation_status']; ?></td>
                    <td>
                        <div class="action-buttons">
                            <?php if ($booking['cancellation_status'] == 'not_cancelled' && !($booking['payment_status'] == 'paid' && $booking['status'] == 'accepted')) : ?>
                                <form action="" method="POST">
                                    <input type="hidden" name="booking_id" value="<?= $booking['id']; ?>">
                                    <button type="submit" name="cancel_booking">Cancel Booking</button>
                                </form>
                            <?php elseif ($booking['cancellation_status'] == 'cancelled') : ?>
                                <form action="" method="POST">
                                    <input type="hidden" name="booking_id" value="<?= $booking['id']; ?>">
                                    <button type="submit" name="delete_booking">Delete Booking</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>
