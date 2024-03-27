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
        
        header("Location: ../view/dahsboard.php");
        exit(); 
    } else {
        // Login gagal, tampilkan pesan kesalahan
        $errorMessage = "Email atau password salah. Mohon periksa kembali.";
    }
}
?>
