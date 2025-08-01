<?php
$conn = new mysqli("localhost", "root", "", "cargo_db");

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>
