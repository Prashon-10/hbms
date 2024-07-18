<?php
session_start();
include './config/connection.php';
include_once './includes/header.php';

function sanitize($conn, $input)
{
    return mysqli_real_escape_string($conn, htmlspecialchars(strip_tags(trim($input))));
}

// Handle form submission for adding a new room
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = sanitize($conn, $_POST['type']);
    $price = sanitize($conn, $_POST['price']);
    $number = sanitize($conn, $_POST['number']);

    // Check if price is greater than 0
    if ($price <= 0) {
        $_SESSION['error'] = "Price must be Positive!";
        header('Location: add_room.php');
        exit();
    }

    $insert_query = "INSERT INTO rooms (room_number, type, price) VALUES ('$number', '$type', '$price')";
    if ($conn->query($insert_query)) {
        $_SESSION['message'] = "Room added successfully!";
        header('Location: dashboard.php');
        exit();
    } else {
        $_SESSION['error'] = "Error adding room: " . $conn->error;
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Room</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1217px;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .form-group button {
            width: 100%;
            padding: 10px;
            background-color: #5cb85c;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .form-group button:hover {
            background-color: #4cae4c;
        }

        .message {
            background-color: #dff0d8;
            color: #3c763d;
            border: 1px solid #d6e9c6;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 4px;
        }

        .error {
            background-color: #f2dede;
            color: #a94442;
            border: 1px solid #ebccd1;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 4px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Add Room</h2>

        <?php if (isset($_SESSION['message'])) : ?>
            <p class="message"><?= $_SESSION['message']; ?></p>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])) : ?>
            <p class="error"><?= $_SESSION['error']; ?></p>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="form-group">
                <label for="number">Room Number:</label>
                <input type="number" id="number" name="number" required>
            </div>
            <div class="form-group">
                <label for="type">Type:</label>
                <input type="text" id="type" name="type" required>
            </div>
            <div class="form-group">
                <label for="price">Price:</label>
                <input type="number" id="price" name="price" required>
            </div>
            <div class="form-group">
                <button type="submit">Add Room</button>
            </div>
        </form>
    </div>
</body>

</html>