<?php
include 'db.php';
session_start();

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
  header("Location: ../../login.html");
  exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'])) {
  $user_id = intval($_POST['user_id']);

  // Optional: prevent admin from deleting their own account
  if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $user_id) {
    echo "You cannot delete your own account!";
    exit();
  }

  $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
  $stmt->bind_param("i", $user_id);

  if ($stmt->execute()) {
    header("Location: view_users.php"); // Redirect back after deletion
    exit();
  } else {
    echo "Error deleting user.";
  }

  $stmt->close();
  $conn->close();
} else {
  echo "Invalid request.";
}
