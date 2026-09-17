<?php
$host = "localhost";
$db_user = "victoria.nyonator";
$db_pass = "YOUR_NEW_PASSWORD";
$db_name = "ecommerce_2026A_victoria_nyonator";

$conn = new mysqli($host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>