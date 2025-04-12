<?php
$servername = "127.0.0.1:5522";
$username = "seanljvy_stslaug";
$password = "Darkose123!";
$database = "seanljvy_main_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";
?>