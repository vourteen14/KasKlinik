<?php
include './config/config.php';
$message = '';
$isPage = 'penerimaan-kas';

// Jika UUID dikirim melalui GET request
if (isset($_GET['id'])) {
  // Ambil UUID dari GET request
  $uuid = $_GET['id'];

  // Query untuk mengambil data dari database berdasarkan UUID
  $sql = "SELECT ti.*, p.fullname AS patient_name, a.diagnosis, t.comment AS transaction_comment
            FROM transaction_in ti
            JOIN action a ON ti.action_id = a.id
            JOIN patient p ON a.patient_id = p.id
            LEFT JOIN `transaction` t ON ti.id = t.transaction_in_id
            WHERE ti.id = :id";
  $stmt = $conn->prepare($sql);
  $stmt->bindParam(':id', $uuid);
  $stmt->execute();
  $transaction = $stmt->fetch(PDO::FETCH_ASSOC);

  // Pastikan data ditemukan
  if (!$transaction) {
    die("Data not found");
  }
}

// Jika form edit disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Ambil data dari form
  $price = $_POST['total_price'];
  $comment = $_POST['comment'];
  $uuid = $_POST['id'];

  // Debug: Cetak data yang diterima dari form
  echo "Data yang diterima dari form:<br>";
  echo "total_price: " . $price . "<br>";
  echo "comment: " . $comment . "<br>";
  echo "uuid: " . $uuid . "<br>";

  try {
    // Mulai transaksi
    $conn->beginTransaction();

    // Query untuk memperbarui data di tabel transaction_in
    $sqlTransactionIn = "UPDATE transaction_in 
                             SET total_price = :total_price 
                             WHERE id = :id";
    $stmtTransactionIn = $conn->prepare($sqlTransactionIn);
    $stmtTransactionIn->execute([
      ':id' => $uuid,
      ':total_price' => $price
    ]);

    // Debug: Cetak pesan setelah eksekusi query transaction_in
    $rowsAffectedTransactionIn = $stmtTransactionIn->rowCount();
    echo "Jumlah baris yang terpengaruh di transaction_in: " . $rowsAffectedTransactionIn . "<br>";

    // Query untuk memperbarui data di tabel transaction
    $sqlTransaction = "UPDATE transaction 
                           SET comment = :comment, 
                               price = :price 
                           WHERE transaction_in_id = :transaction_in_id";
    $stmtTransaction = $conn->prepare($sqlTransaction);
    $stmtTransaction->execute([
      ':transaction_in_id' => $uuid,
      ':comment' => $comment,
      ':price' => $price
    ]);

    // Debug: Cetak pesan setelah eksekusi query transaction
    $rowsAffectedTransaction = $stmtTransaction->rowCount();
    echo "Jumlah baris yang terpengaruh di transaction: " . $rowsAffectedTransaction . "<br>";

    // Commit transaksi
    $conn->commit();

    $message = "Transaksi berhasil diperbarui";
    header("Location: penerimaan-kas.php");
    exit();
  } catch (Exception $e) {
    // Rollback transaksi jika terjadi kesalahan
    $conn->rollBack();
    $message = "Gagal memperbarui transaksi: " . $e->getMessage();
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
  <?php if ($message) : ?>
    <script>
      alert('<?php echo $message; ?>');
    </script>
  <?php endif; ?>
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
        <h1 class="app-page-title">Edit Data Transaksi Pasien</h1>

        <form class="auth-form login-form" method="POST">
          <div class="row">
            <div class="col-12 col-lg-6">
              <div class="row">
                <div class="col-12 col-lg-6">
                  <div class="text mb-3">
                    <label class="form-label" for="id">Kode Nota</label>
                    <input readonly name="id" type="text" class="form-control" value="<?php echo $transaction['id']; ?>">
                  </div>
                </div>
                <div class="col-12 col-lg-6">
                  <div class="text mb-3">
                    <label class="form-label" for="tanggal">Tanggal</label>
                    <input id="tanggal" name="tanggal" type="text" value="<?php echo substr($transaction['created_at'], 0, 10); ?>" class="form-control" required="required" disabled>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-12 col-lg-6">
                  <div class="text mb-3">
                    <label class="form-label" for="pasien">Pasien</label>
                    <input id="pasien" name="pasien" type="text" class="form-control" value="<?php echo isset($transaction['patient_name']) ? $transaction['patient_name'] : ''; ?>" readonly>
                  </div>
                </div>

                <div class="col-12 col-lg-6">
                  <div class="text mb-3">
                    <label class="form-label" for="diagnosis">Diagnosis</label>
                    <input id="diagnosis" name="diagnosis" type="text" class="form-control" value="<?php echo isset($transaction['diagnosis']) ? $transaction['diagnosis'] : ''; ?>" readonly>
                  </div>
                </div>

              </div>
              <div class="row">
                <div class="col-12">
                  <div class="text mb-3">
                    <label class="form-label" for="comment">Catatan Transaksi</label>
                    <textarea id="comment" name="comment" type="text-area" class="form-control"><?php echo isset($transaction['transaction_comment']) ? $transaction['transaction_comment'] : ''; ?></textarea>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-12 col-lg-6">
              <div class="row">
                <div class="col-12 col-lg-6">
                  <div class="text mb-3">
                    <label class="form-label" for="doctor">Doctor</label>
                    <input id="doctor" name="doctor" type="text" class="form-control" required="required" value="<?php echo $transaction['doctor']; ?>" disabled>
                  </div>
                </div>
                <div class="col-12 col-lg-6">
                  <div class="text mb-3">
                    <label class="form-label" for="total_price">Harga</label>
                    <div class="input-group">
                      <div class="input-group-text">Rp.</div>
                      <input id="total_price" name="total_price" type="text" class="form-control" required="required" value="<?php echo $transaction['total_price']; ?>">
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-12 col-lg-6">
              <div class="text mb-3">
                <div class="pt-2">
                  <button type="submit" class="btn app-btn-primary w-100 theme-btn mx-auto">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-save" viewBox="0 0 16 16">
                      <path d="M8 5a.5.5 0 0 0-.5.5v5h-2a.5.5 0 0 0 0 1h2v2.5a.5.5 0 0 0 1 0V9h2a.5.5 0 0 0 0-1h-2V5.5A.5.5 0 0 0 8 5z" />
                      <path d="M3 1a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V4.414a1 1 0 0 0-.293-.707l-2-2A1 1 0 0 0 11.586 1H3zm1 1h6v3h-2a1 1 0 0 0-1 1v2H4V2z" />
                    </svg> Simpan
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
      </form>

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
</body>

</html>