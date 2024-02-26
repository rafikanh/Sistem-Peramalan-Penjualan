<?php
// Sertakan file konfigurasi database
require_once "koneksi.php";

// Mulai session (jika belum dimulai)
session_start();

// Hapus semua data sesi
session_unset();

// Hancurkan sesi
session_destroy();

// Redirect ke halaman login atau halaman lain yang sesuai setelah logout
header("Location: login.php"); // Ganti login.php dengan halaman login atau halaman lain yang sesuai
exit();
?>