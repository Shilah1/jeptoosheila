<?php
session_start();
if (!isset($_SESSION['doctor_id'])) {  // Use consistent key
    header("Location: Doctor_login.html");
    exit();
}

$conn = new mysqli("localhost", "root", "", "afiahospital");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$doctor_id = $_SESSION['doctor_id'];  // Use correct variable

$query = "SELECT appointments.P_id, patients.firstname, patients.phone, appointments.department, 
                 appointments.appointment_date, appointments.appointment_time, appointments.status 
          FROM appointments 
          JOIN patients ON appointments.P_id = patients.id 
          WHERE appointments.Doctor_id = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $doctor_id);  // Use correct variable
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Doctor's Dashboard</title>
</head>
<body>
    <h2>Doctor's Dashboard</h2>
    <table border="1">
        <tr>
            <th>Patient Name</th>
            <th>Phone</th>
            <th>Department</th>
            <th>Appointment Date</th>
            <th>Time</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?= htmlspecialchars($row['firstname']) ?></td>
                <td><?= htmlspecialchars($row['phone']) ?></td>
                <td><?= htmlspecialchars($row['department']) ?></td>
                <td><?= htmlspecialchars($row['appointment_date']) ?></td>
                <td><?= htmlspecialchars($row['appointment_time']) ?></td>
                <td><?= htmlspecialchars($row['status']) ?></td>
                <td>
                    <form action="update_appointment.php" method="POST">
                        <input type="hidden" name="appointment_id" value="<?= $row['P_id'] ?>">
                        <input type="hidden" name="phone" value="<?= $row['phone'] ?>">
                        <button type="submit" name="confirm">Confirm</button>
                        <button type="submit" name="decline">Decline</button>
                    </form>
                </td>
            </tr>
        <?php } ?>
    </table>
    <a href="logout.php">Logout</a>
</body>
</html>
