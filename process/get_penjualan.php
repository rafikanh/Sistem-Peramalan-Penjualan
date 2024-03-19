<?php
// Sertakan file koneksi ke database
include '../koneksi.php';

// Periksa apakah parameter merek dan id_brg telah dikirim melalui metode POST
if (isset($_POST['merek']) && isset($_POST['id_brg'])) {
    // Escape string untuk mencegah SQL injection
    $merek = $conn->real_escape_string($_POST['merek']);
    $id_brg = $conn->real_escape_string($_POST['id_brg']);

    // Query SQL untuk mengambil data penjualan berdasarkan merek dan id_brg
    $sql = "SELECT DATE_FORMAT(STR_TO_DATE(CONCAT_WS('-', dp.tahun, dp.bulan, '01'), '%Y-%m-%d'), '%M %Y') AS bulan_tahun, dp.dt_aktual
        FROM dt_penjualan AS dp
        INNER JOIN dt_barang AS db ON dp.id_brg = db.id_brg
        WHERE db.merek = '$merek' AND db.id_brg = '$id_brg'
        ORDER BY dp.tahun, dp.bulan";

    // Lakukan query ke database
    $result = $conn->query($sql);

    // Periksa apakah query berhasil dieksekusi
    if ($result === false) {
        die("Error executing the query: " . $conn->error);
    }

    // Inisialisasi array untuk menyimpan data penjualan
    $dataPenjualan = [];

    // Loop untuk menyimpan setiap baris data penjualan dalam array
    while ($row = $result->fetch_assoc()) {
        $dataPenjualan[] = $row;
    }

    // Kembalikan data penjualan dalam format JSON
    echo json_encode($dataPenjualan);
} else {
    // Jika parameter tidak lengkap, kembalikan pesan error
    echo "Error: Merek dan id_brg tidak tersedia.";
}
?>
