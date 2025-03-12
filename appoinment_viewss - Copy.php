<?php 
session_start();
include 'db_connect.php'; // Database connection

// Include the Twilio PHP SDK
require '/xampp/htdocs/Twilio/twilio-php-main/src/Twilio/autoload.php'; // Update with the correct path to autoload.php

use Twilio\Rest\Client;

// Fetch appointments with patient and doctor details
$query = "SELECT 
            a.APP_id AS APP_id, 
            p.P_id AS P_id, 
            p.firstname AS firstname, 
            a.department, 
            d.first_name AS doctor_name, 
            d.consultant_fee, 
            a.appointment_date, 
            a.appointment_time, 
            a.status, 
            p.phone 
          FROM appointments a 
          JOIN patients p ON a.P_id = p.P_id 
          JOIN doctors d ON a.Doctor_id = d.Doctor_id";

$result = mysqli_query($conn, $query);

$message = ''; // To hold the confirmation message

if (isset($_POST['update_status'])) {
    $app_id = $_POST['APP_id'];
    $phone = $_POST['phone'];
    $status = $_POST['status'];

    // Update the status in the database
    $update_query = "UPDATE appointments SET status='$status' WHERE APP_id=$app_id";
    mysqli_query($conn, $update_query);
    
    // Set the confirmation message based on the appointment status
    if ($status == 'Confirmed') {
        $message = "Your appointment has been confirmed.";
        sendSMS($phone, $message);
    } elseif ($status == 'Declined') {
        $message = "Your appointment has been declined.";
    } else {
        $message = "Your appointment is pending.";
    }

    // Redirect with message
    echo "<script>
            alert('$message');
            setTimeout(function() {
                window.location.href = 'appoinment_viewss.php';
            }, 3000); // Redirect after 3 seconds
          </script>";
    exit(); // Prevent further code execution
}

// Twilio SMS function
function sendSMS($phone, $message) {
    // Ensure the phone number is in E.164 format (e.g., +919123456789)
    $country_code = "+1"; // Change this to your country code (e.g., +91 for India, +1 for USA)
    
    if (substr($phone, 0, 1) !== "+") {
        $phone = $country_code . $phone;
    }

    // Twilio credentials
    $sid = "AC191de170ebccebe2ef2cb04ccea84ba7"; // Replace with your Twilio SID
    $token = "cb5b192d7d6ea0f334649e8a5dee9b27"; // Replace with your Twilio Auth Token
    $from = "+19704279724"; // Replace with your Twilio phone number
    $client = new Client($sid, $token);
    
    try {
        $client->messages->create(
            $phone,
            [
                'from' => $from,
                'body' => $message,
            ]
        );
    } catch (Exception $e) {
        echo "Error sending SMS: " . $e->getMessage();
    }
}

// Logout logic
if (isset($_GET['logout'])) {
    // Destroy the session
    session_destroy();
    header("Location: Medical records form.html"); // Redirect to the Medical Records form after logout
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Doctor's Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        h2 {
            text-align: center;
            padding: 20px;
            background-color: #3498db;
            color: white;
            margin: 0;
        }

        table {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            border: 2px solid #3498db; /* Table border color */
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 2px solid #ddd; /* Border between rows */
            border-right: 2px solid #ddd; /* Border between columns */
        }

        th {
            background-color: #3498db;
            color: white;
            border-top: 2px solid #ddd;
            border-left: 2px solid #ddd; /* Border for the top-left corner */
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        td form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        td form select, td form input {
            padding: 8px;
            margin: 5px;
            width: 150px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }

        td form input[type="submit"] {
            background-color: #3498db;
            color: white;
            cursor: pointer;
        }

        td form input[type="submit"]:hover {
            background-color: #2980b9;
        }

        .status-option {
            text-align: center;
        }

        /* Logout Button Styles */
        .logout-btn {
            background-color: #e74c3c;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            font-size: 16px;
            position: fixed;
            top: 20px;
            right: 20px;
            border-radius: 5px;
            z-index: 1000;
        }

        .logout-btn:hover {
            background-color: #c0392b;
        }

    </style>
</head>
<body>
    <h2>Appointments</h2>
    <table>
        <tr>
            <th>Patient ID</th>
            <th>Patient Name</th>
            <th>Department</th>
            <th>Doctor</th>
            <th>Consultation Fee</th>
            <th>Appointment Date</th>
            <th>Appointment Time</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?php echo $row['P_id']; ?></td>
                <td><?php echo $row['firstname']; ?></td>
                <td><?php echo $row['department']; ?></td>
                <td><?php echo $row['doctor_name']; ?></td>
                <td><?php echo $row['consultant_fee']; ?></td>
                <td><?php echo $row['appointment_date']; ?></td>
                <td><?php echo $row['appointment_time']; ?></td>
                <td><?php echo $row['status']; ?></td>
                <td>
                    <form method="post">
                        <input type="hidden" name="APP_id" value="<?php echo $row['APP_id']; ?>">
                        <input type="hidden" name="phone" value="<?php echo $row['phone']; ?>">
                        <div class="status-option">
                            <select name="status">
                                <option value="Pending" <?php echo ($row['status'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                                <option value="Confirmed" <?php echo ($row['status'] == 'Confirmed') ? 'selected' : ''; ?>>Confirmed</option>
                                <option value="Declined" <?php echo ($row['status'] == 'Declined') ? 'selected' : ''; ?>>Declined</option>
                            </select>
                            <input type="submit" name="update_status" value="Update">
                        </div>
                    </form>
                </td>
            </tr>
        <?php } ?>
    </table>

    <!-- Logout Button -->
    <a href="?logout=true"><button class="logout-btn">Logout</button></a>
</body>
</html>
