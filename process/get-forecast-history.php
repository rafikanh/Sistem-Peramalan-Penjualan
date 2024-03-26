<?php
// Sertakan file koneksi ke database
include '../koneksi.php';

// Periksa apakah parameter merek dan id_brg telah dikirim melalui metode POST
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Query SQL untuk mengambil data history
    $sql = "SELECT 
    dh.id_brg, 
    db.merek, 
    db.tipe, 
    DATE_FORMAT(STR_TO_DATE(CONCAT_WS('-', dh.tahun, dh.bulan, '01'), '%Y-%m-%d'), '%M %Y') AS bulan_tahun, 
    dh.res_forecast,
    dh.mape 
    FROM dt_history AS dh INNER JOIN dt_barang as db ON dh.id_brg = db.id_brg
    ORDER BY dh.id_history DESC
    ";

    // Lakukan query ke database
    $result = $conn->query($sql);

    // Periksa apakah query berhasil dieksekusi
    if ($result === false) {
        die("Error executing the query: " . $conn->error);
    }

    // Inisialisasi array untuk menyimpan data
    $dataHistory = [];

    // Loop untuk menyimpan setiap baris data dalam array
    while ($row = $result->fetch_assoc()) {
        $dataHistory[] = $row;
    }

    // Kembalikan data penjualan dalam format JSON
    echo json_encode($dataHistory);
} else {
    // Jika parameter tidak lengkap, kembalikan pesan error
    echo "Error: Merek dan id_brg tidak tersedia.";
}