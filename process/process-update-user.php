<?php
// Sertakan file koneksi
include '../koneksi.php';

// Tangkap data dari formulir
$userID = isset($_POST['userID']) ? $_POST['userID'] : ''; // Periksa apakah sudah diatur
$email = $_POST['email'];
$password = $_POST['password']; // Password masih dalam format plaintext

// Jika password baru dimasukkan, enkripsi menggunakan MD5
if (!empty($password)) {
    $password = md5($password);
}

// Query SQL untuk memperbarui data pengguna berdasarkan ID
$query = "UPDATE users SET email = '$email', password = '$password' WHERE id = $userID";

if ($conn->query($query) === TRUE) {
    // Jika berhasil diperbarui, arahkan kembali ke halaman user-manajemen.php
    header("Location: ../view/user-manajemen.php");
    exit();
} else {
    // Jika terjadi kesalahan, tampilkan pesan error
    echo "Error: " . $query . "<br>" . $conn->error;
}

// Tutup koneksi
$conn->close();
?>
