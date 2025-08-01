<?php
include 'db.php';
$result = $conn->query("SELECT * FROM orders");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Customer Orders</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
  <style>
    body {
      background-color: #f8f9fa;
    }
    .container {
      margin-top: 50px;
    }
    .card {
      border-radius: 8px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }
    .card-header {
      background-color: #198754;
      color: white;
      font-size: 1.25rem;
      font-weight: 600;
    }
    .table th {
      background-color: #dee2e6;
    }
    .action-buttons {
      margin-bottom: 15px;
    }
  </style>
</head>
<body>

<div class="container">
  <div class="card">
    <div class="card-header">
      📦 Customer Orders
    </div>
    <div class="card-body">
      <!-- Action Buttons -->
      <div class="action-buttons">
        <button onclick="window.print()" class="btn btn-outline-secondary me-2">🖨️ Print</button>
        <button onclick="exportToPDF()" class="btn btn-outline-danger">📄 Export to PDF</button>
      </div>

      <!-- Orders Table -->
      <div id="orders-table">
        <table class="table table-striped table-bordered table-hover mb-0">
          <thead>
            <tr>
              <th>ID</th>
              <th>Material</th>
              <th>Quantity</th>
              <th>Address</th>
              <th>email</th>
              <th>phone number</th>
             
            </tr>
          </thead>
          <tbody>
            <?php while ($row = $result->fetch_assoc()) { ?>
              <tr>
                <td><?= htmlspecialchars($row['id']) ?></td>
                <td><?= htmlspecialchars($row['material']) ?></td>
                <td><?= htmlspecialchars($row['quantity']) ?></td>
                <td><?= htmlspecialchars($row['address']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= htmlspecialchars($row['phone']) ?></td>
                
              </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- PDF Export Script -->
<script>
  async function exportToPDF() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF('p', 'pt', 'a4');

    const element = document.getElementById('orders-table');

    await html2canvas(element).then(canvas => {
      const imgData = canvas.toDataURL('image/png');
      const imgProps = doc.getImageProperties(imgData);
      const pdfWidth = doc.internal.pageSize.getWidth();
      const pdfHeight = (imgProps.height * pdfWidth) / imgProps.width;

      doc.addImage(imgData, 'PNG', 20, 20, pdfWidth - 40, pdfHeight);
      doc.save('orders.pdf');
    });
  }
</script>

</body>
</html>
