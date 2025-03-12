<?php
session_start();
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $patient_id = $_POST['patient_id'];
    $message = $_POST['message'];

    $query = "INSERT INTO messages (patient_id, message) VALUES ('$patient_id', '$message')";
    mysqli_query($conn, $query);

    header("Location: view_appointments.php");
    exit();
}
?>
