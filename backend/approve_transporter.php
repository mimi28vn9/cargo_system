<?php
include 'db.php';
$id = $_GET['id'];

$sql = "UPDATE users SET status='approved' WHERE id='$id'";
if ($conn->query($sql)) {
  header("Location: ../frontend/dashboards/wholesaler.php");
} else {
  echo "Error: " . $conn->error;
}
?>
