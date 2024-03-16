<?php
// Sertakan file koneksi
include '../koneksi.php';

// Cek apakah parameter id_penjualan ada dalam URL
if (isset($_GET['id_penjualan'])) {
    // Ambil nilai id_penjualan dari parameter URL
    $id_penjualan = $_GET['id_penjualan'];

    // Query SQL untuk menghapus data dari tabel dt_penjualan
    $query = "DELETE FROM dt_penjualan WHERE id_penjualan = $id_penjualan";

    // Eksekusi query
    if ($conn->query($query) === TRUE) {
        // Redirect ke halaman data-penjualan setelah menghapus
        header("Location: ../view/data-penjualan.php");
        exit();
    } else {
        echo "Error: " . $query . "<br>" . $conn->error;
    }
} else {
    // Jika parameter id_penjualan tidak ditemukan, tampilkan pesan error
    echo "Error: Parameter ID Penjualan tidak ditemukan.";
}

// Tutup koneksi
$conn->close();
?>
