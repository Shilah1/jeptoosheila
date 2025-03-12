<?php
session_start();
include 'db_connect.php';

// Fetch patients
$patients = mysqli_query($conn, "SELECT id, firstname, lastname FROM patients");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Send Message</title>
</head>
<body>
    <h2>Send Message to Patient</h2>
    <form action="send_message_process.php" method="POST">
        <label for="patient_id">Select Patient:</label>
        <select name="patient_id" required>
            <?php while ($row = mysqli_fetch_assoc($patients)) { ?>
                <option value="<?php echo $row['id']; ?>"><?php echo $row['firstname'] . ' ' . $row['lastname']; ?></option>
            <?php } ?>
        </select>
        <label for="message">Message:</label>
        <textarea name="message" required></textarea>
        <button type="submit">Send</button>
    </form>
</body>
</html>
