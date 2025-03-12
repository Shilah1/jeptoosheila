<?php
session_start();
include 'db_connect.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}

// Fetch all registered doctors
$query = "SELECT Doctor_id, first_name, surname, age, gender, specialization, experience, consultant_fee, phone, email, schedule FROM doctors ORDER BY first_name ASC";
$result = mysqli_query($conn, $query);

// Success message handling
$show_success = isset($_GET['success']) && $_GET['success'] == 'deleted';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registered Doctors</title>
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
    <meta http-equiv="refresh" content="3;url=admin_doctors.php">
    <?php endif; ?>

</head>
<body>
    <div class="container">
        <h2>Registered Doctors</h2>

        <!-- Success Message -->
        <?php if ($show_success): ?>
            <div class="success-message">Doctor successfully removed!</div>
        <?php endif; ?>

        <table>
            <tr>
                <th>ID</th>
                <th>First Name</th>
                <th>Surname</th>
                <th>Age</th>
                <th>Gender</th>
                <th>Specialization</th>
				<th>Consultant Fee</th>
                <th>Experience</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Schedule</th>
                <th>Action</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['Doctor_id']); ?></td>
                    <td><?php echo htmlspecialchars($row['first_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['surname']); ?></td>
                    <td><?php echo htmlspecialchars($row['age']); ?></td>
                    <td><?php echo htmlspecialchars($row['gender']); ?></td>
                    <td><?php echo htmlspecialchars($row['specialization']); ?></td>
					<td><?php echo htmlspecialchars($row['consultant_fee']); ?></td>
                    <td><?php echo htmlspecialchars($row['experience']); ?></td>
                    <td><?php echo htmlspecialchars($row['phone']); ?></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td><?php echo htmlspecialchars($row['schedule']); ?></td>
                    <td><a href="delete_doctor_confirm.php?Doctor_id=<?php echo urlencode($row['Doctor_id']); ?>">Delete</a></td>
                </tr>
            <?php } ?>
        </table>
    </div>
</body>
</html>
