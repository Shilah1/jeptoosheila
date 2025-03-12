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
if ($conn->connect_error) {
    die("❌ Connection failed: " . $conn->connect_error);
}

// Start session
session_start();
if (!isset($_SESSION['doctor_id'])) {
    die("❌ Access denied. Please log in as a doctor.");
}

$doctor_id = $_SESSION['doctor_id']; // Ensure case consistency

// Fetch appointments for the logged-in doctor
$sql = "SELECT a.id AS appointment_id, p.firstname, p.phone, a.appointment_date, 
               a.appointment_time, a.status 
        FROM appointments a 
        JOIN patients p ON a.P_id = p.id 
        WHERE a.Doctor_id = ? 
        ORDER BY a.appointment_date DESC, a.appointment_time DESC";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("❌ SQL error: " . $conn->error);
}

$stmt->bind_param("i", $doctor_id);
$stmt->execute();
$result = $stmt->get_result();

// Include Twilio SDK manually
require_once __DIR__ . '/Twilio/twilio-php-main/src/Twilio/autoload.php';

use Twilio\Rest\Client;

// Twilio Credentials (Replace with actual credentials)
$twilio_sid = "YOUR_TWILIO_SID";
$twilio_token = "YOUR_TWILIO_AUTH_TOKEN";
$twilio_phone = "YOUR_TWILIO_PHONE_NUMBER";

// Handle appointment confirmation & SMS notification
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['confirm_appointment'])) {
    $app_id = $_POST['appointment_id'];
    $patient_phone = $_POST['patient_phone'];

    // Update appointment status in database
    $update_sql = "UPDATE appointments SET status='Confirmed' WHERE id=?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("i", $app_id);

    if ($update_stmt->execute()) {
        // Send SMS to patient
        $client = new Client($twilio_sid, $twilio_token);
        $client->messages->create(
            $patient_phone,
            ["from" => $twilio_phone, "body" => "Your appointment has been confirmed."]
        );
        echo "✅ Appointment confirmed and patient notified.";
    } else {
        echo "❌ Error updating appointment status.";
    }
}

// Handle prescription submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['send_prescription'])) {
    $app_id = $_POST['appointment_id'];
    $prescription = $_POST['prescription'];

    // Store prescription in database
    $insert_sql = "INSERT INTO prescriptions (appointment_id, doctor_id, prescription_text) VALUES (?, ?, ?)";
    $insert_stmt = $conn->prepare($insert_sql);
    $insert_stmt->bind_param("iis", $app_id, $doctor_id, $prescription);

    if ($insert_stmt->execute()) {
        echo "✅ Prescription sent successfully.";
    } else {
        echo "❌ Error saving prescription.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor's Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px; border: 1px solid black; text-align: center; }
        th { background-color: #f2f2f2; }
        button { padding: 5px 10px; cursor: pointer; }
    </style>
</head>
<body>
    <h2>Doctor's Dashboard</h2>

    <table>
        <tr>
            <th>Patient Name</th>
            <th>Phone</th>
            <th>Appointment Date</th>
            <th>Appointment Time</th>
            <th>Status</th>
            <th>Confirm</th>
            <th>Prescription</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?= htmlspecialchars($row['firstname']) ?></td>
            <td><?= htmlspecialchars($row['phone']) ?></td>
            <td><?= htmlspecialchars($row['appointment_date']) ?></td>
            <td><?= htmlspecialchars($row['appointment_time']) ?></td>
            <td><?= htmlspecialchars($row['status']) ?></td>
            <td>
                <?php if ($row['status'] != 'Confirmed') { ?>
                <form method="post">
                    <input type="hidden" name="appointment_id" value="<?= $row['appointment_id'] ?>">
                    <input type="hidden" name="patient_phone" value="<?= $row['phone'] ?>">
                    <button type="submit" name="confirm_appointment">Confirm</button>
                </form>
                <?php } else { echo "✅ Confirmed"; } ?>
            </td>
            <td>
                <form method="post">
                    <input type="hidden" name="appointment_id" value="<?= $row['appointment_id'] ?>">
                    <textarea name="prescription" placeholder="Write prescription..." required></textarea>
                    <button type="submit" name="send_prescription">Send</button>
                </form>
            </td>
        </tr>
        <?php } ?>
    </table>

</body>
</html>

<?php $conn->close(); ?>
