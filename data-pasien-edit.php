<?php
require './config/config.php';
$message = '';
$isPage = 'data-pasien';

// Initialize variables
$patientData = [
  'id' => '',
  'fullname' => '',
  'category' => '',
  'address' => '',
  'phone' => ''
];

if (isset($_GET['id'])) {
  $patientId = $_GET['id'];

  try {
    $fetchPatientQuery = "SELECT * FROM patient WHERE id = :id";
    $stmt = $conn->prepare($fetchPatientQuery);
    $stmt->execute([':id' => $patientId]);
    $patientData = $stmt->fetch(PDO::FETCH_ASSOC);

    // Split the address into kecamatan and desa
    $addressParts = explode(', ', $patientData['address']);
    $patientData['kecamatan'] = $addressParts[0];
    $patientData['desa'] = $addressParts[1];
  } catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
  }
}

// Initialize alert variables
$successMessage = '';
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id = $_POST['id'];
  $fullname = $_POST['fullname'];
  $kecamatan = $_POST['kecamatan'];
  $desa = $_POST['desa'];
  $phone = $_POST['phone'];
  $asuransi = $_POST['idasuransi'];

  try {
    // Combine kecamatan and desa into the address
    $address = $kecamatan . ', ' . $desa;

    $updateQuery = "UPDATE patient SET fullname = :fullname, address = :address, phone = :phone, assurance = :idasuransi WHERE patient_id = :id";
    $stmt = $conn->prepare($updateQuery);
    $stmt->execute([
      ':fullname' => $fullname,
      ':address' => $address,
      ':phone' => $phone,
      ':id' => $id,
      ':idasuransi' => $asuransi
    ]);
    // Set success message
    $message = 'Pasien berhasil diperbarui';
    // Redirect to data-pasien.php after update
    header('Location: ./data-pasien.php');
    exit();
  } catch (PDOException $e) {
    // Set error message
    $message = 'Gagal memperbarui pasien: ' . $e->getMessage();
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
        <h1 class="app-page-title">Edit Data Pasien</h1>
        <form class="auth-form login-form" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
          <div class="row">
            <div class="col-12 col-lg-6">
              <div class="text mb-3">
                <label class="form-label" for="id">ID Pasien</label>
                <input id="id" name="id" type="text" class="form-control readonly-field" value="<?php echo htmlspecialchars($patientData['patient_id']); ?>" required="required" readonly>
              </div>
              <div class="text mb-3">
                <label class="form-label" for="fullname">Nama Pasien</label>
                <input id="fullname" name="fullname" type="text" class="form-control" value="<?php echo htmlspecialchars($patientData['fullname']); ?>" required="required">
              </div>
              <div class="text mb-3">
                <label class="form-label" for="category">Category</label>
                <input id="category" name="category" type="text" class="form-control" value="<?php echo htmlspecialchars($patientData['category']); ?>" required="required" readonly>
              </div>
            </div>
            <div class="col-12 col-lg-6">
              <div class="col-12 col-lg-6">
                <div class="row">
                  <div class="col-12 col-lg-6">
                    <div class="text mb-3">
                      <label class="form-label" for="kecamatan">Kecamatan</label>
                      <input id="kecamatan" name="kecamatan" type="text" class="form-control" value="<?php echo htmlspecialchars($patientData['kecamatan']); ?>" required="required">
                    </div>
                  </div>
                  <div class="col-12 col-lg-6">
                    <div class="text mb-3">
                      <label class="form-label" for="desa">Desa</label>
                      <input id="desa" name="desa" type="text" class="form-control" value="<?php echo htmlspecialchars($patientData['desa']); ?>" required="required">
                    </div>
                  </div>
                </div>
              </div>

              <div class="text">
                <label class="form-label" for="phone">Nomor Phone</label>
                <input id="phone" name="phone" type="text" class="form-control" value="<?php echo htmlspecialchars($patientData['phone']); ?>" required="required">
              </div>
              <div class="text pt-3">
                <label class="form-label" for="idasuransi">ID Asuransi/BPJS (Optional untuk Umum)</label>
                <input id="idasuransi" name="idasuransi" type="text" placeholder="BPJS-<nomor bpjs>, Alliaz-<nomor alliaz>, dsb " value="<?php echo htmlspecialchars($patientData['assurance']); ?>" class="form-control">
              </div>
              <div class="pt-2">
                <button type="submit" class="btn app-btn-primary w-100 theme-btn mx-auto">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                    <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                    <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                  </svg> Edit
                </button>
              </div>
              <div class="pt-1">
                <a href="./data-pasien.php" class="btn app-btn-secondary w-100 theme-btn mx-auto">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8" />
                  </svg> Kembali
                </a>
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
  <script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>
  <script src="assets/plugins/chart.js/chart.min.js"></script>
  <script src="assets/js/index-charts.js"></script>
  <script src="assets/js/app.js"></script>
</body>

</html>