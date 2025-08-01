<?php
session_start();
require_once '../../backend/db.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'transporter') {
  header("Location: ../../login.html");
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Transporter Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f4f6f9;
    }
    .dashboard-container {
      max-width: 1000px;
      margin: auto;
    }
    .card-header-custom {
      background: linear-gradient(to right, #198754, #146c43);
      color: white;
      font-weight: bold;
    }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-success mb-4">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Build Cargo</a>
    <div class="d-flex">
      <span class="navbar-text text-white me-3">
        Logged in as: <?php echo $_SESSION['username']; ?>
      </span>
      <a href="../../backend/logout.php" class="btn btn-outline-light btn-sm">Logout</a>
    </div>
  </div>
</nav>

<!-- Main Container -->
<div class="dashboard-container">
  <div class="card shadow mb-4">
    <div class="card-header card-header-custom">
      🚚 Assigned Orders & Tracking
    </div>
    <div class="card-body">
      <?php
      $transporterUsername = $_SESSION['username'];
      $getId = $conn->query("SELECT id FROM users WHERE username = '$transporterUsername' AND role = 'transporter'");
      $transporter = $getId->fetch_assoc();
      $transporterId = $transporter['id'];
      
      $result = $conn->query("SELECT * FROM orders WHERE transporter_id = '$transporterId' AND tracking_status != 'Delivered'");
      

      if ($result->num_rows > 0) {
        echo "<table class='table table-bordered'>
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Tracking</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>";

        while ($row = $result->fetch_assoc()) {
          $orderId = $row['id'];
          $material = $row['material'];
          $quantity = $row['quantity'];
          $tracking = $row['tracking_status'];

          echo "<tr>
                  <td>$orderId</td>
                  <td>$material</td>
                  <td>$quantity</td>
                  <td>$tracking</td>
                  <td>
                    <form method='POST' action='../../backend/update_tracking.php'>
                      <input type='hidden' name='order_id' value='$orderId'>
                      <select name='tracking_status' class='form-select form-select-sm'>
                        <option value='In Transit'" . ($tracking == 'In Transit' ? ' selected' : '') . ">In Transit</option>
                        <option value='Delivered'" . ($tracking == 'Delivered' ? ' selected' : '') . ">Delivered</option>
                      </select>
                      <button type='submit' class='btn btn-sm btn-primary mt-1'>Update</button>
                    </form>
                  </td>
                </tr>";
        }
        echo "</tbody></table>";
      } else {
        echo "<div class='alert alert-info'>No orders to track at the moment.</div>";
      }
      ?>
      <a href="../../frontend/reports/delivery_report.php" target="_blank" class="btn btn-outline-dark mb-3">
  🧾 View Printable Delivery Report
</a>

    </div>
  </div>
</div>

</body>
</html>
