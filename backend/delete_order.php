<?php
session_start();
include 'db.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'customer') {
    header("Location: ../frontend/login.html");
    exit();
}

$order_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$username = $_SESSION['username'];

// Get customer ID
$stmt = $conn->prepare("SELECT id FROM users WHERE username = ? AND role = 'customer'");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$customer = $result->fetch_assoc();

if (!$customer) {
    $_SESSION['payment_message'] = "Customer not found!";
    header("Location: ../frontend/dashboards/customer.php");
    exit();
}

$customer_id = $customer['id'];

// Check if order belongs to the customer
$check = $conn->prepare("SELECT * FROM orders WHERE id = ? AND customer_id = ?");
$check->bind_param("ii", $order_id, $customer_id);
$check->execute();
$orderResult = $check->get_result();

if ($orderResult->num_rows > 0) {
    // Delete order
    $del = $conn->prepare("DELETE FROM orders WHERE id = ?");
    $del->bind_param("i", $order_id);
    if ($del->execute()) {
        $_SESSION['payment_message'] = "Order #$order_id deleted successfully.";
    } else {
        $_SESSION['payment_message'] = "Failed to delete order #$order_id.";
    }
} else {
    $_SESSION['payment_message'] = "Unauthorized access or order not found.";
}

header("Location: ../frontend/dashboards/customer.php");
exit();
?>
