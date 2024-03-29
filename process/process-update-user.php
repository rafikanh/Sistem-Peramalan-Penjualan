<?php
// Sertakan file koneksi
include '../koneksi.php';

// Tangkap data dari formulir
$userID = isset($_POST['userID']) ? $_POST['userID'] : '';
$nama_depan = isset($_POST['nm_depan']) ? $_POST['nm_depan'] : '';
$nama_belakang = isset($_POST['nm_belakang']) ? $_POST['nm_belakang'] : '';
$email = $_POST['email'];
$newPassword = isset($_POST['ganti_password']) ? $_POST['ganti_password'] : ''; // Password baru dari form "Ganti Password"

// Query SQL untuk mengambil data pengguna berdasarkan ID
$query = "SELECT * FROM users WHERE id = $userID";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    // Ambil data pengguna
    $row = $result->fetch_assoc();
    $password = $row['password']; // Password dari database (sudah dienkripsi)

    // Jika password baru dimasukkan, enkripsi menggunakan MD5
    if (!empty($newPassword)) {
        $password = md5($newPassword);
    }

    // Query SQL untuk memperbarui data pengguna berdasarkan ID
    $query = "UPDATE users SET nm_depan='$nama_depan', nm_belakang ='$nama_belakang', email = '$email', password = '$password' WHERE id = $userID";

    if ($conn->query($query) === TRUE) {
        // Jika berhasil diperbarui, arahkan kembali ke halaman user-manajemen.php
        header("Location: ../view/user-manajemen.php");
        exit();
    } else {
        // Jika terjadi kesalahan, tampilkan pesan error
        echo "Error: " . $query . "<br>" . $conn->error;
    }
} else {
    echo "Data pengguna tidak ditemukan.";
    exit();
}

// Tutup koneksi
$conn->close();
?>
