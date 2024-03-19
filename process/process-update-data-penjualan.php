<?php
include '../koneksi.php';

if (isset($_POST['id_penjualan'], $_POST['dt_aktual'], $_POST['admin'], $_POST['merek'], $_POST['bulan'], $_POST['tahun'], $_POST['tipe'])) {
    $id_penjualan = $_POST['id_penjualan'];
    $dt_aktual = $_POST['dt_aktual'];
    $admin = $_POST['admin'];
    $merek = $_POST['merek'];
    $bulan = $_POST['bulan'];
    $tahun = $_POST['tahun'];
    $tipe = $_POST['tipe'];

    // Dapatkan ID barang berdasarkan merek dan tipe yang dipilih
    $sql_get_barang_id = "SELECT id_brg FROM dt_barang WHERE merek = '$merek' AND id_brg = '$tipe'";
    $result_get_barang_id = $conn->query($sql_get_barang_id);

    if ($result_get_barang_id && $result_get_barang_id->num_rows > 0) {
        $row = $result_get_barang_id->fetch_assoc();
        $id_brg = $row['id_brg'];

        // Update data penjualan dengan ID barang yang ditemukan
        $sql_update_data_penjualan = "UPDATE dt_penjualan SET dt_aktual = '$dt_aktual', admin = '$admin', bulan = '$bulan', tahun = '$tahun', id_brg = '$id_brg' WHERE id_penjualan = $id_penjualan";

        if ($conn->query($sql_update_data_penjualan) === TRUE) {
            header("Location: ../view/data-penjualan.php");
            exit();
        } else {
            echo "Gagal memperbarui data penjualan: " . $conn->error;
        }
    } else {
        // Tampilkan pesan jika tidak ada barang yang sesuai dengan merek dan tipe yang dipilih
        echo "Tidak ada barang yang sesuai dengan merek dan tipe yang dipilih.";
    }
} else {
    echo "Data yang diperlukan tidak lengkap.";
}

$conn->close();
?>
