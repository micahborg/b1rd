<?php
$db_host = "localhost";
$db_user = "root";
$db_password = "password";
$db_name = "B1RD_DB";

$con = new mysqli($db_host, $db_user, $db_password, $db_name);

// Check
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}
?>
