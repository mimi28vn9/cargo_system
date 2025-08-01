<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'customer') {
  header("Location: ../../login.html");
  exit();
}
include '../../backend/db.php';
$username = $_SESSION['username'];

$fetch = $conn->prepare("SELECT id FROM users WHERE username = ? AND role='customer'");
$fetch->bind_param("s", $username);
$fetch->execute();
$result = $fetch->get_result();
$customerData = $result->fetch_assoc();
$customer_id = $customerData['id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Customer Dashboard - Build Cargo</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(to right, #e3f2fd, #ffffff);   /*kubadilisha background*/
      min-height: 100vh;
    }
    .dashboard-container {
      max-width: 1200px;
      margin: auto;
    }
    .logout-btn {
      position: fixed;
      top: 20px;
      right: 20px;
    }
    .product-img {
      height: 200px;
      object-fit: cover;
    }
    .total-amount {
      font-weight: bold;
      color: #0d6efd;
    }
  </style>
</head>
<body>
  <a href="../../backend/logout.php" class="btn btn-danger logout-btn">Logout</a> <!-- rangi ya batani-->

  <div class="container dashboard-container mt-5">
    <div class="card shadow-sm border-0">
      <div class="card-header bg-success text-white text-center">
        <h4>Customer Dashboard - Place an Order</h4>
        <p class="mb-0">Welcome, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong></p>
      </div>
      <div class="card-body bg-light">
      <?php if (isset($_SESSION['payment_message'])): ?>
        <div class="alert alert-info alert-dismissible fade show" role="alert">
          <?php
            echo $_SESSION['payment_message'];
            unset($_SESSION['payment_message']);
          ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      <?php endif; ?>

        <div class="card mb-4">
          <div class="card-header bg-info text-white">
            Available Construction Products
          </div>
          <div class="card-body bg-white">
            <form id="multiOrderForm" action="../../backend/multi_order_handler.php" method="POST">
              <div class="row row-cols-1 row-cols-md-3 g-4">
                <?php
                $result = $conn->query("SELECT * FROM products");
                while ($row = $result->fetch_assoc()) {
                  echo "<div class='col'>
                    <div class='card h-100 shadow-sm'>
                      <img src='../../frontend/uploads/{$row['image']}' class='card-img-top product-img' alt='{$row['name']}'>
                      <div class='card-body'>
                        <h5 class='card-title'>{$row['name']}</h5>
                        <p class='text-success fw-bold'>TZS {$row['price']}</p>
                        <input type='hidden' name='product_ids[]' value='{$row['id']}'>
                        <input type='hidden' class='unit-price' value='{$row['price']}'>
                        <label>Quantity</label>
                        <input type='number' class='form-control quantity-input' name='quantities[]' min='0' value='0'>
                      </div>
                    </div>
                  </div>";
                }
                ?>
              </div>

              <div class="mt-4">
                <label class="form-label">Delivery Address</label>
                <input type="text" name="address" class="form-control" required>
              </div>

              <div class="mt-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required>
              </div>

              <div class="mt-3">
                <label class="form-label">Phone Number</label>
                <input type="tel" name="phone" class="form-control" required>
              </div>

              <div class="mt-3">
                <p><strong>Total Amount:</strong> <span id="totalAmount">TZS 0</span></p>
                <p><strong>Delivery Fee (10%):</strong> <span id="deliveryFee">TZS 0</span></p>
                <input type="hidden" name="total_amount" id="total_amount_input">
                <input type="hidden" name="delivery_fee" id="delivery_fee_input">
                <button type="submit" class="btn btn-success w-100">Place Order</button>
              </div>
            </form>
          </div>
        </div>

        <?php
        $result = $conn->query("SELECT * FROM orders WHERE customer_id = '$customer_id'");
        $unpaid = $conn->query("SELECT COUNT(*) AS total FROM orders WHERE customer_id = '$customer_id' AND payment_status != 'confirmed'");
        $unpaidCount = $unpaid->fetch_assoc()['total'];

        if ($result->num_rows > 0) {
          echo "<div class='card mt-4'>
                  <div class='card-header bg-primary text-white'>Your Orders</div>
                  <div class='card-body'>
                    <table class='table table-bordered'>
                      <thead>
                        <tr><th>ID</th><th>Product</th><th>Status</th><th>Tracking</th><th>Action</th></tr>
                      </thead><tbody>";
          while ($row = $result->fetch_assoc()) {
            $orderId = $row['id'];
            $status = ucfirst($row['payment_status']);
            $badge = $status === 'Confirmed' ? 'success' : ($status === 'Awaiting_confirmation' ? 'warning' : 'secondary');
            echo "<tr>
                    <td>{$orderId}</td>
                    <td>{$row['material']}</td>
                    <td><span class='badge bg-{$badge}'>{$status}</span></td>
                    <td><span class='badge bg-info'>{$row['tracking_status']}</span></td>
                    <td>
                      <a href='../../backend/delete_order.php?id={$orderId}' class='btn btn-sm btn-outline-danger' onclick='return confirm(\"Delete order #{$orderId}?\")'>Delete</a>
                    </td>
                  </tr>";
          }
          echo "</tbody></table></div></div>";

          if ($unpaidCount > 0) {
            echo "<div class='d-grid gap-2 mt-3'>
              <button class='btn btn-primary btn-lg' onclick='showPaymentFormForAll()'>Pay for All Pending Orders ({$unpaidCount})</button>
            </div>";
          }
        }
        ?>

        <div id="paymentSection" class="card mt-4" style="display: none;">
          <div class="card-header bg-warning text-dark">
            Complete Your Payment to <strong>Dory Shagy</strong>
          </div>
          <div class="card-body">
            <ul class="list-group mb-3">
              <li class="list-group-item">Mpesa - LIPA NAMBA 1012</li>
              <li class="list-group-item">Tigo - LIPA NAMBA 2324</li>
              <li class="list-group-item">Airtel - LIPA NAMBA 5456</li>
              <li class="list-group-item">Halopesa - LIPA NAMBA 6787</li>
            </ul>
            <form action="../../backend/confirm_payment_by_customer.php" method="POST" enctype="multipart/form-data">
  <input type="hidden" name="order_id" id="order_id_input" value="all">

  <!--<div class="mb-3">
    <label>Transaction Code</label>
    <input type="text" name="transaction_code" class="form-control" required>
  </div>
  <div class="mb-3">
    <label>Upload Proof (Screenshot or Receipt)</label>
    <input type="file" name="proof_file" class="form-control" accept="image/*,application/pdf">
  </div> -->
  <button type="submit" class="btn btn-success w-100">I Have Paid</button>
</form>

          </div>
        </div>

      </div>
      <div class="card-footer text-center text-muted small bg-white">
        &copy; <?php echo date("Y"); ?> Build Cargo System. All rights reserved.
      </div>
    </div>
  </div>

  <script>
    function showPaymentFormForAll() {
      document.getElementById('paymentSection').style.display = 'block';
      document.getElementById('order_id_input').value = 'all';
      window.scrollTo({ top: document.getElementById('paymentSection').offsetTop, behavior: 'smooth' });
    }
    document.querySelectorAll('.quantity-input').forEach(input => {
      input.addEventListener('input', calculateTotals);
    });
    function calculateTotals() {
      let total = 0;
      const quantities = document.querySelectorAll('.quantity-input');
      const prices = document.querySelectorAll('.unit-price');
      quantities.forEach((input, index) => {
        const qty = parseInt(input.value) || 0;
        const price = parseFloat(prices[index].value);
        total += qty * price;
      });
      const delivery = Math.round(total * 0.1);
      document.getElementById('totalAmount').textContent = 'TZS ' + total.toLocaleString();
      document.getElementById('deliveryFee').textContent = 'TZS ' + delivery.toLocaleString();
      document.getElementById('total_amount_input').value = total;
      document.getElementById('delivery_fee_input').value = delivery;
    }
  </script>
</body>
</html>
