<?php
// Memuat konfigurasi database
require_once './config/config.php';

// Fungsi untuk mengarahkan kembali ke halaman pengeluaran-kas.php dengan pesan
function redirectToExpensesPage($message)
{
  header("Location: penerimaan-kas.php?message=" . urlencode($message));
  exit();
}

// Fungsi untuk mengupdate saldo
function updateBalance($conn, $totalPrice)
{
  try {
    // Ambil saldo saat ini
    $stmt = $conn->query("SELECT balance FROM balance WHERE id = 1");
    $balance = $stmt->fetchColumn();

    // Perbarui saldo dengan menambahkan total harga
    $newBalance = $balance - $totalPrice;

    // Persiapkan dan jalankan query UPDATE
    $stmt = $conn->prepare("UPDATE balance SET balance = ?");
    $stmt->execute([$newBalance]);
  } catch (PDOException $e) {
    // Tangani kesalahan jika terjadi
    $errorMessage = "Error updating balance: " . $e->getMessage();
    throw new Exception($errorMessage);
  }
}

// Periksa apakah permintaan adalah metode POST dan apakah ID diterima
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
  // Ambil ID tindakan dari data yang dikirimkan
  $id = $_POST['id'];

  try {
    // Mulai transaksi
    $conn->beginTransaction();

    // Ambil total harga dari data yang akan dihapus
    $stmt = $conn->prepare("SELECT total_price FROM transaction_in WHERE id = ?");
    $stmt->execute([$id]);
    $totalPrice = $stmt->fetchColumn();

    // Persiapkan dan jalankan query DELETE
    $stmt = $conn->prepare("DELETE FROM transaction_in WHERE id = ?");
    $stmt->execute([$id]);

    // Update saldo
    updateBalance($conn, $totalPrice);

    // Commit transaksi
    $conn->commit();

    // Beri respons berhasil ke klien dan arahkan kembali ke halaman pengeluaran-kas.php
    redirectToExpensesPage("Tindakan berhasil dihapus.");
  } catch (Exception $e) {
    // Rollback transaksi jika terjadi kesalahan
    $conn->rollBack();

    // Beri respons gagal ke klien jika terjadi kesalahan
    $errorMessage = "Error deleting action: " . $e->getMessage();
    // Tambahkan pesan debug untuk mengetahui detail kesalahan
    echo $errorMessage;
    redirectToExpensesPage($errorMessage);
  }
} else {
  // Beri respons jika metode bukan POST atau ID tidak diterima
  redirectToExpensesPage("Permintaan tidak valid.");
}

// Tutup koneksi database
$conn = null;
