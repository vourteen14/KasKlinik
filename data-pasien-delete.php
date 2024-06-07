<?php
// Memuat konfigurasi database
require_once './config/config.php';

// Fungsi untuk mengarahkan kembali ke halaman data-pasien.php
function redirectToPatientPage($message)
{
  header("Location: data-pasien.php?message=" . urlencode($message));
  exit();
}

// Periksa apakah permintaan adalah metode POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
  // Ambil ID pasien dari data yang dikirimkan
  $id = $_POST['id'];

  try {
    // Persiapkan dan jalankan query DELETE
    $stmt = $conn->prepare("DELETE FROM patient WHERE id = ?");
    $stmt->execute([$id]);

    // Beri respons berhasil ke klien dan arahkan kembali ke halaman data-pasien.php
    redirectToPatientPage("Patient deleted successfully.");
  } catch (PDOException $e) {
    // Beri respons gagal ke klien jika terjadi kesalahan
    echo ("Error deleting patient: " . $e->getMessage());
  }
} else {
  // Beri respons jika metode bukan POST atau ID tidak diterima
  redirectToPatientPage("Invalid request.");
}

// Tutup koneksi database
$conn = null;
