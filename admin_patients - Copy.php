<?php
session_start();
include 'db_connect.php'; // Include database connection

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php"); // Redirect if not logged in
    exit();
}

// Fetch all registered patients
$query = "SELECT P_id, firstname, lastname, username, gender, dob, bloodgroup, email, phone, paymentmethod, address FROM patients ORDER BY firstname ASC";
$result = mysqli_query($conn, $query);

// Check if success message should be displayed
$show_success = isset($_GET['success']) && $_GET['success'] == 'deleted';
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
        .success-message { 
            text-align: center; 
            color: green; 
            font-weight: bold;
            background-color: #d4edda; 
            padding: 10px;
            border: 1px solid #c3e6cb;
            width: 50%;
            margin: 20px auto;
            border-radius: 5px;
        }
    </style>

    <?php if ($show_success): ?>
    <meta http-equiv="refresh" content="2;url=admin_patients.php">
    <?php endif; ?>

</head>
<body>
    <div class="container">
        <h2>Registered Patients</h2>

        <!-- Success message -->
        <?php if ($show_success): ?>
            <div class="success-message">Patient successfully deleted!</div>
        <?php endif; ?>

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
                <th>Action</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['P_id']); ?></td>
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
                    <td><a href="delete_patient_confirm.php?P_id=<?php echo urlencode($row['P_id']); ?>">Delete</a></td>
                </tr>
            <?php } ?>
        </table>
    </div>
</body>
</html>
