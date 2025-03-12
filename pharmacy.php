<?php
$servername = "localhost";
$username = "root"; // Change this to your database username
$password = ""; // Change this to your database password
$dbname = "afiahospital"; // Change this to your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $patient_id = $_POST['patient_id'];
    $medication_name = $_POST['medication_name'];
    $dosage = $_POST['dosage'];
    $frequency = $_POST['frequency'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    $sql = "INSERT INTO pharmacy (P_id, medication_name, dosage, frequency, start_date, end_date) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $patient_id, $medication_name, $dosage, $frequency, $start_date, $end_date);

		 if ($stmt->execute()) {
                echo "Prescription saved successfully! Redirecting to Homepage...";
                header("refresh:3;url=AFIAHOSPITAL.HTML");
                exit();

    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
