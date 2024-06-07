<?php
include './config/config.php';
include './config/config.php';
$isPage = 'penerimaan-kas';

// Fungsi untuk mendapatkan data dari database
function getDataFromDatabase($page, $itemsPerPage, $searchQuery)
{
	global $conn; // Use the $conn variable from the config file

	// Start from where the data will be fetched
	$offset = ($page - 1) * $itemsPerPage;

	// Build the SQL query to fetch data
	$sql = "SELECT t.id, a.notes, a.diagnosis, a.medicine, t.created_at, t.doctor, t.total_price, p.fullname AS nama_pasien
        FROM transaction_in t
        INNER JOIN action a ON t.action_id = a.id
        INNER JOIN patient p ON a.patient_id = p.id
        WHERE 
        t.doctor LIKE '%$searchQuery%' OR 
        t.total_price LIKE '%$searchQuery%' OR
        a.notes LIKE '%$searchQuery%' OR
        a.diagnosis LIKE '%$searchQuery%' OR
        a.medicine LIKE '%$searchQuery%'
        LIMIT $offset, $itemsPerPage";

	// Run the query
	$result = $conn->query($sql);

	$data = []; // Initialize $data as an empty array

	// Check the number of result rows
	if ($result->rowCount() > 0) {
		// Loop through the query result and store it in the array
		while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
			$data[] = $row;
		}
	}

	// Return the data
	return $data;
}

// Fungsi untuk render pagination
function renderPagination($page, $totalPages, $searchQuery)
{
	include './component/pagination.php';
}

// Ambil data dari database (contoh)
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';
$itemsPerPage = 10; // Tetapkan nilai itemsPerPage di sini
$data = getDataFromDatabase($page, $itemsPerPage, $searchQuery);

$totalItems = count($data);
$totalPages = ceil($totalItems / $itemsPerPage);
$offset = ($page - 1) * $itemsPerPage;
$currentItems = array_slice($data, $offset, $itemsPerPage);
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
				<div class="row g-3 mb-4 align-items-center justify-content-between">
					<div class="col-auto">
						<h1 class="app-page-title mb-0">Transaksi Penerimaan Kas Masuk</h1>
					</div>
					<div class="col-auto">
						<div class="page-utilities">
							<div class="row g-2 justify-content-start justify-content-md-end align-items-center">
								<div class="col-auto">
									<?php include './component/pasien-search-box.php'; ?>
								</div>
								<div class="col-auto">
									<a class="btn app-btn-primary" href="./penerimaan-kas-input.php">
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
												<th class="cell">ID Transaksi</th>
												<th class="cell">Nama Pasien</th>
												<th class="cell">Dokter</th>
												<th class="cell">Diagnosis</th>
												<th class="cell">Total Harga</th>
												<th class="cell">Aksi</th>
											</tr>
										</thead>
										<tbody>
											<?php foreach ($currentItems as $index => $row) : ?>
												<tr>
													<td class="cell"><?php echo ($offset + $index + 1) ?></td>
													<td class="cell"><?php echo htmlspecialchars($row['id']); ?></td>
													<td class="cell"><?php echo htmlspecialchars($row['nama_pasien']); ?></td>
													<td class="cell"><?php echo htmlspecialchars($row['doctor']); ?></td>
													<td class="cell"><?php echo htmlspecialchars($row['diagnosis']); ?></td>
													<td class="cell"><?php echo htmlspecialchars($row['total_price']); ?></td>
													<td class="cell">
														<div class="d-flex justify-content-between w-50">
															<a class="btn-sm app-btn-primary me-1" href="./penerimaan-kas-edit.php?id=<?php echo htmlspecialchars($row['id'] ?? ''); ?>">Edit</a>
															<form method="POST" action="penerimaan-kas-delete.php" onsubmit="return confirm('Are you sure you want to delete this patient?')">
																<input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id'] ?? ''); ?>">
																<button type="submit" class="btn-sm app-btn-secondary ms-1" name="delete">Delete</button>
															</form>
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