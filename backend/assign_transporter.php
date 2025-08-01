<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['transporter_id'])) {
  $orderId = $_POST['order_id'];
  $transporterId = $_POST['transporter_id'];

  $update = $conn->query("UPDATE orders SET transporter_id = '$transporterId', tracking_status = 'Assigned' WHERE id = '$orderId'");

  if ($update) {
    header("Location: ../frontend/dashboards/wholesaler.php?assigned=1");
  } else {
    echo "Error assigning transporter: " . $conn->error;
  }
} else {
  echo "Invalid request.";
}
?>
