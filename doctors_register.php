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

    // Retrieve form data
    $first_name = trim($_POST['first_name'] ?? '');
    $surname = trim($_POST['surname'] ?? '');
    $age = trim($_POST['age'] ?? '');
    $gender = trim($_POST['gender'] ?? '');
    $specialization = trim($_POST['specialization'] ?? '');
	$consultant_fee = trim($_POST['consultant_fee'] ?? '');
    $experience = trim($_POST['experience'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $schedule = trim($_POST['schedule'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $confirm_password = trim($_POST['confirm_password'] ?? '');

    // Validate required fields
    if (
        empty($first_name) || empty($surname) || empty($age) || empty($gender) ||
        empty($specialization) || empty($consultant_fee) || empty($experience)||
        empty($phone) || empty($email) || empty($schedule) || empty($password) || empty($confirm_password)
    ) {
        die("❌ Error: All fields are required!");
    }

    // Validate age and experience
    if ($age < 0 || $experience < 0) {
        die("❌ Error: Age and Experience must be non-negative!");
    }

    // Validate phone number format
    if (!preg_match('/^\d{10,15}$/', $phone)) {
        die("❌ Error: Invalid phone number format!");
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("❌ Error: Invalid email address!");
    }

    // Validate password match
    if ($password !== $confirm_password) {
        die("❌ Error: Passwords do not match!");
    }

    // Check for duplicate phone number or email
    $checkStmt = $conn->prepare("SELECT Doctor_id FROM doctors WHERE phone = ? OR email = ?");
    $checkStmt->bind_param("ss", $phone, $email);
    $checkStmt->execute();
    $result = $checkStmt->get_result();

    if ($result->num_rows > 0) {
        die("❌ Error: Phone number or email already exists! Try again.");
    }
    $checkStmt->close();

    // Insert doctor details into the database
	$stmt = $conn->prepare("INSERT INTO doctors (first_name, surname, age, gender, specialization, consultant_fee, experience, phone, email, schedule, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssisssissss", $first_name, $surname, $age, $gender, $specialization, $consultant_fee, $experience, $phone, $email, $schedule, $password);

    if ($stmt->execute()) {
        echo "✅ Doctor registered successfully! Redirecting to login...";
        header("refresh:3; url=Doctors_login");
        exit();
    } else {
        echo "❌ Error: " . $stmt->error;
    }

    // Close connections
    $stmt->close();
    $conn->close();
}
?>
