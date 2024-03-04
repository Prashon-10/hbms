<?php
// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'hotelbooking';

// Create a new mysqli instance
$mysqli = new mysqli($host, $username, $password, $database);

// Check connection
if ($mysqli->connect_error) {
    die('Connection failed: ' . $mysqli->connect_error);
}

// Connection successful
echo 'Connected to the database successfully!';
?>