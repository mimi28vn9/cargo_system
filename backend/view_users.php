<?php
include 'db.php';
session_start();

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
  header("Location: ../../login.html");
  exit();
}

// Pagination settings
$limit = 5; // Users per page
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $limit;

// Search
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$search_sql = $search !== '' ? " WHERE username LIKE '%$search%' OR role LIKE '%$search%'" : '';

// Total records
$total_result = $conn->query("SELECT COUNT(*) as total FROM users $search_sql");
$total_users = $total_result->fetch_assoc()['total'];
$total_pages = ceil($total_users / $limit);

// Fetch users with limit and offset
$sql = "SELECT id, username, role FROM users $search_sql LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Registered Users - Admin Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f4f6f9;
    }
    .container {
      margin-top: 50px;
    }
    .table-container {
      background-color: #ffffff;
      border-radius: 8px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.05);
      padding: 20px;
    }
    .table th {
      background-color: #0d6efd;
      color: white;
    }
    h2 {
      color: #0d6efd;
      margin-bottom: 20px;
    }
    .search-bar {
      margin-bottom: 20px;
    }
  </style>
</head>
<body>

<div class="container">
  <div class="table-container">
    <h2>👥 Registered Users</h2>

    <!-- Search Bar -->
    <form method="GET" class="search-bar">
      <div class="input-group">
        <input type="text" name="search" class="form-control" placeholder="Search by username or role" value="<?= htmlspecialchars($search) ?>">
        <button class="btn btn-primary" type="submit">Search</button>
      </div>
    </form>

    <!-- Users Table -->
    <table class="table table-bordered table-hover">
      <thead>
        <tr>
          <th>ID</th>
          <th>Username</th>
          <th>Role</th>
          <th>Action</th>
          
        </tr>
      </thead>
      <tbody>
        <?php if ($result && $result->num_rows > 0): ?>
          <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
              <td><?= htmlspecialchars($row['id']) ?></td>
              <td><?= htmlspecialchars($row['username']) ?></td>
              
              <td>
                <?php
                  $role = htmlspecialchars($row['role']);
                  if ($role === 'admin') {
                    echo "<span class='badge bg-danger'>Admin</span>";
                  } elseif ($role === 'customer') {
                    echo "<span class='badge bg-success'>Customer</span>";
                  } elseif ($role === 'user') {
                    echo "<span class='badge bg-primary'>User</span>";
                  } else {
                    echo "<span class='badge bg-secondary'>$role</span>";
                  }
                ?>
              </td>
              <td>
                <form method="POST" action="delete_user.php" onsubmit="return confirm('Are you sure you want to delete this user?');">
                  <input type="hidden" name="user_id" value="<?= $row['id'] ?>">
                  <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                </form>
              </td>
            </tr>
          <?php } ?>
        <?php else: ?>
          <tr><td colspan="4" class="text-center text-muted">No users found.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>

    <!-- Pagination Controls -->
    <?php if ($total_pages > 1): ?>
      <nav>
        <ul class="pagination justify-content-center">
          <?php if ($page > 1): ?>
            <li class="page-item">
              <a class="page-link" href="?page=<?= $page - 1 ?>&search=<?= urlencode($search) ?>">Previous</a>
            </li>
          <?php endif; ?>

          <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <li class="page-item <?= $i === $page ? 'active' : '' ?>">
              <a class="page-link" href="?page=<?= $i ?>&search=<?= urlencode($search) ?>"><?= $i ?></a>
            </li>
          <?php endfor; ?>

          <?php if ($page < $total_pages): ?>
            <li class="page-item">
              <a class="page-link" href="?page=<?= $page + 1 ?>&search=<?= urlencode($search) ?>">Next</a>
            </li>
          <?php endif; ?>
        </ul>
      </nav>
    <?php endif; ?>

  </div>
</div>

</body>
</html>
