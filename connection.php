<?php
$db_host = "localhost";
$db_user = getenv('MY_SQL_USER');
$db_password = getenv('MY_SQL_PASS');
$db_name = "B1RD_DB";

$con = new mysqli($db_host, $db_user, $db_password, $db_name);

// Check
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}
?>
