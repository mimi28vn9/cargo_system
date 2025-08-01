<?php
session_start();
include '../../backend/db.php';

// Ensure group ID is set
if (!isset($_GET['group'])) {
    echo "No order group selected.";
    exit();
}

$order_group = $_GET['group'];

$result = $conn->query("SELECT o.*, u.username FROM orders o
                        JOIN users u ON o.customer_id = u.id
                        WHERE o.order_group = '$order_group'");

if ($result->num_rows === 0) {
    echo "No orders found for this group.";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Order Group Details</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
  <div class="container">
    <h3 class="mb-4">📦 Order Group: <?php echo $order_group; ?></h3>
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>#</th>
          <th>Product</th>
          <th>Quantity</th>
          <th>Address</th>
          <th>Total Price (TZS)</th>
          <th>Delivery Fee (TZS)</th>
          <th>Customer</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        <?php
          $i = 1;
          while ($row = $result->fetch_assoc()) {
              echo "<tr>
                      <td>{$i}</td>
                      <td>{$row['material']}</td>
                      <td>{$row['quantity']}</td>
                      <td>{$row['address']}</td>
                      <td>{$row['total_price']}</td>
                      <td>{$row['delivery_fee']}</td>
                      <td>{$row['username']}</td>
                      <td><span class='badge bg-secondary'>{$row['payment_status']}</span></td>
                    </tr>";
              $i++;
          }
        ?>
      </tbody>
    </table>
    <a href="../dashboards/wholesaler.php" class="btn btn-primary mt-3">⬅ Back to Dashboard</a>
  </div>
</body>
</html>
