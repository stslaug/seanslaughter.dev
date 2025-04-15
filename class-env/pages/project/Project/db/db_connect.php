<?php

/*
 * Database Configuration
 *
 * to include this data, include require_once 'db_connect.php';
 *
 */

$servername = "127.0.0.1:5522";
$username = "seanljvy_seanljvy";
$password = "MasterPass123!";
$database = "seanljvy_mostlikely";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
