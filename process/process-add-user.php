<?php
// Sertakan file koneksi
include '../koneksi.php';

// Tangkap data dari formulir
$email = $_POST['email'];
$password = md5($_POST['password']); // Gunakan md5 untuk menyimpan password secara terenkripsi

// Query SQL untuk menyimpan data ke database
$query = "INSERT INTO users (email, password) VALUES ('$email', '$password')";

if ($conn->query($query) === TRUE) {
    // Jika berhasil disimpan, arahkan kembali ke halaman user-manajemen.php
    header("Location: ../view/user-manajemen.php");
    exit();
} else {
    // Jika terjadi kesalahan, tampilkan pesan error
    echo "Error: " . $query . "<br>" . $conn->error;
}

// Tutup koneksi
$conn->close();
?>
