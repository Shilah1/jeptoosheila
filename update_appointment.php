<?php
session_start();
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $date = $_POST['date'];
    $time = $_POST['time'];

    $query = "UPDATE appointments SET appointment_date='$date', appointment_time='$time' WHERE id='$id'";
    mysqli_query($conn, $query);

    header("Location: view_appointments.php");
    exit();
}
?>
