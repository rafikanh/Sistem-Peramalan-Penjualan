<?php
include '../koneksi.php';

// Tangkap data yang dikirimkan dari formulir
$bulan = $_POST['bulan'];
$tahun = $_POST['tahun'];
$tipe = $_POST['tipe'];

// Lakukan operasi pengecekan apakah data sudah ada dalam database
$sql_check_duplicate = "SELECT * FROM dt_penjualan WHERE bulan = '$bulan' AND tahun = '$tahun' AND id_brg = '$tipe'";
$result_check_duplicate = $conn->query($sql_check_duplicate);

// Buat variabel untuk menampung hasil pengecekan
$isDataExists = false;

// Jika query berhasil dieksekusi dan jumlah baris hasil query lebih dari 0, maka data sudah ada
if ($result_check_duplicate && $result_check_duplicate->num_rows > 0) {
    $isDataExists = true;
}

// Kirimkan respons ke JavaScript
echo json_encode($isDataExists);

// Tutup koneksi database
$conn->close();
?>
