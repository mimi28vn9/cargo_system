<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['tracking_status'])) {
  $orderId = $_POST['order_id'];
  $status = $_POST['tracking_status'];

  $conn->query("UPDATE orders SET tracking_status = '$status' WHERE id = '$orderId'");

  header("Location: ../frontend/dashboards/transporter.php");
  exit();
}
?>
