<?php
include '../koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $merek = isset($_POST['merek']) ? $_POST['merek'] : '';
    $tipe = isset($_POST['tipe']) ? $_POST['tipe'] : '';

    $check_query = "SELECT * FROM dt_barang WHERE merek='$merek' AND tipe='$tipe'";
    $result_check_duplicate_barang = $conn->query($check_query);

    if ($result_check_duplicate_barang->num_rows > 0) {
        header("Location: check-data-barang.php");
        exit();
    } else {
        $query = "INSERT INTO dt_barang (merek, tipe) VALUES ('$merek', '$tipe')";

        if ($conn->query($query) === TRUE) {
            header("Location: ../view/data-barang.php");
            exit();
        } else {
            echo "Error: " . $query . "<br>" . $conn->error;
        }
    }

    $conn->close();
}
?>
