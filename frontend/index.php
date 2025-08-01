<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Build Cargo Logistics</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background-color: #f5f7fa;
      min-height: 100vh;
    }

    .welcome-content {
      margin-top: 100px;
      animation: fadeInUp 1.2s ease-out;
    }

    .card-custom {
      max-width: 600px;
      margin: auto;
      border-radius: 15px;
      padding: 30px;
      box-shadow: 0 5px 20px rgba(0,0,0,0.1);
      background-color: white;
    }

    .stat-box {
      margin-top: 30px;
      background: #f8f9fa;
      border-radius: 12px;
      padding: 20px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    @keyframes fadeInUp {
      from {
        transform: translateY(40px);
        opacity: 0;
      }
      to {
        transform: translateY(0);
        opacity: 1;
      }
    }
  </style>
</head>
<body>

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
    <div class="container-fluid">
      <a class="navbar-brand d-flex align-items-center" href="#">
        <i class="bi bi-truck me-2"></i> Build Cargo
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMenu">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarMenu">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <a class="nav-link active" href="login.html"><i class="bi bi-box-arrow-in-right"></i> Login</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="register.html"><i class="bi bi-person-plus"></i> Register</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Welcome Section -->
  <div class="container welcome-content">
    <div class="card card-custom text-center">
      <h1 class="mb-3 text-primary"><i class="bi bi-building"></i> Build Cargo Logistic System</h1>
      <p class="text-muted mb-4">Streamline your cargo transport, materials, and delivery operations with ease.</p>
      <a href="login.html" class="btn btn-outline-primary me-3">
        <i class="bi bi-box-arrow-in-right"></i> Login
      </a>
      <a href="register.html" class="btn btn-outline-success">
        <i class="bi bi-person-plus"></i> Register</a>

      <!-- Stats Section -->
      <!--<div class="stat-box mt-5">
        <div class="row">
          <div class="col-md-6">
            <h5><i class="bi bi-people-fill text-primary"></i> Total Users</h5>
            <p class="fs-4 text-muted">
              <?php echo 123; // Replace with: $conn->query("SELECT COUNT(*) FROM users")->fetch_assoc()['COUNT(*)']; ?>
            </p>
          </div>
          <div class="col-md-6">
            <h5><i class="bi bi-box-seam text-success"></i> Total Orders</h5>
            <p class="fs-4 text-muted">
              <?php echo 47; // Replace with: $conn->query("SELECT COUNT(*) FROM orders")->fetch_assoc()['COUNT(*)']; ?>
            </p>
          </div>
        </div>
      </div>
    </div>
  </div> -->

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  
</body>
</html>
