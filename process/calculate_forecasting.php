<?php
// Sertakan file koneksi ke database
include '../koneksi.php';

// Periksa apakah parameter merek dan id_brg telah dikirim melalui metode POST
if (isset($_POST['alpha']) && isset($_POST['beta']) && isset($_POST['data_penjualan'])) {
 // Ambil data penjualan

 $dataPenjualan = json_decode($_POST['data_penjualan'], true) ?? [];

 // Inisialisasi variabel alpha dan beta tanpa nilai default
 $alpha = (float)(($_POST['alpha']) ? $_POST['alpha'] : (isset($_COOKIE['alpha']) ? $_COOKIE['alpha'] : null));
 $beta = (float)(isset($_POST['beta']) ? $_POST['beta'] : (isset($_COOKIE['beta']) ? $_COOKIE['beta'] : null));

 // Inisialisasi array untuk menyimpan hasil perhitungan DES
 $forecasts = [];

 foreach ($dataPenjualan as $index => $item) {
     // Ambil nilai data aktual
     $actual = (int)$item["dt_aktual"];

     // Hitung level dan trend
     $level = ($index == 0) ? $actual : $alpha * $actual + (1 - $alpha) * ($forecasts[$index - 1]["Level"] + $forecasts[$index - 1]["Trend"]);
     $trend = ($index == 0) ? 0 : $beta * ($level - $forecasts[$index - 1]["Level"]) + (1 - $beta) * $forecasts[$index - 1]["Trend"];

     // Hitung forecast DES
     $forecast = $level + $trend;

     // Hitung error
     $error = $actual - $forecast;

     // Hitung absolute error
     $absError = abs($error);

     // Hitung percentage error (menghindari pembagian oleh nol)
     $percentError = ($actual != 0) ? ($error / $actual) * 100 : 0;

     // Simpan hasil perhitungan
     $forecasts[] = [
         "Level" => $level,
         "Trend" => $trend,
         "Forecast" => $forecast,
         "Error" => $error,
         "Abs Error" => $absError,
         "% Error" => $percentError,
     ];
 }

 // Kembalikan hasil perhitungan dalam format JSON
 echo json_encode($forecasts);
} else {
    // Jika parameter tidak lengkap, kembalikan pesan error
    echo "Error: Alpha, Beta dan Data Penjualan tidak tersedia.";
}
?>