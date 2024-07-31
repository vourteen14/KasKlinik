<?php
include './config/config.php';
$isPage = 'laporan';

// Fungsi untuk mendapatkan total item dari database
function getTotalItems($searchQuery)
{
	global $conn; // Gunakan variabel $conn dari file config

	// SQL query untuk menghitung total item berdasarkan search query
	$sql = "SELECT COUNT(*) AS total
            FROM patient 
            INNER JOIN action ON patient.id = action.patient_id
            INNER JOIN transaction_in ON action.id = transaction_in.action_id
            WHERE 
                patient.fullname LIKE :searchQuery OR
                patient.address LIKE :searchQuery OR
                patient.phone LIKE :searchQuery OR
                patient.category LIKE :searchQuery";

	$stmt = $conn->prepare($sql);
	$searchTerm = '%' . $searchQuery . '%';
	$stmt->bindParam(':searchQuery', $searchTerm, PDO::PARAM_STR);
	$stmt->execute();
	$result = $stmt->fetch(PDO::FETCH_ASSOC);

	return $result['total'];
}

// Fungsi untuk mendapatkan data dari database
function getDataFromDatabase($page, $itemsPerPage, $searchQuery)
{
	global $conn; // Gunakan variabel $conn dari file config

	// Mulai dari mana data akan diambil
	$offset = ($page - 1) * $itemsPerPage;

	// Bangun query SQL untuk mengambil data
	$sql = "SELECT 
                patient.id AS patient_id,
                patient.fullname,
                patient.address,
                patient.phone,
                patient.category,
                transaction_in.id AS transaction_id,
                transaction_in.total_price
            FROM 
                patient
            INNER JOIN 
                action ON patient.id = action.patient_id
            INNER JOIN 
                transaction_in ON action.id = transaction_in.action_id
            WHERE 
                patient.fullname LIKE :searchQuery OR
                patient.address LIKE :searchQuery OR
                patient.phone LIKE :searchQuery OR
                patient.category LIKE :searchQuery
            LIMIT :offset, :itemsPerPage";

	$stmt = $conn->prepare($sql);
	$searchTerm = '%' . $searchQuery . '%';
	$stmt->bindParam(':searchQuery', $searchTerm, PDO::PARAM_STR);
	$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
	$stmt->bindParam(':itemsPerPage', $itemsPerPage, PDO::PARAM_INT);
	$stmt->execute();

	$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

	return $data;
}

// Fungsi untuk render pagination
function renderPagination($page, $totalPages, $searchQuery)
{
	include './component/pagination.php';
}

// Ambil data dari database
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';
$itemsPerPage = 10; // Tetapkan nilai itemsPerPage di sini

$totalItems = getTotalItems($searchQuery); // Hitung total item dari database berdasarkan search query
$totalPages = ceil($totalItems / $itemsPerPage);
$data = getDataFromDatabase($page, $itemsPerPage, $searchQuery);

$offset = ($page - 1) * $itemsPerPage; // Menghitung offset untuk nomor baris

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
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
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
						<h1 class="app-page-title mb-0">Daftar Laporan</h1>
					</div>
					<div class="col-auto">
						<div class="page-utilities">
							<div class="row g-2 justify-content-start justify-content-md-end align-items-center">
								<div class="col-auto">
									<?php include './component/search-box.php'; ?>
								</div>
								<div class="col-auto">
									<button class="btn app-btn-primary" onclick="generateCSV()">Download CSV</button>
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
												<th class="cell">Kode Pasien</th>
												<th class="cell">Nama Pasien</th>
												<th class="cell">Tempat Tinggal</th>
												<th class="cell">Nomor Telepon</th>
												<th class="cell">Kategori</th>
												<th class="cell">Transaksi</th>
												<th class="cell">Harga</th>
												<th class="cell">Aksi</th>
											</tr>
										</thead>
										<tbody>
											<?php foreach ($data as $index => $row) : ?>
												<tr id="row-<?php echo $index; ?>">
													<td class="cell"><?php echo ($offset + $index + 1); ?></td>
													<td class="cell"><?php echo htmlspecialchars($row['patient_id']); ?></td>
													<td class="cell"><?php echo htmlspecialchars($row['fullname']); ?></td>
													<td class="cell"><?php echo htmlspecialchars($row['address']); ?></td>
													<td class="cell"><?php echo htmlspecialchars($row['phone']); ?></td>
													<td class="cell"><?php echo htmlspecialchars($row['category']); ?></td>
													<td class="cell"><?php echo htmlspecialchars($row['transaction_id']); ?></td>
													<td class="cell"><?php echo htmlspecialchars($row['total_price']); ?></td>
													<td class="cell">
														<div class="d-flex justify-content-between w-50">
															<button class="btn app-btn-primary" onclick="generateInvoice(<?php echo $index; ?>)">Receipt</button>
															<?php if(htmlspecialchars($row['category']) == "Asuransi") { ?>
																	<button class="ms-1 btn app-btn-primary" onclick="generateBilling(<?php echo $index; ?>)">Receipt</button>
															<?php } ?>
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
	<script>
		function generateCSV() {
			var csv = [];
			var rows = document.querySelectorAll("table tr");

			for (var i = 0; i < rows.length; i++) {
				var row = [],
					cols = rows[i].querySelectorAll("td, th");
				for (var j = 0; j < cols.length; j++)
					row.push(cols[j].innerText);
				csv.push(row.join(","));
			}

			// Download CSV file
			downloadCSV(csv.join("\n"), 'table.csv');
		}

		function downloadCSV(csv, filename) {
			var csvFile;
			var downloadLink;

			// CSV file
			csvFile = new Blob([csv], {
				type: "text/csv"
			});

			// Download link
			downloadLink = document.createElement("a");

			// File name
			downloadLink.download = filename;

			// Create a link to the file
			downloadLink.href = window.URL.createObjectURL(csvFile);

			// Hide download link
			downloadLink.style.display = "none";

			// Add the link to the DOM
			document.body.appendChild(downloadLink);

			// Click download link
			downloadLink.click();
		}

		function generateBilling(index) {
			// Ambil data untuk baris yang dipilih
			var row = <?php echo json_encode($data); ?>[index];
			
			// Hitung total jumlah dengan biaya tambahan
			var totalPrice = parseFloat(row['total_price']);
			var biayaAdministrasi = 10000; // Biaya administrasi
			var biayaLayanan = 5000; // Biaya layanan
			var jumlahTotal = totalPrice + biayaAdministrasi + biayaLayanan;

			// Buat jendela baru untuk pernyataan tagihan
			var billingWindow = window.open('', '_blank');
			billingWindow.document.write('<html><head><title>Tagihan</title>');
			billingWindow.document.write('<style>');
			billingWindow.document.write('body { font-family: Arial, sans-serif; margin: 20px; }');
			billingWindow.document.write('.header { margin-bottom: 30px; }');
			billingWindow.document.write('.title { text-align: center; font-size: 28px; font-weight: bold; margin-bottom: 20px; }');
			billingWindow.document.write('.label { font-weight: bold; }');
			billingWindow.document.write('.value { margin-left: 10px; }');
			billingWindow.document.write('table { border-collapse: collapse; width: 100%; margin-top: 20px; }');
			billingWindow.document.write('th, td { border: 1px solid #dddddd; text-align: left; padding: 8px; }');
			billingWindow.document.write('th { background-color: #f4f4f4; }');
			billingWindow.document.write('tr:nth-child(even) { background-color: #f9f9f9; }');
			billingWindow.document.write('</style>');
			billingWindow.document.write('</head><body>');

			// Tambahkan judul dan informasi header
			billingWindow.document.write('<div class="title">Tagihan</div>');
			billingWindow.document.write('<div class="header">');
			billingWindow.document.write('<p><span class="label">ID Asuransi/No:</span><span class="value">' + row['insurance_id'] + '</span></p>');
			billingWindow.document.write('<p><span class="label">Tipe Asuransi:</span><span class="value">' + row['insurance_type'] + '</span></p>');
			billingWindow.document.write('<p><span class="label">Nama Lengkap:</span><span class="value">' + row['fullname'] + '</span></p>');
			billingWindow.document.write('<p><span class="label">Alamat:</span><span class="value">' + row['address'] + '</span></p>');
			billingWindow.document.write('<p><span class="label">Telepon:</span><span class="value">' + row['phone'] + '</span></p>');
			billingWindow.document.write('</div>');

			// Tambahkan tabel untuk item tagihan
			billingWindow.document.write('<table>');
			billingWindow.document.write('<thead><tr><th>Deskripsi</th><th>Jumlah</th></tr></thead><tbody>');

			// Tambahkan item tagihan untuk baris yang dipilih
			billingWindow.document.write('<tr><td>Biaya Konsultasi (Sebelum Biaya Tambahan)</td><td>' + totalPrice.toFixed(2) + '</td></tr>');
			billingWindow.document.write('<tr><td>Biaya Administrasi</td><td>' + biayaAdministrasi.toFixed(2) + '</td></tr>');
			billingWindow.document.write('<tr><td>Biaya Layanan</td><td>' + biayaLayanan.toFixed(2) + '</td></tr>');
			billingWindow.document.write('<tr><td>Jumlah Total (Setelah Biaya Tambahan)</td><td>' + jumlahTotal.toFixed(2) + '</td></tr>');

			// Tutup tabel dan body
			billingWindow.document.write('</tbody></table>');
			billingWindow.document.write('</body></html>');

			// Tutup dokumen
			billingWindow.document.close();
			billingWindow.print();
		}


		function generateInvoice(index) {
			// Get data for the selected row
			var row = <?php echo json_encode($data); ?>[index];

			// Create a new window for the invoice
			var invoiceWindow = window.open('', '_blank');
			invoiceWindow.document.write('<html><head><title>Invoice</title>');
			invoiceWindow.document.write('<style>');
			invoiceWindow.document.write('body { font-family: Arial, sans-serif; margin: 20px; }');
			invoiceWindow.document.write('.invoice-container { width: 80%; margin: 0 auto; }');
			invoiceWindow.document.write('.header { margin-bottom: 30px; }');
			invoiceWindow.document.write('.title { text-align: center; font-size: 24px; font-weight: bold; margin-bottom: 20px; }');
			invoiceWindow.document.write('.header p { margin: 5px 0; }');
			invoiceWindow.document.write('.label { display: inline-block; width: 150px; font-weight: bold; }');
			invoiceWindow.document.write('.value { display: inline-block; margin-left: 10px; }');
			invoiceWindow.document.write('table { border-collapse: collapse; width: 100%; margin-top: 20px; }');
			invoiceWindow.document.write('th, td { border: 1px solid #dddddd; text-align: left; padding: 8px; }');
			invoiceWindow.document.write('th { background-color: #f2f2f2; }');
			invoiceWindow.document.write('tr:nth-child(even) { background-color: #f9f9f9; }');
			invoiceWindow.document.write('</style>');
			invoiceWindow.document.write('</head><body>');

			// Add invoice container
			invoiceWindow.document.write('<div class="invoice-container">');

			// Add title and header information
			invoiceWindow.document.write('<div class="title">Invoice</div>');
			invoiceWindow.document.write('<div class="header">');
			invoiceWindow.document.write('<p><span class="label">Invoice ID:</span><span class="value">' + row['transaction_id'] + '</span></p>');
			invoiceWindow.document.write('<p><span class="label">Date:</span><span class="value">' + new Date().toLocaleDateString() + '</span></p>');
			invoiceWindow.document.write('<p><span class="label">Doctor:</span><span class="value">Dr. Achmad Irawan</span></p>');
			invoiceWindow.document.write('</div>');

			// Add table for invoice items
			invoiceWindow.document.write('<table>');
			invoiceWindow.document.write('<thead><tr><th>Fullname</th><th>Address</th><th>Phone</th><th>Category</th><th>Price</th></tr></thead><tbody>');

			// Add invoice item for the selected row
			invoiceWindow.document.write('<tr><td>' + row['fullname'] + '</td><td>' + row['address'] + '</td><td>' + row['phone'] + '</td><td>' + row['category'] + '</td><td>' + row['total_price'] + '</td></tr>');

			// Close table and body
			invoiceWindow.document.write('</tbody></table>');
			invoiceWindow.document.write('</div>'); // Close invoice container
			invoiceWindow.document.write('</body></html>');

			// Close the document
			invoiceWindow.document.close();
			invoiceWindow.print();
		}


	</script>


</body>
<?php include './component/dialog.php'; ?>

</html>