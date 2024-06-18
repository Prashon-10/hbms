<?php
session_start();
include("config/connection.php");

$message = '';
$room_type = $_GET['type'] ?? '';
$price = $_GET['price'] ?? '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $check_in_date = $_POST['check-in-date'] ?? '';
    $check_out_date = $_POST['check-out-date'] ?? '';
    $room_type = $_POST['room-type'] ?? '';
    $first_name = $_POST['first-name'] ?? '';
    $last_name = $_POST['last-name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone_number = $_POST['phone-number'] ?? '';
    $address = $_POST['address'] ?? '';

    if (empty($check_in_date) || empty($check_out_date) || empty($room_type) || empty($first_name) || empty($last_name) || empty($email) || empty($phone_number) || empty($address)) {
        $message = 'All fields are required!';
    } else {
        $user_email = $_SESSION['email'] ?? '';
        if (!empty($user_email)) {
            $query = "SELECT id FROM users WHERE email='$user_email'";
            $result = $conn->query($query);
            if ($result && $result->num_rows > 0) {
                $user = $result->fetch_assoc();
                $user_id = $user['id'];

                $query = "INSERT INTO reservations (user_id, room_type, check_in_date, check_out_date, first_name, last_name, email, phone_number, address)
                          VALUES ('$user_id', '$room_type', '$check_in_date', '$check_out_date', '$first_name', '$last_name', '$email', '$phone_number', '$address')";

                if ($conn->query($query) === TRUE) {
                    $_SESSION['message'] = "Reservation successful!";
                    header("Location: details.php?type=$room_type&price=$price");
                    exit();
                } else {
                    $message = "Error: " . $conn->error;
                }
            } else {
                $message = "User not found.";
            }
        } else {
            $message = "User not logged in.";
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Hotel Details</title>
    <link rel="stylesheet" href="./style/bookHere.css">
</head>

<body>

    <?php include 'includes/header.php'; ?>

    <div class="hotel-details">
        <div class="gallery">
            <img src="./images/hotel1.jpg" alt="Hotel Image 1" onclick="openModal('./images/hotel1.jpg')">
            <img src="./images/hotel2.jpg" alt="Hotel Image 2" onclick="openModal('./images/hotel2.jpg')">
            <img src="./images/hotel3.jpg" alt="Hotel Image 3" onclick="openModal('./images/hotel3.jpg')">
            <img src="./images/hotel4.jpg" alt="Hotel Image 4" onclick="openModal('./images/hotel4.jpg')">
            <img src="./images/hotel5.jpg" alt="Hotel Image 5" onclick="openModal('./images/hotel5.jpg')">
        </div>

        <div class="room-types">
            <h3>Room Types</h3>
            <label for="room-type">Select Room Type:</label>
            <form method="post" action="details.php?type=<?= htmlspecialchars($room_type) ?>&price=<?= htmlspecialchars($price) ?>">
                <select id="room-type" name="room-type" required>
                    <option value="normal" <?= $room_type == 'normal' ? 'selected' : '' ?>>Normal Room - $100/night</option>
                    <option value="premium" <?= $room_type == 'premium' ? 'selected' : '' ?>>Premium Room - $200/night</option>
                    <option value="deluxe" <?= $room_type == 'deluxe' ? 'selected' : '' ?>>Deluxe Room - $300/night</option>
                    <option value="executive" <?= $room_type == 'executive' ? 'selected' : '' ?>>Executive Suite - $400/night</option>
                </select>
        </div>

        <div class="pricing" id="pricing">
            <p>Starting from $<?= htmlspecialchars($price) ?> per night</p>
        </div>

        <div class="booking-form">
            <h3>Book Your Stay</h3>
            <?php if (!empty($message)) { echo '<p class="message">' . $message . '</p>'; } ?>
            <?php if (!empty($_SESSION['message'])) { echo '<p class="message">' . $_SESSION['message'] . '</p>'; unset($_SESSION['message']); } ?>
            <form method="post" action="details.php?type=<?= htmlspecialchars($room_type) ?>&price=<?= htmlspecialchars($price) ?>">
                <label for="first-name">First Name:</label>
                <input type="text" id="first-name" name="first-name" value="<?= htmlspecialchars($_POST['first-name'] ?? '') ?>" required>

                <label for="last-name">Last Name:</label>
                <input type="text" id="last-name" name="last-name" value="<?= htmlspecialchars($_POST['last-name'] ?? '') ?>" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>

                <label for="phone-number">Phone Number:</label>
                <input type="tel" id="phone-number" name="phone-number" value="<?= htmlspecialchars($_POST['phone-number'] ?? '') ?>" required>

                <label for="address">Address:</label>
                <input type="text" id="address" name="address" value="<?= htmlspecialchars($_POST['address'] ?? '') ?>" required>

                <label for="check-in-date">Check-in Date:</label>
                <input type="date" id="check-in-date" name="check-in-date" value="<?= htmlspecialchars($_POST['check-in-date'] ?? '') ?>" required>

                <label for="check-out-date">Check-out Date:</label>
                <input type="date" id="check-out-date" name="check-out-date" value="<?= htmlspecialchars($_POST['check-out-date'] ?? '') ?>" required>

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
