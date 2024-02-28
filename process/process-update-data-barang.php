<?php
// Sertakan file koneksi
include '../koneksi.php';

// Cek apakah formulir dikirim dengan metode POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari formulir
    $id = $_POST['id'];
    $merek = $_POST['merek'];
    $tipe = $_POST['tipe'];

    // Gunakan prepared statement untuk mencegah SQL injection
    $query = $conn->prepare("UPDATE dt_barang SET merek = ?, tipe = ? WHERE id_brg = ?");
    $query->bind_param("ssi", $merek, $tipe, $id);

    // Eksekusi query
    if ($query->execute()) {
        // Jika berhasil diperbarui, kembali ke halaman data barang
        header("Location: ../view/data-barang.php");
        exit();
    } else {
        echo "Error: " . $query->error;
    }

    // Tutup statement
    $query->close();
}

// Tutup koneksi
$conn->close();
