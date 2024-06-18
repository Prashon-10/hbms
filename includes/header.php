<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['email'])) {
    header("Location: /hbms/login.php"); // Use an absolute path
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title></title>
    <link rel="stylesheet" href="/hbms/style/index.css" /> <!-- Use an absolute path -->
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
                <li><a href="#services">Service</a></li>
                <li><a href="#hotel-ticket">Rooms</a></li>
                <li><a href="./contact.php">Contact</a></li>
                <li><a href="/hbms/logout.php" id="logout">Logout</a></li>


            </ul>
        </nav>
    </div>
</body>

</html>