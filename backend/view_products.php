<?php
include 'db.php';

// Fetch all products
$result = $conn->query("SELECT * FROM products");

// Include Bootstrap CSS
echo '
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<div class="container py-3">
  <div class="row row-cols-1 row-cols-md-3 g-4">
';

while ($row = $result->fetch_assoc()) {
  $id = $row['id'];
  $name = $row['name'];
  $price = $row['price'];
  $image = $row['image']; // Make sure images are uploaded to ../frontend/uploads/

  echo "
    <div class='col'>
      <div class='card h-100 shadow-sm'>
        <img src='../frontend/uploads/$image' class='card-img-top' alt='$name' style='height: 200px; object-fit: cover;'>
        <div class='card-body'>
          <h5 class='card-title'>$name</h5>
          <p class='card-text text-success fw-bold'>TZS $price</p>
          <form action='../backend/order_handler.php' method='POST'>
            <input type='hidden' name='material' value='$name'>
            <div class='mb-2'>
              <input type='number' name='quantity' class='form-control' placeholder='Quantity' required>
            </div>
            <div class='mb-2'>
              <input type='text' name='address' class='form-control' placeholder='Delivery Address' required>
            </div>
            <button type='submit' class='btn btn-primary w-100'>Order Now</button>
          </form>
        </div>
      </div>
    </div>
  ";
}

echo '</div></div>';
?>
