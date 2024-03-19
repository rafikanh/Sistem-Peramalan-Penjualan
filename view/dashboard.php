<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <!-- Chart JS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Style -->
    <link rel="stylesheet" href="../css/style.css">
    <title>Sistem Peramalan Penjualan</title>
</head>

<body>
    <?php include '../view/component/sidebar.php'; ?>
    <div class="content-large">
        <div class="container-fluid">
            <h1>Hai, Admin!</h1>
            <p>Selamat datang di website Sistem Peramalan Penjualan</p>

            <?php
            include '../koneksi.php';

            // Query untuk mengambil data penjualan aktual dari tabel dt_penjualan
            $query = "SELECT db.merek AS merek_laptop, db.tipe AS tipe_laptop, 
        tahun,
        SUM(dp.dt_aktual) AS total_penjualan 
        FROM dt_penjualan dp
        JOIN dt_barang db ON dp.id_brg = db.id_brg
        GROUP BY merek_laptop, tipe_laptop, tahun";
            $result = mysqli_query($conn, $query);

            // Data penjualan
            $data_penjualan = [];

            // Loop melalui setiap baris hasil query dan menambahkannya ke array data penjualan
            while ($row = mysqli_fetch_assoc($result)) {
                $merek = $row['merek_laptop'];
                $tipe = $row['tipe_laptop'];
                $tahun = $row['tahun'];
                $jumlah_penjualan = $row['total_penjualan'];

                // Inisialisasi data penjualan untuk merek dan tipe tertentu jika belum ada
                if (!isset($data_penjualan[$tahun][$merek][$tipe])) {
                    $data_penjualan[$tahun][$merek][$tipe] = 0;
                }

                // Tambahkan jumlah penjualan ke data penjualan untuk merek, tipe, dan tahun tertentu
                $data_penjualan[$tahun][$merek][$tipe] += $jumlah_penjualan;
            }

            // Buat array untuk merek laptop
            $labels_merek = [];
            // Buat array untuk tipe laptop
            $labels_tipe = [];
            foreach ($data_penjualan as $tahun_data) {
                foreach ($tahun_data as $merek => $tipe_data) {
                    if (!in_array($merek, $labels_merek)) {
                        $labels_merek[] = $merek;
                    }
                    foreach ($tipe_data as $tipe => $jumlah) {
                        if (!in_array($tipe, $labels_tipe)) {
                            $labels_tipe[] = $tipe;
                        }
                    }
                }
            }

            // Buat array untuk tahun
            $labels_tahun = array_keys($data_penjualan);

            // Data untuk datasets
            $datasets = [];

            // Loop melalui setiap tahun
            foreach ($data_penjualan as $tahun => $tahun_data) {
                // Inisialisasi array untuk menyimpan 3 merek dan tipe dengan jumlah penjualan tertinggi
                $top_sales = [];

                // Loop melalui data penjualan tahun ini
                foreach ($tahun_data as $merek => $tipe_data) {
                    foreach ($tipe_data as $tipe => $jumlah) {
                        // Bandingkan jumlah penjualan dengan top 3
                        if (count($top_sales) < 3) {
                            $top_sales[] = ['merek' => $merek, 'tipe' => $tipe, 'jumlah' => $jumlah];
                        } else {
                            // Temukan dan ganti nilai terkecil di dalam top 3
                            $min_index = 0;
                            for ($i = 1; $i < count($top_sales); $i++) {
                                if ($top_sales[$i]['jumlah'] < $top_sales[$min_index]['jumlah']) {
                                    $min_index = $i;
                                }
                            }
                            // Jika jumlah penjualan saat ini lebih besar dari nilai terkecil di dalam top 3, ganti nilainya
                            if ($jumlah > $top_sales[$min_index]['jumlah']) {
                                $top_sales[$min_index] = ['merek' => $merek, 'tipe' => $tipe, 'jumlah' => $jumlah];
                            }
                        }
                    }
                }

                // Tambahkan data top sales ke dalam dataset
                foreach ($top_sales as $top_sale) {
                    $datasets[$top_sale['merek'] . ' ' . $top_sale['tipe']][] = $top_sale['jumlah'];
                }
            }

            // Konversi data datasets menjadi format JSON
            $datasets_json = json_encode(array_values($datasets));
            ?>

            <!-- Container untuk line chart -->
            <div class="chart-container">
                <canvas id="myLineChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Script untuk line chart -->
    <script>
        // Data untuk datasets
        var datasets = <?php echo $datasets_json; ?>;

        // Data untuk tahun
        var tahun = <?php echo json_encode($labels_tahun); ?>;

        // Konfigurasi line chart
        var config = {
            type: 'line',
            data: {
                labels: tahun,
                datasets: [
                    <?php foreach ($datasets as $label => $data) : ?> {
                            label: '<?php echo $label; ?>',
                            data: <?php echo json_encode($data); ?>,
                            fill: false,
                            pointStyle: 'circle',
                        },
                    <?php endforeach; ?>
                ]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        };

        // Inisialisasi line chart
        var ctx = document.getElementById('myLineChart').getContext('2d');
        var myLineChart = new Chart(ctx, config);
    </script>

    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>

</html>