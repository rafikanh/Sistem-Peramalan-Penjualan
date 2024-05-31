<?php
// Sertakan file koneksi ke database
include '../koneksi.php';

// Periksa apakah parameter data_penjualan telah dikirim melalui metode POST
if (isset($_POST['data_penjualan'])) {
    // Ambil data penjualan
    $dataPenjualan = json_decode($_POST['data_penjualan'], true) ?? [];

    // Inisialisasi array untuk menyimpan hasil perhitungan MAPE
    $results = [];

    // Inisialisasi variabel untuk melacak MAPE terendah
    $lowestMAPE = PHP_FLOAT_MAX;
    $bestAlpha = null;
    $bestBeta = null;

    // Loop untuk alpha dan beta dalam rentang 0.1 hingga 0.9 dengan langkah 0.1
    for ($alpha = 0.1; $alpha <= 0.9; $alpha += 0.1) {
        for ($beta = 0.1; $beta <= 0.9; $beta += 0.1) {
            // Inisialisasi array untuk menyimpan hasil perhitungan DES
            $forecasts = [];

            foreach ($dataPenjualan as $index => $item) {
                // Ambil nilai data aktual
                $actual = (int)$item["dt_aktual"];

                if ($index == 0) {
                    $level = 0;
                    $trend = 0;
                    $forecast = 0;
                    $error = 0;
                    $absError = 0;
                    $percentError = 0;
                } else if ($index == 1) {
                    $level = $actual;
                    $trend =  $actual - $dataPenjualan[$index - 1]["dt_aktual"];
                    $forecast = 0;
                    $error = 0;
                    $absError = 0;
                    $percentError = 0;
                } else {
                    // Hitung level dan trend
                    $level =  $alpha * $actual + (1 - $alpha) * ($forecasts[$index - 1]["Level"] + $forecasts[$index - 1]["Trend"]);
                    $trend =  $beta * ($level - $forecasts[$index - 1]["Level"]) + (1 - $beta) * $forecasts[$index - 1]["Trend"];

                    // Hitung forecast DES
                    $forecast = ($forecasts[$index - 1]["Level"] + $forecasts[$index - 1]["Trend"]);

                    // Hitung error
                    $error = $actual - $forecast;

                    // Hitung absolute error
                    $absError = abs($error);

                    // Hitung percentage error (menghindari pembagian oleh nol)
                    $percentError = ($actual != 0) ? ($absError / $actual) * 100 : 0;
                }

                // Simpan hasil perhitungan
                $forecasts[] = [
                    "Level" => $level,
                    "Trend" => $trend,
                    "Forecast" => $forecast,
                    "Error" => $error,
                    "Abs Error" => $absError,
                    "% Error" => abs($percentError),
                ];
            }

            // Hitung MAPE
            $totalPercentageError = 0;
            foreach ($forecasts as $forecast) {
                $totalPercentageError += $forecast["% Error"];
            }
            $mape = $totalPercentageError / count($dataPenjualan);

            // Simpan hasil MAPE untuk kombinasi alpha dan beta ini
            $results[] = [
                "Alpha" => $alpha,
                "Beta" => $beta,
                "MAPE" => $mape,
            ];

            // Cek jika MAPE saat ini adalah yang terendah
            if ($mape < $lowestMAPE) {
                $lowestMAPE = $mape;
                $bestAlpha = $alpha;
                $bestBeta = $beta;
            }
        }
    }

    // Tambahkan MAPE terendah dan parameter alpha, beta terbaik ke hasil
    $results[] = [
        "Best Alpha" => $bestAlpha,
        "Best Beta" => $bestBeta,
        "Lowest MAPE" => $lowestMAPE,
    ];

    // Kembalikan hasil perhitungan dalam format JSON
    header('Content-Type: application/json');
    echo json_encode($results);
} else {
    // Jika parameter tidak lengkap, kembalikan pesan error
    echo "Error: Data Penjualan tidak tersedia.";
}
?>
