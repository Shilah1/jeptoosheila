<?php
session_start();
include 'db_connect.php';

if (!isset($_GET['Doctor_id'])) {
    header("Location: admin_doctors.php");
    exit();
}

$doctor_id = $_GET['Doctor_id'];
$query = "SELECT first_name, surname FROM doctors WHERE Doctor_id='$doctor_id'";
$result = mysqli_query($conn, $query);
$doctor = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Confirm Delete</title>
</head>
<body>
    <h2>Confirm Delete</h2>
    <p>Are you sure you want to remove Doctor <strong>
    <?php echo htmlspecialchars($doctor['first_name'] . ' ' . $doctor['surname']); ?></strong>?</p>
    
    <form action="delete_doctor.php" method="POST">
        <input type="hidden" name="Doctor_id" value="<?php echo htmlspecialchars($doctor_id); ?>">
        <button type="submit">Yes, Remove</button>
    </form>
    <a href="admin_doctors.php">Cancel</a>
</body>
</html>
