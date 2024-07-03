<?php
session_start();
include './config/connection.php';
include_once './includes/header.php';

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

function sanitize($conn, $input) {
    return mysqli_real_escape_string($conn, htmlspecialchars(strip_tags(trim($input))));
}

$user_email = sanitize($conn, $_SESSION['email']);
$query = "SELECT id FROM users WHERE email='$user_email'";
$result = $conn->query($query);
$user = $result->fetch_assoc();
$user_id = $user['id'];

// Handle cancel booking operation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_booking'])) {
    $booking_id = sanitize($conn, $_POST['booking_id']);
    $cancel_query = "DELETE FROM reservations WHERE id = $booking_id AND user_id = $user_id";
    if ($conn->query($cancel_query)) {
        $_SESSION['message'] = "Booking canceled successfully!";
    } else {
        $_SESSION['error'] = "Error canceling booking: " . $conn->error;
    }
    header("Location: my_booking.php");
    exit();
}

// Fetch user's bookings
$bookings_query = "SELECT * FROM reservations WHERE user_id = $user_id";
$bookings_result = $conn->query($bookings_query);

if (!$bookings_result) {
    $_SESSION['error'] = "Error fetching bookings: " . $conn->error;
}

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
            background-color: #d9534f;
            color: white;
        }

        .action-buttons button:hover {
            background-color: #c9302c;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>My Bookings</h2>

        <?php if (isset($_SESSION['message'])): ?>
            <p class="message"><?= $_SESSION['message']; ?></p>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <p class="error"><?= $_SESSION['error']; ?></p>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <table>
            <tr>
                <th>ID</th>
                <th>Room Type</th>
                <th>Check-in Date</th>
                <th>Check-out Date</th>
                <th>Actions</th>
            </tr>
            <?php if ($bookings_result && $bookings_result->num_rows > 0): ?>
                <?php $booking_counter = 1; ?>
                <?php while ($booking = $bookings_result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $booking_counter++; ?></td>
                        <td><?= $booking['room_type']; ?></td>
                        <td><?= $booking['check_in_date']; ?></td>
                        <td><?= $booking['check_out_date']; ?></td>
                        <td>
                            <div class="action-buttons">
                                <form action="" method="POST">
                                    <input type="hidden" name="booking_id" value="<?= $booking['id']; ?>">
                                    <button type="submit" name="cancel_booking">Cancel Booking</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">No bookings found.</td>
                </tr>
            <?php endif; ?>
        </table>
    </div>
</body>

</html>
