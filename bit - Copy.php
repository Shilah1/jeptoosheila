<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

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

// Check if the request is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize user input
    $firstname = trim($_POST['firstname']);
    $lastname = trim($_POST['lastname']);
    $username = trim($_POST['username']);
    $gender = $_POST['gender'];
    $dob = $_POST['dob'];
    $bloodgroup = $_POST['bloodgroup'];
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $paymentmethod = $_POST['paymentmethod'];
    $address = trim($_POST['address']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate required fields
    if (empty($firstname) || empty($lastname) || empty($username) || empty($gender) || empty($dob) || empty($bloodgroup) || empty($email) || empty($phone) || empty($paymentmethod) || empty($address) || empty($password)) {
        die("❌ Error: All fields are required!");
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("❌ Error: Invalid email format!");
    }

    // Validate password match
    if ($password !== $confirm_password) {
        die("❌ Error: Passwords do not match!");
    }

    // Check if email or phone already exists
    $check_stmt = $conn->prepare("SELECT P_id FROM patients WHERE email = ? OR phone = ?");
    $check_stmt->bind_param("ss", $email, $phone);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
        die("❌ Error: Email or phone number already registered!");
    }
    $check_stmt->close();

    // Prepare SQL statement to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO patients 
        (firstname, lastname, username, gender, dob, bloodgroup, email, phone, paymentmethod, address, password) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt->bind_param("sssssssssss", $firstname, $lastname, $username, $gender, $dob, $bloodgroup, $email, $phone, $paymentmethod, $address, $password);

    // Execute statement and check success
    if ($stmt->execute()) {
        // Redirect to login page after 2 seconds
        echo "✅ Registration successful! Redirecting to login...";
        header("refresh:2; url=Login1.html");
        exit();
    } else {
        echo "❌ Error: " . $stmt->error;
    }

    // Close statement and database connection
    $stmt->close();
    $conn->close();
}
?>
