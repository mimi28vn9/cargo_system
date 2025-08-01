<?php
include 'db.php';

// Collect form data
$product = $_POST['product_name'];
$price = $_POST['price'];
$fee = $_POST['delivery_fee'];

// Set upload path
$upload_dir = '../frontend/uploads/';
$image1_name = $_FILES['image1']['name'];
$image2_name = $_FILES['image2']['name'];

$image1_path = $upload_dir . basename($image1_name);
$image2_path = $upload_dir . basename($image2_name);

// Move uploaded files to the uploads directory
if (move_uploaded_file($_FILES['image1']['tmp_name'], $image1_path) &&
    move_uploaded_file($_FILES['image2']['tmp_name'], $image2_path)) {
    
    // Save to database
    $sql = "INSERT INTO products (name, price, delivery_fee, image1, image2) 
            VALUES ('$product', '$price', '$fee', '$image1_name', '$image2_name')";

    if ($conn->query($sql)) {
        echo "Product added with images!";
    } else {
        echo "Database error: " . $conn->error;
    }

} else {
    echo "Image upload failed.";
}
?>
