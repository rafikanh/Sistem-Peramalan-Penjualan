<?php
// Sertakan file koneksi ke database
include '../koneksi.php';

// Periksa apakah request methodnya GET
if ($_SERVER["REQUEST_METHOD"] == "GET") {

    //ambil parameter keyword
    $keyword = $_GET['keyword'];

    // Query SQL untuk mengambil data history
    $sql = "SELECT 
    dh.id_brg, 
    db.merek, 
    db.tipe, 
    DATE_FORMAT(STR_TO_DATE(CONCAT_WS('-', dh.tahun, dh.bulan, '01'), '%Y-%m-%d'), '%M %Y') AS bulan_tahun, 
    dh.res_forecast,
    dh.mape 
    FROM dt_history AS dh INNER JOIN dt_barang as db ON dh.id_brg = db.id_brg
    WHERE db.merek LIKE '%$keyword%' OR db.tipe LIKE '%$keyword%' OR DATE_FORMAT(STR_TO_DATE(CONCAT_WS('-', dh.tahun, dh.bulan, '01'), '%Y-%m-%d'), '%M %Y') LIKE '%$keyword%'
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
    // Kembalikan pesan error
    echo "Error: Request not allowed.";
}