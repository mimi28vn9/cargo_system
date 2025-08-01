<?php
session_start();
include 'db.php';

// Sanitize and get input
$username = trim($_POST['username']);
$password = trim($_POST['password']);
$role = trim($_POST['role']);

// Prepared statement to prevent SQL injection
$stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ? AND role = ?");
$stmt->bind_param("sss", $username, $password, $role);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
  $user = $result->fetch_assoc();

  // Check if transporter is approved
  if ($role === 'transporter' && $user['status'] !== 'approved') {
    echo "
      <!DOCTYPE html>
      <html lang='en'>
      <head>
        <meta charset='UTF-8'>
        <title>Login Error</title>
        <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>
      </head>
      <body class='bg-light'>
        <div class='container mt-5'>
          <div class='alert alert-warning text-center' role='alert'>
            🚫 <strong>Access Denied:</strong> You are not yet approved by a wholesaler.
          </div>
          <div class='text-center'>
            <a href='../login.html' class='btn btn-primary'>← Back to Login</a>
          </div>
        </div>
      </body>
      </html>
    ";
    exit();
  }

  // Set session variables
  $_SESSION['username'] = $username;
  $_SESSION['role'] = $role;

  // Redirect to dashboard
  if ($role === 'admin') {
    header("Location: ../frontend/dashboards/admin.php");
  } else {
    header("Location: ../frontend/dashboards/$role.php");
  }
} else {
  echo "
    <!DOCTYPE html>
    <html lang='en'>
    <head>
      <meta charset='UTF-8'>
      <title>Login Failed</title>
      <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>
    </head>
    <body class='bg-light'>
      <div class='container mt-5'>
        <div class='alert alert-danger text-center' role='alert'>
          ❌ <strong>Invalid Login:</strong> Incorrect username, password, or role.
        </div>
        <div class='text-center'>
          <a href='../login.html' class='btn btn-secondary'>Try Again</a>
        </div>
      </div>
    </body>
    </html>
  ";
}
?>
