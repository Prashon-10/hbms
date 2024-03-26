
<?php

$servername = "localhost";
$username = "root";
$password = "";
$database = "hbms"; 

// Create connection
$mysqli = new mysqli($servername, $username, $password, $database);

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Set UTF-8 encoding
$mysqli->set_charset("utf8");

// Return the connection object
return $mysqli;
?>
