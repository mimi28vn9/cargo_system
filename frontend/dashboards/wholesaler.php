<?php
session_start();
require_once '../../backend/db.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'wholesaler') {
  header("Location: ../../login.html");
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Wholesaler Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
    }
    .dashboard-container {
      max-width: 900px;
      margin: auto;
    }
    .card-header-custom {
      background: linear-gradient(to right, #007bff, #0056b3);
      color: white;
      font-weight: bold;
    }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
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

<!-- Flash Messages -->
<?php if (isset($_SESSION['success'])): ?>
  <div class="alert alert-success text-center mx-3">
    <?php
      echo $_SESSION['success'];
      unset($_SESSION['success']);
    ?>
  </div>
<?php elseif (isset($_SESSION['error'])): ?>
  <div class="alert alert-danger text-center mx-3">
    <?php
      echo $_SESSION['error'];
      unset($_SESSION['error']);
    ?>
  </div>
<?php endif; ?>

<!-- Pending Payments to Confirm -->
<?php
  $result = $conn->query("SELECT * FROM orders WHERE payment_status = 'awaiting_confirmation'");

  if ($result->num_rows > 0) {
    echo "<div class='card shadow mb-4'>
            <div class='card-header card-header-custom bg-warning text-dark'>
              💰 Pending Payments (To Confirm)
            </div>
            <div class='card-body'>
              <table class='table table-bordered'>
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Customer</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>";
    while ($row = $result->fetch_assoc()) {
      $orderId = $row['id'];
      $material = $row['material'];
      $quantity = $row['quantity'];
      $customerId = $row['customer_id'];
      $status = ucfirst($row['payment_status']);

      echo "<tr>
              <td>$orderId</td>
              <td>$material</td>
              <td>$quantity</td>
              <td>$customerId</td>
              <td><span class='badge bg-warning'>$status</span></td>
              <td>
                <form action='../../backend/confirm_payment_by_wholesaler.php' method='POST'>
                  <input type='hidden' name='order_id' value='$orderId'>
                  <button type='submit' class='btn btn-success btn-sm'>Confirm</button>
                </form>
              </td>
            </tr>";
    }
    echo "</tbody></table></div></div>";
  } else {
    echo "<div class='alert alert-info mx-3'>No pending payments to confirm.</div>";
  }
?>

<!-- ... everything else below remains unchanged (Assign Transporter, Add Product, etc.) ... -->


  <!-- Assign Transporter to Orders -->
  <?php
    $orders = $conn->query("SELECT * FROM orders WHERE payment_status = 'confirmed' AND transporter_id IS NULL");

    if ($orders->num_rows > 0) {
      echo "<div class='card shadow mb-4'>
              <div class='card-header card-header-custom bg-secondary text-white'>
                🚚 Assign Transporter to Orders
              </div>
              <div class='card-body'>
                <table class='table table-bordered'>
                  <thead>
                    <tr>
                      <th>Order ID</th>
                      <th>Product</th>
                      <th>Quantity</th>
                      <th>Assign Transporter</th>
                    </tr>
                  </thead>
                  <tbody>";

      while ($order = $orders->fetch_assoc()) {
        $orderId = $order['id'];
        $material = $order['material'];
        $quantity = $order['quantity'];

        $transporters = $conn->query("SELECT id, username FROM users WHERE role = 'transporter' AND status = 'approved'");

        echo "<tr>
                <td>$orderId</td>
                <td>$material</td>
                <td>$quantity</td>
                <td>
                  <form method='POST' action='../../backend/assign_transporter.php'>
                    <input type='hidden' name='order_id' value='$orderId'>
                    <select name='transporter_id' class='form-select form-select-sm' required>
                      <option value=''>-- Select Transporter --</option>";

        while ($t = $transporters->fetch_assoc()) {
          echo "<option value='{$t['id']}'>{$t['username']}</option>";
        }

        echo "    </select>
                    <button type='submit' class='btn btn-sm btn-primary mt-2'>Assign</button>
                  </form>
                </td>
              </tr>";
      }

      echo "    </tbody>
              </table>
            </div>
          </div>";
    } else {
      echo "<div class='alert alert-info'>No confirmed orders awaiting transporter assignment.</div>";
    }
  ?>

  <a href="../../frontend/reports/delivery_report.php" target="_blank" class="btn btn-outline-dark mb-3">
    🧾 View Printable Delivery Report
  </a>

  <!-- Main Container -->
  <div class="dashboard-container">

    <!-- Add Product Form -->
    <div class="card shadow mb-4">
      <div class="card-header card-header-custom">
        🛒 Add New Product
      </div>
      <div class="card-body">
        <form action="../../backend/product_handler.php" method="POST" enctype="multipart/form-data">
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">Product Name</label>
              <input type="text" class="form-control" name="product_name" required />
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Price (TZS)</label>
              <input type="number" class="form-control" name="price" required />
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Delivery Fee (TZS)</label>
              <input type="number" class="form-control" name="delivery_fee" required />
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Product Image 1</label>
              <input type="file" class="form-control" name="image1" required />
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Product Image 2</label>
              <input type="file" class="form-control" name="image2" required />
            </div>
          </div>
          <button type="submit" class="btn btn-primary w-100">➕ Add Product</button>
        </form>
      </div>
    </div>

    <!-- Pending Transporters -->
    <?php
      $res = $conn->query("SELECT * FROM users WHERE role='transporter' AND status='pending'");

      if ($res && $res->num_rows > 0) {
        echo "<div class='card shadow mb-4'>
                <div class='card-header card-header-custom bg-warning text-dark'>
                  🚚 Pending Transporters for Approval
                </div>
                <div class='card-body'>";
        while ($row = $res->fetch_assoc()) {
          echo "<div class='card mb-3'>
                  <div class='card-header bg-light fw-bold'>
                    🚛 Transporter Username: {$row['username']}
                  </div>
                  <div class='card-body'>
                    <p><strong>Full Name:</strong> {$row['full_name']}</p>
                    <p><strong>NIDA Number:</strong> {$row['nida_number']}</p>
                    <p><strong>Plate Number:</strong> {$row['plate_number']}</p>
                    <p><strong>Email:</strong> {$row['email']}</p>
                    <p><strong>Vehicle Type:</strong> {$row['vehicle_type']}</p>
                    <p><strong>Capacity:</strong> {$row['capacity']}</p>
                    <p><strong>Photo:</strong><br>
                      <img src='../../frontend/uploads/{$row['photo']}' alt='Transporter Photo' style='max-width: 200px; border: 1px solid #ccc; padding: 4px; border-radius: 5px;'>
                    </p>
                    <a href='../../backend/approve_transporter.php?id={$row['id']}' class='btn btn-success'>✅ Approve Transporter</a>
                  </div>
                </div>";
        }
        echo "</div></div>";
      } else {
        echo "<div class='alert alert-info'>No pending transporters at the moment.</div>";
      }
    ?>

    <!-- Your new Incoming Orders block starts here -->
    <?php
      $grouped = $conn->query("SELECT DISTINCT order_group, address, customer_id, payment_status FROM orders ORDER BY id DESC");

      echo "<div class='card mt-4'>
              <div class='card-header bg-dark text-white'>📝 Incoming Orders</div>
              <div class='card-body'>
                <table class='table table-bordered'>
                  <thead>
                    <tr><th>Order Group</th><th>Customer</th><th>Address</th><th>Status</th><th>Details</th></tr>
                  </thead><tbody>";

      while ($group = $grouped->fetch_assoc()) {
        $groupId = $group['order_group'];
        $address = $group['address'];
        $customerId = $group['customer_id'];
        $status = ucfirst($group['payment_status']);

        echo "<tr>
                <td>$groupId</td>
                <td>$customerId</td>
                <td>$address</td>
                <td><span class='badge bg-warning'>$status</span></td>
                <td><a href='view_group_orders.php?group=$groupId' class='btn btn-sm btn-info'>View</a></td>
              </tr>";
      }

      echo "</tbody></table></div></div>";
    ?>
    <!-- End Incoming Orders block -->

  </div> <!-- end of dashboard-container -->

</body>
</html>
