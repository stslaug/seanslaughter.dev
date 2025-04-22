<?php

/*
 * Database Configuration
 *
 * to include this data, include require_once 'db_connect.php';
 *
 */

$servername = "";
$username = "";      // default in XAMPP
$password = "";          // default is empty in XAMPP
$database = "";      // default is empty in XAMPP

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
