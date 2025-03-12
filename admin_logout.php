<?php
session_start();
session_destroy(); // Destroy session
header("Location: admin_login.php");
exit();
?>
