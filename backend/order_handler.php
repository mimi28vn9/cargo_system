<?php
session_start();
include 'db.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'customer') {
  echo "Unauthorized access!";
  exit();
}

$username = $_SESSION['username'];
$material = $_POST['material'];
$quantity = $_POST['quantity'];
$address = $_POST['address'];

// Get customer ID from users table
$result = $conn->query("SELECT id FROM users WHERE username='$username' AND role='customer'");
$row = $result->fetch_assoc();
$customer_id = $row['id'];

// Insert the order with customer_id
$sql = "INSERT INTO orders (material, quantity, address, customer_id, payment_status)
        VALUES ('$material', '$quantity', '$address', '$customer_id', 'pending')";

if ($conn->query($sql)) {
  header("Location: ../frontend/dashboards/customer.php");
} else {
  echo "Error: " . $conn->error;
}
?>
