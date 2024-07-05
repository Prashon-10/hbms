<?php
session_start();
include './config/connection.php';
// include_once './includes/header.php';

// Function to sanitize user inputs
function sanitize($conn, $input)
{
    return mysqli_real_escape_string($conn, htmlspecialchars(strip_tags(trim($input))));
}

// Fetch room details if ID is provided
$room_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($room_id > 0) {
    $query = "SELECT * FROM rooms WHERE id = $room_id";
    $result = $conn->query($query);
    if ($result->num_rows == 1) {
        $room = $result->fetch_assoc();
    } else {
        $_SESSION['error'] = "Room not found!";
        header('Location: dashboard.php');
        exit();
    }
} else {
    $_SESSION['error'] = "Invalid room ID!";
    header('Location: dashboard.php');
    exit();
}

// Handle form submission for updating room
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $room_id = sanitize($conn, $_POST['room_id']);
    $room_number = sanitize($conn, $_POST['room_number']);
    $type = sanitize($conn, $_POST['type']);
    $price = sanitize($conn, $_POST['price']);

    $update_query = "UPDATE rooms SET type = '$type', price = '$price' WHERE id = $room_id";
    if ($conn->query($update_query)) {
        $_SESSION['message'] = "Room updated successfully!";
        header('Location: dashboard.php');
        exit();
    } else {
        $_SESSION['error'] = "Error updating room: " . $conn->error;
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Room</title>
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
            margin-bottom: 20px;
        }

        form label {
            display: block;
            margin-bottom: 8px;
        }

        form input[type="text"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 10px;
        }

        form button {
            background-color: #5cb85c;
            color: #fff;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 4px;
        }

        form button:hover {
            background-color: #4cae4c;
        }

        .error-message {
            background-color: #f2dede;
            color: #a94442;
            border: 1px solid #ebccd1;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 4px;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Edit Room</h2>

        <?php if (isset($_SESSION['error'])): ?>
            <p class="error-message"><?= $_SESSION['error']; ?></p>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <form action="" method="POST">
            <input type="hidden" name="room_id" value="<?= $room['id']; ?>">
            <label for="room_number">Room Number:</label>
            <input type="text" id="room_number" name="room_number" value="<?= $room['room_number']; ?>" required>
            <label for="type">Room Type:</label>
            <input type="text" id="type" name="type" value="<?= $room['type']; ?>" required>

            <label for="price">Price:</label>
            <input type="text" id="price" name="price" value="<?= $room['price']; ?>" required>

            <button type="submit">Update Room</button>
        </form>
    </div>
</body>

</html>
