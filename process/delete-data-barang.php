<?php
// Sertakan file koneksi
include '../koneksi.php';

// Cek apakah parameter id ada dalam URL
if (isset($_GET['id'])) {
    // Ambil nilai id dari parameter URL
    $id = $_GET['id'];

    // Query SQL untuk menghapus data dari tabel dt_barang
    $query = "DELETE FROM dt_barang WHERE id_brg = $id";

    // Eksekusi query
    if ($conn->query($query) === TRUE) {
        // Redirect ke halaman data-barang setelah menghapus
        header("Location: ../view/data-barang.php");
        exit();
    } else {
        echo "Error: " . $query . "<br>" . $conn->error;
    }
} else {
    // Jika parameter id tidak ditemukan, tampilkan pesan error
    echo "Error: Parameter ID tidak ditemukan.";
}

// Tutup koneksi
$conn->close();
