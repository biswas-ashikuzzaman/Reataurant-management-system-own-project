<?php
$servername = "localhost"; // Database server name
$username   = "root";      // Database username
$password   = "";          // Database password
$dbname     = "restuarant_db";    // Database name

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
echo "Database connected successfully!";
?>
