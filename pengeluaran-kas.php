<?php

$isPage = 'pengeluaran-kas';

$data = [
	['kode_nota' => '2887552', 'nama_pasien' => 'Udin', 'nama_dokter' => 'Asep', 'tindakan' => 'suntik dengan obat A', 'subtotal' => '50000'],
	['kode_nota' => '2887553', 'nama_pasien' => 'Rina', 'nama_dokter' => 'Budi', 'tindakan' => 'pemeriksaan fisik', 'subtotal' => '75000'],
	['kode_nota' => '2887554', 'nama_pasien' => 'Siti', 'nama_dokter' => 'Cici', 'tindakan' => 'tes darah', 'subtotal' => '60000'],
	['kode_nota' => '2887555', 'nama_pasien' => 'Eko', 'nama_dokter' => 'Dewi', 'tindakan' => 'röntgen', 'subtotal' => '80000'],
	['kode_nota' => '2887556', 'nama_pasien' => 'Fajar', 'nama_dokter' => 'Eka', 'tindakan' => 'konsultasi', 'subtotal' => '45000'],
	['kode_nota' => '2887557', 'nama_pasien' => 'Gita', 'nama_dokter' => 'Fandi', 'tindakan' => 'pemeriksaan mata', 'subtotal' => '70000'],
	['kode_nota' => '2887558', 'nama_pasien' => 'Hani', 'nama_dokter' => 'Gina', 'tindakan' => 'tes urine', 'subtotal' => '55000'],
	['kode_nota' => '2887559', 'nama_pasien' => 'Iwan', 'nama_dokter' => 'Hendra', 'tindakan' => 'pemeriksaan gigi', 'subtotal' => '65000'],
	['kode_nota' => '2887560', 'nama_pasien' => 'Joko', 'nama_dokter' => 'Indra', 'tindakan' => 'konsultasi gizi', 'subtotal' => '70000'],
	['kode_nota' => '2887561', 'nama_pasien' => 'Kiki', 'nama_dokter' => 'Joni', 'tindakan' => 'pemeriksaan darah', 'subtotal' => '60000'],
	['kode_nota' => '2887562', 'nama_pasien' => 'Lina', 'nama_dokter' => 'Krisna', 'tindakan' => 'tes kolesterol', 'subtotal' => '55000'],
	['kode_nota' => '2887563', 'nama_pasien' => 'Mira', 'nama_dokter' => 'Luki', 'tindakan' => 'pemeriksaan jantung', 'subtotal' => '75000'],
	['kode_nota' => '2887564', 'nama_pasien' => 'Nina', 'nama_dokter' => 'Mila', 'tindakan' => 'konsultasi psikologi', 'subtotal' => '80000'],
	['kode_nota' => '2887565', 'nama_pasien' => 'Oscar', 'nama_dokter' => 'Nina', 'tindakan' => 'pemeriksaan kulit', 'subtotal' => '65000'],
	['kode_nota' => '2887566', 'nama_pasien' => 'Puput', 'nama_dokter' => 'Oki', 'tindakan' => 'tes alergi', 'subtotal' => '60000'],
	['kode_nota' => '2887567', 'nama_pasien' => 'Rudi', 'nama_dokter' => 'Pipit', 'tindakan' => 'pemeriksaan pencernaan', 'subtotal' => '70000'],
	['kode_nota' => '2887568', 'nama_pasien' => 'Sari', 'nama_dokter' => 'Qori', 'tindakan' => 'konsultasi kehamilan', 'subtotal' => '75000'],
];

$itemsPerPage = 10;
$totalItems = count($data);
$totalPages = ceil($totalItems / $itemsPerPage);

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
if ($page > $totalPages) $page = $totalPages;

$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';

$filteredData = array_filter($data, function ($item) use ($searchQuery) {
	return stripos($item['kode'], $searchQuery) !== false ||
		stripos($item['nama'], $searchQuery) !== false ||
		stripos($item['tempat'], $searchQuery) !== false ||
		stripos($item['telepon'], $searchQuery) !== false ||
		stripos($item['kategori'], $searchQuery) !== false;
});

$totalItems = count($filteredData);
$totalPages = ceil($totalItems / $itemsPerPage);
$offset = ($page - 1) * $itemsPerPage;
$currentItems = array_slice($filteredData, $offset, $itemsPerPage);

function renderPagination($page, $totalPages, $searchQuery)
{
	include './component/pagination.php';
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
	<title>Portal - Bootstrap 5 Admin Dashboard Template For Developers</title>
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
				<div class="row g-3 mb-4 align-items-center justify-content-between">
					<div class="col-auto">
						<h1 class="app-page-title mb-0">Transaksi Pengeluaran Kas Keluar</h1>
					</div>
					<div class="col-auto">
						<div class="page-utilities">
							<div class="row g-2 justify-content-start justify-content-md-end align-items-center">
								<div class="col-auto">
									<?php include './component/pasien-search-box.php'; ?>
								</div>
								<div class="col-auto">
									<a class="btn app-btn-primary" href="./pengeluaran-kas-input.php">
										<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-lg" viewBox="0 0 16 16">
											<path fill-rule="evenodd" d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2" />
										</svg> Tambah
									</a>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="tab-content" id="orders-table-tab-content">
					<div class="tab-pane fade show active" id="orders-all" role="tabpanel" aria-labelledby="orders-all-tab">
						<div class="app-card app-card-orders-table shadow-sm mb-5">
							<div class="app-card-body">
								<div class="table-responsive">
									<table class="table app-table-hover mb-0 text-left">
										<thead>
											<tr>
												<th class="cell">No</th>
												<th class="cell">Kode Nota</th>
												<th class="cell">Nama Pasien</th>
												<th class="cell">Dokter</th>
												<th class="cell">Tindakan</th>
												<th class="cell">Subtotal</th>
												<th class="cell">Aksi</th>
											</tr>
										</thead>
										<tbody>
											<?php foreach ($currentItems as $index => $row) : ?>
												<tr>
													<td class="cell"><?php echo ($offset + $index + 1) ?></td>
													<td class="cell"><?php echo htmlspecialchars($row['kode_nota']); ?></td>
													<td class="cell"><?php echo htmlspecialchars($row['nama_pasien']); ?></td>
													<td class="cell"><?php echo htmlspecialchars($row['nama_dokter']); ?></td>
													<td class="cell"><?php echo htmlspecialchars($row['tindakan']); ?></td>
													<td class="cell"><?php echo htmlspecialchars($row['subtotal']); ?></td>
													<td class="cell">
														<div class="d-flex justify-content-between w-50">
															<a class="btn-sm app-btn-primary me-1" href="./pengeluaran-kas-edit.php?id=<?php echo htmlspecialchars($row['kode']); ?>">Edit</a>
															<button class="btn-sm app-btn-secondary ms-1" onclick="showDialog(this, <?php echo htmlspecialchars($row['kode']); ?>)">Delete</button>
														</div>
													</td>
												</tr>
											<?php endforeach; ?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
						<?php renderPagination($page, $totalPages, $searchQuery); ?>
					</div>
				</div>
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
<?php include './component/dialog.php'; ?>

</html>