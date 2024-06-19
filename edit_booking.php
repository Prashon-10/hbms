<?php
session_start();
include("config/connection.php");

$message = '';
$booking_id = $_GET['id'] ?? '';

// Fetch existing booking details from the database
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])) {
    $query = "SELECT * FROM reservations WHERE id = '$booking_id'";
    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        $booking = $result->fetch_assoc();
    } else {
        $_SESSION['error'] = "Booking not found!";
        header("Location: dashboard.php");
        exit();
    }
}

// Handle form submission for updating booking details
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $check_in_date = $_POST['check-in-date'] ?? '';
    $check_out_date = $_POST['check-out-date'] ?? '';
    $first_name = $_POST['first-name'] ?? '';
    $last_name = $_POST['last-name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone_number = $_POST['phone-number'] ?? '';
    $address = $_POST['address'] ?? '';

    if (empty($check_in_date) || empty($check_out_date) || empty($first_name) || empty($last_name) || empty($email) || empty($phone_number) || empty($address)) {
        $message = 'All fields are required!';
    } else {
        $update_query = "UPDATE reservations SET 
                        check_in_date = '$check_in_date',
                        check_out_date = '$check_out_date',
                        first_name = '$first_name',
                        last_name = '$last_name',
                        email = '$email',
                        phone_number = '$phone_number',
                        address = '$address'
                        WHERE id = '$booking_id'";

        if ($conn->query($update_query) === TRUE) {
            $_SESSION['message'] = "Booking updated successfully!";
            header("Location: dashboard.php");
            exit();
        } else {
            $message = "Error updating booking: " . $conn->error;
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Booking</title>
    <link rel="stylesheet" href="styles.css">
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
            display: grid;
            gap: 10px;
        }

        label {
            font-weight: bold;
        }

        input[type="text"],
        input[type="email"],
        input[type="tel"],
        input[type="date"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 4px;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .error {
            background-color: #f2dede;
            color: #a94442;
            border: 1px solid #ebccd1;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 4px;
        }

        .message {
            background-color: #dff0d8;
            color: #3c763d;
            border: 1px solid #d6e9c6;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 4px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Edit Booking</h2>

        <?php if (!empty($message)) : ?>
            <p class="error"><?= $message; ?></p>
        <?php endif; ?>

        <form action="edit_booking.php?id=<?= htmlspecialchars($booking_id); ?>" method="POST">
            <label for="first-name">First Name:</label>
            <input type="text" id="first-name" name="first-name" value="<?= htmlspecialchars($booking['first_name'] ?? '') ?>" required>

            <label for="last-name">Last Name:</label>
            <input type="text" id="last-name" name="last-name" value="<?= htmlspecialchars($booking['last_name'] ?? '') ?>" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($booking['email'] ?? '') ?>" required>

            <label for="phone-number">Phone Number:</label>
            <input type="tel" id="phone-number" name="phone-number" value="<?= htmlspecialchars($booking['phone_number'] ?? '') ?>" required>

            <label for="address">Address:</label>
            <input type="text" id="address" name="address" value="<?= htmlspecialchars($booking['address'] ?? '') ?>" required>

            <label for="check-in-date">Check-in Date:</label>
            <input type="date" id="check-in-date" name="check-in-date" value="<?= htmlspecialchars($booking['check_in_date'] ?? '') ?>" required>

            <label for="check-out-date">Check-out Date:</label>
            <input type="date" id="check-out-date" name="check-out-date" value="<?= htmlspecialchars($booking['check_out_date'] ?? '') ?>" required>

            <input type="submit" value="Update Booking">
        </form>
    </div>
</body>

</html>
