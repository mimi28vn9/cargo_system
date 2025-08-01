<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
  header("Location: ../../login.html");
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard - Build Cargo</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
    }
    .logout-btn {
      position: fixed;
      top: 20px;
      right: 30px;
      z-index: 1000;
    }
    .dashboard-title {
      font-size: 1.75rem;
      font-weight: 600;
      color: #0d6efd;
    }
    iframe {
      border-radius: 5px;
      background-color: #fff;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
  </style>
</head>
<body>

  <a href="../../backend/logout.php" class="btn btn-danger logout-btn">Logout</a>

  <div class="container mt-5 mb-5">

    <!-- Header -->
    <div class="mb-4">
      <h3 class="dashboard-title">👤 Admin Dashboard</h3>
      <p class="text-muted">Manage users and monitor customer orders.</p>
    </div>

    <!-- Registered Users Section -->
    <div class="card mb-4 shadow-sm">
      <div class="card-header bg-dark text-white">
        <strong>Registered Users</strong>
      </div>
      <div class="card-body">
        <iframe src="../../backend/view_users.php" width="100%" height="300"></iframe>
      </div>
    </div>

    <!-- Customer Orders Section -->
    <div class="card shadow-sm">
      <div class="card-header bg-secondary text-white">
        <strong>Customer Orders</strong>
      </div>
      <div class="card-body">
        <iframe src="../../backend/view_orders.php" width="100%" height="300"></iframe>
      </div>
    </div>

  </div>

</body>
</html>
