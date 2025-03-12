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

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $patient_email = trim($_POST['patient_email']); // Retrieve patient email from form
    $doctor_id = trim($_POST['doctor']);
    $department = trim($_POST['department']);
    $appointment_date = trim($_POST['date']);
    $appointment_time = trim($_POST['time']);

    // Validate required fields
    if (empty($patient_email) || empty($doctor_id) || empty($department) || empty($appointment_date) || empty($appointment_time)) {
        die("❌ Error: All fields are required!");
    }

    // Retrieve P_id from patients table using email
    $stmt = $conn->prepare("SELECT P_id FROM patients WHERE email = ?");
    $stmt->bind_param("s", $patient_email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 0) {
        die("❌ Error: Patient not found!");
    }
    $row = $result->fetch_assoc();
    $patient_id = $row['P_id'];
    $stmt->close();

    // Retrieve doctor name and consultation fee from doctors table
    $stmt = $conn->prepare("SELECT first_name, CAST(consultant_fee AS DECIMAL(10,2)) AS consultant_fee FROM doctors WHERE Doctor_id = ?");
    $stmt->bind_param("i", $doctor_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 0) {
        die("❌ Error: Doctor not found!");
    }
    $row = $result->fetch_assoc();
    $doctor_name = $row['first_name'];
    $consultant_fee = floatval(trim($row['consultant_fee'])); // Convert fee to float to avoid issues
    $stmt->close();

    // Debugging: Check retrieved values
    echo "Doctor Name: $doctor_name <br>";
    echo "Consultant Fee: $consultant_fee <br>";

    // If consultant fee is still 0, throw an error
    if ($consultant_fee == 0) {
        die("❌ Error: Consultant fee is missing or zero for doctor ID: $doctor_id.");
    }

    // Insert appointment into database
    $stmt = $conn->prepare("INSERT INTO appointments (P_id, Doctor_id, doctor_name, department, consultant_fee, appointment_date, appointment_time) 
                            VALUES (?, ?, ?, ?, ?, ?, ?)");

    // Bind parameters correctly ("iissdss" → i = int, s = string, d = decimal/float)
    $stmt->bind_param("iissdss", $patient_id, $doctor_id, $doctor_name, $department, $consultant_fee, $appointment_date, $appointment_time);

    if ($stmt->execute()) {
        echo "✅ Appointment booked successfully!";
        header("refresh:3; url=AFIAHOSPITAL.html");
        exit();
    } else {
        echo "❌ Error: " . $stmt->error;
    }

    // Close connections
    $stmt->close();
    $conn->close();
}
?>
