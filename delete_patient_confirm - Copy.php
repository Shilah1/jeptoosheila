<?php
session_start();
include 'db_connect.php';

if (!isset($_GET['P_id'])) {
    header("Location: admin_patients.php");
    exit();
}

$id = mysqli_real_escape_string($conn, $_GET['P_id']); // Prevent SQL Injection
$query = "SELECT firstname, lastname FROM patients WHERE P_id='$id'";
$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) == 0) {
    echo "Patient not found.";
    exit();
}

$patient = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Confirm Delete</title>
</head>
<body>
    <h2>Confirm Delete</h2>
    <p>Are you sure you want to delete patient <strong>
    <?php echo htmlspecialchars($patient['firstname'] . ' ' . $patient['lastname']); ?></strong>?</p>
    
    <form action="delete_patient.php" method="POST">
        <input type="hidden" name="P_id" value="<?php echo htmlspecialchars($id); ?>">  <!-- Correct hidden input -->
        <button type="submit">Yes, Delete</button>
    </form>
    <a href="admin_patients.php">Cancel</a>
</body>
</html>
