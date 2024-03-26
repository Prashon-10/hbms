<head>
    <title>Hotel Details</title>
    <link rel="stylesheet" href="./style/bookHere.css">

    <?php include 'includes/header.php'; ?>
</head>

<body>

    <div class="hotel-details">
        <div class="gallery">
            <img src="./images/hotel1.jpg" alt="Hotel Image 1" onclick="openModal('./images/hotel1.jpg')">
            <img src="./images/hotel2.jpg" alt="Hotel Image 1" onclick="openModal('./images/hotel2.jpg')">
            <!-- <img src="./images/hotel3.jpg" alt="Hotel Image 1" onclick="openModal('./images/hotel3.jpg')">
            <img src="./images/hotel4.jpg" alt="Hotel Image 1" onclick="openModal('./images/hotel4.jpg')"> -->
            <img src="./images/hotel5.jpg" alt="Hotel Image 1" onclick="openModal('./images/hotel5.jpg')">
</div>

        <div class="room-types">
            <h3>Room Types</h3>
            <label for="room-type">Select Room Type:</label>
            <?php
            $type = isset($_GET['type']) ? $_GET['type'] : 'normal';
            $price = isset($_GET['price']) ? $_GET['price'] : 100;
            ?>
            <select id="room-type">
                <option value="normal" <?php echo ($type === 'normal') ? 'selected' : ''; ?>>Normal Room - $
                    <?php echo $price; ?>/night
                </option>
                <option value="standard" <?php echo ($type === 'premium') ? 'selected' : ''; ?>>Premium Room -
                    $
                    <?php echo $price; ?>/night
                <option value="deluxe" <?php echo ($type === 'deluxe') ? 'selected' : ''; ?>>Deluxe Room - $
                    <?php echo $price; ?>/night
                </option>
                <option value="suite" <?php echo ($type === 'executive') ? 'selected' : ''; ?>>Executive Suite - $
                    <?php echo $price; ?>/night
                </option>

            </select>

            <div class="pricing" id="pricing">
                <p>Starting from $100 per night</p>
            </div>
        </div>

        <div class="booking-form">
            <h3>Book Your Stay</h3>
            <form>
                <label for="check-in">Check-in Date:</label>
                <input type="date" id="check-in" required>

                <label for="check-out">Check-out Date:</label>
                <input type="date" id="check-out" required>

                <label for="guests">Number of Guests:</label>
                <input type="number" id="guests" min="1" required>

                <button type="submit">Book Now</button>
            </form>
        </div>
    </div>

    <script src="./js/bookHere.js"></script>

</body>

</html>