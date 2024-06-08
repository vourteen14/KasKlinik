<?php
session_start();

// Menghapus semua variabel sesi
$_SESSION = array();

// Jika ada cookie yang diset untuk mengingat pengguna, hapus juga
if (isset($_COOKIE['user_id'])) {
  setcookie('user_id', '', time() - 3600, "/"); // Hapus cookie dengan mengatur waktu kedaluwarsa di masa lalu
}

// Menghapus sesi
session_destroy();

// Redirect ke halaman login atau halaman lain setelah logout
header("Location: login.php");
exit();
