<!DOCTYPE html>
<html lang="en">
<head>
    <title>HotelDynasty - Contact Page</title>
    <link rel="stylesheet" href="./style/contact.css">
</head>
<body>
    <div class="container">
        <h1>Contact Us</h1>
        <form action="send_email.php" method="post">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="message">Message:</label>
            <textarea id="message" name="message" rows="4" cols="50" required></textarea>

            <input type="submit" value="Send Message">
        </form>
    </div>
</body>
</html>