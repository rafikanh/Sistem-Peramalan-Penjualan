<?php
// Sertakan file koneksi
include '../koneksi.php';

// Periksa apakah ID pengguna disertakan di URL
if (isset($_GET['id'])) {
    // Ambil ID pengguna dari URL
    $userIDToDelete = $_GET['id'];

    // Query SQL untuk menghapus data pengguna berdasarkan ID
    $deleteQuery = "DELETE FROM users WHERE id = $userIDToDelete";

    // Lakukan query DELETE
    if ($conn->query($deleteQuery) === TRUE) {
        // Jika berhasil dihapus, arahkan kembali ke halaman user-manajemen.php
        header("Location: ../view/user-manajemen.php");
        exit();
    } else {
        // Jika terjadi kesalahan, tampilkan pesan error
        echo "Error: " . $deleteQuery . "<br>" . $conn->error;
    }
} else {
    echo "ID pengguna tidak disertakan dalam URL.";
    exit();
}

// Tutup koneksi
$conn->close();
?>
