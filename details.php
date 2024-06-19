<?php
session_start();
include ("config/connection.php");

$message = '';
$room_type = $_GET['type'] ?? '';
$price = $_GET['price'] ?? '';

// Static room types with prices
$static_room_types = [
    'normal' => 100,
    'premium' => 200,
    'deluxe' => 300,
    'executive' => 400
];

// Fetch available room types from the database
$query = "SELECT * FROM rooms";
$result = $conn->query($query);

$room_types = [];
while ($row = $result->fetch_assoc()) {
    $room_types[$row['type']] = $row['price']; // Store room types and prices in an array
}

// Merge static and dynamic room types
$all_room_types = array_merge($static_room_types, $room_types);

// Function to sanitize user inputs
function sanitize($conn, $input)
{
    return mysqli_real_escape_string($conn, htmlspecialchars(strip_tags(trim($input))));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate inputs
    $first_name = sanitize($conn, $_POST['first-name']);
    $last_name = sanitize($conn, $_POST['last-name']);
    $email = sanitize($conn, $_POST['email']);
    $phone_number = sanitize($conn, $_POST['phone-number']);
    $address = sanitize($conn, $_POST['address']);
    $check_in_date = $_POST['check-in-date'];
    $check_out_date = $_POST['check-out-date'];
    $room_type = sanitize($conn, $_POST['room-type']);
    $price = $all_room_types[$room_type] ?? ''; // Get price based on selected room type

    // Validate dates
    $today = date('Y-m-d');
    if ($check_in_date < $today) {
        $message = "Check-in date must be today or later.";
    } elseif ($check_out_date <= $check_in_date) {
        $message = "Check-out date must be after check-in date.";
    } else {
        // Insert into database
        $insert_query = "INSERT INTO reservations (first_name, last_name, email, phone_number, address, check_in_date, check_out_date, room_type, price)
                         VALUES ('$first_name', '$last_name', '$email', '$phone_number', '$address', '$check_in_date', '$check_out_date', '$room_type', '$price')";

        if ($conn->query($insert_query)) {
            $_SESSION['message'] = "Booking successful!";
            header('Location: details.php?type=' . urlencode($room_type) . '&price=' . urlencode($price));
            exit();
        } else {
            $message = "Error: " . $conn->error;
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
    <title>Hotel Details</title>
    <link rel="stylesheet" href="./style/bookHere.css">
    <style>
        /* Add your custom CSS styles here */
        .message {
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
        }
    </style>
    <script>
        function validateDates() {
            var checkInDate = document.getElementById('check-in-date').value;
            var checkOutDate = document.getElementById('check-out-date').value;

            var today = new Date().toISOString().slice(0, 10);

            if (checkInDate < today) {
                alert('Check-in date must be today or later.');
                return false;
            }

            if (checkOutDate <= checkInDate) {
                alert('Check-out date must be after check-in date.');
                return false;
            }

            return true;
        }
    </script>
</head>

<body>
    <?php include 'includes/header.php'; ?>

    <div class="hotel-details">
        <div class="gallery">
            <img src="./images/hotel1.jpg" alt="Hotel Image 1" onclick="openModal('./images/hotel1.jpg')">
            <img src="./images/hotel2.jpg" alt="Hotel Image 2" onclick="openModal('./images/hotel2.jpg')">
            <img src="./images/hotel3.jpg" alt="Hotel Image 3" onclick="openModal('./images/hotel3.jpg')">
            <!-- <img src="./images/hotel4.jpg" alt="Hotel Image 4" onclick="openModal('./images/hotel4.jpg')"> -->
            <!-- <img src="./images/hotel5.jpg" alt="Hotel Image 5" onclick="openModal('./images/hotel5.jpg')"> -->
        </div>

        <div class="room-types">
            <h3>Room Types</h3>
            <label for="room-type">Select Room Type:</label>
            <form method="post" action="details.php">
                <select id="room-type" name="room-type" required>
                    <?php foreach ($all_room_types as $type => $pricePerNight): ?>
                        <option value="<?= htmlspecialchars($type) ?>" <?= $room_type == $type ? 'selected' : '' ?>>
                            <?= ucfirst($type) ?> Room - $<?= number_format($pricePerNight, 2) ?>/night
                        </option>
                    <?php endforeach; ?>
                </select>
        </div>

        <!-- <div class="pricing" id="pricing">
            <p>Starting from $<?= htmlspecialchars($price) ?> per night</p>
        </div> -->

        <div class="booking-form">
            <h3>Book Your Stay</h3>
            <?php if (!empty($message)) {
                echo '<p class="message error">' . $message . '</p>';
            } ?>
            <?php if (!empty($_SESSION['message'])) {
                echo '<p class="message success">' . $_SESSION['message'] . '</p>';
                unset($_SESSION['message']);
            } ?>
            <form method="post" action="details.php" onsubmit="return validateDates()">
                <label for="first-name">First Name:</label>
                <input type="text" id="first-name" name="first-name"
                    value="<?= htmlspecialchars($_POST['first-name'] ?? '') ?>" required>

                <label for="last-name">Last Name:</label>
                <input type="text" id="last-name" name="last-name"
                    value="<?= htmlspecialchars($_POST['last-name'] ?? '') ?>" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                    required>

                <label for="phone-number">Phone Number:</label>
                <input type="tel" id="phone-number" name="phone-number"
                    value="<?= htmlspecialchars($_POST['phone-number'] ?? '') ?>" required>

                <label for="address">Address:</label>
                <input type="text" id="address" name="address" value="<?= htmlspecialchars($_POST['address'] ?? '') ?>"
                    required>

                <label for="check-in-date">Check-in Date:</label>
                <input type="date" id="check-in-date" name="check-in-date"
                    value="<?= htmlspecialchars($_POST['check-in-date'] ?? '') ?>" required>

                <label for="check-out-date">Check-out Date:</label>
                <input type="date" id="check-out-date" name="check-out-date"
                    value="<?= htmlspecialchars($_POST['check-out-date'] ?? '') ?>" required>

                <input type="submit" value="Submit">
            </form>
        </div>
    </div>

    <div id="imageModal" class="modal">
        <span class="close" onclick="closeModal()">&times;</span>
        <img class="modal-content" id="modalImage">
    </div>

    <script>
        function openModal(src) {
            var modal = document.getElementById("imageModal");
            var modalImg = document.getElementById("modalImage");
            modal.style.display = "block";
            modalImg.src = src;
        }

        function closeModal() {
            var modal = document.getElementById("imageModal");
            modal.style.display = "none";
        }
    </script>

    <?php include 'includes/footer.php'; ?>
</body>

</html>