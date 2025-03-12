<?php
session_start();
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['P_id'])) {
    $id = mysqli_real_escape_string($conn, $_POST['P_id']); // Sanitize input

    // Execute DELETE query
    $query = "DELETE FROM patients WHERE P_id='$id'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        header("Location: admin_patients.php?success=deleted");
        exit();
    } else {
        echo "Error deleting patient: " . mysqli_error($conn);
    }
} else {
    header("Location: admin_patients.php");
    exit();
}
?>
