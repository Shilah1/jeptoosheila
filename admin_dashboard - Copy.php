<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php"); // Redirect if not logged in
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; text-align: center; padding: 50px; }
        .container { width: 500px; background: white; padding: 20px; box-shadow: 0px 0px 10px #ccc; margin: auto; border-radius: 10px; }
        a { display: block; margin: 10px 0; padding: 10px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; }
        a:hover { background: #0056b3; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Welcome, Admin</h2>
        <a href="admin_patients.php">View Registered Patients</a>
		<a href="admin_doctors.php">View Registered Doctors</a>
		<a href="view_appointments.php">View Appointments</a>
        <a href="admin_logout.php">Logout</a>
    </div>
</body>
</html>
