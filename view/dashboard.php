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
    <div class="content-medium">
        <div class="container-fluid">
            <h1>Hai, Admin!</h1>
            <p>Selamat datang di website Sistem Peramalan Penjualan</p>

            <?php
            include '../koneksi.php';

            $sql_merek = "SELECT DISTINCT merek FROM dt_barang";
            $result = $conn->query($sql_merek);

            $jumlah_merek = 0;

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $jumlah_merek++;
                }
            }

            $sql_tipe = "SELECT DISTINCT tipe FROM dt_barang";
            $result = $conn->query($sql_tipe);

            $jumlah_tipe = 0;

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $jumlah_tipe++;
                }
            }

            $sql_penjualanTahun = "SELECT tahun, SUM(dt_aktual) AS total_dt_aktual FROM dt_penjualan GROUP BY tahun ORDER BY total_dt_aktual DESC LIMIT 1";
            $result = $conn->query($sql_penjualanTahun);

            $penjualan_tertinggi = 0;
            $tahun_penjualan_tertinggi = '';

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $penjualan_tertinggi = $row["total_dt_aktual"]; 
                $tahun_penjualan_tertinggi = $row["tahun"]; 
            }

            $conn->close();
            ?>

            <div class="gutter-container">
                <div class="gutter row g-2">
                    <div class="gutter col-4">
                        <div class="gutter">
                            <i class="gutter-icon bi bi-display"></i>
                            <div class="gutter-item">
                                <b>Jumlah Merek</b>
                                <p><?php echo $jumlah_merek; ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="gutter col-4">
                        <div class="gutter">
                            <i class="gutter-icon bi bi bi-laptop"></i>
                            <div class="gutter-item">
                                <b>Jumlah Tipe</b>
                                <p><?php echo $jumlah_tipe; ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="gutter col-4">
                        <div class="gutter">
                            <i class="gutter-icon bi bi-bar-chart"></i>
                            <div class="gutter-item">
                                <b>Penjualan Tertinggi</b>
                                <p><?php echo "Tahun: " . $tahun_penjualan_tertinggi . " " . "Jumlah: " . $penjualan_tertinggi; ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bootstrap -->
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>

</html>