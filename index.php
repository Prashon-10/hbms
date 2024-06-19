<?php
session_start();
include("includes/header.php");
include ("config/connection.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $check_in_date = $_POST['check-in-date'];
    $check_out_date = $_POST['check-out-date'];
    $room_type = $_POST['room-type'];
    $user_email = $_SESSION['email'];

    $query = "SELECT id FROM users WHERE email='$user_email'";
    $result = $conn->query($query);
    $user = $result->fetch_assoc();
    $user_id = $user['id'];

    $query = "INSERT INTO reservations (user_id, room_type, check_in_date, check_out_date) VALUES ('$user_id', '$room_type', '$check_in_date', '$check_out_date')";
    if ($conn->query($query) === TRUE) {
        echo "Reservation successful!";
    } else {
        echo "Error: " . $query . "<br>" . $conn->error;
    }
}

$conn->close();
?>

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

    <!-- <div class="reservation-container">
        <h1>Room Reservation</h1>


        <form id="reservation-form" method="post" action="index.php">
            <label for="check-in-date">Check-In Date:</label>
            <input type="date" id="check-in-date" name="check-in-date" required>

            <label for="check-out-date">Check-Out Date:</label>
            <input type="date" id="check-out-date" name="check-out-date" required>

            <label for="room-type">Room Type:</label>
            <select id="room-type" name="room-type">
                <option value="normal">Normal Room</option>
                <option value="deluxe">Deluxe Suite</option>
                <option value="executive">Executive Room</option>
                <option value="premium">Premium Suite</option>
            </select>

            <button type="submit">Reserve Room</button>
        </form>

    </div> -->

    <!-- <h1 id="about-hotel">About</h1>
    <div class="about-myhotel">
        <img src="./images/hotel.jpg" alt="hotel">
        <p>Hotel Dynasty is a luxury hotel located in the heart of the city, offering a perfect blend of modern
            amenities and traditional hospitality. Our hotel features elegantly designed rooms and suites, a
            multi-cuisine restaurant, a rooftop bar, a fitness center, and a spa. Whether you are traveling for
            business or leisure, Hotel Dynasty is the ideal choice for a comfortable and memorable stay.</p>
    </div> -->

    <h1 id="hotel-ticket">Rooms</h1>
    <div class="cards-room">

        <div class="card">
            <img src="./images/room-types/normal.jpg" alt="Normal">
            <h2>Normal Room</h2>
            <p>Comfortable and budget-friendly room for a relaxing stay.</p>
            <a href="details.php?type=normal&price=100" class="btn">Book Now</a>
        </div>

        <div class="card">
            <img src="./images/room-types/deluxe.jpg" alt="Deluxe">
            <h2>Deluxe Suite</h2>
            <p>Spacious and luxurious suite with a breathtaking view.</p>
            <a href="details.php?type=deluxe&price=200" class="btn">Book Now</a>
        </div>

        <div class="card">
            <img src="./images/room-types/executive.jpg" alt="Executive">
            <h2>Executive Room</h2>
            <p>Modern and comfortable room designed for business travelers.</p>
            <a href="details.php?type=executive&price=250" class="btn">Book Now</a>
        </div>

        <div class="card">
            <img src="./images/room-types/premium.jpg" alt="Premium">
            <h2>Premium Suite</h2>
            <p>Elegant suite with premium amenities for a luxurious experience.</p>
            <a href="details.php?type=premium&price=350sdkfsdfjksjdfjksdfjksdkf" class="btn">Book Now</a>

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