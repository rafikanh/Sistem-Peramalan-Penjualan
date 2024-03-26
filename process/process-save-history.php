<?php
// Sertakan file koneksi
include '../koneksi.php';

// Cek apakah formulir dikirim
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari formulir
    $id_brg = isset($_POST['id_brg']) ? $_POST['id_brg'] : '';
    $res_forecast = isset($_POST['res_forecast']) ? $_POST['res_forecast'] : '';
    $mape = isset($_POST['mape']) ? $_POST['mape'] : '';
    $bulan = isset($_POST['bulan']) ? $_POST['bulan'] : '';
    $tahun = isset($_POST['tahun']) ? $_POST['tahun'] : '';


    // Query SQL untuk menyimpan data ke dalam tabel dt_barang
    $query = "INSERT INTO dt_history (id_brg, res_forecast, mape, bulan, tahun) VALUES ('$id_brg', '$res_forecast', '$mape', '$bulan', '$tahun')";
    if ($conn->query($query) === TRUE) {
        // Jika data berhasil disimpan, arahkan pengguna ke halaman data barang
        echo json_encode(
            array(

                "id_brg" => $id_brg,
                "res_forecast" => $res_forecast,
                "mape" => $mape,
                "bulan" => $bulan,
                "tahun" => $tahun

            )
        ); // Pastikan untuk keluar setelah mengarahkan pengguna
    } else {
        echo json_encode(
            array(
                "message" => "Error: " . $query . "<br>" . $conn->error
            )
        );
    }

    // Tutup koneksi
    $conn->close();
}