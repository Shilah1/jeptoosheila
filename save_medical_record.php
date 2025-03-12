<?php
ob_start(); // Prevents header errors

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "afiahospital";

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form data is received
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate inputs
    $patient_id = htmlspecialchars($_POST['patient_id'], ENT_QUOTES, 'UTF-8');
    $date_of_visit = htmlspecialchars($_POST['date_of_visit'], ENT_QUOTES, 'UTF-8');
    $symptoms = htmlspecialchars($_POST['symptoms'], ENT_QUOTES, 'UTF-8');
    $diagnosis = htmlspecialchars($_POST['diagnosis'], ENT_QUOTES, 'UTF-8');
    $treatment_plan = htmlspecialchars($_POST['treatment_plan'], ENT_QUOTES, 'UTF-8');

    // Ensure no fields are empty
    if (empty($patient_id) || empty($date_of_visit) || empty($symptoms) || empty($diagnosis) || empty($treatment_plan)) {
        echo "All fields are required!";
    } else {
        // Prepare SQL statement
        $sql = "INSERT INTO medical_records (patient_id, date_of_visit, symptoms, diagnosis, treatment_plan) 
                VALUES (?, ?, ?, ?, ?)";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("sssss", $patient_id, $date_of_visit, $symptoms, $diagnosis, $treatment_plan);

            // Execute and check success
            if ($stmt->execute()) {
                echo "Medical record saved successfully! Redirecting to Pharmacy form...";
                header("refresh:3;url=Pharmacy form.html");
                exit();
            } else {
                echo "Error: " . $stmt->error;
            }

            // Close statement
            $stmt->close();
        } else {
            echo "Database query failed.";
        }
    }
}

// Close connection
$conn->close();
?>
