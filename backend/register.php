<?php
include 'db.php';

$username = $_POST['username'];
$password = $_POST['password'];
$role = $_POST['role'];

$status = ($role === 'transporter') ? 'pending' : 'approved';

// Initialize extra fields
$full_name = $_POST['full_name'] ?? '';
$nida_number = $_POST['nida_number'] ?? '';
$plate_number = $_POST['plate_number'] ?? '';
$email = $_POST['email'] ?? '';
$vehicle_type = $_POST['vehicle_type'] ?? '';
$capacity = $_POST['capacity'] ?? '';
$photoName = '';

// Handle photo upload if transporter
if ($role === 'transporter' && isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
  $photoName = basename($_FILES['photo']['name']);
  $targetDir = '../frontend/uploads/';
  $targetPath = $targetDir . $photoName;
  move_uploaded_file($_FILES['photo']['tmp_name'], $targetPath);
}

// Insert into users table with all fields
$sql = "INSERT INTO users (
  username, password, role, status,
  full_name, nida_number, plate_number,
  email, vehicle_type, capacity, photo
) VALUES (
  '$username', '$password', '$role', '$status',
  '$full_name', '$nida_number', '$plate_number',
  '$email', '$vehicle_type', '$capacity', '$photoName'
)";

if ($conn->query($sql)) {
  header("Location: ../frontend/login.html");
  exit();
} else {
  echo "Error: " . $conn->error;
}
?>
