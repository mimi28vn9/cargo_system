<?php
include '../../backend/db.php';
session_start();

// Optional access control
if (!isset($_SESSION['username']) || !in_array($_SESSION['role'], ['wholesaler', 'transporter', 'admin'])) {
  header("Location: ../../login.html");
  exit();
}

$result = $conn->query("SELECT o.*, u.username FROM orders o JOIN users u ON o.customer_id = u.id WHERE tracking_status = 'Delivered'");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Delivery Report - Build Cargo</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    @media print {
      .no-print { display: none; }
    }
    body {
      padding: 20px;
      font-family: Arial, sans-serif;
    }
    h3 {
      margin-bottom: 20px;
    }
  </style>
</head>
<body>

  <div class="container">
    <h3 class="text-center">📋 Delivery Report - Delivered Orders</h3>
    <div class="text-end no-print mb-3">
      <button onclick="window.print()" class="btn btn-primary">🖨️ Print Report</button>
    </div>

    <table class="table table-bordered table-striped">
      <thead class="table-dark">
        <tr>
          <th>Order ID</th>
          <th>Product</th>
          <th>Quantity</th>
          <th>Customer</th>
          <th>Address</th>
          <th>Order Date</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?= $row['id'] ?></td>
            <td><?= $row['material'] ?></td>
            <td><?= $row['quantity'] ?></td>
            <td><?= $row['username'] ?></td>
            <td><?= $row['address'] ?></td>
            <td><?= $row['created_at'] ?></td>
            <td><span class="badge bg-success"><?= $row['tracking_status'] ?></span></td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>

    <p class="text-muted small text-center no-print mt-5">© <?= date("Y") ?> Build Cargo System</p>
  </div>

</body>
</html>
