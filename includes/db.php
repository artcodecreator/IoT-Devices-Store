<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "iot_store";

$conn = new mysqli("localhost", "root", "", "iot_store");

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>
