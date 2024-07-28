<?php
include './config/config.php';
$message = '';
$isPage = 'penerimaan-kas';

$payments = ['BPJS', 'Asuransi', 'Umum'];

// Mengambil data dari tabel action untuk dropdown
$sql = "SELECT a.id, p.fullname, a.diagnosis, a.notes 
        FROM action a 
        JOIN patient p ON a.patient_id = p.id";
$stmt = $conn->prepare($sql);
$stmt->execute();
$actions = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fungsi untuk membuat UUID
function generateUUID()
{
  return bin2hex(random_bytes(16)); // Generate 32-character hexadecimal string
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Mengambil data dari form
  $action_id = $_POST['pasien'];
  $price = $_POST['total_price'];
  $comment = $_POST['comment'];

  // Membuat UUID
  $uuid = generateUUID();

  try {
    // Mulai transaksi
    $conn->beginTransaction();

    // Menambahkan data ke tabel transaction_in
    $sqlTransactionIn = "INSERT INTO transaction_in (id, action_id, doctor, total_price, created_at) 
                           VALUES (:id, :action_id, 'Dr. Achmad Irawan', :total_price, NOW())";
    $stmtTransactionIn = $conn->prepare($sqlTransactionIn);
    $stmtTransactionIn->execute([
      ':id' => $uuid,
      ':action_id' => $action_id,
      ':total_price' => $price
    ]);

    // Menambahkan data ke tabel transaction
    $sqlTransaction = "INSERT INTO transaction (transaction_in_id, type, comment, price, created_at) 
                         VALUES (:transaction_in_id, 'IN', :comment, :price, NOW())";
    $stmtTransaction = $conn->prepare($sqlTransaction);
    $stmtTransaction->execute([
      ':transaction_in_id' => $uuid,
      ':comment' => $comment,
      ':price' => $price
    ]);

    // Commit transaksi
    $conn->commit();

    $message = "Transaksi berhasil ditambahkan";
  } catch (Exception $e) {
    // Rollback transaksi jika terjadi kesalahan
    $conn->rollBack();
    $message = "Gagal menambahkan transaksi: " . $e->getMessage();
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <title><?php echo $isPage; ?></title>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Portal - Bootstrap 5 Admin Dashboard Template For Developers">
  <meta name="author" content="Xiaoying Riley at 3rd Wave Media">
  <link rel="shortcut icon" href="favicon.ico">
  <link id="theme-style" rel="stylesheet" href="assets/css/portal.css">
  <link id="theme-style" rel="stylesheet" href="assets/css/custom.css">
  <script defer src="assets/plugins/fontawesome/js/all.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body class="app">
  <header class="app-header fixed-top">
    <div class="app-header-inner">
      <div class="container-fluid py-2">
        <div class="app-header-content">
          <div class="row justify-content-between align-items-center">
            <div class="col-auto">
              <a id="sidepanel-toggler" class="sidepanel-toggler d-inline-block d-xl-none" href="#">
                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 30 30" role="img">
                  <title>Menu</title>
                  <path stroke="currentColor" stroke-linecap="round" stroke-miterlimit="10" stroke-width="2" d="M4 7h22M4 15h22M4 23h22"></path>
                </svg>
              </a>
            </div>
            <?php include './component/profile-icon.php'; ?>
          </div>
        </div>
      </div>
    </div>
    <div id="app-sidepanel" class="app-sidepanel">
      <div id="sidepanel-drop" class="sidepanel-drop"></div>
      <div class="sidepanel-inner d-flex flex-column">
        <a href="#" id="sidepanel-close" class="sidepanel-close d-xl-none">&times;</a>
        <?php include './sidebar/sidebar-header.php'; ?>
        <?php include './sidebar/sidebar-body.php'; ?>
        <?php include './sidebar/sidebar-footer.php'; ?>
      </div>
    </div>
  </header>
  <div class="app-wrapper">
    <div class="app-content pt-3 p-md-3 p-lg-4">
      <div class="container-xl">
        <h1 class="app-page-title">Input Data Transaksi Pasien</h1>

        <form class="auth-form login-form" method="POST">
          <div class="row">
            <div class="col-12 col-lg-6">
              <div class="row">
                <div class="col-12 col-lg-6">
                  <div class="text mb-3">
                    <label class="form-label" for="doctor">Doctor</label>
                    <input id="doctor" name="doctor" type="text" class="form-control" required="required" value="Dr. Achmad Irawan" disabled>
                  </div>
                </div>
                <div class="col-12 col-lg-6">
                  <div class="text mb-3">
                    <label class="form-label" for="tanggal">Tanggal</label>
                    <input id="tanggal" value="<?php echo date('Y-m-d'); ?>" name="tanggal" type="text" class="form-control" required="required" disabled>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-12 col-lg-6">
                  <div class="text mb-3">
                    <label class="form-label" for="pasien">Pasien</label>
                    <select id="pasien" name="pasien" class="form-select w-100">
                      <?php foreach ($actions as $index => $row) : ?>
                        <option value="<?php echo $row['id']; ?>"><?php echo $row['id'] . ', ' . $row['fullname']; ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                </div>
                <div class="col-12 col-lg-6">
                  <div class="text mb-3">
                    <label class="form-label" for="diagnosis">Tindakan</label>
                    <select id="diagnosis" name="diagnosis" class="form-select w-100">
                      <?php foreach ($actions as $index => $row) : ?>
                        <option value="<?php echo $row['diagnosis']; ?>" data-patient="<?php echo $row['id']; ?>"><?php echo $row['diagnosis']; ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-12 col-lg-6">
                  <div class="text mb-3">
                    <div class="pt-2">
                      <button type="submit" class="btn app-btn-primary w-100 theme-btn mx-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-lg" viewBox="0 0 16 16">
                          <path fill-rule="evenodd" d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2" />
                        </svg> Tambahkan Transaksi
                      </button>
                    </div>
                  </div>
                </div>
                <div class="col-12 col-lg-6">
                  <div class="text mb-3">
                    <div class="pt-2">
                      <a href="./penerimaan-kas.php" class="btn app-btn-secondary w-100 theme-btn mx-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                          <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8" />
                        </svg> Kembali
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-12 col-lg-6">
              <div class="row">
                <div class="col-12 col-lg-6">
                  <div class="text mb-3">
                    <label class="form-label" for="payment">Metode Pembayaran</label>
                      <select id="payment" name="payment">
                        <?php foreach ($options as $option): ?>
                            <option value="<?php echo htmlspecialchars($option); ?>">
                                <?php echo htmlspecialchars($option); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                  </div>
                </div>
                <div class="col-12 col-lg-6">
                  <div class="text mb-3">
                    <label class="form-label" for="total_price">Harga</label>
                    <div class="input-group">
                      <div class="input-group-text">Rp.</div>
                      <input id="total_price" name="total_price" type="text" class="form-control" required="required">
                    </div>
                  </div>
                </div>
                <div class="col-12 col-lg-6">
                  <div class="text mb-3">
                    <label class="form-label" for="notes">Catatan Pasien</label>
                    <select id="notes" name="notes" class="form-select w-100">
                      <?php foreach ($actions as $index => $row) : ?>
                        <option value="<?php echo $row['notes']; ?>" data-patient="<?php echo $row['id']; ?>"><?php echo $row['notes']; ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                </div>
                <div class="col-12 col-lg-6">
                  <div class="text mb-3">
                    <label class="form-label" for="comment">Catatan Transaksi</label>
                    <textarea id="comment" name="comment" type="text-area" class="form-control"></textarea>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </form>
        <?php if ($message) : ?>
          <div class="alert alert-info mt-3">
            <?php echo $message; ?>
          </div>
        <?php endif; ?>
      </div>
    </div>
    <footer class="app-footer">
      <?php include './footer.php'; ?>
    </footer>
  </div>
  <script src="assets/plugins/popper.min.js"></script>
  <script src="assets/plugins/jquery-3.5.1.slim.min.js"></script>
  <script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>
  <script src="assets/plugins/chart.js/chart.min.js"></script>
  <script src="assets/js/charts-demo.js"></script>
  <script src="assets/js/app.js"></script>
  <script src="assets/js/custom.js"></script>
  <script>
    $(document).ready(function() {
      $('#pasien').change(function() {
        var selectedPatient = $(this).val();
        $('#diagnosis option').each(function() {
          if ($(this).data('patient') == selectedPatient) {
            $(this).show();
          } else {
            $(this).hide();
          }
        });
        $('#notes option').each(function() {
          if ($(this).data('patient') == selectedPatient) {
            $(this).show();
          } else {
            $(this).hide();
          }
        });
      }).change();
    });
  </script>
</body>

</html>