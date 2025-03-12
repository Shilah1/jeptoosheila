<?php
session_start();
include 'db_connect.php'; // Include database connection

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: Admin login.html"); // Redirect if not logged in
    exit();
}

// Fetch all registered patients
$query = "SELECT id, firstname, lastname, username, gender, dob, bloodgroup, email, phone, paymentmethod, address FROM patients ORDER BY firstname ASC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registered Patients</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; }
        .container { width: 90%; margin: auto; background: white; padding: 20px; box-shadow: 0px 0px 10px #ccc; margin-top: 20px; border-radius: 10px; }
        h2 { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid #ccc; padding: 10px; text-align: left; }
        th { background-color: #007bff; color: white; }
        tr:nth-child(even) { background-color: #f9f9f9; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Registered Patients</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Username</th>
                <th>Gender</th>
                <th>Date of Birth</th>
                <th>Blood Group</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Payment Method</th>
                <th>Address</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                    <td><?php echo htmlspecialchars($row['firstname']); ?></td>
                    <td><?php echo htmlspecialchars($row['lastname']); ?></td>
                    <td><?php echo htmlspecialchars($row['username']); ?></td>
                    <td><?php echo htmlspecialchars($row['gender']); ?></td>
                    <td><?php echo htmlspecialchars($row['dob']); ?></td>
                    <td><?php echo htmlspecialchars($row['bloodgroup']); ?></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td><?php echo htmlspecialchars($row['phone']); ?></td>
                    <td><?php echo htmlspecialchars($row['paymentmethod']); ?></td>
                    <td><?php echo htmlspecialchars($row['address']); ?></td>
                </tr>
            <?php } ?>
        </table>
    </div>
</body>
</html>
