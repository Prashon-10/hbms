<?php
include("./config/connection.php");
include_once './includes/header.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Hotel Dynasty</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="./style/contact.css">

</head>

<body>

    <div class="container">

        <h1>Contact Us - Hotel Dynasty</h1>
        <p>Welcome to Hotel Dynasty. Feel free to contact us using the form below.</p>

        <form action="submit_contact.php" method="POST">
            <label for="name">Your Name</label>
            <input type="text" id="name" name="name" required>

            <label for="email">Your Email</label>
            <input type="email" id="email" name="email" required>

            <label for="message">Message</label>
            <textarea id="message" name="message" required></textarea>

            <button type="submit">Submit</button>
        </form>

        <!-- Hotel Details -->
        <div>
            <h2>Hotel Dynasty</h2>
            <p>Address: Kathmandu, Nepal</p>
            <p>Contact: +977-1-1234567</p>
            <p>Email: info@hoteldynasty.com</p>
        </div>
    </div>

</body>

</html>