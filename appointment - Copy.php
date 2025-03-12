<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection settings
$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "afiahospital"; 

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check database connection
if ($conn->connect_error) {
    die("❌ Database connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {


    // Fix field names to match the HTML form
	$P_id =trim($_POST['P_id'] ?? '');
    $department = trim($_POST['department'] ?? '');
    $doctor_name = trim($_POST['doctor'] ?? '');
    $consultant_fee = trim($_POST['consultant_fee'] ?? ''); // Keep the original fee format
    $appointment_date = trim($_POST['date'] ?? ''); // FIXED field name
    $appointment_time = trim($_POST['time'] ?? ''); // FIXED field name

    // Validate required fields
    if (empty($department) || empty($doctor_name) || empty($consultant_fee) || empty($appointment_date) || empty($appointment_time)) {
        die("❌ Error: All fields are required! Please check your input.");
    }

    // Insert appointment details
    $stmt = $conn->prepare("INSERT INTO appointments (P_id,department, doctor_name, consultant_fee, appointment_date, appointment_time) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $P_id, $department, $doctor_name, $consultant_fee, $appointment_date, $appointment_time);

    if ($stmt->execute()) {
        echo "✅ Appointment booked successfully! Redirecting...";
        header("refresh:3; url=AFIAHOSPITAL.HTML");
        exit();
    } else {
        echo "❌ Error inserting data: " . $stmt->error;
    }

    // Close connections
    $stmt->close();
    $conn->close();
}
?>
