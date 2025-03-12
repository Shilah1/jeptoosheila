<?php
session_start();
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['Doctor_id'])) {
    $doctor_id = $_POST['Doctor_id'];

    // Execute DELETE query
    $query = "DELETE FROM doctors WHERE Doctor_id='$doctor_id'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        header("Location: admin_doctors.php?success=deleted");
        exit();
    } else {
        echo "Error removing doctor: " . mysqli_error($conn);
    }
} else {
    header("Location: admin_doctors.php");
    exit();
}
?>
