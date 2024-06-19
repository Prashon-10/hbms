<?php
session_start();
include ("config/connection.php");

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$user_email = $_SESSION['email'];

// Get user ID from email
$query = "SELECT id FROM users WHERE email='$user_email'";
$result = $conn->query($query);
$user = $result->fetch_assoc();
$user_id = $user['id'];

// Handle booking cancellation
if (isset($_GET['cancel'])) {
    $reservation_id = $_GET['cancel'];
    $delete_query = "DELETE FROM reservations WHERE id='$reservation_id' AND user_id='$user_id'";
    if ($conn->query($delete_query) === TRUE) {
        $message = "Booking cancelled successfully.";
    } else {
        $message = "Error cancelling booking: " . $conn->error;
    }
}

// Get user reservations
$query = "SELECT * FROM reservations WHERE user_id='$user_id'";
$reservations = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings</title>
    <link rel="stylesheet" href="style/mybookings.css">
</head>

<body>
    <?php include 'includes/header.php'; ?>

    <div class="container">
        <h1>My Bookings</h1>
        <?php if (isset($message)): ?>
            <p class="message <?php echo strpos($message, 'Error') === false ? 'success' : 'error'; ?>">
                <?php echo $message; ?>
            </p>
        <?php endif; ?>
        <?php if ($reservations->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Room Type</th>
                        <th>Check-In Date</th>
                        <th>Check-Out Date</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th>Price</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $reservations->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['room_type']); ?></td>
                            <td><?php echo htmlspecialchars($row['check_in_date']); ?></td>
                            <td><?php echo htmlspecialchars($row['check_out_date']); ?></td>
                            <td><?php echo htmlspecialchars($row['first_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['last_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['phone']); ?></td>
                            <td><?php echo htmlspecialchars($row['address']); ?></td>
                            <td><?php echo htmlspecialchars($row['price']); ?></td>
                            <td>
                                <a href="mybookings.php?cancel=<?php echo $row['id']; ?>"
                                    onclick="return confirm('Are you sure you want to cancel this booking?')">Cancel</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>You have no bookings.</p>
        <?php endif; ?>
    </div>

    <?php include 'includes/footer.php'; ?>
</body>

</html>

<?php $conn->close(); ?>