<?php
session_start();

// Periksa apakah pengguna telah login
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    // Jika belum login, arahkan ke halaman login atau tampilkan pesan kesalahan
    header("Location: ../index.php"); // Ganti dengan lokasi halaman login Anda
    exit;
}
?>