<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title></title>
    <link rel="stylesheet" href="/hbms/style/index.css" />
</head>

<body>
    <div class="container">
        <nav class="nav-bar">
            <div id="logo">
                <a href="/hbms/index.php"><img src="/hbms/images/logo.png" alt="logo"><span id="hotel-name"><span
                            class="HD-logo">H</span>otel<span class="HD-logo">D</span>ynasty</span></a>
            </div>
            <ul>
                <li><a href="/hbms/index.php">Home</a></li>
                <li><a href="../hbms/details.php">Rooms</a></li>
                <li><a href="./contact.php">Contact</a></li>
                <li><a href="./my_booking.php">My Booking</a></li>
                <li><a href="/hbms/admin_login.php">Admin</a></li>
                <li><a href="/hbms/admin_logout.php" id="logout">Logout</a></li>
                <li><a href="/hbms/account.php">My Profile</a></li>


            </ul>
        </nav>
    </div>
</body>

</html>