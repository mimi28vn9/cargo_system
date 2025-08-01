<?php
session_start();
include 'db.php';

if ($_SESSION['role'] !== 'transporter') {
  header("Location: ../frontend/login.html");
  exit();
}

$type = $_POST['type'];
$description = $_POST['description'];
$username = $_SESSION['username'];

// Get transporter ID
$result = $conn->query("SELECT id FROM users WHERE username='$username' AND role='transporter'");
$row = $result->fetch_assoc();
$transporter_id = $row['id'];

// Insert into vehicles table
$sql = "INSERT INTO vehicles (transporter_id, type, description) VALUES ('$transporter_id', '$type', '$description')";
if ($conn->query($sql)) {
  header("Location: ../frontend/dashboards/transporter.php");
} else {
  echo "Error: " . $conn->error;
}
?>
