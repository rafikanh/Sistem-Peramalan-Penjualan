<?php
// Sertakan file koneksi
include '../koneksi.php';

session_start();

// Cek apakah formulir dikirim
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari formulir

    $data = isset($_POST['data']) ? json_decode($_POST['data']) : [];

    $duplicate = array();
    $dataImport = array();

    if ($data != []) {
        for ($i = 1; $i < count($data); $i++) {
            $currentMerk = $data[$i][0];
            $currentType = $data[$i][1];
            $currentBulan = $data[$i][2];
            $currentTahun = $data[$i][3];
            $currentActual = $data[$i][4];

            //cek apakah barang sudah ada
            $sqlCheckDuplicateBarang = "SELECT * FROM dt_barang WHERE merek='$currentMerk' AND tipe='$currentType'";
            $resCheckDuplicateBarang = $conn->query($sqlCheckDuplicateBarang);

            //jika belum, input ke database
            if ($resCheckDuplicateBarang->num_rows === 0) {
                $sqlInsertBarang = "INSERT INTO dt_barang (merek, tipe) VALUES ('$currentMerk', '$currentType')";
                $conn->query($sqlInsertBarang);
            }

            //ambil id barang untuk dilakukan insert data penjualan
            $sqlGetIdBarang = "SELECT id_brg FROM dt_barang WHERE merek='$currentMerk' AND tipe='$currentType'";
            $resGetIdBarang = $conn->query($sqlGetIdBarang);

            if ($resGetIdBarang->num_rows > 0) {
                $row = $resGetIdBarang->fetch_assoc();
                $id_brg = $row['id_brg'];

                //check apakah data dengan bulan, tahun, dan id barang yang di import sudah ada di database
                $sqlCheckDuplicateSale = "SELECT * FROM dt_penjualan WHERE bulan = '$currentBulan' AND tahun = '$currentTahun' AND id_brg = '$id_brg'";
                $resCheckDuplicateSale = $conn->query($sqlCheckDuplicateSale);

                if ($resCheckDuplicateSale->num_rows === 0) {

                    $id_user = $_SESSION['id'];
                    $sqlInsertSale = "INSERT INTO dt_penjualan (bulan, tahun, id_brg, dt_aktual, id_user) VALUES ('$currentBulan', '$currentTahun', '$id_brg', '$currentActual', '$id_user')";
                    $conn->query($sqlInsertSale);
                }
            }
        }
        echo json_encode(
            array(
                "status" => "success",
                "message" => "Data berhasil diimpor"
            )
        );
    } else {
        echo json_encode(
            array(
                "status" => "error",
                "message" => "Error: " . $conn->error
            )
        );
    }

    // Tutup koneksi
    $conn->close();
}