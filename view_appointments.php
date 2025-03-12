<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "afiahospital";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("❌ Connection failed: " . $conn->connect_error);
}

// Fetch appointments with patient details
$sql = "SELECT 
    a.APP_id, 
    p.P_id AS patient_P_id,
    p.firstname AS patient_firstname,
    a.Doctor_id,
    a.doctor_name,
    a.department, 
    a.consultant_fee, 
    a.appointment_date, 
    a.appointment_time 
FROM appointments a
JOIN patients p ON a.P_id = p.P_id
ORDER BY a.appointment_date, a.appointment_time";

$result = $conn->query($sql);

// Check if query was successful
if (!$result) {
    die("❌ Query Failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - View Appointments</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            text-align: center;
        }
        h2 {
            margin-top: 20px;
            color: #333;
        }
        table {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
            background: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.1);
        }
        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background: #007BFF;
            color: white;
            text-transform: uppercase;
        }
        tr:nth-child(even) {
            background: #f9f9f9;
        }
        tr:hover {
            background: #f1f1f1;
        }
    </style>
</head>
<body>
    <h2>Admin - View Appointments</h2>
    <table>
        <tr>
            <th>Patient ID</th>
            <th>Patient Name</th>
            <th>Doctor ID</th>
            <th>Doctor Name</th>
            <th>Department</th>
            <th>Consultation Fee (KSH)</th>
            <th>Date</th>
            <th>Time</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['patient_P_id']}</td>
                        <td>{$row['patient_firstname']}</td>
                        <td>{$row['Doctor_id']}</td>
                        <td>Dr. {$row['doctor_name']}</td>
                        <td>{$row['department']}</td>
                        <td>KSH " . number_format($row['consultant_fee'], 2) . "</td>
                        <td>{$row['appointment_date']}</td>
                        <td>{$row['appointment_time']}</td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='8'>No appointments found.</td></tr>";
        }
        ?>
    </table>
</body>
</html>

<?php $conn->close(); ?>
