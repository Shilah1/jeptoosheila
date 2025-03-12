<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session
session_start();

// Database connection settings
$servername = "localhost";
$username = "root"; // Default for XAMPP
$password = ""; // Default password is empty in XAMPP
$dbname = "afiahospital"; // Change this to your actual database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check database connection
if ($conn->connect_error) {
    die("❌ Database connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Validate required fields
    if (empty($username) || empty($password)) {
        die("❌ Error: Both fields are required!");
    }

    // Prepare SQL statement to fetch user details
    $stmt = $conn->prepare("SELECT P_id, username, password FROM patients WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    
    // Check if user exists
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $db_username, $db_password);
        $stmt->fetch();

        // Verify password (ensure passwords are stored securely in production)
        if ($password === $db_password) { 
            // Store user session
            $_SESSION['user_id'] = $id;
            $_SESSION['username'] = $db_username;

            // Display success message
            echo "✅ Login successful! Redirecting to appointment page...";
            
            // Redirect to appointment page after 3 seconds
            header("refresh:3; url=appointment_form.html");
            exit();
        } else {
            echo "❌ Error: Incorrect password!";
        }
    } else {
        echo "❌ Error: Username not found!";
    }

    // Close statement and database connection
    $stmt->close();
    $conn->close();
}
?>
