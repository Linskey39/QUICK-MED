<?php
// config.php - Database configuration
// Use environment variables for security
$host = getenv('DB_HOST') ?: "localhost";
$username = getenv('DB_USERNAME') ?: "root";
$password = getenv('DB_PASSWORD') ?: ""; // Remove hardcoded password
$dbname = getenv('DB_NAME') ?: "ambulance";

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?> 