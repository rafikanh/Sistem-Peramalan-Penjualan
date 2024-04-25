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
    $dataUpdate = array(); // Tambahkan array untuk menyimpan data yang akan diperbarui

    if ($data != []) {
        // Loop untuk mengecek duplikat barang dan membangun array untuk data yang akan diimpor
        for ($i = 0; $i < count($data); $i++) { // Perhatikan bahwa Anda harus mulai dari indeks 0, bukan 1
            $currentMerk = $data[$i][0];
            $currentType = $data[$i][1];
            $currentBulan = $data[$i][2];
            $currentTahun = $data[$i][3];
            $currentActual = $data[$i][4];

            // Cek apakah barang sudah ada dalam database
            $sqlCheckDuplicateBarang = "SELECT id_brg FROM dt_barang WHERE merek='$currentMerk' AND tipe='$currentType'";
            $resCheckDuplicateBarang = $conn->query($sqlCheckDuplicateBarang);

            // Jika barang sudah ada, tambahkan ke array $duplicate
            if ($resCheckDuplicateBarang->num_rows > 0) {
                // Jika user ingin memperbarui, tambahkan ke array $dataUpdate
                $dataUpdate[] = array(
                    'merek' => $currentMerk,
                    'tipe' => $currentType,
                    'bulan' => $currentBulan,
                    'tahun' => $currentTahun,
                    'actual' => $currentActual
                );
            } else {
                // Jika barang belum ada, tambahkan ke array $dataImport
                $dataImport[] = array(
                    'merek' => $currentMerk,
                    'tipe' => $currentType,
                    'bulan' => $currentBulan,
                    'tahun' => $currentTahun,
                    'actual' => $currentActual
                );
            }
        }

        // Jika terdapat barang yang sudah ada
        if (!empty($dataUpdate)) {
            // Periksa duplikat barang
            foreach ($dataUpdate as $item) {
                $currentMerk = $item['merek'];
                $currentType = $item['tipe'];

                // Cek apakah barang sudah ada dalam database
                $sqlCheckDuplicateBarang = "SELECT id_brg FROM dt_barang WHERE merek='$currentMerk' AND tipe='$currentType'";
                $resCheckDuplicateBarang = $conn->query($sqlCheckDuplicateBarang);

                // Jika barang sudah ada, tambahkan ke array $duplicate
                if ($resCheckDuplicateBarang->num_rows > 0) {
                    $duplicate[] = $item;
                }
            }

            // Jika terdapat duplikat dan user ingin mengganti
            if (!empty($duplicate)) {
                echo json_encode(
                    array(
                        "status" => "duplicate",
                        "data" => $duplicate
                    )
                );
            } else {
                // Jika tidak ada duplikat, langsung jalankan proses impor
                importData($dataImport);
            }
        }

        // Jika tidak ada duplikat dan ada data yang baru akan diimpor
        if (empty($duplicate) && !empty($dataImport)) {
            importData($dataImport);
        }
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

// Fungsi untuk mengimpor data
function importData($data)
{
    global $conn;

    foreach ($data as $item) {
        $currentMerk = $item['merek'];
        $currentType = $item['tipe'];
        $currentBulan = $item['bulan'];
        $currentTahun = $item['tahun'];
        $currentActual = $item['actual'];

        // Impor data penjualan
        $sqlInsertSale = "INSERT INTO dt_penjualan (bulan, tahun, id_brg, dt_aktual, id_user) 
                          SELECT '$currentBulan', '$currentTahun', id_brg, '$currentActual', '" . $_SESSION['id'] . "' 
                          FROM dt_barang 
                          WHERE merek='$currentMerk' AND tipe='$currentType'";
        $conn->query($sqlInsertSale);
    }

    echo json_encode(
        array(
            "status" => "success",
            "message" => "Data berhasil diimpor"
        )
    );
}

// Fungsi untuk memperbarui data yang sudah ada
function updateData($data)
{
    global $conn;

    foreach ($data as $item) {
        $currentMerk = $item['merek'];
        $currentType = $item['tipe'];
        $currentBulan = $item['bulan'];
        $currentTahun = $item['tahun'];
        $currentActual = $item['actual'];

        // Update data penjualan
        $sqlUpdateSale = "UPDATE dt_penjualan 
                          SET dt_aktual = '$currentActual' 
                          WHERE bulan = '$currentBulan' 
                          AND tahun = '$currentTahun' 
                          AND id_brg IN (SELECT id_brg FROM dt_barang WHERE merek='$currentMerk' AND tipe='$currentType')";
        $conn->query($sqlUpdateSale);
    }

    echo json_encode(
        array(
            "status" => "success",
            "message" => "Data berhasil diperbarui"
        )
    );
}
