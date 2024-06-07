<?php
include './config/config.php';

?>

<div class="row g-4 mb-4">
	<div class="col-4 col-lg-4">
		<div class="app-card app-card-stat shadow-sm h-100">
			<div class="app-card-body p-3 p-lg-4">
				<h4 class="stats-type mb-1">Pengeluaran</h4>
				<?php
				$sql = "SELECT SUM(total_price) AS total FROM transaction_out";
				$result = $conn->query($sql);

				if ($result->rowCount() > 0) {
					// Mengambil data dari setiap baris
					while ($row = $result->fetch()) {
						$total = $row["total"];
						if ($total >= 1000000) {
							echo "<div class=\"stats-figure\">" . number_format($total / 1000000, 1, ',', '.') . " juta</div>";
						} elseif ($total >= 1000) {
							echo "<div class=\"stats-figure\">" . round($total / 1000) . " ribu</div>";
						} else {
							echo "<div class=\"stats-figure\">" . $total . "</div>";
						}
					}
				} else {
					echo "0 results";
				}
				?>
				<div class="stats-meta text-success">Rupiah</div>
			</div>
			<a class="app-card-link-mask" href="#"></a>
		</div>
	</div>
	<div class="col-4 col-lg-4">
		<div class="app-card app-card-stat shadow-sm h-100">
			<div class="app-card-body p-3 p-lg-4">
				<h4 class="stats-type mb-1">Penerimaan</h4>
				<?php
				$sql = "SELECT SUM(total_price) AS total FROM transaction_in";
				$result = $conn->query($sql);

				if ($result->rowCount() > 0) {
					// Mengambil data dari setiap baris
					while ($row = $result->fetch()) {
						$total = $row["total"];
						if ($total >= 1000000) {
							echo "<div class=\"stats-figure\">" . number_format($total / 1000000, 1, ',', '.') . " juta</div>";
						} elseif ($total >= 1000) {
							echo "<div class=\"stats-figure\">" . round($total / 1000) . " ribu</div>";
						} else {
							echo "<div class=\"stats-figure\">" . $total . "</div>";
						}
					}
				} else {
					echo "0 results";
				}
				?>
				<div class="stats-meta text-success">Rupiah</div>
			</div>
			<a class="app-card-link-mask" href="#"></a>
		</div>
	</div>
	<div class="col-4 col-lg-4">
		<div class="app-card app-card-stat shadow-sm h-100">
			<div class="app-card-body p-3 p-lg-4">
				<h4 class="stats-type mb-1">Saldo</h4>
				<div class="stats-figure">100 juta</div>
				<div class="stats-meta text-success">Rupiah</div>
			</div>
			<a class="app-card-link-mask" href="#"></a>
		</div>
	</div>
</div>

<div class="row g-4 mb-4">
	<div class="col-6 col-lg-6">
		<div class="app-card app-card-stat shadow-sm h-100">
			<div class="app-card-body p-3 p-lg-4">
				<h4 class="stats-type mb-1">Pasien</h4>
				<?php
				$sql = "SELECT COUNT(id) AS total FROM patient";
				$result = $conn->query($sql);

				if ($result->rowCount() > 0) {
					// Mengambil data dari setiap baris
					while ($row = $result->fetch()) {
						$total = $row["total"];
						echo "<div class=\"stats-figure\">" . $total . "</div>";
					}
				} else {
					echo "0 results";
				}
				?>
			</div>
			<a class="app-card-link-mask" href="#"></a>
		</div>
	</div>
	<div class="col-6 col-lg-6">
		<div class="app-card app-card-stat shadow-sm h-100">
			<div class="app-card-body p-3 p-lg-4">
				<h4 class="stats-type mb-1">Tindakan</h4>
				<?php
				$sql = "SELECT COUNT(id) AS total FROM action";
				$result = $conn->query($sql);

				if ($result->rowCount() > 0) {
					// Mengambil data dari setiap baris
					while ($row = $result->fetch()) {
						$total = $row["total"];
						echo "<div class=\"stats-figure\">" . $total . "</div>";
					}
				} else {
					echo "0 results";
				}
				?>
			</div>
			<a class="app-card-link-mask" href="#"></a>
		</div>
	</div>
</div>