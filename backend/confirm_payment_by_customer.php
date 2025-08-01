<?php
session_start();
include 'db.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'customer') {
  header("Location: ../login.html");
  exit();
}

$username = $_SESSION['username'];
$code = $_POST['transaction_code'];

$customerQuery = $conn->prepare("SELECT id FROM users WHERE username = ?");
$customerQuery->bind_param("s", $username);
$customerQuery->execute();
$customerId = $customerQuery->get_result()->fetch_assoc()['id'];

$proofPath = "";
if (isset($_FILES['proof_file']) && $_FILES['proof_file']['error'] === UPLOAD_ERR_OK) {
  $uploadDir = "../frontend/uploads/proofs/";
  if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
  $proofName = uniqid() . '_' . basename($_FILES['proof_file']['name']);
  $proofPath = $uploadDir . $proofName;
  move_uploaded_file($_FILES['proof_file']['tmp_name'], $proofPath);
}

$conn->query("UPDATE orders SET payment_status = 'awaiting_confirmation', transaction_code = '$code', proof_file = '$proofPath' WHERE customer_id = $customerId AND payment_status != 'confirmed'");

$_SESSION['payment_message'] = "Payment details submitted. Awaiting wholesaler confirmation.";
header("Location: ../frontend/dashboards/customer.php");
exit();
?>
