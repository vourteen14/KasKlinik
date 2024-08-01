<?php
include './config/config.php';
$message = '';
$isPage = 'pengeluaran-kas';
$uuids =  '00000-' . str_pad(rand(0, 65535), 4, '0', STR_PAD_LEFT) . '-' . str_pad(rand(0, 65535), 7, '0', STR_PAD_LEFT);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Ambil data dari form
  $uuid = $_POST['id'];
  $catatan = $_POST['catatan'];
  $suppliers = $_POST['suppliers'];
  $total_price = $_POST['total_price'];
  $transaction_in_id = !empty($_POST['transaction_in_id']) ? $_POST['transaction_in_id'] : null;

  // Generate UUID untuk `transaction_out` id

  try {
    // Mulai transaksi
    $conn->beginTransaction();

    // Query untuk menambahkan data ke tabel transaction_out
    $sqlTransactionOut = "INSERT INTO transaction_out (id, information, total_price, suppliers) 
                              VALUES (:id, :information, :total_price, :suppliers)";
    $stmtTransactionOut = $conn->prepare($sqlTransactionOut);
    $stmtTransactionOut->execute([
      ':id' => $uuid,
      ':information' => $catatan,
      ':total_price' => $total_price,
      ':suppliers' => $suppliers
    ]);

    // Query untuk menambahkan data ke tabel transaction
    $sqlTransaction = "INSERT INTO transaction (transaction_out_id, type, comment, suppliers, price, transaction_in_id) 
                           VALUES (:transaction_out_id, 'OUT', :comment, :suppliers, :price, :transaction_in_id)";
    $stmtTransaction = $conn->prepare($sqlTransaction);
    $stmtTransaction->execute([
      ':transaction_out_id' => $uuid,
      ':comment' => $catatan,
      ':suppliers' => $suppliers,
      ':price' => $total_price,
      ':transaction_in_id' => $transaction_in_id
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
  <script defer src="assets/plugins/fontawesome/js/all.min.js"></script>
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
        <h1 class="app-page-title">Input Pengeluaran Kas</h1>
        <form class="auth-form login-form" method="POST">
          <div class="row">
            <div class="col-12 col-lg-6">
              <div class="text mb-3">
                <label class="form-label" for="id">ID Transaksi</label>
                <input id="id" name="id" type="text" class="form-control" value="<?php echo $uuids; ?>" readonly>
              </div>
              <div class="text mb-3">
                <label class="form-label" for="catatan">Catatan</label>
                <textarea id="catatan" name="catatan" class="form-control" required="required" rows="4"></textarea>
              </div>
            </div>
            <div class="col-12 col-lg-6">
              <div class="text mb-3">
                <label class="form-label" for="suppliers">Supplier</label>
                <input id="suppliers" name="suppliers" type="text" class="form-control" required="required">
              </div>
              <div class="text">
                <label class="form-label" for="total_price">Total Harga</label>
                <input id="total_price" name="total_price" type="text" class="form-control" required="required">
              </div>
              <div class="pt-2">
                <button type="submit" class="btn app-btn-primary w-100 theme-btn mx-auto">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-lg" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2" />
                  </svg> Tambah
                </button>
              </div>
              <div class="pt-1">
                <a href="./pengeluaran-kas.php" class="btn app-btn-secondary w-100 theme-btn mx-auto">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8" />
                  </svg> Kembali
                </a>
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
  <script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>
  <script src="assets/plugins/chart.js/chart.min.js"></script>
  <script src="assets/js/index-charts.js"></script>
  <script src="assets/js/app.js"></script>
</body>

</html>