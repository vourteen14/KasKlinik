<?php
// Memuat konfigurasi database
require_once './config/config.php';

// Fungsi untuk mengarahkan kembali ke halaman data-tindakan.php dengan pesan
function redirectToActionPage($message)
{
  header("Location: penerimaan-kas.php?message=" . urlencode($message));
  exit();
}

// Periksa apakah permintaan adalah metode POST dan apakah ID diterima
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
  // Ambil ID tindakan dari data yang dikirimkan
  $id = $_POST['id'];

  try {
    // Persiapkan dan jalankan query DELETE
    $stmt = $conn->prepare("DELETE FROM transaction_in WHERE id = ?");
    $stmt->execute([$id]);

    // Beri respons berhasil ke klien dan arahkan kembali ke halaman data-tindakan.php
    redirectToActionPage("Tindakan berhasil dihapus.");
  } catch (PDOException $e) {
    // Beri respons gagal ke klien jika terjadi kesalahan
    $errorMessage = "Error deleting action: " . $e->getMessage();
    // Tambahkan pesan debug untuk mengetahui detail kesalahan
    echo $errorMessage;
    redirectToActionPage($errorMessage);
  }
} else {
  // Beri respons jika metode bukan POST atau ID tidak diterima
  redirectToActionPage("Permintaan tidak valid.");
}

// Tutup koneksi database
$conn = null;
