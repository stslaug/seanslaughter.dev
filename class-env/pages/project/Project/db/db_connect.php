<?php

/*
 * Database Configuration
 *
 * to include this data, include require_once 'db_connect.php';
 *
 */

$servername = "localhost:5522";
$username = "seanljvy_seanljvy";      // default in XAMPP
$password = "MasterPass123!";          // default is empty in XAMPP
$database = "seanljvy_mostlikely";      // default is empty in XAMPP

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
