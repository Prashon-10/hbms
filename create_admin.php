<?php
$adminUsername = 'admin';
$adminPassword = 'admin002'; // Change this to your desired password
$hashedPassword = password_hash($adminPassword, PASSWORD_DEFAULT);
echo "INSERT INTO admins (username, password) VALUES ('$adminUsername', '$hashedPassword');";
?>
