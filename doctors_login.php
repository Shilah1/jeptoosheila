<?php
session_start(); // Start the session

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
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // Validate required fields
    if (empty($email) || empty($password)) {
        die("❌ Error: Email and Password are required!");
    }

    // Prepare and execute query to check email and password
    $stmt = $conn->prepare("SELECT id, first_name, email, password FROM doctors WHERE email = ? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if doctor exists
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();

        // Verify password (without hashing)
        if ($password === $row['password']) { // Direct comparison since password is not hashed
            // Set session variables
            $_SESSION['doctor_id'] = $row['id'];
            $_SESSION['doctor_name'] = $row['first_name'];

            echo "✅ Login successful! Redirecting to Medical Records...";
            header("refresh:2; url=medical_records.php"); // Redirect to Medical Records Page
            exit();
        } else {
            echo "❌ Error: Incorrect password!";
        }
    } else {
        echo "❌ Error: Doctor not found!";
    }

    // Close connections
    $stmt->close();
    $conn->close();
}
?>
