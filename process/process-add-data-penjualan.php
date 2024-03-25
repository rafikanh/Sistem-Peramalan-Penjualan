<?php
include '../koneksi.php';

// Tangkap data yang dikirimkan dari formulir
$bulan = $_POST['bulan'];
$tahun = $_POST['tahun'];
$merek = $_POST['merek'];
$tipe = $_POST['tipe'];
$dt_aktual = $_POST['dt_aktual'];
$admin = $_POST['admin'];

// Lakukan operasi pengecekan apakah data sudah ada dalam database
$sql_check_duplicate = "SELECT * FROM dt_penjualan WHERE bulan = '$bulan' AND tahun = '$tahun' AND id_brg = '$tipe'";
$result_check_duplicate = $conn->query($sql_check_duplicate);

if ($result_check_duplicate->num_rows > 0) {
    // Jika data sudah ada, tampilkan pesan peringatan kepada pengguna
    header("Location: check_duplicate_data.php"); 
    exit();
} else {
    // Jika data belum ada, lakukan penyimpanan data baru ke dalam database
    $sql_get_id_brg = "SELECT id_brg FROM dt_barang WHERE merek = '$merek' AND id_brg = '$tipe'";
    $result_get_id_brg = $conn->query($sql_get_id_brg);

    if ($result_get_id_brg->num_rows > 0) {
        // Ambil id_brg dari hasil query
        $row = $result_get_id_brg->fetch_assoc();
        $id_brg = $row['id_brg'];

        // Query untuk menyimpan data ke dalam tabel dt_penjualan
        $sql_insert_data_penjualan = "INSERT INTO dt_penjualan (bulan, tahun, id_brg, dt_aktual, admin) 
                                    VALUES ('$bulan', '$tahun', '$id_brg', '$dt_aktual', '$admin')";

        // Eksekusi query
        if ($conn->query($sql_insert_data_penjualan) === TRUE) {
            // Redirect pengguna ke halaman data penjualan
            header("Location: ../view/data-penjualan.php");
            exit();
        } else {
            // Jika penyimpanan gagal, tampilkan pesan kesalahan
            echo "Gagal menyimpan data: " . $conn->error;
        }
    } else {
        // Jika id_brg tidak ditemukan, tampilkan pesan kesalahan
        echo "Gagal menyimpan data: ID Barang tidak ditemukan";
    }
}

// Tutup koneksi database
$conn->close();
