<head>
    <title>HotelDynasty - Home</title>
</head>

<?php include_once "includes/header.php" ?>

<div class="container">
    <div class="image-content">
        <img src="./images/hotelroom1.jpg" alt="hotelroom" id="mainhotelroom">
        <h1 id="textinimage">Welcome to Hotel Dynasty</h1>
        <p id="mainimagetext"><span> Escape to Hotel Dynasty – where luxury meets tranquility, ensuring an
                unparalleled
                experience with every <br> stay.</span>
            <a href="#" id="getstarted">Get Started</a>
            <a href="#" id="learnmore">Learn More</a>
        </p>
    </div>
</div>

<div class="main-contents">

    <div class="reservation-container">
        <h1>Room Reservation</h1>

        <?php

        $mysqli = include_once "./config/connection.php";

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['check-in-date']) && isset($_POST['check-out-date'])) {
            $checkInDate = validateAndSanitizeDate($_POST['check-in-date']);
            $checkOutDate = validateAndSanitizeDate($_POST['check-out-date']);

            if ($checkInDate && $checkOutDate) {
                $insertQuery = "INSERT INTO reservations (check_in_date, check_out_date) VALUES ('$checkInDate', '$checkOutDate')";

                if ($mysqli->query($insertQuery) === TRUE) {
                    echo 'Room reserved successfully!<br>';
                } else {
                    echo 'Error reserving room: ' . $mysqli->error . '<br>';
                }
            } else {
                echo 'Invalid date format!<br>';
            }
        }

        $mysqli->close();

        function validateAndSanitizeDate($date)
        {
            $sanitizedDate = filter_var($date, FILTER_SANITIZE_STRING);

            // Validate date format (YYYY-MM-DD)
            $dateRegex = "/^\d{4}-\d{2}-\d{2}$/";
            if (preg_match($dateRegex, $sanitizedDate)) {
                return $sanitizedDate;
            } else {
                return false;
            }
        }

        ?>

        <!DOCTYPE html>
        <html lang="en">

        </html>


        <form id="reservation-form" method="post" action="">
            <label for="check-in-date">Check-In Date:</label>
            <input type="date" id="check-in-date" name="check-in-date" required>

            <label for="check-out-date">Check-Out Date:</label>
            <input type="date" id="check-out-date" name="check-out-date" required>

            <button type="submit">Reserve Room</button>
        </form>
    </div>

    <h1 id="hotel-ticket">Rooms</h1>
    <div class="cards-room">

        <div class="card">
            <img src="./images/room-types/normal.jpg" alt="Normal">
            <h2>Normal Room</h2>
            <p>Comfortable and budget-friendly room for a relaxing stay.</p>
            <a href="#" class="btn">Book Now</a>
        </div>

        <div class="card">
            <img src="./images/room-types/deluxe.jpg" alt="Deluxe">
            <h2>Deluxe Suite</h2>
            <p>Spacious and luxurious suite with a breathtaking view.</p>
            <a href="#" class="btn">Book Now</a>
        </div>

        <div class="card">
            <img src="./images/room-types/executive.jpg" alt="Executive">
            <h2>Executive Room</h2>
            <p>Modern and comfortable room designed for business travelers.</p>
            <a href="#" class="btn">Book Now</a>
        </div>

        <div class="card">
            <img src="./images/room-types/premium.jpg" alt="Premium">
            <h2>Premium Suite</h2>
            <p>Elegant suite with premium amenities for a luxurious experience.</p>
            <a href="#" class="btn">Book Now</a>

        </div>

    </div>

    <h1 id="services">Services</h1>
    <div class="cards-services">

        <div class="serviceCard">
            <img src="./images/services/RoomService.jpg" alt="Normal">
            <h2>Room Service</h2>
            <p>Enjoy delicious meals and snacks delivered to your room 24/7.</p>
        </div>

        <div class="serviceCard">
            <img src="./images/services/Spa.jpg" alt="Deluxe">
            <h2>Spa & Wellness</h2>
            <p>Relax and rejuvenate with our spa treatments and wellness services.</p>
        </div>

        <div class="serviceCard">
            <img src="./images/services/Parking.jpg" alt="">
            <h2>Parking</h2>
            <p>Convenient and secure parking options available for guests.</p>
        </div>

        <div class="serviceCard">
            <img src="./images/services/Laundry.jpg" alt="">
            <h2>Laundry Service</h2>
            <p>Professional laundry and dry cleaning services for your convenience.</p>
        </div>

        <div class="serviceCard">
            <img src="./images/services/Gym.jpg" alt="">
            <h2>Gym Access</h2>
            <p>Stay fit and healthy with our state-of-the-art fitness center.</p>
        </div>

        <div class="serviceCard">
            <img src="./images/services/confRoom.jpg" alt="">
            <h2>Conference Room Booking</h2>
            <p>Host your meetings and events with our well-equipped conference rooms. Book in advance.</p>
        </div>


    </div>
    <?php include_once "includes/footer.php" ?>