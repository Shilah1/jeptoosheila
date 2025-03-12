<?php
session_start();
include 'db_connect.php'; // Database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Check if username and password match the database
    $query = "SELECT * FROM admin WHERE username = '$username' AND password = '$password' LIMIT 1";
    $result = mysqli_query($conn, $query);
    $admin = mysqli_fetch_assoc($result);

    if ($admin) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $username;
        header("Location: admin_dashboard.php"); // Redirect to dashboard
        exit();
    } else {
        $_SESSION['error'] = "Invalid username or password.";
        header("Location: admin_login.php");
        exit();
    }
}
?>
