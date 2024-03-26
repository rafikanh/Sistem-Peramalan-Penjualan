<?php
// Sertakan file konfigurasi database
require_once "../koneksi.php";

// Mulai sesi
session_start();

// Cek apakah form login disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Mengambil nilai dari form login
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Menggunakan fungsi md5 untuk hash password
    $hashedPassword = md5($password);

    // Query untuk memeriksa apakah username dan password valid
    $sql = "SELECT * FROM users WHERE email='$email' AND password='$hashedPassword'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Login berhasil
        $_SESSION['email'] = $email;
        $_SESSION['logged_in'] = true;

        // Periksa apakah ada URL yang diminta sebelumnya
        $redirect_url = isset($_SESSION['redirect_url']) ? $_SESSION['redirect_url'] : "../view/dashboard.php";
        
        // Alihkan pengguna ke halaman yang diminta sebelumnya atau halaman dashboard
        header("Location: $redirect_url");
        exit(); // Pastikan untuk keluar dari skrip setelah melakukan pengalihan
    } else {
        // Login gagal, tampilkan pesan kesalahan
        $errorMessage = "Email atau password salah. Mohon periksa kembali.";
    }
}
?>
