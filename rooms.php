<?php
include 'config/connection.php';
// CRUD operations for rooms
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];

    if ($action == 'create') {
        $room_number = $_POST['room_number'];
        $room_type = $_POST['room_type'];
        $price = $_POST['price'];

        $sql = "INSERT INTO rooms (room_number, room_type, price) VALUES ('$room_number', '$room_type', '$price')";
        if ($conn->query($sql) === TRUE) {
            echo "New room created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } elseif ($action == 'update') {
        $id = $_POST['id'];
        $room_number = $_POST['room_number'];
        $room_type = $_POST['room_type'];
        $price = $_POST['price'];

        $sql = "UPDATE rooms SET room_number='$room_number', room_type='$room_type', price='$price' WHERE id='$id'";
        if ($conn->query($sql) === TRUE) {
            echo "Room updated successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } elseif ($action == 'delete') {
        $id = $_POST['id'];

        $sql = "DELETE FROM rooms WHERE id='$id'";
        if ($conn->query($sql) === TRUE) {
            echo "Room deleted successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}
?>





<head>
    <title>HotelDynasty - Rooms</title>
    <link rel="stylesheet" href="./style/rooms.css">
</head>

<?php include_once "includes/header.php" ?>

<div class="container">
    <h2>Rooms</h2>
    <div class="room normal">
        <img src="./images/room-types/normal.jpg" alt="Normal Room">
        <h3>Normal Room</h3>
        <p class="price">$80 per night</p>
        <p class="description">A comfortable room with essential amenities for a pleasant stay.</p>
        <button>Book / Reserve</button>
    </div>

    <div class="room deluxe">
        <img src="./images/room-types/deluxe.jpg" alt="Deluxe Room">
        <h3>Deluxe Room</h3>
        <p class="price">$120 per night</p>
        <p class="description">An upgraded room offering additional space and luxury amenities.</p>
        <button>Book / Reserve</button>
    </div>

    <div class="room executive">
        <img src="./images/room-types/executive.jpg" alt="Executive Room">
        <h3>Executive Room</h3>
        <p class="price">$180 per night</p>
        <p class="description">An elegant room designed for business travelers with added conveniences.</p>
        <button>Book / Reserve</button>
    </div>

    <div class="room premium">
        <img src="./images/room-types/premium.jpg" alt="Premium Room">
        <h3>Premium Room</h3>
        <p class="price">$250 per night</p>
        <p class="description">The ultimate luxury experience with spacious accommodation and top-notch amenities.</p>
        <button>Book / Reserve</button>
    </div>
</div>

</body>

</html>