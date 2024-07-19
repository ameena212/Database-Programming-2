<?php
// Hashing a password using bcrypt
$password = 'administrator';
$hashedPassword = password_hash($password, PASSWORD_BCRYPT);
echo $hashedPassword;
?>