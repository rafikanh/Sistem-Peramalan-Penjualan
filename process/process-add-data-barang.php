<?php
// Sertakan file koneksi
include '../koneksi.php';

// Cek apakah formulir dikirim
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari formulir
    $merek = isset($_POST['merek']) ? $_POST['merek'] : '';
    $tipe = isset($_POST['tipe']) ? $_POST['tipe'] : '';

    // Query SQL untuk menyimpan data ke dalam tabel dt_barang
    $query = "INSERT INTO dt_barang (merek, tipe) VALUES ('$merek', '$tipe')";

    // Eksekusi query
    if ($conn->query($query) === TRUE) {
        // Jika data berhasil disimpan, arahkan pengguna ke halaman data barang
        header("Location: ../view/data-barang.php");
        exit(); // Pastikan untuk keluar setelah mengarahkan pengguna
    } else {
        echo "Error: " . $query . "<br>" . $conn->error;
    }

    // Tutup koneksi
    $conn->close();
}
?>
