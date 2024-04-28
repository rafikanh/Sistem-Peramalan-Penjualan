<?php
// Sertakan file koneksi
include '../koneksi.php';

session_start();

// Cek apakah formulir dikirim
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari formulir
    $data = isset($_POST['data']) ? json_decode($_POST['data']) : [];

    $existingData = array();
    $newData = array();

    if ($data != []) {
        // Loop untuk mengecek duplikat barang dan membangun array untuk data yang akan diimpor
        for ($i = 1; $i < count($data); $i++) { // Dimulai dari 1 karena hasil baca file excel index ke 0 adalah nama kolom (merek, type, bulan,tahun, aktual), jadi di skip
            $currentMerk = $data[$i][0];
            $currentType = $data[$i][1];
            $currentBulan = $data[$i][2];
            $currentTahun = $data[$i][3];
            $currentActual = $data[$i][4];

            //ambil id barang untuk check duplikat data penjualan
            $sqlGetIdBarang = "SELECT id_brg FROM dt_barang WHERE merek='$currentMerk' AND tipe='$currentType'";
            $resGetIdBarang = $conn->query($sqlGetIdBarang);

            $id_brg = '';

            if ($resGetIdBarang->num_rows > 0) {
                $row = $resGetIdBarang->fetch_assoc();
                $id_brg = $row['id_brg'];
            }

            //check apakah data dengan bulan, tahun, dan id barang yang di import sudah ada di database
            $sqlCheckDuplicateSale = "SELECT * FROM dt_penjualan WHERE bulan = '$currentBulan' AND tahun = '$currentTahun' AND id_brg = '$id_brg'";
            $resCheckDuplicateSale = $conn->query($sqlCheckDuplicateSale);


            // Jika barang sudah ada, tambahkan ke array $duplicate
            if ($resCheckDuplicateSale->num_rows > 0) {
                // Jika user ingin memperbarui, tambahkan ke array $existingData
                $existingData[] = array(
                    'merek' => $currentMerk,
                    'tipe' => $currentType,
                    'bulan' => $currentBulan,
                    'tahun' => $currentTahun,
                    'actual' => $currentActual
                );
            } else {
                // Jika barang belum ada, tambahkan ke array $newData
                $newData[] = array(
                    'merek' => $currentMerk,
                    'tipe' => $currentType,
                    'bulan' => $currentBulan,
                    'tahun' => $currentTahun,
                    'actual' => $currentActual
                );
            }
        }

        //return nilai existingData dan newData untuk dilakukan preview
        echo json_encode(
            (object) [
                "status" => "success",
                "existingData" => $existingData,
                "newData" => $newData
            ]
        );
    } else {
        echo json_encode(
            array(
                "status" => "error",
                "message" => "Error: Data tidak ditemukan"
            )
        );
    }

    // Tutup koneksi
    $conn->close();
}