<?php
// Memuat konfigurasi database
require_once './config/config.php';

// Mengambil data transaksi dari database
$query = "
    SELECT 
        DATE(created_at) as date,
        SUM(CASE WHEN transaction_in_id IS NOT NULL THEN price ELSE 0 END) as total_in,
        SUM(CASE WHEN transaction_out_id IS NOT NULL THEN price ELSE 0 END) as total_out
    FROM 
        transaction
    WHERE
        created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
    GROUP BY 
        DATE(created_at)
    ORDER BY 
        DATE(created_at) ASC";

$stmt = $conn->prepare($query);
$stmt->execute();
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Format data untuk Chart.js
$dates = [];
$totalIn = [];
$totalOut = [];

foreach ($data as $row) {
  $dates[] = $row['date'];
  $totalIn[] = $row['total_in'];
  $totalOut[] = $row['total_out'];
}

// Mengambil data penerimaan terbaru
$query_in = "
    SELECT 
        DATE(created_at) as date,
        price as total_price
    FROM 
        transaction
    WHERE 
        transaction_in_id IS NOT NULL
    ORDER BY 
        created_at DESC
    LIMIT 1";

$stmt_in = $conn->prepare($query_in);
$stmt_in->execute();
$data_in = $stmt_in->fetch(PDO::FETCH_ASSOC);

// Mengambil data pengeluaran terbaru
$query_out = "
    SELECT 
        DATE(created_at) as date,
        price as total_price
    FROM 
        transaction
    WHERE 
        transaction_out_id IS NOT NULL
    ORDER BY 
        created_at DESC
    LIMIT 1";

$stmt_out = $conn->prepare($query_out);
$stmt_out->execute();
$data_out = $stmt_out->fetch(PDO::FETCH_ASSOC);

// Fungsi untuk mengubah format nominal
function format_nominal($amount)
{
  if ($amount >= 1000000) {
    return number_format($amount / 1000000, 1, ',', '.') . ' juta';
  } elseif ($amount >= 1000) {
    return number_format($amount / 1000, 0, ',', '.') . ' ribu';
  } else {
    return number_format($amount, 0, ',', '.');
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Statistik Mingguan</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
</head>

<body>

  <div class="row g-4 mb-4">
    <div class="col-12 col-lg-6">
      <div class="app-card app-card-chart h-100 shadow-sm">
        <div class="app-card-header p-3">
          <div class="row justify-content-between align-items-center">
            <div class="col-auto">
              <h4 class="app-card-title">Statistik Mingguan</h4>
            </div>
          </div>
        </div>
        <div style="width: 80%; margin: auto;">
          <h2>Statistik Mingguan</h2>
          <canvas id="weeklyChart"></canvas>
        </div>
      </div>
    </div>
    <div class="col-12 col-lg-6">
      <div class="app-card app-card-stats-table h-50 shadow-sm">
        <div class="app-card-header p-3">
          <div class="row justify-content-between align-items-center">
            <div class="col-auto">
              <h4 class="app-card-title">Penerimaan</h4>
            </div>
          </div>
        </div>
        <div class="app-card-body p-3 p-lg-4">
          <div class="table-responsive">
            <table class="table table-borderless mb-0">
              <thead>
                <tr>
                  <th class="meta">Tanggal</th>
                  <th class="meta">Nominal</th>
                </tr>
              </thead>
              <tbody>
                <?php if ($data_in) : ?>
                  <tr>
                    <td>
                      <div class="intro meta"><?php echo date('d-m-Y', strtotime($data_in['date'])); ?></div>
                    </td>
                    <td>
                      <div class="intro meta"><?php echo format_nominal($data_in['total_price']); ?></div>
                    </td>
                  </tr>
                <?php else : ?>
                  <tr>
                    <td colspan="2">Tidak ada data penerimaan terbaru</td>
                  </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="app-card app-card-stats-table h-50 shadow-sm">
        <div class="app-card-header p-3">
          <div class="row justify-content-between align-items-center">
            <div class="col-auto">
              <h4 class="app-card-title">Pengeluaran</h4>
            </div>
          </div>
        </div>
        <div class="app-card-body p-3 p-lg-4">
          <div class="table-responsive">
            <table class="table table-borderless mb-0">
              <thead>
                <tr>
                  <th class="meta">Tanggal</th>
                  <th class="meta">Nominal</th>
                </tr>
              </thead>
              <tbody>
                <?php if ($data_out) : ?>
                  <tr>
                    <td>
                      <div class="meta"><?php echo date('d-m-Y', strtotime($data_out['date'])); ?></div>
                    </td>
                    <td>
                      <div class="meta"><?php echo format_nominal($data_out['total_price']); ?></div>
                    </td>
                  </tr>
                <?php else : ?>
                  <tr>
                    <td colspan="2">Tidak ada data pengeluaran terbaru</td>
                  </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script>
    // Data untuk Chart.js
    const labels = <?php echo json_encode($dates); ?>;
    const data = {
      labels: labels,
      datasets: [{
          label: 'Penerimaan',
          backgroundColor: 'rgba(54, 162, 235, 0.5)',
          borderColor: 'rgba(54, 162, 235, 1)',
          borderWidth: 1,
          data: <?php echo json_encode($totalIn); ?>
        },
        {
          label: 'Pengeluaran',
          backgroundColor: 'rgba(75, 192, 192, 0.5)',
          borderColor: 'rgba(75, 192, 192, 1)',
          borderWidth: 1,
          data: <?php echo json_encode($totalOut); ?>
        }
      ]
    };

    // Konfigurasi Chart.js
    const config = {
      type: 'bar',
      data: data,
      options: {
        scales: {
          y: {
            beginAtZero: true,
            max: 100 // Atur batas maksimal sesuai kebutuhan
          }
        },
        plugins: {
          tooltip: {
            callbacks: {
              label: function(context) {
                return context.dataset.label + ': ' + context.raw + '%';
              }
            }
          }
        }
      }
    };

    // Inisialisasi Chart.js
    const weeklyChart = new Chart(
      document.getElementById('weeklyChart'),
      config
    );
  </script>
</body>

</html>