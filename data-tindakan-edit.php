<?php
include './config/config.php'; // Menghubungkan ke database

$isPage = 'data-tindakan';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Tangani permintaan POST untuk memperbarui data
  $action_id = $_POST['action_id'];
  $patient_id = $_POST['patient_id'];
  $notes = $_POST['notes'];
  $diagnosis = $_POST['diagnosis'];
  $medicine = $_POST['medicine'];

  $sql = "UPDATE action SET patient_id = :patient_id, notes = :notes, diagnosis = :diagnosis, medicine = :medicine WHERE id = :action_id";
  $stmt = $conn->prepare($sql);
  $stmt->bindParam(':action_id', $action_id);
  $stmt->bindParam(':patient_id', $patient_id);
  $stmt->bindParam(':notes', $notes);
  $stmt->bindParam(':diagnosis', $diagnosis);
  $stmt->bindParam(':medicine', $medicine);

  if ($stmt->execute()) {
    echo "Data berhasil diperbarui!";
    // Redirect kembali ke halaman data-tindakan.php
    header("Location: data-tindakan.php");
    exit();
  } else {
    echo "Terjadi kesalahan saat memperbarui data!";
  }
} else {
  // Tangani permintaan GET untuk menampilkan form edit
  $action_id = $_GET['id'];

  $sql = "SELECT a.id AS action_id, a.patient_id, p.fullname, a.notes, a.diagnosis, a.medicine
            FROM action a
            JOIN patient p ON a.patient_id = p.id
            WHERE a.id = :action_id";
  $stmt = $conn->prepare($sql);
  $stmt->bindParam(':action_id', $action_id);
  $stmt->execute();
  $data = $stmt->fetch(PDO::FETCH_ASSOC);

  if (!$data) {
    echo "Data tidak ditemukan!";
    exit();
  }
}

// Mengambil data pasien untuk dropdown
$sqlPatients = "SELECT id, fullname FROM patient";
$stmtPatients = $conn->prepare($sqlPatients);
$stmtPatients->execute();
$patients = $stmtPatients->fetchAll(PDO::FETCH_ASSOC);

$conn = null; // Menutup koneksi
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
        <h1 class="app-page-title">Edit Data Tindakan</h1>
        <form class="auth-form login-form" method="POST">
          <input type="hidden" name="action_id" value="<?php echo htmlspecialchars($data['action_id']); ?>">
          <div class="row">
            <div class="col-12 col-lg-6">
              <!--<div class="text mb-3">
                <label class="form-label" for="patient_id">Pasien</label>
                <select id='patient_id' name='patient_id' class="form-select w-100">
                <?php foreach ($patients as $patient) : ?>
                    <option value="<?php echo $patient['id']; ?>" <?php echo $patient['id'] == $data['patient_id'] ? 'selected' : ''; ?>>
                      <?php echo htmlspecialchars($patient['id']) . ', ' . htmlspecialchars($patient['fullname']); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>-->

              <div class="text mb-3">
                <label class="form-label" for="patient_id">Pasien</label>
                <?php foreach ($patients as $patient) : ?>
                  <?php if ($patient['id'] == $data['patient_id']) : ?>
                    <!-- <label class="form-label w-100"><?php echo htmlspecialchars($patient['id']) . ', ' . htmlspecialchars($patient['fullname']); ?></label>
                    <input name="patient_id" value="<?php echo $patient['id']; ?>"> -->
                    <input name="patient_id" type="text" class="form-control" value="<?php echo htmlspecialchars($patient['id']) . ', ' . htmlspecialchars($patient['fullname']); ?>" readonly>
                  <?php endif; ?>
                <?php endforeach; ?>
              </div>

              <div class="text mb-3">
                <label class="form-label" for="notes">Catatan</label>
                <textarea class="form-control h-25" id="notes" name="notes" rows="6"><?php echo htmlspecialchars($data['notes']); ?></textarea>
              </div>
            </div>
            <div class="col-12 col-lg-6">
              <div class="text mb-3">
                <label class="form-label" for="diagnosis">Diagnosa</label>
                <input id="diagnosis" name="diagnosis" type="text" class="form-control" value="<?php echo htmlspecialchars($data['diagnosis']); ?>" required="required">
              </div>
              <div class="text mb-4">
                <label class="form-label" for="medicine">Obat</label>
                <input id="medicine" name="medicine" type="text" class="form-control" value="<?php echo htmlspecialchars($data['medicine']); ?>" required="required">
              </div>
              <div class="pt-2">
                <button type="submit" class="btn app-btn-primary w-100 theme-btn mx-auto">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-save" viewBox="0 0 16 16">
                    <path d="M8 5a.5.5 0 0 0-.5.5v5h-2a.5.5 0 0 0 0 1h2v2.5a.5.5 0 0 0 1 0V9h2a.5.5 0 0 0 0-1h-2V5.5A.5.5 0 0 0 8 5z" />
                    <path d="M3 1a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V4.414a1 1 0 0 0-.293-.707l-2-2A1 1 0 0 0 11.586 1H3zm1 1h6v3h-2a1 1 0 0 0-1 1v2H4V2z" />
                  </svg> Simpan
                </button>
              </div>
              <div class="pt-2">
                <a href="./data-tindakan.php" class="btn app-btn-secondary w-100 theme-btn mx-auto">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z" />
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