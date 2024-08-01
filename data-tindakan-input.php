<?php
include './config/config.php'; // Menghubungkan ke database

$isPage = 'data-tindakan';
$message = '';
// Mengambil data dari tabel patient
$sql = "SELECT id, patient_id, fullname FROM patient";
$stmt = $conn->prepare($sql);
$stmt->execute();

$id = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($id)) {
  echo "0 results";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Validasi input
  if (isset($_POST['pasien'], $_POST['catatan'], $_POST['diagnosa'], $_POST['obat'])) {
    $patient_id = $_POST['pasien'];
    $notes = $_POST['catatan'];
    $diagnosis = $_POST['diagnosa'];
    $medicine = $_POST['obat'];

    // Menyimpan data ke tabel action
    $sql = "INSERT INTO action (patient_id, notes, diagnosis, medicine) VALUES (:patient_id, :notes, :diagnosis, :medicine)";
    try {
      $stmt = $conn->prepare($sql);
      $stmt->bindParam(':patient_id', $patient_id);
      $stmt->bindParam(':notes', $notes);
      $stmt->bindParam(':diagnosis', $diagnosis);
      $stmt->bindParam(':medicine', $medicine);

      if ($stmt->execute())

        $message = "Tindakan berhasil ditambahkan";
    } catch (PDOException $e) {
      $message = "Gagal menambahkan tindakan: " . $e->getMessage();
    }
  }

  $conn = null; // Menutup koneksi
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
        <h1 class="app-page-title">Input Data Tindakan</h1>
        <form class="auth-form login-form" method="POST">
          <div class="row">
            <div class="col-12 col-lg-6">
              <div class="text mb-3">
                <label class="form-label" for="pasien">Pasien</label>
                <select id='pasien' name='pasien' class="form-select w-100">
                  <?php foreach ($id as $index => $row) : ?>
                    <option value="<?php echo $row['id']; ?>"><?php echo $row['patient_id'] . ', ' . $row['fullname']; ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="text mb-3">
                <label class="form-label" for="catatan">Catatan</label>
                <textarea class="form-control h-25" id="catatan" name="catatan" rows="6" required></textarea>
              </div>
            </div>
            <div class="col-12 col-lg-6">
              <div class="text mb-3">
                <label class="form-label" for="diagnosa">Diagnosa</label>
                <input id="diagnosa" name="diagnosa" type="text" class="form-control" required="required">
              </div>
              <div class="text mb-4">
                <label class="form-label" for="obat">Obat</label>
                <input id="obat" name="obat" type="text" class="form-control" required="required">
              </div>
              <div class="pt-2">
                <button type="submit" class="btn app-btn-primary w-100 theme-btn mx-auto"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-lg" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2" />
                  </svg> Tambah</button>
              </div>
              <div class="pt-2">
                <a href="./data-tindakan.php" class="btn app-btn-secondary w-100 theme-btn mx-auto"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8" />
                  </svg> Kembali</a>
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