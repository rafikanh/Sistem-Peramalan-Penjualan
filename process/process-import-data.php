<?php
// Sertakan file koneksi
include '../koneksi.php';

session_start();

// Cek apakah formulir dikirim
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari formulir
    $existingData = isset($_POST['existingData']) ? json_decode($_POST['existingData']) : [];
    $newData = isset($_POST['newData']) ? json_decode($_POST['newData']) : [];

    if ((!empty($existingData)) || (!empty($newData))) {
        if (!empty($existingData)) {
            updateData($existingData);
        }

        if (!empty($newData)) {
            importData($newData);
        }

        echo json_encode(
            array(
                "status" => "success",
                "message" => "Data berhasil ditambahkan"
            )
        );
    } else {
        echo json_encode(
            array(
                "status" => "error",
                "message" => 'Error: Data tidak ditemukan'
            )
        );
    }
}

// Fungsi untuk mengimpor data
function importData($newData)
{
    global $conn;

    foreach ($newData as $item) {
        $currentMerk = $item->merek;
        $currentType = $item->tipe;
        $currentBulan = $item->bulan;
        $currentTahun = $item->tahun;
        $currentActual = $item->actual;

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

            $id_user = $_SESSION['id'];
            $sqlInsertSale = "INSERT INTO dt_penjualan (bulan, tahun, id_brg, dt_aktual, id_user) VALUES ('$currentBulan', '$currentTahun', '$id_brg', '$currentActual', '$id_user')";
            $conn->query($sqlInsertSale);
        }
    }
}

// Fungsi untuk memperbarui data yang sudah ada
function updateData($existingData)
{
    global $conn;

    foreach ($existingData as $item) {
        $currentMerk = $item->merek;
        $currentType = $item->tipe;
        $currentBulan = $item->bulan;
        $currentTahun = $item->tahun;
        $currentActual = $item->actual;

        // Update data penjualan
        $sqlUpdateSale = "UPDATE dt_penjualan 
                          SET dt_aktual = '$currentActual' 
                          WHERE bulan = '$currentBulan' 
                          AND tahun = '$currentTahun' 
                          AND id_brg IN (SELECT id_brg FROM dt_barang WHERE merek='$currentMerk' AND tipe='$currentType')";
        $conn->query($sqlUpdateSale);
    }
}
?>
