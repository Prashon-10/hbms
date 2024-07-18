<?php
session_start();
include("config/connection.php");

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

// Static services with prices
$services = [
    'spa' => 100,
    'parking' => 150,
    'laundry' => 200,
    'gym' => 120,
    'wifi' => 20
];

// Static food options with prices
$food_options = [
    'breakfast' => 50,
    'lunch' => 100,
    'dinner' => 150
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

// Fetch user details based on session email
$user_email = $_SESSION['email'] ?? '';
$user_id = null;
if (!empty($user_email)) {
    $user_query = "SELECT id, firstName, lastName, phone FROM users WHERE email = '$user_email'";
    $user_result = $conn->query($user_query);
    $user_data = $user_result->fetch_assoc();
    $user_id = $user_data['id'] ?? null;
    $first_name = $user_data['firstName'] ?? '';
    $last_name = $user_data['lastName'] ?? '';
    $phone_number = $user_data['phone'] ?? '';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and retrieve inputs
    $first_name = htmlspecialchars(trim($_POST['first-name']));
    $last_name = htmlspecialchars(trim($_POST['last-name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $phone_number = htmlspecialchars(trim($_POST['phone-number']));
    $address = htmlspecialchars(trim($_POST['address']));
    $check_in_date = $_POST['check-in-date'];
    $check_out_date = $_POST['check-out-date'];
    $room_type = $_POST['room-type'];

    // Calculate total price including room price and selected services
    $room_price = $all_room_types[$room_type] ?? 0;
    $selected_services = $_POST['services'] ?? [];
    $selected_foods = $_POST['food'] ?? [];

    $service_price = array_reduce($selected_services, function ($acc, $service) use ($services) {
        return $acc + ($services[$service] ?? 0);
    }, 0);

    $food_price = array_reduce($selected_foods, function ($acc, $food) use ($food_options) {
        return $acc + ($food_options[$food] ?? 0);
    }, 0);

    $total_price = $room_price + $service_price + $food_price;

    // Validate dates
    $today = date('Y-m-d');
    if ($check_in_date < $today) {
        $message = "Check-in date must be today or later.";
    } elseif ($check_out_date <= $check_in_date) {
        $message = "Check-out date must be after check-in date.";
    } else {
        // Insert into database
        $insert_query = "INSERT INTO reservations (user_id, first_name, last_name, email, phone_number, address, check_in_date, check_out_date, room_type, price)
                         VALUES ('$user_id', '$first_name', '$last_name', '$email', '$phone_number', '$address', '$check_in_date', '$check_out_date', '$room_type', '$total_price')";

        if ($conn->query($insert_query)) {
            $_SESSION['message'] = "Booking successful!";
            header('Location: details.php?type=' . urlencode($room_type) . '&price=' . urlencode($total_price));
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

        .total-price {
            font-size: 1.2rem;
            margin-top: 10px;
        }
    </style>
    <script>
        function calculateTotalPrice() {
            var roomType = document.getElementById('room-type').value;
            var roomPrice = parseFloat(document.getElementById('room-type').options[document.getElementById('room-type').selectedIndex].getAttribute('data-price'));
            var servicesTotal = 0;
            var checkboxes = document.getElementsByName('services[]');
            var foodDropdown = document.getElementById('food');

            for (var i = 0; i < checkboxes.length; i++) {
                if (checkboxes[i].checked) {
                    var servicePrice = parseFloat(checkboxes[i].getAttribute('data-price'));
                    servicesTotal += servicePrice;
                }
            }

            var foodPrice = parseFloat(foodDropdown.options[foodDropdown.selectedIndex].getAttribute('data-price'));
            servicesTotal += foodPrice;

            var totalPrice = roomPrice + servicesTotal;
            document.getElementById('total-price').innerText = 'Total Price: Rs. ' + totalPrice.toFixed(2);
        }

        window.onload = function() {
            calculateTotalPrice();
        };
    </script>
</head>

<body>
    <?php include 'includes/header.php'; ?>

    <div class="hotel-details">

        <div class="room-types">
            <h3>Room Types</h3>
            <label for="room-type">Select Room Type:</label>
            <form method="post" action="details.php">
                <select id="room-type" name="room-type" onchange="calculateTotalPrice()" required>
                    <?php foreach ($all_room_types as $type => $pricePerNight) : ?>
                        <option value="<?= htmlspecialchars($type) ?>" data-price="<?= $pricePerNight ?>" <?= $room_type == $type ? 'selected' : '' ?>>
                            <?= ucfirst($type) ?> Room - Rs. <?= number_format($pricePerNight, 2) ?>/night
                        </option>
                    <?php endforeach; ?>
                </select>
        </div>

        <div class="services">
            <h3>Additional Services</h3>
            <label>Select Services (Optional):</label><br>
            <?php foreach ($services as $service => $price) : ?>
                <input type="checkbox" id="<?= $service ?>" name="services[]" value="<?= $service ?>" data-price="<?= $price ?>" onchange="calculateTotalPrice()" style="position: relative; top: 39px;">
                <label for="<?= $service ?>"><?= ucfirst($service) ?> - Rs. <?= number_format($price, 2) ?></label><br>
            <?php endforeach; ?>
        </div>

        <div class="foods">
            <h3>Food Options</h3>
            <label for="food">Select Food (Optional):</label>
            <select name="food[]" id="food" onchange="calculateTotalPrice()">
                <option value="0" data-price="0">None</option>
                <?php foreach ($food_options as $food => $price) : ?>
                    <option value="<?= $food ?>" data-price="<?= $price ?>"><?= ucfirst($food) ?> - Rs. <?= number_format($price, 2) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

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
                <input type="text" id="first-name" name="first-name" value="<?= htmlspecialchars($first_name) ?>" required><br>

                <label for="last-name">Last Name:</label>
                <input type="text" id="last-name" name="last-name" value="<?= htmlspecialchars($last_name) ?>" required><br>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($user_email) ?>" required><br>

                <label for="phone-number">Phone Number:</label>
                <input type="text" id="phone-number" name="phone-number" value="<?= htmlspecialchars($phone_number) ?>" required><br>

                <label for="address">Address:</label>
                <input type="text" id="address" name="address" required><br>

                <label for="check-in-date">Check-in Date:</label>
                <input type="date" id="check-in-date" name="check-in-date" required><br>

                <label for="check-out-date">Check-out Date:</label>
                <input type="date" id="check-out-date" name="check-out-date" required><br>

                <div class="total-price" id="total-price">Total Price: Rs. 0.00</div>

                <button type="submit">Book Now</button>
            </form>
        </div>
    </div>
</body>

</html>