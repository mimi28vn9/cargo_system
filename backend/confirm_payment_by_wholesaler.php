<?php
session_start();
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'])) {
    $orderId = intval($_POST['order_id']);

    // Update payment status
    $stmt = $conn->prepare("UPDATE orders SET payment_status = 'confirmed' WHERE id = ?");
    $stmt->bind_param("i", $orderId);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Payment for Order ID $orderId has been confirmed successfully.";
    } else {
        $_SESSION['error'] = "Failed to confirm payment. Try again.";
    }

    $stmt->close();
    header("Location: ../frontend/dashboards/wholesaler.php");
    exit();
}
?>
