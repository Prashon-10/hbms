<?php

$host = 'localhost';
$username = 'root';
$password = '';

$mysqli = new mysqli($host, $username, $password);

if ($mysqli->connect_error) {
    die('Connection failed: ' . $mysqli->connect_error);
}
return $mysqli;

?>
