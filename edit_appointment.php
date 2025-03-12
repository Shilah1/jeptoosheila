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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $appointment_date = $_POST['appointment_date'];
    $appointment_time = $_POST['appointment_time'];

    $stmt = $conn->prepare("UPDATE appointments SET appointment_date = ?, appointment_time = ? WHERE id = ?");
    $stmt->bind_param("ssi", $appointment_date, $appointment_time, $id);

    if ($stmt->execute()) {
        echo "✅ Appointment updated successfully!";
        header("refresh:2; url=admin_view_appointments.php");
        exit();
    } else {
        echo "❌ Error updating appointment: " . $stmt->error;
    }
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM appointments WHERE id = $id";
    $result = $conn->query($sql);
    $appointment = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Appointment</title>
</head>
<body>
    <h2>Edit Appointment</h2>
    <form method="POST">
        <input type="hidden" name="id" value="<?php echo $appointment['id']; ?>">
        <label for="date">New Date:</label>
        <input type="date" name="appointment_date" value="<?php echo $appointment['appointment_date']; ?>" required>
        <label for="time">New Time:</label>
        <input type="time" name="appointment_time" value="<?php echo $appointment['appointment_time']; ?>" required>
        <button type="submit">Update</button>
    </form>
</body>
</html>

<?php $conn->close(); ?>
