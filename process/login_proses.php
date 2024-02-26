<?php
// Sertakan file konfigurasi database
require_once "../koneksi.php";

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
    session_start();
    $_SESSION['email'] = $email;
    header("Location: ../view/dashboard.php"); // Ganti dashboard.php dengan halaman setelah login
} else {
    // Login gagal
    echo "Email atau password salah.";
}

$conn->close();
?>
