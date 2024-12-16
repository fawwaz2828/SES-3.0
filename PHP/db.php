<?php
$servername = "localhost";
$username = "root"; // replace with your database username
$password = "123"; // replace with your database password
$dbname = "ses"; // replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
