<?php
session_start();

if (!isset($_SESSION['username'])) {

    header("Location: login.php");
    exit();
}
?>

<head>
    <title>HD - Welcome</title>
    <link rel="stylesheet" href="css/welcome.css">
</head>

<body>
    <header>
        <div class="container">
            <h1>Welcome to Hotel Dynasty</h1>
        </div>
    </header>

    <main>
        <div class="container">
            <div class="welcome-message">
                <h2>Dear Guest,</h2>
                <p>We're delighted to have you with us!</p>
                <p>Explore our services and start planning your next stay with us.</p>
                <a href="reservation.php" class="btn">Make a Reservation</a>
            </div>
        </div>
    </main>

    <?php include_once "includes/footer.php" ?>
</body>

</html>