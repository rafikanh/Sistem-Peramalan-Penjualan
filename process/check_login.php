<?php
// Mulai sesi
session_start();

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    
    header("Location: ../index.php");
    exit(); // Pastikan untuk keluar setelah melakukan pengalihan
}
?>