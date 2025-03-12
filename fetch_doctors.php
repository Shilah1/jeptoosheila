<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "afiahospital";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("❌ Connection failed: " . $conn->connect_error);
}

// Fetch doctors and their fees
$sql = "SELECT Doctor_id, first_name, surname, consultant_fee FROM doctors";
$result = $conn->query($sql);

// Store doctors in an array
$doctors = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $doctors[] = $row;
    }
}

// Convert the array into JSON format
echo json_encode($doctors);

// Close the database connection
$conn->close();
?>
