<?php
include '../koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $merek = isset($_POST['merek']) ? $_POST['merek'] : '';
    $tipe = isset($_POST['tipe']) ? $_POST['tipe'] : '';

    $sql_check_duplicate_barang = "SELECT * FROM dt_barang WHERE merek='$merek' AND tipe='$tipe'";
    $result_check_duplicate_barang = $conn->query($sql_check_duplicate_barang);

    $isDataExists = false;

    if ($result_check_duplicate_barang && $result_check_duplicate_barang->num_rows > 0) {
        $isDataExists = true;
    }

    echo json_encode($isDataExists);

    $conn->close();
}
?>
