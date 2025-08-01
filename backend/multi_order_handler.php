<?php
session_start();
include 'db.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'customer') {
    header("Location: ../login.html");
    exit();
}

$username = $_SESSION['username'];

// Get customer ID
$stmt = $conn->prepare("SELECT id FROM users WHERE username = ? AND role = 'customer'");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$customer_id = $user['id'] ?? 0;

if (!$customer_id) {
    die("Customer not found.");
}

// Collect and sanitize form data
$product_ids = $_POST['product_ids'] ?? [];
$quantities = $_POST['quantities'] ?? [];
$address = trim($_POST['address'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$total_amount = intval($_POST['total_amount'] ?? 0);
$delivery_fee = intval($_POST['delivery_fee'] ?? 0);

// Generate a unique order group ID
$order_group = uniqid('order_');

// Prepare statement for getting product name
$getProductStmt = $conn->prepare("SELECT name FROM products WHERE id = ?");

// Prepare insert statement
$insertOrderStmt = $conn->prepare("INSERT INTO orders (product_id, material, quantity, address, email, phone, total_price, delivery_fee, payment_status, customer_id, order_group) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending', ?, ?)");

for ($i = 0; $i < count($product_ids); $i++) {
    $pid = intval($product_ids[$i]);
    $qty = intval($quantities[$i]);

    if ($qty <= 0) continue;

    // Get material name
    $getProductStmt->bind_param("i", $pid);
    $getProductStmt->execute();
    $productResult = $getProductStmt->get_result();
    $productRow = $productResult->fetch_assoc();
    $material = $productRow['name'] ?? 'Unknown';

    // Insert the order
    $insertOrderStmt->bind_param("isisssiiii", $pid, $material, $qty, $address, $email, $phone, $total_amount, $delivery_fee, $customer_id, $order_group);
    $insertOrderStmt->execute();
}

$_SESSION['payment_message'] = "✅ Order placed successfully!";
header("Location: ../frontend/dashboards/customer.php");
exit();
